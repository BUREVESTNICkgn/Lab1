@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Корзина</h1>
    @if($products->isEmpty())
        <p class="text-muted">Корзина пуста</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->title }}</td>
                        <td>{{ $product->price }} ₽</td>
                        <td>
                            <form action="{{ route('cart.update', $product) }}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="{{ $product->quantity }}" min="1" class="form-control d-inline w-25">
                                <button type="submit" class="btn btn-sm btn-primary">Обновить</button>
                            </form>
                        </td>
                        <td>{{ $product->price * $product->quantity }} ₽</td>
                        <td>
                            <form action="{{ route('cart.remove', $product) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p class="h5">Итого: {{ $total }} ₽</p>
        <a href="{{ route('orders.create') }}" class="btn btn-success">Оформить заказ</a>
    @endif
</div>
@endsection