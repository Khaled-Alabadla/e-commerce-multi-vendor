@extends('layouts.dashboard')


@section('title', 'Edit Product')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item ">Products</li>
    <li class="breadcrumb-item active">Edit Product</li>
@endsection
@section('content')

    <div class="mb-4">
        <a href="{{ route('dashboard.products.index') }}" class="btn btn-primary btn-sm">All Products</a>
    </div>

    <form action="{{ route('dashboard.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('dashboard.products._form', [
            'button_label' => 'Update'
        ])
    </form>
@endsection
