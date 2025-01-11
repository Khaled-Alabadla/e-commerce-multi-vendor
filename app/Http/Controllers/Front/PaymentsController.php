<?php

namespace App\Http\Controllers\Front;


use Exception;
use Stripe\Stripe;
use Stripe\Customer;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentsController extends Controller
{
    public function checkout(Order $order)
    {

        \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));

        $lineItems = [];

        foreach ($order->items as $item) {

            $lineItems[] = [
                'price' => $item->price * 100,
                'currency' => $order->currency,
                'quantity' => $item->quantity,
            ];
        }
        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('orders.payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('front.checkout.create'),
        ]);

        $order->update([
            'status' => 'paid',
        ]);

        return redirect($checkout_session->url);
    }

    public function success(Request $request)
    {

        $sessionId = $request->get('session_id');

        \Stripe\Stripe::setApiKey(config('services.stripe.secret_key'));


        try {

            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if (!$session) {

                return;
            }

            // $customer = \Stripe\Customer::retrieve($session->customer);

            if (session()->has('orders')) {
                foreach (session('orders') as $order) {
                    $order->update([
                        'status' => 'completed',
                        'payment_status' => 'paid',
                    ]);
                }
            }



            foreach (session('payments') as $payment) {
                $payment->update([
                    'status' => 'completed'
                ]);
            }
        } catch (Exception $e) {
            throw $e;
        }


        return view('front.payments.success');
    }
}
