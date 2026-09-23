<nav class="navbar navbar-expand-lg navbar-main">
    <div class="container">
        <a class="navbar-brand navbar-brand-logo" href="{{ route('home') }}">
            <div class="brand-icon">
                <i class="bi bi-basket-fill"></i>
            </div>
            MarketLink
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('products.*') || request()->routeIs('customer.products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('markets.*') || request()->routeIs('customer.markets.*') ? 'active' : '' }}" href="{{ route('markets.index') }}">Markets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-custom {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
            <div class="d-flex align-items-center gap-3">
                @auth
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('customer.cart') }}" class="text-primary-ml position-relative text-decoration-none fs-5 me-3">
                            <i class="bi bi-cart3"></i>
                            @php
                                $cartCount = is_array(session('cart')) ? array_sum(session('cart')) : 0;
                            @endphp
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cart-count" style="display: {{ $cartCount > 0 ? 'inline-block' : 'none' }}; font-size: 0.6rem;">
                                {{ $cartCount }}
                            </span>
                        </a>
                    @endif
                    <div class="dropdown">
                        <a class="text-decoration-none dropdown-toggle d-flex align-items-center gap-2 text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="rounded-circle" width="32" height="32" style="object-fit: cover;">
                            <span class="fw-semibold">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li><a class="dropdown-item" href="{{ route(auth()->user()->dashboard_route) }}"><i class="bi bi-speedometer2 me-2 text-muted"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-nav-login">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-nav-register">Sign Up</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
