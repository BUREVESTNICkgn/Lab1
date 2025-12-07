@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <h1 class="h4 fw-bold mb-1">Редактировать пользователя</h1>
        <p class="text-muted mb-0">{{ $user->email }}</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.users.partials.form', ['user' => $user])
            </form>
        </div>
    </div>
</div>
@endsection
