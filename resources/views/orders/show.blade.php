@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-1">Заказ #{{ $order->id }}</h1>
            <p class="mb-0 text-muted">{{ $order->created_at->format('d.m.Y H:i') }} • {{ $order->user->name ?? 'Покупатель' }}</p>
        </div>
        <div class="text-end">
            <div class="badge-role mb-2">{{ ucfirst($order->status) }}</div>
            <div class="fs-4 fw-bold">{{ number_format($order->total, 0, ',', ' ') }} ₽</div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h4 class="mb-3">Товары</h4>
                    <table class="table align-middle mb-0">
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
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 mb-3">
                <div class="card-body">
                    <h5 class="fw-semibold mb-2">Доставка</h5>
                    <p class="mb-1">Способ: <strong>{{ $order->shipping_method === 'pickup' ? 'Самовывоз' : 'Доставка продавца' }}</strong></p>
                    <p class="mb-1">Стоимость: <strong>{{ number_format($order->shipping_cost, 0, ',', ' ') }} ₽</strong></p>
                    @if($order->shipping_address)
                        <p class="mb-1">Адрес: <strong>{{ $order->shipping_address }}</strong></p>
                    @endif
                    @if($order->contact_phone)
                        <p class="mb-0 text-muted">Телефон: {{ $order->contact_phone }}</p>
                    @endif
                </div>
            </div>
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h6 class="text-uppercase text-muted mb-2">Итоги</h6>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Товары</span>
                        <span>{{ number_format($order->total - $order->shipping_cost, 0, ',', ' ') }} ₽</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Доставка</span>
                        <span>{{ number_format($order->shipping_cost, 0, ',', ' ') }} ₽</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5">
                        <span>Итого</span>
                        <span>{{ number_format($order->total, 0, ',', ' ') }} ₽</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($canMessage)
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Чат по заказу</h4>
                    <span class="badge-role">Менеджер ↔ Покупатель</span>
                </div>

                <div class="mb-3">
                    @forelse($order->messages as $message)
                        <div class="p-3 mb-2 rounded-3 {{ $message->from_user_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
                            <div class="small fw-semibold mb-1">{{ $message->fromUser->name ?? 'Пользователь' }} • {{ $message->created_at->format('d.m H:i') }}</div>
                            <div>{{ $message->body }}</div>
                        </div>
                    @empty
                        <p class="text-muted">Пока нет переписки. Напишите клиенту, чтобы уточнить адрес или детали доставки.</p>
                    @endforelse
                </div>

                <form method="POST" action="{{ route('orders.messages.store', $order) }}">
                    @csrf
                    <div class="mb-3">
                        <textarea name="body" class="form-control" rows="3" placeholder="Напишите сообщение покупателю или менеджеру" required></textarea>
                    </div>
                    <button class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
