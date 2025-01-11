@extends('layouts.dashboard')


@section('title', $category->name)

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item ">{{ $category->name }}</li>
    <li class="breadcrumb-item active">All Products</li>
@endsection
@section('content')

    <div class="mb-4">
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-primary btn-sm">All Categories</a>
    </div>

    <table class="table table-striped table-hover text-center table-bordered">
        <thead>
            <tr class="bg-dark">
                <th>Image</th>
                <th>Name</th>
                <th>Category Name</th>
                <th>Store</th>
                <th>Actions</th>
            </tr>
            {{-- @dd($products); --}}
            @foreach ($products as $product)
            {{-- @dd($product) --}}
                <tr>
                    <td><img src="{{ asset('uploads/products/' . $product->image) }}" alt="" height="100"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $product->store->name }}</td>
                    <td>
                        <a href="{{ route('dashboard.products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('dashboard.products.destroy', $product->id) }}" class="d-inline"
                            method="POST">
                            @csrf
                            @method('delete')
                            <button class="submit btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </thead>
    </table>
    {{ $products->withQueryString()->links() }}

@endsection
