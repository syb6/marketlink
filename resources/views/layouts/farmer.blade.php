<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Farmer Dashboard - MarketLink</title>

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
                    <div class="sidebar-brand-sub">Farmer Portal</div>
                </div>
            </div>
            
            <div class="sidebar-user">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="sidebar-user-avatar">
                <div>
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role">Farmer</div>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li>
                    <span class="sidebar-section-label">Overview</span>
                </li>
                <li>
                    <a href="{{ route('farmer.dashboard') }}" class="sidebar-link {{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                
                <li class="mt-3">
                    <span class="sidebar-section-label">Store Management</span>
                </li>
                <li>
                    <a href="{{ route('farmer.orders.index') }}" class="sidebar-link {{ request()->routeIs('farmer.orders.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i> Orders
                        @php $pending = \App\Models\Order::where('farmer_id', auth()->id())->where('status', 'placed')->count(); @endphp
                        @if($pending > 0)
                            <span class="sidebar-badge">{{ $pending }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('farmer.products.index') }}" class="sidebar-link {{ request()->routeIs('farmer.products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam"></i> Products
                    </a>
                </li>
                <li>
                    <a href="{{ route('farmer.reviews.index') }}" class="sidebar-link {{ request()->routeIs('farmer.reviews.*') ? 'active' : '' }}">
                        <i class="bi bi-star"></i> Reviews
                    </a>
                </li>

                <li class="mt-3">
                    <span class="sidebar-section-label">Settings</span>
                </li>
                <li>
                    <a href="{{ route('farmer.profile.edit') }}" class="sidebar-link {{ request()->routeIs('farmer.profile.*') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i> Profile & Stall
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
                
                @if(auth()->user()->farmerProfile && !auth()->user()->farmerProfile->is_approved)
                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> Account Pending Approval</span>
                @endif
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
