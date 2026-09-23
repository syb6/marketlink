@extends('layouts.farmer')
@section('page_title', 'Farmer Dashboard')

@section('content')

@if(auth()->user()->farmerProfile && !auth()->user()->farmerProfile->is_approved)
    <div class="alert alert-warning border-0 p-4 mb-4 rounded-ml shadow-sm d-flex gap-3 align-items-start">
        <i class="bi bi-shield-lock fs-1 text-warning mt-1"></i>
        <div>
            <h5 class="fw-bold mb-1">Account Pending Approval</h5>
            <p class="mb-0 text-dark">Your account is currently under review by our administration team. Once approved, your stall and products will become visible to customers. You can still set up your profile and add products in the meantime.</p>
        </div>
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="stat-number">${{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-gold"><i class="bi bi-bell"></i></div>
            <div>
                <div class="stat-number">{{ $pendingOrders }}</div>
                <div class="stat-label">Pending Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="stat-number">{{ $totalProducts }}</div>
                <div class="stat-label">Active Products</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-red"><i class="bi bi-star"></i></div>
            <div>
                <div class="stat-number">{{ number_format($averageRating, 1) }}</div>
                <div class="stat-label">Average Rating</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Orders -->
    <div class="col-lg-8">
        <div class="card-ml border-0 h-100">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Orders Needing Action</h6>
                <a href="{{ route('farmer.orders.index') }}" class="btn btn-sm btn-outline-ml">View All</a>
            </div>
            
            @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ml mb-0 border-0 shadow-none">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Pickup Slot</th>
                                <th>Total</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('farmer.orders.show', $order) }}" class="fw-bold text-decoration-none">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</a></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $order->customer->profile_photo_url }}" class="rounded-circle" width="24" height="24">
                                            {{ $order->customer->name }}
                                        </div>
                                    </td>
                                    <td>{{ $order->pickup_date->format('M d') }} - <small class="text-muted">{{ explode('(', $order->pickup_slot)[0] }}</small></td>
                                    <td class="fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-end">
                                        @if($order->status == 'placed')
                                            <form action="{{ route('farmer.orders.accept', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">Accept</button>
                                            </form>
                                        @else
                                            <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state py-5">
                    <i class="bi bi-check-circle empty-state-icon text-success"></i>
                    <h6 class="fw-bold">All caught up!</h6>
                    <p class="text-muted small">You don't have any pending orders at the moment.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Inventory Alerts -->
    <div class="col-lg-4">
        <div class="card-ml border-0 p-4 h-100">
            <h6 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle text-warning"></i> Inventory Alerts
            </h6>
            
            @forelse($lowStockProducts as $product)
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ $product->image_url }}" class="rounded shadow-sm" width="40" height="40" style="object-fit:cover;">
                        <div>
                            <div class="fw-bold small text-dark">{{ $product->name }}</div>
                            <div class="text-danger small fw-bold">{{ $product->stock_quantity }} left</div>
                        </div>
                    </div>
                    <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-box2-heart fs-1 mb-2 d-block"></i>
                    <p class="small mb-0">Stock levels are looking good.</p>
                </div>
            @endforelse
            
            <a href="{{ route('farmer.products.create') }}" class="btn btn-primary-ml w-100 mt-2 justify-content-center">
                <i class="bi bi-plus-circle"></i> Add New Product
            </a>
        </div>
    </div>
</div>

@endsection
