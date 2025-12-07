@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Модерация объявлений</h1>
            <p class="text-muted mb-0">Администратор может править и удалять любые карточки товаров.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Автор</th>
                        <th class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td class="fw-semibold">{{ $product->title }}</td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td>{{ number_format($product->price, 0, ',', ' ') }} ₽</td>
                            <td>{{ $product->user->name ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-secondary me-1">Открыть</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary me-1">Редактировать</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить карточку?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $products->links() }}</div>
</div>
@endsection
