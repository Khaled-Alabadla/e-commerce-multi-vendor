@props([
    'type' => 'text',
    'name',
    'value' => '',
    'label' => false,
])

@if ($label)
    <label>{{ $label }}</label>
@endif
<textarea name="{{ $name }}" 
{{ $attributes->class([
    'form-control',
    'is-invalid' => $errors->has($name)
]) }}
>{{ old($name, $value) }}</textarea>
@error($name)
    <p class="text-danger small">{{ $message }}</p>
@enderror