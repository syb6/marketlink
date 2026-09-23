<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page_title', 'Farmer Dashboard') - MarketLink</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') . '?v=' . time() }}">

    @stack('styles')
</head>
<body>

<header class="site-header" style="z-index: 50;">
    <div class="app-container header-inner">
        <a class="brand" href="{{ route('home') }}">
            <span class="brand-mark"><i class="bi bi-basket-fill"></i></span>
            <span>Market<span>Link</span></span>
        </a>

        <div class="header-actions" style="margin-left: auto;">
            <div class="nav-dropdown-wrapper" style="position: relative;">
                <button type="button" class="outline-button nav-dropdown-trigger" onclick="this.nextElementSibling.classList.toggle('d-none')" style="gap: 8px;">
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" style="width:18px;height:18px;border-radius:50%;object-fit:cover;">
                    {{ auth()->user()->farmerProfile->stall_name ?? auth()->user()->name }}
                    <i class="bi bi-chevron-down" style="font-size: 10px;"></i>
                </button>
                
                <div class="nav-dropdown-menu d-none" style="position: absolute; top: calc(100% + 8px); right: 0; width: 180px; background: var(--surface-glass); backdrop-filter: blur(12px); border: 1px solid var(--border-glass); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); display: flex; flex-direction: column; padding: 6px; z-index: 100;">
                    <a href="{{ route('home') }}" class="dropdown-item" style="padding: 8px 12px; font-size: 11px; font-weight: 600; color: var(--stone-700); text-decoration: none; border-radius: 6px; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                        <i class="bi bi-house"></i> Public Store
                    </a>
                    <hr style="margin: 4px 0; border: none; border-top: 1px solid var(--border-glass);">
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width: 100%; padding: 8px 12px; font-size: 11px; font-weight: 600; color: #dc2626; background: none; border: none; text-align: left; border-radius: 6px; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
            
            <script>
                document.addEventListener('click', function(e) {
                    if(!e.target.closest('.nav-dropdown-wrapper')) {
                        document.querySelectorAll('.nav-dropdown-menu').forEach(menu => menu.classList.add('d-none'));
                    }
                });
            </script>
            
            <style>
                .dropdown-item:hover {
                    background: var(--emerald-50);
                    color: var(--emerald-700) !important;
                }
            </style>
        </div>
    </div>
</header>

<div class="dashboard-page">
    <div class="app-container">
        <div class="dashboard-layout">
            <aside class="dashboard-sidebar">
                <div class="dashboard-role">
                    <div class="role-avatar" style="background: var(--clay); color: #fff;">
                        <i class="bi bi-shop"></i>
                    </div>
                    <div>
                        <strong>{{ auth()->user()->farmerProfile->stall_name ?? auth()->user()->name }}</strong>
                        <span>Farmer Account</span>
                    </div>
                </div>

                <nav class="dashboard-nav">
                    <button type="button" class="{{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}" onclick="window.location='{{ route('farmer.dashboard') }}'">
                        <i class="bi bi-speedometer2"></i> Dashboard <i class="bi bi-chevron-right"></i>
                    </button>
                    <button type="button" class="{{ request()->routeIs('farmer.orders.*') ? 'active' : '' }}" onclick="window.location='{{ route('farmer.orders.index') }}'">
                        <i class="bi bi-receipt"></i> Orders <i class="bi bi-chevron-right"></i>
                        @php $pending = \App\Models\Order::where('farmer_id', auth()->id())->where('status', 'placed')->count(); @endphp
                        @if($pending > 0)
                            <span style="background: var(--clay); color: #fff; padding: 2px 6px; border-radius: 10px; font-size: 9px; margin-left: auto; margin-right: 10px;">{{ $pending }}</span>
                        @endif
                    </button>
                    <button type="button" class="{{ request()->routeIs('farmer.products.*') ? 'active' : '' }}" onclick="window.location='{{ route('farmer.products.index') }}'">
                        <i class="bi bi-box-seam"></i> Products <i class="bi bi-chevron-right"></i>
                    </button>
                    <button type="button" class="{{ request()->routeIs('farmer.reviews.*') ? 'active' : '' }}" onclick="window.location='{{ route('farmer.reviews.index') }}'">
                        <i class="bi bi-star"></i> Reviews <i class="bi bi-chevron-right"></i>
                    </button>
                    <button type="button" class="{{ request()->routeIs('farmer.profile.*') ? 'active' : '' }}" onclick="window.location='{{ route('farmer.profile.edit') }}'">
                        <i class="bi bi-person-gear"></i> Profile & Stall <i class="bi bi-chevron-right"></i>
                    </button>
                </nav>

                <div class="dashboard-sidebar-bottom">
                    <button type="button" onclick="window.location='{{ route('home') }}'">
                        <i class="bi bi-house"></i> Back to Home
                    </button>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="width: 100%; color: var(--clay);">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </aside>

            <main class="dashboard-content">
                @if(auth()->user()->farmerProfile && !auth()->user()->farmerProfile->is_approved)
                    <div class="admin-alert" style="border-color: var(--clay); background: #fff2ea; color: var(--ink);">
                        <i class="bi bi-exclamation-triangle text-warning"></i>
                        <div>
                            <strong>Account Pending Approval:</strong> Your farmer account is currently pending approval by an administrator. You may set up your stall and add products, but they will not be visible to customers until approved.
                        </div>
                    </div>
                @endif
                
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>
</div>

@include('partials.ai-widget')
<!-- App JS -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>





