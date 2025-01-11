@extends('layouts.dashboard')


@section('title', 'Roles')

@section('breadcrumbs')
    @parent
    <li class="breadcrumb-item active">Roles</li>
@endsection
@section('content')

    <div class="mb-4">
        <a href="{{ route('dashboard.roles.index') }}" class="btn btn-primary btn-sm">All Roles</a>
    </div>

    <form action="{{ route('dashboard.roles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('dashboard.roles._form', [
            'button_label' => 'Create'
        ])
    </form>
@endsection
