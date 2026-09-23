<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - MarketLink</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/app.css') . '?v=' . time() }}">
</head>
<body>

<div class="auth-page">
    <div class="auth-visual">
        <div class="auth-visual-image" style="background-image: url('{{ asset('storage/default-hero.jpg') }}'); background-color: var(--forest-dark);"></div>
        <div class="auth-visual-content">
            <div class="eyebrow"><span class="eyebrow-dot" style="background: #d9eead;"></span> Welcome Back</div>
            <h1>Good to <em>see you</em> again</h1>
            <p>Sign in to access your pre-orders, manage your favorites, or update your market stall.</p>

            <div class="auth-quote">
                <span>"</span>
                <p>MarketLink completely changed how we buy our weekly groceries. So fresh, so easy.</p>
                <small>- Sarah J., Verified Customer</small>
            </div>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-panel-inner">
            <div class="auth-panel-top">
                <a class="brand" href="{{ route('home') }}" style="text-decoration:none;">
                    <span class="brand-mark"><i class="bi bi-basket-fill"></i></span>
                    <span>Market<span>Link</span></span>
                </a>
                <button type="button" onclick="window.location='{{ route('home') }}'"><i class="bi bi-arrow-left"></i> Back to home</button>
            </div>

            <div class="auth-heading">
                <h2>Sign in</h2>
                <p>Enter your details to access your account.</p>
            </div>

            @if (session('error'))
                <div style="padding:10px; background:#f4dfd5; color:var(--clay); font-size:11px; border-radius:7px; margin-bottom:15px;">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div style="padding:10px; background:#e4efe1; color:var(--forest); font-size:11px; border-radius:7px; margin-bottom:15px;">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="auth-form">
                @csrf
                
                <label>
                    Email Address
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com" style="{{ $errors->has('email') ? 'border-color: var(--clay);' : '' }}">
                    @error('email')
                        <span style="color:var(--clay); font-weight:400;">{{ $message }}</span>
                    @enderror
                </label>

                <label>
                    Password
                    <input type="password" name="password" required placeholder="••••••••" style="{{ $errors->has('password') ? 'border-color: var(--clay);' : '' }}">
                    @error('password')
                        <span style="color:var(--clay); font-weight:400;">{{ $message }}</span>
                    @enderror
                </label>

                <div class="auth-form-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember">
                        Remember me for 30 days
                    </label>
                </div>

                <button type="submit" class="primary-button" style="width: 100%; margin-top: 5px;">Sign In</button>
            </form>

            <div class="auth-divider">or</div>

            <button type="button" class="alternate-button" onclick="window.location='{{ route('register') }}'">
                Create an account
            </button>

            <div class="auth-legal">
                By signing in, you agree to our <button type="button">Terms of Service</button> and <button type="button">Privacy Policy</button>.
            </div>
        </div>
    </div>
</div>

@include('partials.footer')
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>





