@extends('layouts.backend')

@section('title', 'Daftar Produk')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h1 class="h3 text-gray-800">Daftar Produk</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Tabel Produk</h6>
        <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Tambah Produk
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name ?? '-' }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection