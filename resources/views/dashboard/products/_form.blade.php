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
    <x-form.input name="name" :value="$product->name" label="Name" />
</div>

<div class="form-group">
    <label for="">Category</label>
    <select name="category_id" @class([
        'form-control',
        'form-select',
        'is-invalid' => $errors->has('category_id'),
    ])>
        <option value="">Primary Category</option>
        @foreach ($categories as $category)
            <option @selected(old('category_id', $product->category_id) == $category->id) value="{{ $category->id }}">{{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('category_id')
        <p class="text-danger small">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <x-form.input name="price" :value="$product->price" label="Price" />
</div>

<div class="form-group">
    <x-form.input name="compare_price" :value="$product->compare_price" label="Compare_price" />
</div>

<div class="form-group">
    <x-form.textarea name="description" :value="$product->description" label="Description" />
</div>

<div class="form-group">
    <x-form.radio name="status" :checked="$product->status" label="Status" :options="['active' => 'Active', 'draft' => 'Draft', 'archived' => 'Archived']" />
</div>

<div class="form-group">
    <x-form.input type="file" name="image" label="Image" />
    <img src="{{ asset('uploads/products/' . $product->image) }}" alt="" height="100">
</div>
<div class="form-group">
    <x-form.input name="tags" label="Tags" :value="$tags" />
</div>

<button class="submit btn btn-primary mb-4 px-5">{{ $button_label ?? 'Save' }}</button>

@push('styles')
    <link rel="stylesheet" href="{{ asset('tagify.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('tagify2.js') }}"></script>
    <script src="{{ asset('tagify.polyfills.min.js') }}"></script>
    <script>
        var inputElm = document.querySelector("[name=tags]"),
            tagify = new Tagify(inputElm);
    </script>
@endpush
