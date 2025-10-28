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

                        <!-- Категория -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-medium">Категория <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ... твои поля с value="{{ old('field', $product->field) }}" ... -->

                        <!-- Срок -->
                        <div class="mb-3">
                            <label for="expires_at" class="form-label fw-medium">Срок размещения</label>
                            <input type="datetime-local" name="expires_at" class="form-control" value="{{ old('expires_at', $product->expires_at?->format('Y-m-d\TH:i')) }}">
                        </div>

                        <!-- Текущие фото -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">Текущие фото</label>
                            <div class="d-flex flex-wrap">
                                @foreach($product->images as $img)
                                    <div class="me-2 mb-2">
                                        <img src="{{ Storage::url($img->path) }}" alt="" width="100">
                                        <form action="/images/{{ $img->id }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Новые фото -->
                        <div class="mb-4">
                            <label for="images" class="form-label fw-medium">Добавить новые фото</label>
                            <input type="file" name="images[]" class="form-control" multiple>
                        </div>

                        <!-- Кнопки -->
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