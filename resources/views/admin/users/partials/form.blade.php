<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Имя</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Пароль @if($user) <span class="text-muted">(оставьте пустым, если не меняем)</span> @endif</label>
        <input type="password" name="password" class="form-control" @if(!$user) required @endif>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Роль</label>
        <select name="role" class="form-select" required>
            @foreach(['user' => 'Покупатель', 'manager' => 'Менеджер заказов', 'admin' => 'Администратор'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role ?? 'user') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary">Сохранить</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Отмена</a>
</div>

@if($errors->any())
    <div class="alert alert-danger mt-3">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
