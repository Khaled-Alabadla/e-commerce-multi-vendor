@extends('layouts.dashboard')


@section('title', 'Categories')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item active">Categories</li>
@endsection
@section('content')

    <div class="mb-4">
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-primary btn-sm">All Categories</a>
    </div>

    <form action="{{ route('dashboard.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('dashboard.categories._form', [
            'button_label' => 'Create'
        ])
    </form>
@endsection
