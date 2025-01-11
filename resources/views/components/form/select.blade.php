@props(['options', 'selected', 'name', 'label' => false])

@if ($label)
    <label>{{ $label }}</label>
@endif

<select
    name="{{ $name }}"{{ $attributes->class(['form-control', 'form-select', 'is-invalid' => $errors->has($name)]) }}>
    @foreach ($options as $value => $text)
        <option @selected(old($name, $selected) == $value) value="{{ $value }}">{{ $text }}</option>
    @endforeach
</select>

@error($name)
    <p class="text-danger small">{{ $message }}</p>
@enderror
