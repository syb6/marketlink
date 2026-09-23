@extends('layouts.customer')
@section('page_title', 'My Dashboard')

@section('content')

<!-- Announcements -->
@if($announcements->count() > 0)
    @foreach($announcements as $announcement)
        <div class="announcement-bar animate-up text-{{ $announcement->type == 'info' ? 'primary' : ($announcement->type == 'success' ? 'success' : 'warning') }}">
            <i class="bi bi-megaphone-fill me-2"></i>
            <strong>{{ $announcement->title }}:</strong> {{ $announcement->body }}
        </div>
    @endforeach
@endif

<!-- Stats Overview -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card animate-up-delay-1">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-bag"></i></div>
            <div>
                <div class="stat-number">{{ $totalOrders }}</div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card animate-up-delay-2">
            <div class="stat-icon stat-icon-gold"><i class="bi bi-clock"></i></div>
            <div>
                <div class="stat-number">{{ $pendingOrders }}</div>
                <div class="stat-label">Pending / Ready</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card animate-up-delay-3">
            <div class="stat-icon stat-icon-green"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-number">{{ $completedOrders }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card animate-up-delay-4">
            <div class="stat-icon stat-icon-red"><i class="bi bi-heart"></i></div>
            <div>
                <div class="stat-number">{{ $favoriteCount }}</div>
                <div class="stat-label">Saved Favorites</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card-ml border-0 h-100">
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
                <h6 class="fw-bold mb-0">Recent Orders</h6>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-ml">View All</a>
            </div>
            
            @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-ml mb-0 border-0 shadow-none">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Farmer</th>
                                <th>Pickup Date</th>
                                <th>Status</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('customer.orders.show', $order) }}" class="fw-bold text-decoration-none">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</a></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $order->farmer->profile_photo_url }}" class="rounded-circle" width="24" height="24">
                                            {{ $order->farmer->farmerProfile->stall_name ?? $order->farmer->name }}
                                        </div>
                                    </td>
                                    <td>{{ $order->pickup_date->format('M d, Y') }} <br> <small class="text-muted">{{ $order->pickup_slot }}</small></td>
                                    <td><span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span></td>
                                    <td class="fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-receipt empty-state-icon"></i>
                    <h5 class="fw-bold">No orders yet</h5>
                    <p>Start browsing markets and local products to place your first order.</p>
                    <a href="{{ route('customer.products.index') }}" class="btn btn-primary-ml mt-2">Browse Products</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="card-ml border-0 p-4 h-100">
            <h6 class="fw-bold mb-4">Quick Actions</h6>
            
            <a href="{{ route('customer.markets.index') }}" class="btn btn-outline-ml w-100 mb-3 justify-content-start py-3">
                <i class="bi bi-shop fs-5 me-2"></i> 
                <div class="text-start">
                    <div class="fw-bold text-dark">Find a Market</div>
                    <small class="text-muted">Discover nearby locations</small>
                </div>
            </a>
            
            <a href="{{ route('customer.products.index') }}" class="btn btn-outline-ml w-100 mb-3 justify-content-start py-3">
                <i class="bi bi-bag fs-5 me-2"></i> 
                <div class="text-start">
                    <div class="fw-bold text-dark">Shop Produce</div>
                    <small class="text-muted">Search fresh inventory</small>
                </div>
            </a>

            <a href="{{ route('customer.favorites.index') }}" class="btn btn-outline-ml w-100 justify-content-start py-3">
                <i class="bi bi-heart fs-5 me-2"></i> 
                <div class="text-start">
                    <div class="fw-bold text-dark">My Favorites</div>
                    <small class="text-muted">View saved items</small>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
