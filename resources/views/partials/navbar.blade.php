@php
    $searchAction = auth()->check() && auth()->user()->isCustomer()
        ? route('customer.products.index')
        : route('products.index');
    $cartCount = is_array(session('cart')) ? array_sum(session('cart')) : 0;
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-success" href="{{ route('home') }}">
            <i class="bi bi-basket-fill"></i> MarketLink
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <form class="d-flex mx-lg-3 my-2 my-lg-0 flex-grow-1" style="max-width: 360px;" action="{{ $searchAction }}" method="GET" id="header-search-form">
                <div class="input-group">
                    <input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="Search produce..." aria-label="Search">
                    <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('markets.index') }}">Markets</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>

                @auth
                    @if(auth()->user()->isCustomer())
                        <li class="nav-item">
                            <a href="{{ route('customer.cart') }}" class="nav-link position-relative px-3" title="Cart">
                                <i class="bi bi-cart3 fs-5"></i>
                                <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" @if($cartCount < 1) style="display:none" @endif>{{ $cartCount }}</span>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="" width="28" height="28" class="rounded-circle">
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route(auth()->user()->dashboard_route) }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-success btn-sm ms-lg-2" href="{{ route('register') }}">Sign Up</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
