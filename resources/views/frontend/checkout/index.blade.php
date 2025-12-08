@extends('layouts.frontend')

@section('title', 'Checkout')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/checkout.css') }}">
@endpush

@section('content')
<div class="container py-5">

    <!-- Breadcrumb -->
    @include('layouts.breadcrumbs')

    <h2 class="mb-4">Checkout</h2>

    <form id="checkout-form" method="POST" action="{{ route('checkout.submit') }}">
        @csrf
        <div class="row">

            <!-- Alamat Pengiriman -->
            <div class="col-md-8">
                <div class="card p-4 mb-4">
                    <h5 class="mb-3">Alamat Pengirim</h5>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama *</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Contoh: Alfian Rahman"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email', $user->email) }}"
                                placeholder="Email aktif, contoh: alfian@mail.com"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No. Telepon *</label>
                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="Contoh: 0812xxxxxxxx"
                                required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kode Pos *</label>
                            <input
                                type="text"
                                name="postal_code"
                                class="form-control"
                                value="{{ old('postal_code', $user->postal_code) }}"
                                placeholder="Contoh: 40115"
                                required>
                        </div>

                        {{-- PROVINSI --}}
                        <div class="col-md-6">
                            <label class="form-label">Provinsi *</label>
                            <select name="province" id="province" class="form-control" required>
                                <option value="">-- Pilih Provinsi --</option>
                                @if(isset($provinces) && $provinces->count())
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov->id }}" {{ old('province') == $prov->id ? 'selected' : '' }}>{{ $prov->name }}</option>
                                    @endforeach
                                @endif
                                {{-- opsi juga akan diisi via JS dari /ajax/provinces jika tersedia --}}
                            </select>
                            <small class="text-muted">Saat ini pengiriman difokuskan ke wilayah Pulau Jawa.</small>
                        </div>

                        {{-- KOTA / KABUPATEN --}}
                        <div class="col-md-6">
                            <label class="form-label">Kota / Kabupaten *</label>
                            <select name="city" id="city" class="form-control" required>
                                <option value="">-- Pilih Kota / Kabupaten --</option>
                            </select>
                            <small class="text-muted">Pilih kota/kabupaten tujuan pengiriman.</small>
                        </div>

                        {{-- KECAMATAN --}}
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan *</label>
                            <select name="district" id="district" class="form-control" required>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                            <small class="text-muted">Pilih kecamatan tujuan pengiriman.</small>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat Lengkap *</label>
                            <textarea
                                name="address"
                                rows="3"
                                class="form-control"
                                placeholder="Contoh: Jl. Sukajadi No.10, RT 02 RW 03, dekat minimarket X"
                                required>{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan & Metode Bayar -->
            <div class="col-md-4">
                <div class="card p-4 mb-4">
                    <h5 class="mb-3">Ringkasan Pesanan</h5>

                    @foreach ($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <small>{{ $item->product->name }}</small><br>
                                <small>
                                    Kuantitas: {{ $item->quantity }}
                                    {{ $item->size ? ', Ukuran: '.$item->size : '' }}
                                </small>
                            </div>
                            <strong>Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</strong>
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Total Pembayaran:</span>
                        <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <div class="card p-4">
                    <h5 class="mb-3">Pilih Metode Pembayaran</h5>

                    {{-- Hanya transfer & e-wallet --}}
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="transfer" id="transfer" required>
                        <label class="form-check-label" for="transfer">Transfer Bank</label>
                    </div>
                    <div class="ms-4 mb-3 d-none" id="bank-options">
                        <select name="bank_name" class="form-control">
                            <option value="">-- Pilih Bank --</option>
                            <option value="bca">BCA</option>
                            <option value="bri">BRI</option>
                            <option value="mandiri">Mandiri</option>
                            <option value="bni">BNI</option>
                        </select>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" value="ewallet" id="ewallet">
                        <label class="form-check-label" for="ewallet">E-Wallet</label>
                    </div>
                    <div class="ms-4 d-none" id="ewallet-options">
                        <select name="ewallet_name" class="form-control">
                            <option value="">-- Pilih E-Wallet --</option>
                            <option value="dana">DANA</option>
                            <option value="ovo">OVO</option>
                            <option value="gopay">GoPay</option>
                        </select>
                    </div>

                    <button class="btn btn-dark-checkout w-100 mt-3">LANJUTKAN PEMBAYARAN</button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect = document.getElementById('province');
    const citySelect     = document.getElementById('city');
    const districtSelect = document.getElementById('district');

    const transferRadio  = document.getElementById('transfer');
    const ewalletRadio   = document.getElementById('ewallet');
    const bankOptions    = document.getElementById('bank-options');
    const ewalletOptions = document.getElementById('ewallet-options');

    // ====== LOAD PROVINCES ======
    fetch('/ajax/provinces')
        .then(response => response.json())
        .then(data => {
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;      // kirim ID ke backend
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        });

    // ====== LOAD CITIES WHEN PROVINCE SELECTED ======
    provinceSelect.addEventListener('change', function () {
        const selectedProvinceId = this.value;

        citySelect.innerHTML     = '<option value="">-- Pilih Kota / Kabupaten --</option>';
        districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        if (!selectedProvinceId) return;

        fetch(`/ajax/cities?province_id=${selectedProvinceId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;       // kirim ID kota
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
            });
    });

    // ====== LOAD DISTRICTS WHEN CITY SELECTED ======
    citySelect.addEventListener('change', function () {
        const selectedCityId = this.value;

        districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        if (!selectedCityId) return;

        fetch(`/ajax/districts?city_id=${selectedCityId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;   // kirim ID kecamatan (lebih konsisten)
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            });
    });

    // ====== TOGGLE METODE BAYAR ======
    function updatePaymentOptions() {
        if (transferRadio.checked) {
            bankOptions.classList.remove('d-none');
            ewalletOptions.classList.add('d-none');
        } else if (ewalletRadio.checked) {
            ewalletOptions.classList.remove('d-none');
            bankOptions.classList.add('d-none');
        } else {
            bankOptions.classList.add('d-none');
            ewalletOptions.classList.add('d-none');
        }
    }

    if (transferRadio) {
        transferRadio.addEventListener('change', updatePaymentOptions);
    }
    if (ewalletRadio) {
        ewalletRadio.addEventListener('change', updatePaymentOptions);
    }

    // panggil sekali di awal (kalau nanti mau ada default)
    updatePaymentOptions();
});
</script>
@endpush
