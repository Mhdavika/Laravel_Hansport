@extends('layouts.backend')
@section('title', 'Manajemen Blog')

@section('content')
<h1 class="h3 mb-4 text-gray-800 d-flex justify-content-between align-items-center">
    Daftar Blog
    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">+ Tambah Blog</a>
</h1>

@if(request('status') == 'created')
    <div class="alert alert-success">Blog berhasil ditambahkan.</div>
@elseif(request('status') == 'updated')
    <div class="alert alert-success">Blog berhasil diperbarui.</div>
@elseif(request('status') == 'deleted')
    <div class="alert alert-success">Blog berhasil dihapus.</div>
@endif

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Author</th>
            <th>Dipublikasikan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($blogs as $blog)
        <tr>
            <td>{{ $blog->title }}</td>
            <td>{{ $blog->author }}</td>
            <td>{{ $blog->published_at?->format('d M Y') ?? '-' }}</td>
            <td>
                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-warning">Edit</a>

                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" style="display:inline-block;" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus blog ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
