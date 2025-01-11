<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('categories.view');

        $categories = Category::leftJoin('categories as parents', 'categories.parent_id', '=', 'parents.id')
            ->select([
                'categories.*',
                'parents.name as parent_name'
            ])
            ->selectRaw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id) as products_count')
            ->filter(request()->query())
            ->paginate(2);

        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('categories.create');

        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create', compact('parents', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('categories.create');

        $request->validate([
            'name' => 'required|string|max:20',
            'parent_id' => 'nullable|numeric|exists:categories,id',
            'status' => 'required|in:active,archived',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|dimensions:min_height=100,min_width=100'
        ]);

        $image_name = null;

        if ($request->hasFile('image')) {
            $image_name = rand() . time() . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/categories'), $image_name);
        }

        $category = Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'image' => $image_name,
            'status' => $request->status,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('dashboard.categories.index')->with('message', 'Category created successfully')->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        Gate::authorize('categories.view');

        $products = $category->products()->with('store')->paginate();

        // $products = collect($products);

        // dd($products);

        return view('dashboard.categories.show', compact('products', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('categories.update');
        // select * from categories where id != $category->id and 
        $category = Category::findOrFail($id);
        $parents = Category::where('id', '!=', $id)->where(function ($query) use ($id) {
            $query->whereNull('parent_id')->orWhere('parent_id', '!=', $id);
        })->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Gate::authorize('categories.update');

        $request->validate([
            'name' => 'required|string|max:20',
            'parent_id' => 'nullable|numeric|exists:categories,id',
            'status' => 'required|in:active,archived',
            'description' => 'nullable|text',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|min_height:100,min_width:100'
        ]);

        $category = Category::findOrFail($id);

        $image_name = $category->image;

        if ($image_name && $request->image) {

            File::delete(public_path('uploads/categories/' . $image_name));
        }

        if ($request->hasFile('image')) {
            $image_name = rand() . time() . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads'), $image_name);
        }

        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'image' => $image_name,
            'status' => $request->status
        ]);

        return redirect()->route('dashboard.categories.index')->with('message', 'Category updated successfully')->with('type', 'info');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('categories.delete');

        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->route('dashboard.categories.index')->with('message', 'Category deleted successfully')->with('type', 'danger');
    }

    public function trash()
    {

        $categories = Category::onlyTrashed()->paginate();

        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore($id)
    {

        $category = Category::withTrashed()->findOrFail($id);

        $category->restore();

        return redirect()->route('dashboard.categories.index')->with('message', 'Category restored')->with('type', 'info');
    }

    public function force_delete($id)
    {

        $category = Category::withTrashed()->findOrFail($id);


        $category->forceDelete();

        if ($category->image) {

            File::delete(public_path('uploads/categories/' . $category->image));
        }

        return redirect()->route('dashboard.categories.index')->with('message', 'Category deleted permenantely!!')->with('type', 'danger');
    }
}
