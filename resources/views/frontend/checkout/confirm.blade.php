@extends('layouts.frontend')

@section('title', 'Konfirmasi Pembayaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('frontend/styles/confirm.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="text-center mb-4">
        <h2>Terima Kasih!</h2>
        <p class="lead">Silakan lakukan pembayaran sesuai metode yang kamu pilih.</p>
    </div>

    {{-- Pesan error untuk file bukti --}}
    @if ($errors->has('proof'))
        <div class="alert alert-danger mb-4 text-center">
            {{ $errors->first('proof') }}
        </div>
    @endif

    @php
        $data      = session('checkout_data', []);
        $payment   = $data['payment_method'] ?? 'transfer';
        $bank      = $data['bank_name'] ?? null;
        $ewallet   = $data['ewallet_name'] ?? null;
        $orderId   = session('order_id');
        $expiresAt = $data['expires_at'] ?? null;
    @endphp

    {{-- Info batas waktu pembayaran + countdown --}}
    @if($expiresAt)
        <div class="alert alert-warning text-center mb-4">
            Silakan lakukan pembayaran sebelum
            <strong>{{ \Carbon\Carbon::parse($expiresAt)->format('d M Y H:i') }}</strong> WIB.<br>
            <span id="countdown-text">
                Sisa waktu: <strong id="countdown">--:--:--</strong>
            </span>
        </div>
    @endif

    {{-- TRANSFER BANK --}}
    @if($payment === 'transfer')
        @php
            $rekening = [
                'bca'     => ['no' => '9876543210', 'nama' => 'HANSPORT'],
                'bri'     => ['no' => '1234567890', 'nama' => 'HANSPORT'],
                'mandiri' => ['no' => '1122334455', 'nama' => 'HANSPORT'],
                'bni'     => ['no' => '5566778899', 'nama' => 'HANSPORT'],
            ];
        @endphp

        @if(isset($rekening[$bank]))
            <div class="card mx-auto p-4 text-center" style="max-width: 500px;">
                <div class="mb-3">
                    <img src="{{ asset('frontend/images/bank-logo/' . $bank . '.png') }}" alt="{{ strtoupper($bank) }}" height="40">
                </div>

                <h5 class="fw-bold mb-2">Transfer ke Bank {{ strtoupper($bank) }}</h5>
                <p class="text-muted">Silakan transfer ke rekening berikut:</p>

                <p><strong>Bank:</strong> {{ strtoupper($bank) }}</p>
                <p><strong>Rekening:</strong> {{ $rekening[$bank]['no'] }} a.n. {{ $rekening[$bank]['nama'] }}</p>

                <form method="POST" action="{{ route('checkout.finalize') }}" enctype="multipart/form-data" id="payment-form">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $orderId }}">

                    <div class="mb-3 text-start">
                        <label for="proof" class="form-label fw-semibold">Upload Bukti Pembayaran</label>
                        <input
                            type="file"
                            name="proof"
                            id="proof"
                            class="form-control"
                            accept="image/*"
                            required>
                        <small class="text-muted">
                            Hanya gambar (JPG, JPEG, PNG, WEBP), maksimal 5MB.
                        </small>
                    </div>

                    <button type="submit" class="btn btn-dark w-100" id="submit-btn">
                        Kirim Bukti & Konfirmasi
                    </button>

                    {{-- Tombol kembali ke beranda, muncul kalau waktu habis --}}
                    <a href="{{ route('homepage') }}" id="back-home-btn" class="btn btn-outline-secondary w-100 mt-3 d-none">
                        Kembali ke Beranda
                    </a>
                </form>
            </div>
        @else
            {{-- Kalau bank gak dikenali, kasih info umum --}}
            <div class="alert alert-danger text-center">
                Metode bank tidak dikenali. Silakan ulangi proses checkout.
                <br>
                <a href="{{ route('checkout.index') }}" class="btn btn-sm btn-outline-dark mt-2">Kembali ke Checkout</a>
            </div>
        @endif

    {{-- E-WALLET --}}
    @elseif($payment === 'ewallet')
        @php
            $ewallet_nama = strtoupper($ewallet ?? '');
        @endphp

        <div class="card mx-auto p-4 text-center" style="max-width: 500px;">
            <div class="mb-3">
                @if($ewallet)
                    <img src="{{ asset('frontend/images/ewallet-logo/' . $ewallet . '.png') }}" alt="{{ $ewallet_nama }}" height="40">
                @endif
            </div>

            <h5 class="fw-bold mb-2">Pembayaran via {{ $ewallet_nama ?: 'E-WALLET' }}</h5>
            <p class="text-muted">Silakan transfer ke nomor berikut:</p>
            <p><strong>0812-3456-7890 a.n. HANSPORT</strong></p>

            <form method="POST" action="{{ route('checkout.finalize') }}" enctype="multipart/form-data" id="payment-form">
                @csrf
                <input type="hidden" name="order_id" value="{{ $orderId }}">

                <div class="mb-3 text-start">
                    <label for="proof" class="form-label fw-semibold">Upload Bukti Pembayaran</label>
                    <input
                        type="file"
                        name="proof"
                        id="proof"
                        class="form-control"
                        accept="image/*"
                        required>
                    <small class="text-muted">
                        Hanya gambar (JPG, JPEG, PNG, WEBP), maksimal 5MB.
                    </small>
                </div>

                <button type="submit" class="btn btn-dark w-100" id="submit-btn">
                    Kirim Bukti & Konfirmasi
                </button>

                {{-- Tombol kembali ke beranda, muncul kalau waktu habis --}}
                <a href="{{ route('homepage') }}" id="back-home-btn" class="btn btn-outline-secondary w-100 mt-3 d-none">
                    Kembali ke Beranda
                </a>
            </form>
        </div>
    @else
        {{-- fallback kalau payment_method gak valid --}}
        <div class="alert alert-danger text-center">
            Metode pembayaran tidak valid. Silakan ulangi proses checkout.
            <br>
            <a href="{{ route('checkout.index') }}" class="btn btn-sm btn-outline-dark mt-2">Kembali ke Checkout</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const proofInput   = document.getElementById('proof');
        const submitButton = document.getElementById('submit-btn');
        const backHomeBtn  = document.getElementById('back-home-btn');

        // Cek file bukti pembayaran (tipe & ukuran)
        if (proofInput) {
            proofInput.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                // Tipe file yang diizinkan
                const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

                if (!allowedTypes.includes(file.type)) {
                    alert('File tidak sesuai. Harus berupa gambar (JPG, JPEG, PNG, atau WEBP).');
                    this.value = '';
                    return;
                }

                // Batas ukuran 5MB
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    this.value = '';
                    return;
                }
            });
        }

        // ====== COUNTDOWN TIMER ======
        @if($expiresAt)
            const countdownEl  = document.getElementById('countdown');
            const expiresAtMs  = {{ \Carbon\Carbon::parse($expiresAt)->timestamp * 1000 }};

            function updateCountdown() {
                const now  = Date.now();
                let diff   = expiresAtMs - now;

                if (!countdownEl) return;

                if (diff <= 0) {
                    countdownEl.textContent = '00:00:00';

                    // disable tombol submit
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerText = 'Waktu pembayaran telah berakhir';
                        submitButton.classList.add('disabled');
                    }

                    // munculkan tombol kembali ke home
                    if (backHomeBtn) {
                        backHomeBtn.classList.remove('d-none');
                    }

                    return;
                }

                const hours   = Math.floor(diff / (1000 * 60 * 60));
                diff         %= (1000 * 60 * 60);
                const minutes = Math.floor(diff / (1000 * 60));
                diff         %= (1000 * 60);
                const seconds = Math.floor(diff / 1000);

                const h = String(hours).padStart(2, '0');
                const m = String(minutes).padStart(2, '0');
                const s = String(seconds).padStart(2, '0');

                countdownEl.textContent = `${h}:${m}:${s}`;
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        @endif
    });
</script>
@endpush
