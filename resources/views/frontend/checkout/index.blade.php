@extends('layouts.frontend')

@section('title', 'Checkout')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/checkout.css') }}">
@endpush

@section('content')
<div class="container py-5">

    @include('layouts.breadcrumbs')

    <h2 class="mb-4">Checkout</h2>

    <form id="checkout-form" method="POST" action="{{ route('checkout.submit') }}">
        @csrf
        <div class="row">

            <!-- ================= ALAMAT ================= -->
            <div class="col-md-8">
                <div class="card p-4 mb-4">
                    <h5 class="mb-3">Alamat Pengirim</h5>
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Nama *</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">No. Telepon *</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $user->phone) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kode Pos *</label>
                            <input type="text" name="postal_code" class="form-control"
                                   value="{{ old('postal_code', $user->postal_code) }}" required>
                        </div>

                        <!-- PROVINSI -->
                        <div class="col-md-6">
                            <label class="form-label">Provinsi *</label>
                            <select name="province" id="province" class="form-control" required>
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                        </div>

                        <!-- KABUPATEN -->
                        <div class="col-md-6">
                            <label class="form-label">Kota / Kabupaten *</label>
                            <select name="city" id="city" class="form-control" required>
                                <option value="">-- Pilih Kota / Kabupaten --</option>
                            </select>
                        </div>

                        <!-- KECAMATAN -->
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan *</label>
                            <select name="district" id="district" class="form-control" required>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Alamat Lengkap *</label>
                            <textarea name="address" rows="3" class="form-control" required>{{ old('address', $user->address) }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ================= RINGKASAN & PEMBAYARAN ================= -->
            <div class="col-md-4">
                <div class="card p-4 mb-4">
                    <h5 class="mb-3">Ringkasan Pesanan</h5>

                    @foreach ($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <small>{{ $item->product->name }} ({{ $item->quantity }})</small>
                            <strong>Rp{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</strong>
                        </div>
                    @endforeach

                    <hr>

                    @php $subtotal = $total; @endphp

                    <div class="mb-2">
                        <label class="form-label">Jasa Pengiriman *</label>
                        <select name="shipping_courier" id="shipping_courier" class="form-control" required>
                            <option value="">-- Pilih Jasa Pengiriman --</option>
                            <option value="jne"  {{ old('shipping_courier') == 'jne'  ? 'selected' : '' }}>JNE Reguler</option>
                            <option value="jnt"  {{ old('shipping_courier') == 'jnt'  ? 'selected' : '' }}>J&T Reguler</option>
                            <option value="pos"  {{ old('shipping_courier') == 'pos'  ? 'selected' : '' }}>POS Kilat Khusus</option>
                        </select>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Subtotal:</span>
                        <strong id="subtotal" data-subtotal="{{ $subtotal }}">
                            Rp{{ number_format($subtotal, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Ongkir:</span>
                        <strong id="ongkir" data-raw="0">Rp0</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Estimasi Pengiriman:</span>
                        <strong id="shipping_eta">-</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Total Pembayaran:</span>
                        <strong id="total">
                            Rp{{ number_format($subtotal, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>

                <!-- METODE PEMBAYARAN -->
                <div class="card p-4">
                    <h5 class="mb-3">Pilih Metode Pembayaran</h5>

                    {{-- Transfer Bank --}}
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method"
                               value="transfer" id="transfer" {{ old('payment_method') == 'transfer' ? 'checked' : '' }} required>
                        <label class="form-check-label" for="transfer">Transfer Bank</label>
                    </div>
                    <div class="ms-4 mb-3 {{ old('payment_method') == 'transfer' ? '' : 'd-none' }}" id="bank-options">
                        <select name="bank_name" class="form-control">
                            <option value="">-- Pilih Bank --</option>
                            <option value="bca"     {{ old('bank_name') == 'bca' ? 'selected' : '' }}>BCA</option>
                            <option value="bri"     {{ old('bank_name') == 'bri' ? 'selected' : '' }}>BRI</option>
                            <option value="mandiri" {{ old('bank_name') == 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                            <option value="bni"     {{ old('bank_name') == 'bni' ? 'selected' : '' }}>BNI</option>
                        </select>
                    </div>

                    {{-- E-Wallet --}}
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method"
                               value="ewallet" id="ewallet" {{ old('payment_method') == 'ewallet' ? 'checked' : '' }}>
                        <label class="form-check-label" for="ewallet">E-Wallet</label>
                    </div>
                    <div class="ms-4 {{ old('payment_method') == 'ewallet' ? '' : 'd-none' }}" id="ewallet-options">
                        <select name="ewallet_name" class="form-control">
                            <option value="">-- Pilih E-Wallet --</option>
                            <option value="dana"  {{ old('ewallet_name') == 'dana' ? 'selected' : '' }}>DANA</option>
                            <option value="ovo"   {{ old('ewallet_name') == 'ovo' ? 'selected' : '' }}>OVO</option>
                            <option value="gopay" {{ old('ewallet_name') == 'gopay' ? 'selected' : '' }}>GoPay</option>
                        </select>
                    </div>

                    <button class="btn btn-dark w-100 mt-3" type="submit">
                        LANJUTKAN PEMBAYARAN
                    </button>

                    {{-- Tampilkan error validasi kalau ada --}}
                    @if ($errors->any())
                        <div class="mt-3 alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinceSelect  = document.getElementById('province');
    const citySelect      = document.getElementById('city');
    const districtSelect  = document.getElementById('district');

    const subtotalEl      = document.getElementById('subtotal');
    const ongkirEl        = document.getElementById('ongkir');
    const totalEl         = document.getElementById('total');
    const courierSelect   = document.getElementById('shipping_courier');
    const etaEl           = document.getElementById('shipping_eta');

    const transferRadio   = document.getElementById('transfer');
    const ewalletRadio    = document.getElementById('ewallet');
    const bankOptions     = document.getElementById('bank-options');
    const ewalletOptions  = document.getElementById('ewallet-options');

    const subtotalValue   = parseInt(subtotalEl.dataset.subtotal || 0);

    function formatRupiah(angka) {
        return 'Rp' + new Intl.NumberFormat('id-ID').format(angka);
    }

    // ========= LOAD PROVINSI (SE-INDONESIA) =========
    fetch('/ajax/provinces')
        .then(res => res.json())
        .then(data => {
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.name;
                provinceSelect.appendChild(opt);
            });
        });

    // ========= LOAD KOTA/KABUPATEN =========
    provinceSelect.addEventListener('change', function () {
        const provinceId = this.value;

        citySelect.innerHTML     = '<option value="">-- Pilih Kota / Kabupaten --</option>';
        districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        if (!provinceId) return;

        fetch(`/ajax/cities?province_id=${provinceId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.name;
                    citySelect.appendChild(opt);
                });
            });
    });

    // helper: panggil API cek ongkir kalau city & courier sudah terpilih
    function hitungOngkirJikaSiap() {
        const cityId   = citySelect.value;
        const courier  = courierSelect.value;

        if (!cityId || !courier) {
            // kalau belum lengkap, reset
            ongkirEl.textContent = formatRupiah(0);
            etaEl.textContent    = '-';
            totalEl.textContent  = formatRupiah(subtotalValue);
            return;
        }

        $.post('/checkout/cek-ongkir', {
            _token: '{{ csrf_token() }}',
            city_id: cityId,
            courier: courier,
            subtotal: subtotalValue
        }, function (res) {
            ongkirEl.textContent = formatRupiah(res.ongkir);
            totalEl.textContent  = formatRupiah(res.total);
            etaEl.textContent    = res.eta || '-';
        });
    }

    // ========= LOAD KECAMATAN =========
    citySelect.addEventListener('change', function () {
        const cityId = this.value;

        districtSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        if (!cityId) {
            hitungOngkirJikaSiap();
            return;
        }

        fetch(`/ajax/districts?city_id=${cityId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.id;
                    opt.textContent = d.name;
                    districtSelect.appendChild(opt);
                });
            });

        hitungOngkirJikaSiap();
    });

    // ========= CHANGE COURIER =========
    courierSelect.addEventListener('change', function () {
        hitungOngkirJikaSiap();
    });

    // ========= TOGGLE METODE PEMBAYARAN =========
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

    updatePaymentOptions();
});
</script>
@endpush
