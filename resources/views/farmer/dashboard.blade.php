@extends('layouts.farmer')
@section('page_title', 'Farmer Dashboard')

@section('content')

<div class="dashboard-header">
    <div>
        <h1>Store Overview</h1>
        <p>Manage your products, track orders, and view customer reviews.</p>
    </div>
</div>

<div class="metrics-grid mb-4">
    <div class="metric-card green">
        <div class="metric-top">
            <span style="color:var(--leaf); background:rgba(255,255,255,0.65)"><i class="bi bi-currency-dollar"></i></span>
        </div>
        <small>Total Revenue</small>
        <strong style="color:var(--leaf);">${{ number_format($totalRevenue, 2) }}</strong>
    </div>
    <div class="metric-card gold">
        <div class="metric-top">
            <span style="color:#97741f; background:rgba(255,255,255,0.65)"><i class="bi bi-bell"></i></span>
        </div>
        <small>Pending Orders</small>
        <strong style="color:#97741f;">{{ $pendingOrders }}</strong>
    </div>
    <div class="metric-card clay">
        <div class="metric-top">
            <span style="color:var(--clay); background:rgba(255,255,255,0.65)"><i class="bi bi-box-seam"></i></span>
        </div>
        <small>Active Products</small>
        <strong style="color:var(--clay);">{{ $totalProducts }}</strong>
    </div>
    <div class="metric-card sage">
        <div class="metric-top">
            <span><i class="bi bi-star"></i></span>
        </div>
        <small>Average Rating</small>
        <strong>{{ number_format($averageRating, 1) }}</strong>
    </div>
</div>

<div class="admin-two-col">
    <!-- Pending Orders -->
    <div>
        <div class="data-card h-100">
            <div class="data-card-toolbar">
                <div class="data-card-header mb-0">
                    <h2 style="font-size: 20px; line-height: 1; margin: 0;">Orders Needing Action</h2>
                </div>
                <a href="{{ route('farmer.orders.index') }}" class="light-button text-decoration-none">View All</a>
            </div>
            
            @if($recentOrders->count() > 0)
                <div class="orders-grid" style="grid-template-columns: 1fr; gap: 14px;">
                    @foreach($recentOrders as $order)
                        <div class="order-card" style="border: 1px solid var(--line);">
                            <div class="order-card-top">
                                <a href="{{ route('farmer.orders.show', $order) }}" class="order-number text-decoration-none">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</a>
                                @if($order->status == 'placed')
                                    <span class="status-pill gold">Pending</span>
                                @else
                                    <span class="status-pill {{ $order->status == 'completed' ? 'green' : 'neutral' }}">{{ ucfirst($order->status) }}</span>
                                @endif
                            </div>
                            <div class="order-market" style="border:none; padding:10px 0; background:transparent;">
                                <span>
                                    <img src="{{ $order->customer->profile_photo_url }}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;">
                                    <div>
                                        <strong>{{ $order->customer->name }}</strong>
                                        <small>Pickup: {{ $order->pickup_date->format('M d') }} - {{ explode('(', $order->pickup_slot)[0] }}</small>
                                    </div>
                                </span>
                                <strong>${{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                            <div class="order-card-bottom">
                                @if($order->status == 'placed')
                                    <form action="{{ route('farmer.orders.accept', $order) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="primary-button" style="padding: 4px 12px; min-height: 28px; font-size: 9px; width: max-content;">Accept Order</button>
                                    </form>
                                @endif
                                <a href="{{ route('farmer.orders.show', $order) }}" class="card-link text-decoration-none" style="margin-left: auto;">Details <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 40px; border: 1px dashed var(--line); border-radius: 12px; margin-top: 15px;">
                    <i class="bi bi-check-circle text-success mb-2 d-block" style="font-size: 24px;"></i>
                    <strong>All caught up!</strong>
                    <p class="text-muted" style="font-size: 11px;">You don't have any pending orders at the moment.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Inventory Alerts -->
    <div style="display: flex; flex-direction: column; gap: 14px;">
        <div class="data-card">
            <h6 class="fw-bold mb-3" style="font-size: 14px; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-exclamation-triangle text-warning"></i> Inventory Alerts
            </h6>
            
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @forelse($lowStockProducts as $product)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px; border: 1px solid var(--line); border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="{{ $product->image_url }}" style="width: 30px; height: 30px; border-radius: 6px; object-fit:cover;">
                            <div>
                                <strong style="display: block; font-size: 11px;">{{ $product->name }}</strong>
                                <span style="font-size: 9px; color: var(--clay); font-weight: 800;">{{ $product->stock_quantity }} left</span>
                            </div>
                        </div>
                        <a href="{{ route('farmer.products.edit', $product) }}" style="color: var(--muted);"><i class="bi bi-pencil"></i></a>
                    </div>
                @empty
                    <div style="text-align: center; padding: 20px;">
                        <i class="bi bi-box2-heart text-muted mb-2 d-block" style="font-size: 20px;"></i>
                        <span style="font-size: 11px; color: var(--muted);">Stock levels are looking good.</span>
                    </div>
                @endforelse
            </div>
            
            <a href="{{ route('farmer.products.create') }}" class="primary-button wide" style="margin-top: 15px; justify-content: center;">
                <i class="bi bi-plus-circle"></i> Add New Product
            </a>
        </div>
    </div>
</div>

@endsection
