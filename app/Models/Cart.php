<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cookie;

class Cart extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['cookie_id', 'product_id', 'user_id', 'quantity', 'options'];

    public function product() {

        return $this->belongsTo(Product::class);

    }
    public function user() {

        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Anonymous',
        ]);

    }

    public static function booted() {

        static::creating(function(Cart $cart) {
            $cart->id = Str::uuid();
            $cart->cookie_id = Cart::getCookieId();
        });

        static::addGlobalScope('cookie_id', function(Builder $builder) {

            $builder->where('cookie_id', '=', Cart::getCookieId());

        });

    }

    public static function getCookieId()
    {

        $cookie_id = Cookie::get('cart_id');
        if (!$cookie_id) {
            $cookie_id = Str::uuid();
            Cookie::queue('cart_id', $cookie_id, 24 * 60);
        }

        return $cookie_id;
    }
}
