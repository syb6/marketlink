<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - MarketLink</title>

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
            <div class="eyebrow"><span class="eyebrow-dot" style="background: #d9eead;"></span> Join Us</div>
            <h1>A fresh way to <em>buy local</em></h1>
            <p>Whether you're a shopper looking for fresh produce or a farmer ready to sell, MarketLink connects you.</p>

            <div class="auth-quote" style="margin-top: 60px;">
                <span>"</span>
                <p>We doubled our pre-orders in the first week. The platform is incredibly easy to use.</p>
                <small>- Mark T., Local Farmer</small>
            </div>
        </div>
    </div>

    <div class="auth-panel">
        <div class="auth-panel-inner" style="max-width: 480px;">
            <div class="auth-panel-top">
                <a class="brand" href="{{ route('home') }}" style="text-decoration:none;">
                    <span class="brand-mark"><i class="bi bi-basket-fill"></i></span>
                    <span>Market<span>Link</span></span>
                </a>
                <button type="button" onclick="window.location='{{ route('home') }}'"><i class="bi bi-arrow-left"></i> Back to home</button>
            </div>

            <div class="auth-heading" style="margin-top: 40px;">
                <h2>Create account</h2>
                <p>Join the community and start your journey with us.</p>
            </div>

            <form method="POST" action="{{ route('register.post') }}" class="auth-form">
                @csrf
                
                <div class="role-selector">
                    <span>I want to join as a:</span>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <label style="cursor: pointer; position: relative;">
                            <input type="radio" name="role" value="customer" {{ old('role', 'customer') == 'customer' ? 'checked' : '' }} style="position:absolute; opacity:0;">
                            <div class="role-card" style="padding: 12px; border: 1px solid var(--line); border-radius: 8px; font-size: 11px; font-weight: 700; color: var(--ink-soft); background: #fff;">
                                <i class="bi bi-person-heart" style="font-size: 16px; margin-bottom: 5px; display: block;"></i>
                                Customer
                            </div>
                        </label>
                        <label style="cursor: pointer; position: relative;">
                            <input type="radio" name="role" value="farmer" {{ old('role') == 'farmer' ? 'checked' : '' }} style="position:absolute; opacity:0;">
                            <div class="role-card" style="padding: 12px; border: 1px solid var(--line); border-radius: 8px; font-size: 11px; font-weight: 700; color: var(--ink-soft); background: #fff;">
                                <i class="bi bi-shop" style="font-size: 16px; margin-bottom: 5px; display: block;"></i>
                                Farmer
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <span style="color:var(--clay); font-weight:400; font-size: 9px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <style>
                    input[type="radio"]:checked + .role-card {
                        border-color: var(--forest) !important;
                        color: var(--forest) !important;
                        background: var(--sage) !important;
                    }
                </style>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <label>
                        Full Name
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe" style="{{ $errors->has('name') ? 'border-color: var(--clay);' : '' }}">
                        @error('name')<span style="color:var(--clay); font-weight:400;">{{ $message }}</span>@enderror
                    </label>
                    <label>
                        Phone (Optional)
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+1 234..." style="{{ $errors->has('phone') ? 'border-color: var(--clay);' : '' }}">
                        @error('phone')<span style="color:var(--clay); font-weight:400;">{{ $message }}</span>@enderror
                    </label>
                </div>

                <label>
                    Email Address
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com" style="{{ $errors->has('email') ? 'border-color: var(--clay);' : '' }}">
                    @error('email')<span style="color:var(--clay); font-weight:400;">{{ $message }}</span>@enderror
                </label>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <label>
                        Password
                        <input type="password" name="password" required placeholder="Min 6 chars" style="{{ $errors->has('password') ? 'border-color: var(--clay);' : '' }}">
                        @error('password')<span style="color:var(--clay); font-weight:400;">{{ $message }}</span>@enderror
                    </label>
                    <label>
                        Confirm Password
                        <input type="password" name="password_confirmation" required>
                    </label>
                </div>

                <button type="submit" class="primary-button" style="width: 100%; margin-top: 10px;">Create Account</button>
            </form>

            <div class="auth-divider">or</div>

            <button type="button" class="alternate-button" onclick="window.location='{{ route('login') }}'">
                Sign in to existing account
            </button>
        </div>
    </div>
</div>

@include('partials.footer')
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>





