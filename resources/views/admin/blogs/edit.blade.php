@extends('layouts.backend')

@section('title', 'Edit Info / Promo')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Edit Info / Promo: {{ $blog->title }}</h1>

{{-- Notifikasi Validasi --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.blogs.update', $blog->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Judul --}}
    <div class="form-group">
        <label>Judul <span class="text-danger">*</span></label>
        <input
            type="text"
            name="title"
            class="form-control"
            value="{{ old('title', $blog->title) }}"
            required
        >
    </div>

    {{-- Jenis --}}
    <div class="form-group">
        <label>Jenis <span class="text-danger">*</span></label>
        <select name="type" id="type" class="form-control" required>
            <option value="info" {{ old('type', $blog->type) == 'info' ? 'selected' : '' }}>Info</option>
            <option value="promo" {{ old('type', $blog->type) == 'promo' ? 'selected' : '' }}>Promo</option>
        </select>
    </div>

    {{-- Kolom khusus PROMO --}}
    <div id="promo-fields" style="{{ old('type', $blog->type) === 'promo' ? '' : 'display:none;' }}">

        <div class="form-group">
            <label>Harga Asli</label>
            <input
                type="number"
                name="original_price"
                class="form-control"
                value="{{ old('original_price', $blog->original_price) }}"
                min="0"
            >
        </div>

        <div class="form-group">
            <label>Harga Promo</label>
            <input
                type="number"
                name="promo_price"
                class="form-control"
                value="{{ old('promo_price', $blog->promo_price) }}"
                min="0"
            >
        </div>

        <div class="form-group">
            <label>Diskon (%)</label>
            <input
                type="number"
                name="discount_percent"
                class="form-control"
                value="{{ old('discount_percent', $blog->discount_percent) }}"
                min="0"
                max="100"
            >
        </div>

        <div class="form-group">
            <label>Masa Promo Mulai</label>
            <input
                type="datetime-local"
                name="promo_start"
                class="form-control"
                value="{{ old('promo_start', optional($blog->promo_start)->format('Y-m-d\TH:i')) }}"
            >
        </div>

        <div class="form-group">
            <label>Masa Promo Berakhir</label>
            <input
                type="datetime-local"
                name="promo_end"
                class="form-control"
                value="{{ old('promo_end', optional($blog->promo_end)->format('Y-m-d\TH:i')) }}"
            >
        </div>

    </div>

    {{-- Script show/hide promo --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const typeSelect  = document.getElementById('type');
            const promoFields = document.getElementById('promo-fields');

            function toggleFields() {
                if (typeSelect.value === 'promo') {
                    promoFields.style.display = 'block';
                } else {
                    promoFields.style.display = 'none';
                }
            }

            typeSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>

    {{-- Penulis --}}
    <div class="form-group">
        <label>Penulis</label>
        <input
            type="text"
            name="author"
            class="form-control"
            value="{{ old('author', $blog->author) }}"
            placeholder="Kosongkan untuk default: Admin Hansport"
        >
    </div>

    {{-- Tanggal Publikasi --}}
    <div class="form-group">
        <label>Tanggal Publikasi</label>
        <input
            type="date"
            name="published_at"
            class="form-control"
            value="{{ old('published_at', optional($blog->published_at)->format('Y-m-d')) }}"
        >
    </div>

    {{-- Gambar --}}
    <div class="form-group">
        <label>Gambar (opsional)</label>
        <input
            type="file"
            name="image"
            class="form-control"
            accept="image/*"
        >
        @if ($blog->image)
            <div class="mt-2">
                <img
                    src="{{ asset('storage/' . $blog->image) }}"
                    alt="Gambar"
                    class="img-fluid"
                    style="max-height: 150px;"
                >
            </div>
        @endif
        <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
    </div>

    {{-- Konten --}}
    <div class="form-group">
        <label>Konten <span class="text-danger">*</span></label>
        <textarea
            name="content"
            class="form-control"
            rows="6"
            required
        >{{ old('content', $blog->content) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
</form>
@endsection
