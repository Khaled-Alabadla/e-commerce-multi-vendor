<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveriesController extends Controller
{
    public function show(Delivery $delivery)
    {
        return $delivery;
    }

    public function update(Request $request, Delivery $delivery)
    {

        $request->validate([
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
        ]);

        $delivery->update([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
        ]);

        // event(new DeliveryUpdated($request->latitude, $request->longitude));

        return $delivery;
    }
}
