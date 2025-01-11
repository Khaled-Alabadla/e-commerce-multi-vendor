<?php

namespace App\Models;

use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'user_id', 'number', 'payment_method', 'status',
        'payment_status', 'pending', 'shipping', 'tax', 'discount', 'total', 'currency'
    ];

    public function user() {

        return $this->belongsTo(User::class)->withDefault();

    }

    public function store() {

        return $this->belongsTo(Store::class);

    }

    public function products() {

        return $this->belongsToMany(Product::class, 'order_items')
        ->using(OrderItem::class)
        ->withPivot([
            'product_name', 'quantity', 'price', 'options'
        ]);

    }

    public function addresses() {

        return $this->hasMany(OrderAddress::class, 'order_id', 'id');

    }

    public function shippingAddress() {

        return $this->hasOne(OrderAddress::class, 'order_id', 'id')->where('type', '=', 'shipping');

    }
    public function billingAddress() {

        return $this->hasOne(OrderAddress::class, 'order_id', 'id')->where('type', '=', 'billing');

    }

    protected static function booted() {

        static::creating(function(Order $order) {

            $order->number = Order::getNextOrderNumber();

        });

    }

    private static function getNextOrderNumber() {

        $year = Carbon::now()->year;

        $number = Order::whereYear('created_at', $year)->max('number');

        if ($number) {

            return $number + 1;

        }

        return $year . '0001';

    }

    public function items() {

        return $this->hasMany(OrderItem::class);

    }

    public function delivery() {

        return $this->hasOne(Delivery::class);

    }
 }
