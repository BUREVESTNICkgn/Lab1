@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h2 class="h4 fw-bold mb-0 text-dark">
                        <i class="bi bi-pencil-square text-primary me-2"></i>
                        Редактировать товар
                    </h2>
                </div>

                <div class="card-body">
                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Название</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $product->title) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Цена</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-medium">Категория <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control">
                                <option value="">Без категории</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Местоположение</label>
                                <input type="text" name="location" class="form-control" value="{{ old('location', $product->location) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Доставка</label>
                                <input type="text" name="delivery" class="form-control" value="{{ old('delivery', $product->delivery) }}">
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Телефон</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $product->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $product->email) }}">
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="expires_at" class="form-label fw-medium">Срок размещения</label>
                            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $product->expires_at?->format('Y-m-d\TH:i')) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Текущие фото</label>
                            <div class="d-flex flex-wrap">
                                @foreach($product->images as $img)
                                    <div class="me-2 mb-2 text-center">
                                        <img src="{{ Storage::url($img->path) }}" alt="" width="100" class="d-block mb-1">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="images" class="form-label fw-medium">Добавить новые фото</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Отмена
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Сохранить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
