@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Оформление заказа</h1>

    @if($products->isEmpty())
        <p class="text-muted">Корзина пуста. Добавьте товары перед оформлением заказа.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->title }}</td>
                        <td>{{ $product->price }} ₽</td>
                        <td>{{ $cart[$product->id]['quantity'] }}</td>
                        <td>{{ $product->price * $cart[$product->id]['quantity'] }} ₽</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Сумма товаров</td>
                    <td>{{ $subtotal }} ₽</td>
                </tr>
                <tr>
                    <td colspan="3">Доставка</td>
                    <td>{{ $shipping }} ₽</td>
                </tr>
                <tr class="fw-bold">
                    <td colspan="3">Итого</td>
                    <td>{{ $total }} ₽</td>
                </tr>
            </tfoot>
        </table>
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
        </form>
    @endif
</div>
@endsection
