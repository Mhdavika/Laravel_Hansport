{{-- filepath: resources/views/frontend/chat/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Chat')

@section('content')
<div class="chat-container">
    <div class="chat-header">
        <a href="{{ route('homepage') }}" class="back-btn">
            <i class="fa fa-arrow-left"></i>
        </a>
        <h3>Admin Hansports</h3>
    </div>

  <div class="messages" style="display: flex; flex-direction: column-reverse;">
    @foreach($chats as $chat)
        <div class="chat-message {{ $chat->sender_id == Auth::id() ? 'me' : 'admin' }}">
            <div class="message-bubble">
                <p>{{ $chat->message }}</p>
            </div>
            <small style="font-size: 11px; opacity: 0.7; margin-top: 5px;">
                {{ $chat->created_at->format('d M Y H:i') }}
            </small>
        </div>
    @endforeach
</div>


    <form action="{{ route('chat.store') }}" method="POST">
        @csrf
        <div class="message-input">
            <textarea name="message" placeholder="Kirim pesan..." required></textarea>
            <button type="submit">Kirim</button>
        </div>
    </form>
</div>
@endsection