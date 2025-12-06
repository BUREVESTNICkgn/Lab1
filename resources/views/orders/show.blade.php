@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Заказ #{{ $order->id }}</h1>
    <p class="mb-3">Статус: <strong>{{ ucfirst($order->status) }}</strong></p>
    <p class="mb-3">Итоговая сумма: {{ $order->total }} ₽</p>

    <h4 class="mt-4">Товары</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Количество</th>
                <th>Цена</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->products as $product)
                <tr>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>{{ $product->pivot->price }} ₽</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
