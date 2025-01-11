@extends('layouts.dashboard')

@section('title', 'Edit Profile')

@section('content')

    <div class="mb-3">
        <a href="{{ route('dashboard.index') }}" class="btn btn-primary btn-sm">Main page</a>
    </div>
    <x-alert />
    @if ($errors->any())
        <div class="alert alert-danger">
            <h3>Errors Occured !!</h3>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('dashboard.profile.update') }}" method="post">
        @csrf
        @method('patch')
        <div class="form-row">
            <div class="col-md-6">
                <x-form.input name="first_name" :value="$user->profile->first_name" label="First Name" />
            </div>
            <div class="col-md-6">
                <x-form.input name="last_name" :value="$user->profile->last_name" label="Last Name" />
            </div>
        </div>

        <div class="form-row mb-3">
            <div class="col-md-6">
                <x-form.input name="birthday" type="date" :value="$user->profile->birthday" label="Birthday" />
            </div>
            <div class="col-md-6">
                <x-form.radio name="gender" :checked="$user->profile->gender" label="Gender" :options="['male' => 'Male', 'female' => 'Female']" />
            </div>
        </div>

        <div class="form-row mb-3">
            <div class="col-md-6">
                <x-form.input name="street_address" :value="$user->profile->street_address" label="Street Address" />
            </div>
            <div class="col-md-6">
                <x-form.input name="city" :value="$user->profile->city" label="City" />
            </div>
        </div>

        <div class="form-row mb-3">
            <div class="col-md-6">
                <x-form.input name="state" :value="$user->profile->state" label="State" />
            </div>
            <div class="col-md-6">
                <x-form.input name="postal_code" :value="$user->profile->postal_code" label="Postal Code" />
            </div>
        </div>

        <div class="form-row mb-3">
            <div class="col-md-6">
                <x-form.select name="country" :selected="$user->profile->country" label="Country" :options="$countries" />
            </div>
            <div class="col-md-6">
                <x-form.select name="locale" :selected="$user->profile->locale" label="Locale" :options="$locales" />
            </div>
        </div>
        <button class="btn btn-primary px-4 mb-3">Save</button>


    </form>
@endsection

@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
    <li class="breadcrumb-item active">Edit Category</li>
@endsection
