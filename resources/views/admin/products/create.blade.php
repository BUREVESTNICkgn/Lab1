{{-- В форме --}}
<div class="mb-4">
    <label>Категория:</label>
    <select name="category_id" class="border p-2 w-full">
        <option value="">Без категории</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
</div>