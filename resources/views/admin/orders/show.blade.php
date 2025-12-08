@extends('layouts.backend')
@section('title', 'Detail Pesanan')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
        <span class="badge badge-pill badge-{{
            match($order->status) {
                'pending' => 'warning',
                'proses' => 'info',
                'dikirim' => 'primary',
                'selesai' => 'success',
                'dibatalkan' => 'danger',
                default => 'secondary'
            }
        }} p-2">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="card shadow-sm p-4 mb-4">
        <h5 class="mb-4">Informasi Pemesan</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <p><i class="fas fa-user text-primary"></i> <strong>Nama:</strong> {{ $order->name }}</p>
                <p><i class="fas fa-envelope text-primary"></i> <strong>Email:</strong> {{ $order->email }}</p>
                <p><i class="fas fa-phone text-primary"></i> <strong>No. Telepon:</strong> {{ $order->phone }}</p>
            </div>
            <div class="col-md-6 mb-3">
                <p><i class="fas fa-map-marker-alt text-primary"></i> <strong>Alamat:</strong><br>
                    {{ $order->address }}, {{ $order->district }}, {{ $order->city }},
                    {{ $order->province }} - {{ $order->postal_code }}
                </p>
                <p><i class="fas fa-credit-card text-primary"></i> <strong>Metode Pembayaran:</strong> {{ ucfirst($order->payment_method) }}</p>

                @if ($order->payment_method == 'transfer')
                <p><strong>Bank:</strong> {{ strtoupper($order->bank_name) }}</p>
                @elseif ($order->payment_method == 'ewallet')
                <p><strong>E-Wallet:</strong> {{ strtoupper($order->ewallet_name) }}</p>
                @endif
            </div>
        </div>

        @if($order->proof_file)
        <div class="mt-3">
            <strong>Bukti Transfer:</strong><br>
            <a href="{{ asset('storage/' . $order->proof_file) }}" class="btn btn-outline-primary btn-sm mt-1" target="_blank">
                <i class="fas fa-receipt"></i> Lihat Bukti
            </a>
        </div>
        @endif
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-3">Daftar Produk</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Produk</th>
                            <th>Ukuran</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->size ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-right">
                <strong>Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="status">Ubah Status Pesanan:</label>
                        <select name="status" class="form-control" id="status">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="proses" {{ $order->status == 'proses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark w-100">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection