@extends('layouts.backend')
@section('title', 'Hasil Pencarian')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Hasil Pencarian untuk: <em>{{ $q }}</em></h1>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="searchTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="produk-tab" data-toggle="tab" href="#produk" role="tab">Produk ({{ $products->count() }})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="blog-tab" data-toggle="tab" href="#blog" role="tab">Blog ({{ $blogs->count() }})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="user-tab" data-toggle="tab" href="#user" role="tab">User ({{ $users->count() }})</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="order-tab" data-toggle="tab" href="#order" role="tab">Pesanan ({{ $orders->count() }})</a>
    </li>
</ul>

<div class="tab-content" id="searchTabContent">
    <!-- Produk -->
    <div class="tab-pane fade show active" id="produk" role="tabpanel">
        @forelse($products as $product)
        <div class="border-bottom py-2">
            <strong>{{ $product->name }}</strong><br>
            Kategori: {{ $product->category->name ?? '-' }} | Harga: Rp{{ number_format($product->price, 0, ',', '.') }}
            <div><a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning mt-1">Edit</a></div>
        </div>
        @empty
        <p class="text-muted">Tidak ada produk ditemukan.</p>
        @endforelse
    </div>

    <!-- Blog -->
    <div class="tab-pane fade" id="blog" role="tabpanel">
        @forelse($blogs as $blog)
        <div class="border-bottom py-2">
            <strong>{{ $blog->title }}</strong> oleh {{ $blog->author }}<br>
            Tanggal: {{ date('d M Y', strtotime($blog->published_at)) }}
            <div><a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-warning mt-1">Edit</a></div>
        </div>
        @empty
        <p class="text-muted">Tidak ada blog ditemukan.</p>
        @endforelse
    </div>

    <!-- User -->
    <div class="tab-pane fade" id="user" role="tabpanel">
        @forelse($users as $user)
        <div class="border-bottom py-2">
            <strong>{{ $user->name }}</strong> - {{ $user->email }}
        </div>
        @empty
        <p class="text-muted">Tidak ada pengguna ditemukan.</p>
        @endforelse
    </div>

    <!-- Order -->
    <div class="tab-pane fade" id="order" role="tabpanel">
        @forelse($orders as $order)
        <div class="border-bottom py-2">
            <strong>{{ $order->name }}</strong> - {{ $order->email }}<br>
            Total: Rp{{ number_format($order->total_price, 0, ',', '.') }} | Status: {{ ucfirst($order->status) }}
            <div><a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info mt-1">Lihat Detail</a></div>
        </div>
        @empty
        <p class="text-muted">Tidak ada pesanan ditemukan.</p>
        @endforelse
    </div>
</div>
@endsection
