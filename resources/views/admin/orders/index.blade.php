@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Менеджмент заказов</h1>
            <p class="text-muted mb-0">Роль менеджера и администратора: просмотр всех заказов и изменение статусов.</p>
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
                        <th>#</th>
                        <th>Покупатель</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th>Товаров</th>
                        <th class="text-end">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? '—' }}</td>
                            <td>{{ number_format($order->total, 0, ',', ' ') }} ₽</td>
                            <td><span class="badge-role">{{ $order->status }}</span></td>
                            <td>{{ $order->products->count() }}</td>
                            <td class="text-end">
                                <form action="{{ route('staff.orders.update', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm d-inline w-auto">
                                        @foreach(['pending' => 'Новый', 'processing' => 'В работе', 'completed' => 'Завершён', 'cancelled' => 'Отменён'] as $value => $label)
                                            <option value="{{ $value }}" @selected($order->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary ms-1">Сохранить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $orders->links() }}</div>
</div>
@endsection
