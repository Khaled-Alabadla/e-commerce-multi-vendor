@extends('layouts.dashboard')


@section('title', 'Products')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item active">Products</li>
@endsection
@section('content')

    <div class="mb-4">
        <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary btn-sm">Create Product</a>
        <a href="{{ route('dashboard.products.trash') }}" class="btn btn-primary btn-sm">Trashed Products</a>
    </div>

    <x-alert />

    <form action="{{ URL::current() }}" class="d-flex justify-content-between align-content-center mb-3">
        <x-form.input name="name" placeholder="Name" class="mx-2" :value="request('name')" />
        <x-form.select name="status" class="mx-2" :options="['' => 'All', 'active' => 'Active', 'draft' => 'Draft', 'archived' => 'Archived']" :selected="request('status')" />

        <button class="btn btn-dark submit">Search</button>

    </form>

    <table class="table table-striped table-hover text-center table-bordered">
        <thead>
            <tr class="bg-dark">
                <th>Image</th>
                <th>Id</th>
                <th>Name</th>
                <th>Category</th>
                <th>Store</th>
                <th>Actions</th>
            </tr>
            @foreach ($products as $product)
                <tr>
                    <td><img src="{{ asset('uploads/products/' . $product->image) }}" alt="" height="100"></td>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
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
