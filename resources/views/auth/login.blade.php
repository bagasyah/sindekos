<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Security-Policy">    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('logobg.png') }}" type="image/png">
    <!-- Bootstrap CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container login-container">
        <img src="{{ asset('logobg.png') }}" alt="Logo" class="logo" />
        <h2 class="login-title">Selamat Datang</h2>
        
        <!-- Alert untuk akun tidak ditemukan -->
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                Akun tidak ditemukan. Silakan periksa kembali email dan password Anda.
            </div>
        @endif

        <form id="loginForm" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Ingat Saya</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('password.request') }}">Lupa Password?</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>