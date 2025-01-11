@extends('layouts.dashboard')


@section('title', 'Roles')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item active">Roles</li>
@endsection
@section('content')

    <div class="mb-4">
        {{-- @can('roles.create') --}}
            <a href="{{ route('dashboard.roles.create') }}" class="btn btn-primary btn-sm">Create Role</a>
        {{-- @endcan --}}
        {{-- <a href="{{ route('dashboard.roles.trash') }}" class="btn btn-primary btn-sm">Trashed Roles</a> --}}
    </div>

    <x-alert />

    <table class="table table-striped table-hover text-center table-bordered">
        <thead>
            <tr class="bg-dark">
                <th>Id</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td><a href="{{ route('dashboard.roles.show', $role->id) }}">{{ $role->name }}</a></td>
                    <td>
                        {{-- @can('roles.update') --}}
                            <a href="{{ route('dashboard.roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                        {{-- @endcan --}}
                        {{-- @can('roles.delete') --}}
                            <form action="{{ route('dashboard.roles.destroy', $role->id) }}" class="d-inline" method="POST">
                                @csrf
                                @method('delete')
                                <button class="submit btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        {{-- @endcan --}}
                    </td>
                </tr>
            @endforeach
        </thead>
    </table>
    {{ $roles->withQueryString()->links() }}
@endsection
