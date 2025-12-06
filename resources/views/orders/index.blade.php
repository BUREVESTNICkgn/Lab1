@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Мои заказы</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($orders->isEmpty())
        <p class="text-muted">Заказов пока нет.</p>
    @else
        <div class="list-group">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <span>Заказ #{{ $order->id }} • {{ $order->created_at->format('d.m.Y') }}</span>
                    <span class="badge bg-primary">{{ ucfirst($order->status) }}</span>
                </a>
            @endforeach
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
