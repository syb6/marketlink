@extends('layouts.app')
@section('title', 'Register - MarketLink')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1">Create account</h1>
                    <p class="text-muted small mb-4">Join as a customer or farmer.</p>

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label d-block">I want to join as</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="role" id="role-customer" value="customer" {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="role-customer"><i class="bi bi-person-heart"></i> Customer</label>
                                <input type="radio" class="btn-check" name="role" id="role-farmer" value="farmer" {{ old('role') === 'farmer' ? 'checked' : '' }}>
                                <label class="btn btn-outline-success" for="role-farmer"><i class="bi bi-shop"></i> Farmer</label>
                            </div>
                            @error('role')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="name">Full name</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="phone">Phone (optional)</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password">Password</label>
                                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="password_confirmation">Confirm password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Create Account</button>
                    </form>

                    <p class="text-center small mt-3 mb-0">
                        Already registered? <a href="{{ route('login') }}">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
