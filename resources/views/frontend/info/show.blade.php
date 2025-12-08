@extends('layouts.frontend')

@section('title', $post->title)

@section('content')
<div class="container py-5">

    <a href="{{ route('info-promo.index') }}" class="btn btn-light mb-3">
        &larr; Kembali
    </a>

    <h2 class="mb-2">{{ $post->title }}</h2>

    <p class="text-muted mb-4">
        {{ $post->published_at ? $post->published_at->format('d M Y') : '-' }}
        • {{ $post->author ?? 'Admin' }}
    </p>

    @if($post->image)
        <img src="{{ asset('storage/' . $post->image) }}"
             class="img-fluid mb-4"
             style="border-radius:8px; max-height:450px; object-fit:cover;">
    @endif

    <div style="font-size: 17px; line-height: 1.6;">
        {!! $post->content !!}
    </div>

</div>
@endsection
