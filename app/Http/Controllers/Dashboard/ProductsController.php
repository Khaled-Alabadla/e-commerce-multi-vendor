<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Tag;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('products.view');

        $products = Product::with(['store', 'category'])->filter(request()->query())
            ->paginate();

        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('products.create');

        $product = new Product();
        $categories = Category::all();
        $tags = $product->tags;
        return view('dashboard.products.create', compact('product', 'categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('products.create');

        $request->validate([
            'name' => 'required|string|max:30',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|gte:price|numeric',
            'status' => 'in:active,archived,draft',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        $image_name = null;

        if ($request->hasFile('image')) {

            $image_name = rand() . time() . $request->file('image')->getClientOriginalName();

            $request->file('image')->move(public_path('uploads/products'), $image_name);
        }

        $request->merge([
            'slug' => Str::slug($request->name),
            'store_id' => $request->user()->store_id
        ]);

        Product::create([
            'name' => $request->name,
            'store_id' => Auth::user()->store_id,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $image_name,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'status' => $request->status
        ]);

        return redirect()->route('dashboard.products.index')->with('message', 'Product created successfully')
            ->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('products.view');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('products.update');

        $product = Product::findOrFail($id);
        $categories = Category::all();
        $tags = implode(',', $product->tags()->pluck('name')->toArray());

        return view('dashboard.products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        Gate::authorize('products.update');

        $request->validate([
            'name' => 'required|string|max:30',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|gte:price|numeric',
            'status' => 'in:active,archived,draft',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        $image_name = $product->image;

        $old_image = $product->image;

        if ($request->hasFile('image')) {

            $image_name = rand() . time() . $request->file('image')->getClientOriginalName();

            $request->file('image')->move(public_path('uploads/products'), $image_name);
        }

        $product->update([
            'name' => $request->name,
            'store_id' => Auth::user()->store_id,
            'category_id' => $request->category_id,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $image_name,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'status' => $request->status
        ]);

        if ($request->hasFile('image') && isset($old_image)) {

            File::delete(public_path('uploads/products/' . $old_image));
        }

        $tags = json_decode($request->tags);

        $saved_tags = Tag::all();

        $tag_ids = [];

        if ($tags) {

            foreach ($tags as $item) {

                $slug = Str::slug($item->value);
                $tag = $saved_tags->where('slug', '=', $slug)->first();

                if (!$tag) {
                    $tag = Tag::create([
                        'name' => $item->value,
                        'slug' => $slug
                    ]);
                }

                $tag_ids[] = $tag->id;

                $product->tags()->sync($tag_ids);
            }
        }

        return redirect()->route('dashboard.products.index')
            ->with('message', 'tags updated successfully')
            ->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('products.delete');

        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->route('dashboard.products.index')
            ->with('message', 'Product deleted successfully')
            ->with('type', 'danger');
    }

    public function trash()
    {

        $products = Product::onlyTrashed()->paginate();

        return view('dashboard.products.trash', compact('products'));
    }

    public function restore($id)
    {

        $product = Product::onlyTrashed()->findOrFail($id);

        $product->restore();

        return redirect()->route('dashboard.products.index')->with('message', 'Products restored successfully')
            ->with('type', 'info');
    }

    public function force_delete($id)
    {

        $product = Product::onlyTrashed()->findOrFail($id);

        $product->forceDelete();

        if ($product->image) {

            File::delete(public_path('uploads/products/' . $product->image));
        }

        return redirect()->route('dashboard.products.index')->with('message', 'Products deleted successfully')
            ->with('type', 'danger');
    }
}
