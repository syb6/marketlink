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
                            <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px;height:40px;"><i class="bi bi-shield-lock"></i></div>
                            <div>
                                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                <div class="small text-muted">Administrator</div>
                            </div>
                        </div>
                        <div class="list-group list-group-flush">
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.farmers') ? 'active' : '' }}" href="{{ route('admin.users.farmers') }}"><i class="bi bi-person-vcard me-2"></i>Farmers</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.users.customers') ? 'active' : '' }}" href="{{ route('admin.users.customers') }}"><i class="bi bi-people me-2"></i>Customers</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.markets.*') ? 'active' : '' }}" href="{{ route('admin.markets.index') }}"><i class="bi bi-shop me-2"></i>Markets</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="bi bi-tags me-2"></i>Categories</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}"><i class="bi bi-graph-up me-2"></i>Reports</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}" href="{{ route('admin.moderation.index') }}"><i class="bi bi-shield-exclamation me-2"></i>Moderation</a>
                            <a class="list-group-item list-group-item-action {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}"><i class="bi bi-megaphone me-2"></i>Announcements</a>
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
