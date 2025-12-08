@extends('layouts.frontend')

@section('title', 'Edit Password')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/profile_styles.css') }}">
@endpush

@section('content')
<div class="profile-page">
    @include('layouts.breadcrumbs')

    <div class="container mt-5">
        <div class="profile-wrapper d-flex flex-nowrap">
            <div class="profile-sidebar">
                @include('layouts.sidebar')
            </div>

            <div class="profile-main">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card p-4 shadow-sm">
                    <h4 class="mb-3">Ganti Password</h4>

                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password">Password Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password">Password Baru</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection