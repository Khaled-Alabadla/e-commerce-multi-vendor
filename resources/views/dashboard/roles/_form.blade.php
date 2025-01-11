@php
    $abilities = include base_path('data/abilities.php');
@endphp

@if ($errors->any())
    <div class="alert alert-danger">
        <h2>Error Occured!</h2>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <x-form.input name="name" :value="$role->name" label="Role Name" />
</div>

<fieldset>
    <legend>{{ __('Abilities') }}</legend>
    @foreach ($abilities as $key => $value)
        <div class="row mb-2">
            <div class="col-md-6">
                {{ $value }}
            </div>
            <div class="col-md-2">
                <input type="radio" name="abilities[{{ $key }}]" value="allow" checked
                    @checked(($role_abilities[$key] ?? '') == 'allow')>Allow
            </div>
            <div class="col-md-2">
                <input type="radio" name="abilities[{{ $key }}]" value="deny"
                    @checked(($role_abilities[$key] ?? '') == 'deny')>Deny
            </div>
            <div class="col-md-2">
                <input type="radio" name="abilities[{{ $key }}]" value="inherit"
                    @checked(($role_abilities[$key] ?? '') == 'inherit')>Inherit
            </div>
        </div>
    @endforeach
</fieldset>
<button class="submit btn btn-primary mb-4 px-5">{{ $button_label ?? 'Save' }}</button>
