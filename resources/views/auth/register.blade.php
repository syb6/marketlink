<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - MarketLink</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="auth-page">
    <div class="auth-card animate-up" style="max-width: 550px;">
        <div class="text-center mb-4">
            <a href="{{ route('home') }}" class="text-decoration-none d-inline-block">
                <div class="auth-logo justify-content-center">
                    <i class="bi bi-basket-fill text-primary"></i> MarketLink
                </div>
            </a>
            <p class="text-muted mt-2">Create an account to join the community.</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}" class="form-ml">
            @csrf
            
            <div class="mb-4 text-center">
                <label class="form-label d-block text-start mb-2">I want to join as a:</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="role" id="role_customer" value="customer" {{ old('role', 'customer') == 'customer' ? 'checked' : '' }}>
                    <label class="btn btn-outline-success" for="role_customer"><i class="bi bi-person-heart me-1"></i> Customer</label>

                    <input type="radio" class="btn-check" name="role" id="role_farmer" value="farmer" {{ old('role') == 'farmer' ? 'checked' : '' }}>
                    <label class="btn btn-outline-success" for="role_farmer"><i class="bi bi-shop me-1"></i> Farmer</label>
                </div>
                @error('role')<div class="text-danger small mt-1 text-start">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-sm-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label for="phone" class="form-label">Phone (Optional)</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+1 234...">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Min 6 chars">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-ml w-100 justify-content-center py-2 fs-6">Create Account</button>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
            <p class="text-muted small mb-0">Already have an account? <a href="{{ route('login') }}" class="fw-bold">Sign in here</a></p>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
