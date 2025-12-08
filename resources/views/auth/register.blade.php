<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>

<div class="login-wrapper position-relative">

    <img src="{{ asset('frontend/images/overlay1.png') }}" class="ball-overlay" alt="ball">

    <div class="login-box">
        <h4 class="text-center mb-4">Daftar ke HanSport</h4>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}">
            @csrf

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="text-center mt-3">Sudah punya akun? <a href="{{ url('/login') }}" style="color: #fff;">Login</a></p>
    </div>
</div>

</body>
</html>
