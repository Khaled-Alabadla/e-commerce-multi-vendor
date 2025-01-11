@extends('layouts.dashboard')


@section('title', 'Create product')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item active">Products</li>
@endsection
@section('content')

    <div class="mb-4">
        <a href="{{ route('dashboard.products.index') }}" class="btn btn-primary btn-sm">All Products</a>
    </div>

    <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('dashboard.products._form', [
            'button_label' => 'Create'
        ])
    </form>
@endsection
