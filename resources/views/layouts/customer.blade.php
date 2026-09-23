<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Customer Dashboard - MarketLink</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body class="bg-light">

    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-basket-fill text-white"></i>
                </div>
                <div>
                    <div class="sidebar-brand-text">MarketLink</div>
                    <div class="sidebar-brand-sub">Customer Panel</div>
                </div>
            </div>
            
            <div class="sidebar-user">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="sidebar-user-avatar">
                <div>
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">Customer</div>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li>
                    <span class="sidebar-section-label">Main Menu</span>
                </li>
                <li>
                    <a href="{{ route('customer.dashboard') }}" class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.markets.index') }}" class="sidebar-link {{ request()->routeIs('customer.markets.*') ? 'active' : '' }}">
                        <i class="bi bi-shop"></i> Browse Markets
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.products.index') }}" class="sidebar-link {{ request()->routeIs('customer.products.*') ? 'active' : '' }}">
                        <i class="bi bi-bag"></i> Browse Products
                    </a>
                </li>
                
                <li class="mt-3">
                    <span class="sidebar-section-label">My Account</span>
                </li>
                <li>
                    <a href="{{ route('customer.orders.index') }}" class="sidebar-link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i> My Orders
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.cart') }}" class="sidebar-link {{ request()->routeIs('customer.cart') ? 'active' : '' }}">
                        <i class="bi bi-cart3"></i> My Cart
                        @php $cartCount = is_array(session('cart')) ? array_sum(session('cart')) : 0; @endphp
                        @if($cartCount > 0)
                            <span class="sidebar-badge">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.favorites.index') }}" class="sidebar-link {{ request()->routeIs('customer.favorites.*') ? 'active' : '' }}">
                        <i class="bi bi-heart"></i> Saved Favorites
                    </a>
                </li>
            </ul>
            
            <div class="sidebar-footer">
                <a href="{{ route('home') }}" class="sidebar-link">
                    <i class="bi bi-house"></i> Back to Home
                </a>
                <form action="{{ route('logout') }}" method="POST" class="mt-1">
                    @csrf
                    <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start text-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <header class="dashboard-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light d-md-none rounded-circle" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h5 class="mb-0 fw-bold">@yield('page_title', 'Dashboard')</h5>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('customer.cart') }}" class="btn btn-outline-ml btn-sm rounded-pill">
                        <i class="bi bi-cart3 me-1"></i> Cart
                    </a>
                </div>
            </header>
            
            <div class="dashboard-content">
                @include('partials.alerts')
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- App JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
