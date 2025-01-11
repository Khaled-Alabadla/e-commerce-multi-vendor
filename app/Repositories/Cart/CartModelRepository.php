<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartModelRepository implements CartRepository
{

    protected $items;

    public function __construct()
    {

        $this->items = collect([]);
    }
    public function get(): Collection
    {
        if (!$this->items->count()) {

            $this->items = Cart::with('product')->get();
        }

        return $this->items;
    }

    public function add(Product $product, $quantity = 1)
    {

        $cart = Cart::where('product_id', '=', $product->id)
            ->first();

        if (!$cart) {
            $cart =  Cart::create([
                'user_id' => Auth::user() ? Auth::user()->id : null,
                'product_id' => $product->id,
                'quantity' => $quantity ?? 1
            ]);

            $this->items->push($cart);
            return $cart;
        }


        return $cart->increment('quantity', $quantity ?? 1);
    }

    public function update($id, $quantity)
    {

        Cart::where('id', '=', $id)
            ->update([
                'quantity' => $quantity,
            ]);
    }

    public function delete($id)
    {

        Cart::where('id', '=', $id)->delete();
    }

    public function empty()
    {

        Cart::query()->delete();
    }

    public function total(): float
    {
        // return (float) Cart::join('products', 'products.id', '=', 'carts.product_id')
        //     ->selectRaw('SUM(products.price * carts.quantity) as total')
        //     ->value('total');
        // return 0;

        return $this->get()->sum(function ($item) {

            return $item->quantity * $item->product->price;
            
        });
    }
}
