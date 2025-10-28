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
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-1"></i>Удалить
                            </button>
                        </div>
                    @endif
                </div>

                <div class="card-body">
                    <!-- Карусель -->
                    @if ($product->images->count() > 0)
                        <div id="productCarousel" class="carousel slide mb-4">
                            <div class="carousel-inner">
                                @foreach ($product->images as $key => $img)
                                    <div class="carousel-item @if($key==0) active @endif">
                                        <img src="{{ Storage::url($img->path) }}" class="d-block w-100 rounded" alt="">
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

                    <!-- ... твои поля описания, цена, etc. ... -->

                    @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="number" name="quantity" value="1" min="1" class="form-control">
                                <button class="btn btn-primary" type="submit">Добавить в корзину</button>
                            </div>
                        </form>

                        <!-- Чат -->
                        <div class="mt-5">
                            <h5><i class="bi bi-chat-left-text me-2"></i>Чат с продавцом</h5>
                            <a href="{{ route('messages.index', $product) }}" class="btn btn-outline-info">Открыть чат</a>
                        </div>
                    @endauth
                </div>

                <!-- ... footer ... -->
            </div>

            <!-- Модальное для удаления -->
            <div class="modal fade" id="deleteModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Подтверждение удаления</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            Уверены, что хотите удалить "{{ $product->title }}"?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                            <form action="{{ route('products.destroy', $product) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">Удалить</button>
                            </form>
                        </div>
                    </div>
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