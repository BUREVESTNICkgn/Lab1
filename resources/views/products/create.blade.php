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

                        <!-- Категория -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-medium">Категория <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ... твои поля title, description, price, location, delivery, phone, email ... -->

                        <!-- Срок размещения -->
                        <div class="mb-3">
                            <label for="expires_at" class="form-label fw-medium">Срок размещения (опционально)</label>
                            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                        </div>

                        <!-- Изображения -->
                        <div class="mb-4">
                            <label for="images" class="form-label fw-medium">Фотографии товара (несколько)</label>
                            <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                            <div class="form-text">Макс. 5 МБ на фото, до 5 фото.</div>
                        </div>

                        <!-- Кнопки -->
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