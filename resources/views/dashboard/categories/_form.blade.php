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
    <x-form.input name="name" :value="$category->name" label="Name" />
</div>

<div class="form-group">
    <label for="">Parent Id</label>
    <select name="parent_id" @class([
        'form-control',
        'form-select',
        'is-invalid' => $errors->has('parent_id'),
    ])>
        <option value="">Primary Category</option>
        @foreach ($parents as $parent)
            <option @selected(old('parent_id', $category->parent_id) == $parent->id) value="{{ $parent->id }}">{{ $parent->name }}
            </option>
        @endforeach
    </select>
    @error('parent_id')
        <p class="text-danger small">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <x-form.textarea name="description" :value="$category->description" label="Description" />
</div>

<div class="form-group">
    <x-form.radio name="status" :checked="$category->status" label="Status" :options="['active' => 'Active', 'archived' => 'Archived']" />

    <div class="form-group">
        <x-form.input type="file" name="image" label="Image" />
        @error('image')
            <p class="text-danger small">{{ $message }}</p>
        @enderror
        <img src="{{ $category->image_url }}" alt="" height="100">
    </div>

    <button class="submit btn btn-primary mb-4 px-5">{{ $button_label ?? 'Save' }}</button>
