<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderCreatedNotification
{
    /**
     * Create the event listener.
     */

    public $order;

    public $cart;

    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = User::where('store_id', $event->order->store_id)->first();

        if ($user) {
            $user->notify(new OrderCreatedNotification($event->order));
        }
    }
}
