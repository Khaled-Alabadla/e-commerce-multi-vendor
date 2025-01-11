@extends('layouts.dashboard')


@section('title', 'Categories')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item active">Categories</li>
@endsection
@section('content')

    <div class="mb-4">
        @can('categories.create')
            <a href="{{ route('dashboard.categories.create') }}" class="btn btn-primary btn-sm">Create Category</a>
        @endcan
        <a href="{{ route('dashboard.categories.trash') }}" class="btn btn-primary btn-sm">Trashed Categories</a>
    </div>

    <x-alert />

    <form action="{{ URL::current() }}" class="d-flex justify-content-between align-content-center mb-3">
        <x-form.input name="name" placeholder="Name" class="mx-2" :value="request('name')" />
        <select name="status" id="" class="mx-2 form-control form-select">
            <option value="">All</option>
            <option @selected(request('status') == 'active') value="active">Active</option>
            <option @selected(request('status') == 'archived') value="archived">Archived</option>
        </select>
        <button class="btn btn-dark submit">Search</button>

    </form>

    <table class="table table-striped table-hover text-center table-bordered">
        <thead>
            <tr class="bg-dark">
                <th>Image</th>
                <th>Id</th>
                <th>Name</th>
                <th>Products Count</th>
                <th>Parent</th>
                <th>Actions</th>
            </tr>
            @foreach ($categories as $category)
                <tr>
                    <td><img src="{{ $category->image_url }}" alt="" height="100"></td>
                    <td>{{ $category->id }}</td>
                    <td><a href="{{ route('dashboard.categories.show', $category->id) }}">{{ $category->name }}</a></td>
                    <td>{{ $category->products_count }}</td>
                    <td>{{ $category->parent_name ?? 'Main Category' }}</td>
                    <td>
                        @can('categories.update')
                            <a href="{{ route('dashboard.categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endcan
                        @can('categories.delete')
                            <form action="{{ route('dashboard.categories.destroy', $category->id) }}" class="d-inline"
                                method="POST">
                                @csrf
                                @method('delete')
                                <button class="submit btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </thead>
    </table>
    {{ $categories->withQueryString()->links() }}
@endsection
