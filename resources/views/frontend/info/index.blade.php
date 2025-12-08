@extends('layouts.frontend')

@section('title', 'Info & Promo Hansport')

@section('content')
<div class="container py-5 mt-5">

    <h2 class="mb-4">Info & Promo Hansport</h2>

    @if ($posts->isEmpty())
        <p class="text-muted">Belum ada info atau promo.</p>
    @else
        <div class="row">
            @foreach ($posts as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="position: relative;">

                        {{-- Badge Promo --}}
                        @if ($item->type === 'promo')
                            <span class="badge bg-danger text-white text-uppercase"
                                  style="position:absolute; top:10px; left:10px; z-index:2;">
                                Promo
                            </span>
                        @endif

                        {{-- Gambar --}}
                        @if ($item->image)
                            <div style="
                                width: 100%;
                                height: 230px;
                                overflow: hidden;
                                border-top-left-radius: 8px;
                                border-top-right-radius: 8px;
                            ">
                                <img
                                    src="{{ asset('storage/' . $item->image) }}"
                                    alt="{{ $item->title }}"
                                    style="
                                        width: 100%;
                                        height: 100%;
                                        object-fit: cover;
                                        object-position: center;
                                        display: block;
                                    "
                                >
                            </div>
                        @endif

                        <div class="card-body">

                            <h5 class="card-title">{{ $item->title }}</h5>

                            {{-- Tanggal & Penulis --}}
                            <p class="text-muted mb-1">
                                {{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}
                                @if ($item->author)
                                    • {{ $item->author }}
                                @endif
                            </p>

                            {{-- DETAIL PROMO --}}
                            @if ($item->type === 'promo')

                                {{-- Periode Promo --}}
                                @if ($item->promo_start && $item->promo_end)
                                    <p class="mb-1 text-danger small">
                                        Promo: {{ $item->promo_start->format('d M Y H:i') }}
                                        s/d {{ $item->promo_end->format('d M Y H:i') }}
                                    </p>
                                @endif

                                {{-- Harga dan Diskon --}}
                                @if ($item->original_price && $item->promo_price)
                                    <p class="mb-0">
                                        <span class="text-muted" style="text-decoration: line-through;">
                                            Rp {{ number_format($item->original_price, 0, ',', '.') }}
                                        </span>
                                    </p>

                                    <p class="mb-1">
                                        <strong>
                                            Rp {{ number_format($item->promo_price, 0, ',', '.') }}
                                        </strong>

                                        @if ($item->discount_percent)
                                            <span class="text-danger">(-{{ $item->discount_percent }}%)</span>
                                        @endif
                                    </p>
                                @endif

                            @endif

                            {{-- Konten Ringkas --}}
                            <p class="card-text">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->content), 110) }}
                            </p>

                            <a href="{{ route('info-promo.show', $item->id) }}" class="btn btn-primary btn-sm">
                                Lihat Selengkapnya
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    @endif

</div>
@endsection
