@extends('layouts.dashboard')


@section('title', 'Edit Category')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item ">Categories</li>
    <li class="breadcrumb-item active">Edit Category</li>
@endsection
@section('content')

    <div class="mb-4">
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-primary btn-sm">All Categories</a>
    </div>

    <form action="{{ route('dashboard.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('dashboard.categories._form', [
            'button_label' => 'Update'
        ])
    </form>
@endsection
