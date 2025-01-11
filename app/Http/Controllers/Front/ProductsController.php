<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index() {

        $products = Product::paginate();

        return view('front.products.index', compact('products'));

    }

    public function show(Product $product) {

        return view('front.products.show', compact('product'));

    }
}
