@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="hero-banner mb-4 d-flex justify-content-between align-items-start">
                <div>
                    <h1>Каталог вычислительной техники</h1>
                    <p class="mb-3 text-white-50">Современный маркетплейс с подборками, фильтрами и аккуратными карточками товаров.</p>
                    <div class="hero-badges d-flex gap-2 flex-wrap">
                        <span class="badge"><i class="bi bi-shield-check me-1"></i> Проверенные продавцы</span>
                        <span class="badge"><i class="bi bi-truck me-1"></i> Доставка и самовывоз</span>
                        <span class="badge"><i class="bi bi-stars me-1"></i> Топовые предложения</span>
                    </div>
                </div>
                @auth
                    <a href="{{ route('products.create') }}" class="btn btn-light text-dark fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Добавить товар
                    </a>
                @endauth
            </div>

            <!-- Поиск + Категории -->
            <form action="{{ route('products.index') }}" method="GET" class="mb-5 search-panel">
                <div class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Что ищем?</label>
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Ноутбук, видеокарта, сервер..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Категория</label>
                        <select name="category" class="form-control form-control-lg">
                            <option value="">Все категории</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-primary btn-lg" type="submit">
                            <i class="bi bi-search"></i> Найти
                        </button>
                    </div>
                </div>
            </form>

            <!-- Список -->
            @if($products->isEmpty())
                <div class="card border-0 shadow-sm text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-search fs-1 text-muted"></i>
                        <h5 class="mt-3">По вашему запросу ничего не найдено</h5>
                        <p class="text-muted">Попробуйте изменить фильтры или добавить свой первый товар.</p>
                    </div>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach ($products as $product)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 hover-shadow product-card rounded-4 overflow-hidden">
                                <!-- Карусель для фото -->
                                @if ($product->images->count() > 0)
                                    <div id="carousel-{{ $product->id }}" class="carousel slide">
                                        <div class="carousel-inner">
                                            @foreach ($product->images as $key => $img)
                                                <div class="carousel-item @if($key==0) active @endif">
                                                    <img src="{{ $img->url }}" class="d-block w-100" style="height: 180px; object-fit: cover;">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if ($product->images->count() > 1)
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $product->id }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $product->id }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                        <i class="bi bi-camera text-muted fs-1"></i>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title fw-bold mb-0">{{ $product->title }}</h5>
                                        @if($product->pickup_available)
                                            <span class="badge bg-success-subtle text-success">Самовывоз</span>
                                        @endif
                                    </div>
                                    <p class="card-text text-muted flex-grow-1">{{ Str::limit($product->description, 110) }}</p>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="price-chip"><i class="bi bi-cash-coin"></i>{{ number_format($product->price, 0, ',', ' ') }} ₽</span>
                                            <span class="badge bg-info-subtle text-info">Доставка {{ number_format($product->shipping_cost, 0, ',', ' ') }} ₽</span>
                                        </div>
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">Подробнее</a>
                                    </div>
                                </div>

                                <div class="card-footer bg-white text-muted small d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-geo-alt me-1"></i>{{ $product->location ?? 'Без города' }}</span>
                                    <span><i class="bi bi-calendar me-1"></i>{{ $product->created_at->format('d.m') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{ $products->links() }}
            @endif
        </div>
    </div>
</div>
@endsection