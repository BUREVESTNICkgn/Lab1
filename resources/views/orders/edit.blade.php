@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Обновить статус заказа #{{ $order->id }}</h1>
    <form action="{{ route('orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Статус</label>
            <select name="status" class="form-select">
                @foreach(['pending','processing','completed','cancelled'] as $status)
                    <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>
@endsection
