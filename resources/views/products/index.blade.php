@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-dark">
                    <i class="bi bi-laptop me-2 text-primary"></i>
                    Товары вычислительной техники
                </h1>
                @auth
                    <a href="{{ route('products.create') }}" class="btn btn-success d-flex align-items-center">
                        <i class="bi bi-plus-circle me-1"></i> Добавить товар
                    </a>
                @endauth
            </div>

            <!-- Поиск + Категории -->
            <form action="{{ route('products.index') }}" method="GET" class="mb-5">
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Поиск..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="category" class="form-control form-control-lg">
                            <option value="">Все категории</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-lg w-100" type="submit">
                            <i class="bi bi-search"></i> Найти
                        </button>
                    </div>
                </div>
            </form>

            <!-- Список -->
            @if($products->isEmpty())
                <!-- твой код empty -->
            @else
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach ($products as $product)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 hover-shadow">
                                <!-- Карусель для фото -->
                                @if ($product->images->count() > 0)
                                    <div id="carousel-{{ $product->id }}" class="carousel slide">
                                        <div class="carousel-inner">
                                            @foreach ($product->images as $key => $img)
                                                <div class="carousel-item @if($key==0) active @endif">
                                                    <img src="{{ Storage::url($img->path) }}" class="d-block w-100" style="height: 180px; object-fit: cover;">
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
                                    <h5 class="card-title fw-bold">{{ $product->title }}</h5>
                                    <p class="card-text text-muted flex-grow-1">
                                        {{ Str::limit($product->description, 100) }}
                                    </p>
                                    <div class="mt-auto">
                                        <p class="h5 text-success fw-bold mb-2">
                                            {{ number_format($product->price, 0, ',', ' ') }} ₽
                                        </p>
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary w-100">
                                            Подробнее <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="card-footer bg-white text-muted small">
                                    <i class="bi bi-calendar me-1"></i> {{ $product->created_at->format('d.m.Y') }}
                                    @if ($product->expires_at)
                                        • Истекает {{ $product->expires_at->format('d.m.Y') }}
                                    @endif
                                    • {{ $product->user->name ?? 'Аноним' }}
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