<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="login-wrapper position-relative">

        <!-- 🏀 Gambar Bola Overlay -->
        <img src="{{ asset('frontend/images/overlay1.png') }}" class="ball-overlay" alt="ball">

        <!-- 📦 Kotak Form Login -->
        <div class="login-box">
            <h4 class="text-center mb-4">Login ke HanSport</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="text-center mt-3">Belum punya akun? <a href="{{ url('/register') }}" style="color: #fff;">Daftar sekarang</a></p>
        </div>
    </div>
</body>

</html>
