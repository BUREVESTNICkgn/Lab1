@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Оформление заказа</h1>

    @if($products->isEmpty())
        <p class="text-muted">Корзина пуста. Добавьте товары перед оформлением заказа.</p>
    @else
        <table class="table rounded-4 shadow-sm">
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
                    <td colspan="3">Стоимость доставки от продавцов</td>
                    <td>{{ $shipping }} ₽</td>
                </tr>
                <tr>
                    <td colspan="3" class="fw-semibold">Итого при доставке</td>
                    <td>{{ $total }} ₽</td>
                </tr>
                <tr class="fw-bold text-success">
                    <td colspan="3">Итого при самовывозе</td>
                    <td>{{ $subtotal }} ₽</td>
                </tr>
            </tfoot>
        </table>
        <form action="{{ route('orders.store') }}" method="POST" class="card shadow-sm border-0 p-4 rounded-4">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Способ получения</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shipping_method" id="shipDelivery" value="delivery" checked>
                            <label class="form-check-label" for="shipDelivery">Доставка продавца ({{ $shipping }} ₽)</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shipping_method" id="shipPickup" value="pickup" {{ $pickupAvailable ? '' : 'disabled' }}>
                            <label class="form-check-label" for="shipPickup">Самовывоз @unless($pickupAvailable)<span class="text-muted">(недоступно)</span>@endunless</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Контактный телефон</label>
                    <input type="text" name="contact_phone" class="form-control" placeholder="Для связи по заказу" value="{{ old('contact_phone') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Адрес доставки</label>
                    <input type="text" name="shipping_address" class="form-control" placeholder="Город, улица, дом" value="{{ old('shipping_address') }}">
                    <small class="text-muted">Заполните адрес, если выбираете доставку. При самовывозе можно указать удобный пункт встречи.</small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">Подтвердить заказ</button>
        </form>
    @endif
</div>
@endsection
