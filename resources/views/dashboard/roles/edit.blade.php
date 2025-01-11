@extends('layouts.dashboard')


@section('title', 'Edit Role')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item ">Roles</li>
    <li class="breadcrumb-item active">Edit Role</li>
@endsection
@section('content')

    <div class="mb-4">
        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-primary btn-sm">All Roles</a>
    </div>

    <form action="{{ route('dashboard.roles.update', $role->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('dashboard.roles._form', [
            'button_label' => 'Update'
        ])
    </form>
@endsection
