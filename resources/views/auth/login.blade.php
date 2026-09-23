<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - MarketLink</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="auth-page">
    <div class="auth-card animate-up">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none d-inline-block">
                <div class="auth-logo justify-content-center">
                    <i class="bi bi-basket-fill text-primary"></i> MarketLink
                </div>
            </a>
            <p class="text-muted mt-2">Welcome back! Please login to your account.</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="form-ml">
            @csrf
            
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label for="password" class="form-label">Password</label>
                </div>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="••••••••">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label text-muted small" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary-ml w-100 justify-content-center py-2 fs-6">Sign In</button>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
            <p class="text-muted small mb-0">Don't have an account? <a href="{{ route('register') }}" class="fw-bold">Sign up here</a></p>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
