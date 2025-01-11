<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $cart;
    public function __construct(CartRepository $cart)
    {

        $this->cart = $cart;
    }
    public function index()
    {
        $items = $this->cart->get();
        $c = $this->cart;

        return view('front.carts.index', compact('items', 'c'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $q = Product::where('id', '=', $request->product_id)->first()->quantity;

        $request->validate([
            'quantity' => "nullable|int|lte:$q|min:1",
        ]);

        $product = Product::findOrFail($request->product_id);

        $this->cart->add($product, $request->post('quantity'));

        // dd($request->post('quantity'));

        return redirect()->back()->with('message', 'Cart added successfully')->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cart = Cart::findOrFail($id);

        $quantity = $cart->product->quantity;

        $request->validate([
            'quantity' => "nullable|int|lte:$quantity|min:1",
        ]);

        $this->cart->update($id, $request->quantity);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->cart->delete($id);

        return [
            'message' => 'Item deleted successfully'
        ];
    }
}
