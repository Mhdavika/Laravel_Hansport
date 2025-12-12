@extends('layouts.frontend')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    @include('layouts.breadcrumbs')

    <h2 class="mb-4">Keranjang Belanja</h2>

    {{-- Flash message --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Menampilkan ukuran produk --}}
    @foreach ($cartItems as $cartItem)
        @if($cartItem->size)
        @endif
    @endforeach

    {{-- TABEL KERANJANG --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">Pilih</th>
                    <th>Produk</th>
                    <th style="width: 120px;">Harga</th>
                    <th style="width: 140px;">Qty</th>
                    <th style="width: 140px;">Subtotal</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cartItems as $item)
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" name="cart_ids[]" value="{{ $item->id }}" class="cart-checkbox">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="" width="60" class="me-2">
                                @endif
                                <div>
                                    <!-- Tautan ke halaman detail produk -->
                                    <a href="{{ route('product.show', $item->product->id) }}" class="product-link">
                                        <strong>{{ $item->product->name ?? 'Produk tidak ditemukan' }}</strong>
                                    </a><br>
                                    @if($item->size)
                                        <small>Ukuran: {{ $item->size }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            Rp{{ number_format($item->product->price ?? 0, 0, ',', '.') }}
                        </td>
                        <td>
                            <div class="d-flex">
                                {{-- tombol minus --}}
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="me-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="decrease">
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">-</button>
                                </form>

                                <div class="px-2 align-self-center">
                                    {{ $item->quantity }}
                                </div>

                                {{-- tombol plus --}}
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="ms-1">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="action" value="increase">
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">+</button>
                                </form>
                            </div>
                        </td>
                        <td>
                            Rp{{ number_format(($item->product->price ?? 0) * $item->quantity, 0, ',', '.') }}
                        </td>
                        <td>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOTAL & TOMBOL CHECKOUT --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div>
            <strong>Total Keranjang:</strong>
            Rp{{ number_format($cartTotal, 0, ',', '.') }}
        </div>

        <button type="button" id="checkout-selected" class="btn btn-dark">
            Checkout Barang Terpilih
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkoutBtn = document.getElementById('checkout-selected');

        if (!checkoutBtn) return;

        checkoutBtn.addEventListener('click', function () {
            const checked = Array.from(document.querySelectorAll('.cart-checkbox:checked'));

            if (checked.length === 0) {
                alert('Silakan pilih minimal satu produk untuk checkout.');
                return;
            }

            let url = "{{ route('checkout.index') }}" + '?';
            checked.forEach((cb, index) => {
                url += (index === 0 ? '' : '&') + 'cart_ids[]=' + encodeURIComponent(cb.value);
            });

            window.location.href = url;
        });

        // Cek parameter URL dan tandai produk yang sudah dipilih
        const urlParams = new URLSearchParams(window.location.search);
        const cartIds = urlParams.getAll('cart_ids[]');
        
        if (cartIds.length > 0) {
            // Tandai checkbox berdasarkan ID produk yang ada di URL
            cartIds.forEach(cartId => {
                const checkbox = document.querySelector(`input.cart-checkbox[value="${cartId}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }
    });
</script>
@endpush


@push('styles')
<style>
    /* Styling untuk link produk agar warna teks hitam */
    .product-link {
        color: #000 !important; /* Warna hitam */
        text-decoration: none; /* Menghilangkan underline */
    }

    /* Efek hover untuk memberikan underline saat mouse berada di atas link */
    .product-link:hover {
        text-decoration: underline; /* Menambahkan underline saat hover */
    }
</style>
@endpush
