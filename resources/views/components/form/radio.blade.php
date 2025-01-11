
@props([
    'options', 'checked', 'name', 'label' => false
])

@if ($label)
    <label >{{ $label }}</label>
@endif

@foreach ($options as $value => $text)
    <div class="form-check">
        <input @checked(old($name, $checked) == $value)
            {{ $attributes->class(['form-check-input', 'is-invalid' => $errors->has($name)]) }}
            type="radio" name="{{ $name }}" id="flexRadioDefault1" value="{{ $value }}"> {{ $text }}

    </div>
@endforeach
@error($name)
<p class="text-danger small">{{ $message }}</p>
@enderror

