@props([
    'type' => 'text',
    'name',
    'value' => '',
    'label' => false,
])

@if ($label)
    <label>{{ $label }}</label>
@endif
<input type="{{ $type }}" name="{{ $name }}" value="{{ old($name, $value) }}"
    {{ $attributes->class(['form-control', 'is-invalid' => $errors->has('name')]) }}>
@error($name)
    <p class="text-danger small">{{ $message }}</p>
@enderror
