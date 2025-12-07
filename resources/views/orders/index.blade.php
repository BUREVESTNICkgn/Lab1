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
        <div class="row g-3">
            @foreach($orders as $order)
                <div class="col-md-6">
                    <a href="{{ route('orders.show', $order) }}" class="text-decoration-none text-reset">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold">Заказ #{{ $order->id }}</span>
                                    <span class="badge-role">{{ ucfirst($order->status) }}</span>
                                </div>
                                <p class="text-muted mb-2">{{ $order->created_at->format('d.m.Y') }} • {{ $order->shipping_method === 'pickup' ? 'Самовывоз' : 'Доставка' }}</p>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Позиций: {{ $order->products->count() }}</span>
                                    <span class="fw-bold">{{ number_format($order->total, 0, ',', ' ') }} ₽</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
