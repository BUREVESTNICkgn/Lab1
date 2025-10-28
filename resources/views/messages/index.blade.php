@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Чат по товару "{{ $product->title }}"</h1>
    <div class="card">
        <div class="card-body">
            @foreach($messages as $msg)
                <div class="mb-3 {{ $msg->from_user_id == auth()->id() ? 'text-end' : '' }}">
                    <strong>{{ $msg->fromUser->name }}:</strong> {{ $msg->body }}
                    <small class="text-muted">{{ $msg->created_at->diffForHumans() }}</small>
                </div>
            @endforeach
        </div>
    </div>
    <form action="{{ route('messages.store', $product) }}" method="POST" class="mt-4">
        @csrf
        <textarea name="body" class="form-control mb-2" placeholder="Ваше сообщение..." required></textarea>
        <button type="submit" class="btn btn-primary">Отправить</button>
    </form>
</div>
@endsection