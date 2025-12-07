@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h2 class="h4 fw-bold mb-0 text-dark">
                        <i class="bi bi-plus-circle text-success me-2"></i>
                        Добавить новый товар
                    </h2>
                    <p class="text-muted small mb-0">Заполните все обязательные поля</p>
                </div>

                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Название</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Цена</label>
                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-3 align-items-end mb-2">
                            <div class="col-md-6">
                                <label class="form-label">Стоимость доставки (₽)</label>
                                <input type="number" step="0.01" name="shipping_cost" class="form-control" value="{{ old('shipping_cost', 0) }}">
                                <div class="form-text">Покупатель увидит эту стоимость при оформлении заказа.</div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="pickup_available" id="pickup_available" {{ old('pickup_available') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="pickup_available">Самовывоз доступен</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-medium">Категория <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                <option value="">Без категории</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Местоположение</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Доставка</label>
                                <input type="text" name="delivery" class="form-control" value="{{ old('delivery') }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Телефон</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="expires_at" class="form-label fw-medium">Срок размещения (опционально)</label>
                            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                        </div>

                        <div class="mb-4">
                            <label for="images" class="form-label fw-medium">Фотографии товара (несколько)</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                            <div class="form-text">Макс. 5 МБ на фото, до 5 фото.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Отмена
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> Опубликовать
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
