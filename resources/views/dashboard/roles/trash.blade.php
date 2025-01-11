@extends('layouts.dashboard')


@section('title', 'Trashed Categories')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item active">Trashed Categories</li>
@endsection
@section('content')

    <div class="mb-4">
        <a href="{{ route('dashboard.categories.index') }}" class="btn btn-primary btn-sm">Back</a>
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
                <th>Deleted At</th>
                <th>Actions</th>
            </tr>
            @foreach ($categories as $category)
                <tr>
                    <td><img src="{{ asset('uploads/categories/' . $category->image) }}" alt="" height="100"></td>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->deleted_at }}</td>
                    <td>
                        <form action=" {{ route('dashboard.categories.restore', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('put')
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-trash-restore"></i>
                            </button>
                        </form>
                        <form action="{{ route('dashboard.categories.force_delete', $category->id) }}" class="d-inline"
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
    {{ $categories->withQueryString()->links() }}
@endsection
