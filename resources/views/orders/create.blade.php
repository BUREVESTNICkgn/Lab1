@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Оформление заказа</h1>
    <table class="table">
        <!-- аналогично cart, но readonly -->
        <tfoot>
            <tr>
                <td colspan="3">Сумма товаров</td>
                <td>{{ $subtotal }} ₽</td>
            </tr>
            <tr>
                <td colspan="3">Доставка</td>
                <td>500 ₽</td>
            </tr>
            <tr class="fw-bold">
                <td colspan="3">Итого (прогноз)</td>
                <td>{{ $total }} ₽</td>
            </tr>
        </tfoot>
    </table>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
    </form>
</div>
@endsection