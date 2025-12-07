@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1">Новый пользователь</h1>
        <p class="text-muted mb-0">Заполните данные и выберите роль.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                @include('admin.users.partials.form', ['user' => null])
            </form>
        </div>
    </div>
</div>
@endsection
