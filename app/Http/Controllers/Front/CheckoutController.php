<?php

namespace App\Http\Controllers\Front;

use App\Events\OrderCreated;
use App\Helpers\Currency;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Repositories\Cart\CartRepository;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Intl\Countries;
use Throwable;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {

        if ($cart->get()->count() == 0) {

            return redirect()->route('home')->with('message', 'Cart is empty')->with('type', 'info');
        }

        $countries = Countries::getNames();

        return view('front.checkout.create', compact('cart', 'countries'));
    }

    public function store(Request $request, CartRepository $cart)
    {

        $orders = [];
        $payments = [];
        $lineItems = [];


        $request->validate([
            'addr.billing.first_name' => 'required|string|max:20'
        ]);

        $items = $cart->get()->groupBy('product.store_id')->all();


        DB::beginTransaction();

        try {

            foreach ($items as $store_id => $cart_items) {

                $total = 0;
                foreach ($cart_items as $item) {

                    $total += $item->product->price * $item->quantity;
                }

                $currency = Session::get('currency_code', config('app.currency'));

                $currency_converter = app()->make('currency.converter');

                $order = Order::create([
                    'store_id' => $store_id,
                    'user_id' => Auth::user() ? Auth::user()->id : null,
                    'payment_method' => 'cod',
                    'total' =>  $currency_converter->convert('USD', $currency) * $total,
                    'currency' => $currency
                ]);

                // dd($order);

                foreach ($cart_items as $item) {

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity
                    ]);
                }

                foreach ($request->post('addr') as $type => $address) {

                    $address['type'] = $type;

                    $order->addresses()->create($address);
                }
                \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));


                foreach ($order->items as $item) {


                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $item->product->name,
                                // 'images' => [$item->product->image]
                            ],
                            'unit_amount' => $item->price * 100,
                        ],
                        'quantity' => $item->quantity,
                    ];
                }


                $payment = Payment::create([
                    'order_id' => $order->id,
                    'currency' => $order->currency,
                    'amount' => $order->total,
                    'method' => 'Stripe',
                    'status' => 'pending',
                    // 'session_id' => $checkout_session->id
                ]);

                $orders[] = $order;

                $payments[] = $payment;

                DB::commit();
            }

            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('orders.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('front.checkout.create'),
            ]);

            foreach ($payments as $payment) {

                $payment->update([
                    'session_id' => $checkout_session->id
                ]);
            }

            event(new OrderCreated($order, $cart));

            return redirect($checkout_session->url)->with('orders', $orders)->with('payments', $payments);
        } catch (Throwable $e) {

            DB::rollback();

            throw $e;
        }
    }
}
