@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h1 class="h4 fw-bold text-dark mb-0">
                        {{ $product->title }}
                    </h1>
                    @if (auth()->check() && auth()->id() === $product->user_id)
                        <div>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary btn-sm me-2">
                                <i class="bi bi-pencil me-1"></i>Редактировать
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i>Удалить
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    @if ($product->images->count() > 0)
                        <div id="productCarousel" class="carousel slide mb-4">
                            <div class="carousel-inner">
                                @foreach ($product->images as $key => $img)
                                    <div class="carousel-item @if($key==0) active @endif">
                                        <img src="{{ $img->url }}" class="d-block w-100 rounded" alt="">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>
                    @else
                        <div class="text-center mb-4">
                            <i class="bi bi-image fs-1 text-muted"></i>
                            <p>Нет изображений</p>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
                        <span class="price-chip fs-5"><i class="bi bi-cash-coin"></i>{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                        <span class="badge-role">{{ $product->category?->name ?? 'Без категории' }}</span>
                        @if($product->pickup_available)
                            <span class="badge bg-success-subtle text-success">Самовывоз</span>
                        @endif
                    </div>
                    <p class="mb-2"><strong>Описание:</strong> {{ $product->description }}</p>
                    @if($product->location)<p class="mb-2"><strong>Местоположение:</strong> {{ $product->location }}</p>@endif
                    <p class="mb-2"><strong>Доставка продавца:</strong> {{ number_format($product->shipping_cost, 0, ',', ' ') }} ₽</p>
                    @if($product->delivery)<p class="mb-2"><strong>Условия:</strong> {{ $product->delivery }}</p>@endif
                    @if($product->phone)<p class="mb-2"><strong>Телефон:</strong> {{ $product->phone }}</p>@endif
                    @if($product->email)<p class="mb-2"><strong>Email:</strong> {{ $product->email }}</p>@endif
                    @if($product->expires_at)
                        <p class="mb-2"><strong>Актуально до:</strong> {{ $product->expires_at->format('d.m.Y H:i') }}</p>
                    @endif

                    @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="number" name="quantity" value="1" min="1" class="form-control">
                                <button class="btn btn-primary" type="submit">Добавить в корзину</button>
                            </div>
                        </form>

                        <div class="mt-5">
                            <h5><i class="bi bi-chat-left-text me-2"></i>Чат с продавцом</h5>
                            <a href="{{ route('messages.index', $product) }}" class="btn btn-outline-info">Открыть чат</a>
                        </div>
                    @endauth
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Все товары
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
