{{-- filepath: resources/views/admin/chat/index.blade.php --}}
@extends('layouts.backend')

@section('title', 'Chat dengan User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Chat dengan <strong>{{ $user->name }}</strong></h6>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="chat-box" style="border: 1px solid #ddd; padding: 20px; height: 400px; overflow-y: auto; margin-bottom: 20px; background-color: #f9f9f9; border-radius: 5px;">
                        @forelse($chats as $chat)
                            <div style="margin-bottom: 15px; display: flex; justify-content: {{ $chat->sender_id === auth()->id() ? 'flex-end' : 'flex-start' }};">
                                <div style="background-color: {{ $chat->sender_id === auth()->id() ? '#007bff' : '#e9ecef' }}; color: {{ $chat->sender_id === auth()->id() ? 'white' : 'black' }}; padding: 12px 15px; border-radius: 10px; max-width: 70%; word-wrap: break-word;">
                                    <p style="margin: 0 0 5px 0; font-size: 14px;">{{ $chat->message }}</p>
                                    <small style="opacity: 0.7; font-size: 12px;">{{ $chat->created_at->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted">Belum ada chat dengan user ini.</p>
                        @endforelse
                    </div>

                    <form action="/admin/chat/store" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <div class="form-group mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea name="message" id="message" placeholder="Ketik pesan..." required class="form-control" rows="3"></textarea>
                            @error('message')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane"></i> Kirim
                            </button>
                            <a href="/admin/chat/users" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection