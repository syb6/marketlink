<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - MarketLink</title>

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
                    <i class="bi bi-shield-lock-fill text-white"></i>
                </div>
                <div>
                    <div class="sidebar-brand-text">MarketLink</div>
                    <div class="sidebar-brand-sub">Admin Control</div>
                </div>
            </div>
            
            <div class="sidebar-user">
                <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="sidebar-user-avatar border-danger">
                <div>
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <div class="sidebar-user-role text-danger">Administrator</div>
                </div>
            </div>

            <ul class="sidebar-nav">
                <li>
                    <span class="sidebar-section-label">Core</span>
                </li>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                
                <li class="mt-3">
                    <span class="sidebar-section-label">Management</span>
                </li>
                <li>
                    <a href="{{ route('admin.users.farmers') }}" class="sidebar-link {{ request()->routeIs('admin.users.farmers') ? 'active' : '' }}">
                        <i class="bi bi-person-vcard"></i> Farmers
                        @php 
                            $pending = \App\Models\User::where('role', 'farmer')->whereHas('farmerProfile', fn($q) => $q->where('is_approved', false))->count(); 
                        @endphp
                        @if($pending > 0)
                            <span class="sidebar-badge bg-danger">{{ $pending }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.customers') }}" class="sidebar-link {{ request()->routeIs('admin.users.customers') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Customers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.markets.index') }}" class="sidebar-link {{ request()->routeIs('admin.markets.*') ? 'active' : '' }}">
                        <i class="bi bi-shop"></i> Markets
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i> Categories
                    </a>
                </li>

                <li class="mt-3">
                    <span class="sidebar-section-label">System</span>
                </li>
                <li>
                    <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.moderation.index') }}" class="sidebar-link {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-exclamation"></i> Moderation
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.announcements.index') }}" class="sidebar-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                        <i class="bi bi-megaphone"></i> Announcements
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
