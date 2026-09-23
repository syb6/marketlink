<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="bg-light">
    @include('partials.navbar')
    <div class="container py-4">
        <div class="row g-4">
            <aside class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="" width="40" height="40" class="rounded-circle">
                            <div>
                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                <div class="small text-muted">Customer</div>
                            </div>
                        </div>
                        <div class="list-group list-group-flush">
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('customer.markets.*') ? 'active' : '' }}" href="{{ route('customer.markets.index') }}"><i class="bi bi-shop me-2"></i>Markets</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('customer.products.*') ? 'active' : '' }}" href="{{ route('customer.products.index') }}"><i class="bi bi-bag me-2"></i>Products</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}" href="{{ route('customer.orders.index') }}"><i class="bi bi-receipt me-2"></i>Orders</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('customer.favorites.*') ? 'active' : '' }}" href="{{ route('customer.favorites.index') }}"><i class="bi bi-heart me-2"></i>Favorites</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('customer.cart') ? 'active' : '' }}" href="{{ route('customer.cart') }}"><i class="bi bi-cart3 me-2"></i>Cart</a>
                        </div>
                    </div>
                </div>
            </aside>
            <main class="col-lg-9">
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>
    @include('partials.scripts')
</body>
</html>
