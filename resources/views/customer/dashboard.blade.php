@extends('layouts.customer')
@section('page_title', 'My Dashboard')

@section('content')

@if($announcements->count() > 0)
    @foreach($announcements as $announcement)
        <div class="admin-alert" style="border-color: {{ $announcement->type == 'info' ? 'var(--forest)' : ($announcement->type == 'success' ? 'var(--leaf)' : 'var(--clay)') }}; background: {{ $announcement->type == 'info' ? 'var(--sage)' : ($announcement->type == 'success' ? '#e9f4e5' : '#fff2ea') }}; color: var(--ink);">
            <i class="bi bi-megaphone-fill text-{{ $announcement->type == 'info' ? 'primary' : ($announcement->type == 'success' ? 'success' : 'warning') }}"></i>
            <div>
                <strong>{{ $announcement->title }}:</strong> {{ $announcement->body }}
            </div>
        </div>
    @endforeach
@endif

<div class="dashboard-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back, {{ auth()->user()->name }}! Here's what's happening with your pre-orders.</p>
    </div>
</div>

<div class="metrics-grid mb-4">
    <div class="metric-card sage">
        <div class="metric-top">
            <span><i class="bi bi-bag"></i></span>
        </div>
        <small>Total Orders</small>
        <strong>{{ $totalOrders }}</strong>
    </div>
    <div class="metric-card gold">
        <div class="metric-top">
            <span style="color:#97741f; background:rgba(255,255,255,0.65)"><i class="bi bi-clock"></i></span>
        </div>
        <small>Pending / Ready</small>
        <strong style="color:#97741f;">{{ $pendingOrders }}</strong>
    </div>
    <div class="metric-card green">
        <div class="metric-top">
            <span style="color:var(--leaf); background:rgba(255,255,255,0.65)"><i class="bi bi-check2-circle"></i></span>
        </div>
        <small>Completed</small>
        <strong style="color:var(--leaf);">{{ $completedOrders }}</strong>
    </div>
    <div class="metric-card clay">
        <div class="metric-top">
            <span style="color:var(--clay); background:rgba(255,255,255,0.65)"><i class="bi bi-heart"></i></span>
        </div>
        <small>Saved Favorites</small>
        <strong style="color:var(--clay);">{{ $favoriteCount }}</strong>
    </div>
</div>

<div class="admin-two-col">
    <!-- Recent Orders -->
    <div>
        <div class="data-card h-100">
            <div class="data-card-toolbar">
                <div class="data-card-header mb-0">
                    <h2 style="font-size: 20px; line-height: 1; margin: 0;">Recent Orders</h2>
                </div>
                <a href="{{ route('customer.orders.index') }}" class="light-button text-decoration-none">View All</a>
            </div>

            @if($recentOrders->count() > 0)
                <div class="orders-grid" style="grid-template-columns: 1fr; gap: 14px;">
                    @foreach($recentOrders as $order)
                        <div class="order-card">
                            <div class="order-card-top">
                                <a href="{{ route('customer.orders.show', $order) }}" class="order-number text-decoration-none">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</a>
                                <span class="status-pill {{ $order->status == 'completed' ? 'green' : ($order->status == 'pending' ? 'neutral' : 'gold') }}">{{ ucfirst($order->status) }}</span>
                            </div>
                            <div class="order-market">
                                <span>
                                    <img src="{{ $order->farmer->profile_photo_url }}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;">
                                    <div>
                                        <strong>{{ $order->farmer->farmerProfile->stall_name ?? $order->farmer->name }}</strong>
                                        <small>Pickup: {{ $order->pickup_date->format('M d, Y') }} at {{ $order->pickup_slot }}</small>
                                    </div>
                                </span>
                                <strong>${{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                            <div class="order-card-bottom">
                                <span style="font-size:10px; color:var(--muted);">{{ $order->items->count() }} items</span>
                                <a href="{{ route('customer.orders.show', $order) }}" class="card-link text-decoration-none">Details <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px; border: 1px dashed var(--line); border-radius: 12px; margin-top: 15px;">
                    <i class="bi bi-receipt text-muted mb-2 d-block" style="font-size: 24px;"></i>
                    <strong>No orders yet</strong>
                    <p class="text-muted" style="font-size: 11px;">Start browsing markets and local products to place your first order.</p>
                    <a href="{{ route('customer.products.index') }}" class="primary-button text-decoration-none mt-2">Browse Products</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions & Danger Zone -->
    <div style="display: flex; flex-direction: column; gap: 14px;">
        <div class="data-card">
            <h6 class="fw-bold mb-3" style="font-size: 14px;">Quick Actions</h6>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('customer.markets.index') }}" class="alternate-button text-decoration-none" style="justify-content: flex-start; padding: 0 15px;">
                    <i class="bi bi-shop text-primary me-2"></i> Find a Market
                </a>
                <a href="{{ route('customer.products.index') }}" class="alternate-button text-decoration-none" style="justify-content: flex-start; padding: 0 15px;">
                    <i class="bi bi-bag text-success me-2"></i> Shop Produce
                </a>
                <a href="{{ route('customer.favorites.index') }}" class="alternate-button text-decoration-none" style="justify-content: flex-start; padding: 0 15px;">
                    <i class="bi bi-heart text-danger me-2"></i> My Favorites
                </a>
            </div>
        </div>

        <div class="data-card" style="border-color: rgba(201, 107, 77, 0.3); background: #fffdfc;">
            <h6 class="fw-bold mb-3" style="font-size: 14px; color: var(--clay);">Danger Zone</h6>
            <p style="font-size: 10px; color: var(--ink-soft); margin-bottom: 15px;">
                Once you delete your account, there is no going back. Please be certain.
            </p>
            <form action="{{ route('customer.profile.destroy') }}" method="POST" onsubmit="return confirm('Are you absolutely sure you want to delete your account? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="primary-button" style="background: var(--clay); width: 100%;">
                    <i class="bi bi-trash"></i> Delete Account
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
