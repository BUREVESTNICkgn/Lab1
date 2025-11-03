@extends('layouts.app')  {{-- предполагаю базовый layout --}}

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Продукты</h1>

    {{-- Фильтр по категориям (строка ~28) --}}
    <form method="GET" action="{{ route('products.index') }}" class="mb-6">
        <div class="flex gap-4">
            <select name="category_id" class="border p-2 rounded">
                <option value="">Все категории</option>
                @foreach($categories as $category)  {{-- ← теперь работает --}}
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Фильтр</button>
        </div>
    </form>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">{{ session('success') }}</div>
    @endif

    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white p-6 rounded-lg shadow">
                    <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
                    <p class="text-gray-600 mt-2">{{ $product->description }}</p>
                    @if($product->category)
                        <p class="text-sm text-blue-600 mt-1">Категория: {{ $product->category->name }}</p>
                    @endif
                    <p class="text-lg font-bold text-green-600 mt-4">{{ $product->price }} ₽</p>
                    <a href="{{ route('products.show', $product) }}" class="text-blue-500 hover:underline">Подробнее</a>
                </div>
            @endforeach
        </div>
        {{ $products->links() }}  {{-- пагинация --}}
    @else
        <p class="text-gray-600">Продуктов пока нет.</p>
    @endif
</div>
@endsection