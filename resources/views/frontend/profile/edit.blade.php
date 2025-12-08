@extends('layouts.frontend')

@section('title', 'Profile - Edit')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/profile_styles.css') }}">
<link href="{{ asset('frontend/plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
<div class="profile-page">
    @include('layouts.breadcrumbs')

    <div class="container mt-5">
        <div class="profile-wrapper">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                @include('layouts.sidebar')
            </div>

            <!-- Main content -->
            <div class="profile-main">
                @if(session('success'))
                <div class="alert alert-success" id="success-alert">
                    {{ session('success') }}
                </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name"><i class="fa fa-user mr-2"></i>Nama</label>
                        <input type="text" id="name" name="name" class="form-control"
                            value="{{ old('name', $user->name) }}" placeholder="Masukkan nama Anda">
                    </div>

                    <div class="mb-3">
                        <label for="email"><i class="fa fa-envelope mr-2"></i>Email</label>
                        <input type="email" id="email" name="email" class="form-control"
                            value="{{ old('email', $user->email) }}" placeholder="Masukkan email aktif">
                    </div>

                    <div class="mb-3">
                        <label for="phone"><i class="fa fa-phone mr-2"></i>Nomor Telepon</label>
                        <input type="text" id="phone" name="phone" class="form-control"
                            value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08xxxxxxxxxx">
                    </div>

                    <div class="mb-3">
                        <label for="address"><i class="fa fa-home mr-2"></i>Alamat</label>
                        <textarea id="address" name="address" class="form-control" placeholder="Masukkan alamat lengkap">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="province"><i class="fa fa-map mr-2"></i>Provinsi</label>
                        <input type="text" id="province" name="province" class="form-control"
                            value="{{ old('province', $user->province) }}" placeholder="Contoh: Sumatera Barat">
                    </div>

                    <div class="mb-3">
                        <label for="city"><i class="fa fa-map-marker mr-2"></i>Kota/Kabupaten</label>
                        <input type="text" id="city" name="city" class="form-control"
                            value="{{ old('city', $user->city) }}" placeholder="Contoh: Padang">
                    </div>

                    <div class="mb-3">
                        <label for="district"><i class="fa fa-location-arrow mr-2"></i>Kecamatan</label>
                        <input type="text" id="district" name="district" class="form-control"
                            value="{{ old('district', $user->district) }}" placeholder="Contoh: Kuranji">
                    </div>

                    <div class="mb-3">
                        <label for="postal_code"><i class="fa fa-envelope-o mr-2"></i>Kode Pos</label>
                        <input type="text" id="postal_code" name="postal_code" class="form-control"
                            value="{{ old('postal_code', $user->postal_code) }}" placeholder="Contoh: 25173">
                    </div>

                    <button type="submit" class="btn btn-dark">
                        <i class="fa fa-save mr-2"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('frontend/js/profile_custom.js') }}"></script>
@endpush
