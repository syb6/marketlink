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
                            <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="bi bi-shop"></i></div>
                            <div>
                                <div class="fw-semibold">{{ auth()->user()->farmerProfile->stall_name ?? auth()->user()->name }}</div>
                                <div class="small text-muted">Farmer</div>
                            </div>
                        </div>
                        <div class="list-group list-group-flush">
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('farmer.dashboard') ? 'active' : '' }}" href="{{ route('farmer.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('farmer.orders.*') ? 'active' : '' }}" href="{{ route('farmer.orders.index') }}"><i class="bi bi-receipt me-2"></i>Orders</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('farmer.products.*') ? 'active' : '' }}" href="{{ route('farmer.products.index') }}"><i class="bi bi-box-seam me-2"></i>Products</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('farmer.reviews.*') ? 'active' : '' }}" href="{{ route('farmer.reviews.index') }}"><i class="bi bi-star me-2"></i>Reviews</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('farmer.profile.*') ? 'active' : '' }}" href="{{ route('farmer.profile.edit') }}"><i class="bi bi-person-gear me-2"></i>Profile</a>
                        </div>
                    </div>
                </div>
            </aside>
            <main class="col-lg-9">
                @if(auth()->user()->farmerProfile && !auth()->user()->farmerProfile->is_approved)
                    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-1"></i> Your farmer account is pending admin approval. Products stay hidden until approved.</div>
                @endif
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>
    @include('partials.scripts')
</body>
</html>
