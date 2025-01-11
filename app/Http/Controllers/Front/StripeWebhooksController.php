<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class StripeWebhooksController extends Controller
{
    public function webhook()
    {


        // The library needs to be configured with your account's secret key.
        // Ensure the key is kept out of any version control system you might be using.
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = config('services.stripe.webhook_sercret');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $paymentIntent = $event->data->object;

                $session_id = $paymentIntent->id;

                $payments = Payment::where('session_id', $session_id)->where('status', 'pending')->get();

                foreach ($payments as $payment) {

                    $payment->update([
                        'status' => 'completed'
                    ]);

                    $order = Order::where('payment_id', $payment->order_id)->first();

                    $order->update([
                        'status' => 'completed',
                        'payment_status' => 'paid',
                    ]);
                }


            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);
    }
}
