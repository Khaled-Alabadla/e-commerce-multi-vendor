<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function show(Order $order)
    {
        $delivery = $order->delivery;

        return view('front.orders.show', compact('order', 'delivery'));
    }
}
