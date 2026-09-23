@extends('layouts.admin')
@section('page_title', 'Admin Dashboard')

@section('content')

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-number">{{ $totalCustomers }}</div>
                <div class="stat-label">Total Customers</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-gold"><i class="bi bi-shop-window"></i></div>
            <div>
                <div class="stat-number">{{ $totalFarmers }}</div>
                <div class="stat-label">Registered Farmers</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-geo-alt"></i></div>
            <div>
                <div class="stat-number">{{ $totalMarkets }}</div>
                <div class="stat-label">Active Markets</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-teal"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="stat-number">${{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-label">Platform Volume</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-ml border-0 h-100">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Recent Platform Orders</h6>
            </div>
            
            <div class="table-responsive">
                <table class="table table-ml mb-0 border-0 shadow-none">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Farmer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="fw-bold">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->customer->name }}</td>
                                <td>{{ $order->farmer->farmerProfile->stall_name ?? $order->farmer->name }}</td>
                                <td class="fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                                <td><span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card-ml border-0 h-100">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">Pending Approvals</h6>
            </div>
            <div class="p-4">
                @if($pendingApprovals > 0)
                    <div class="alert alert-warning border-0 d-flex gap-3 align-items-center">
                        <i class="bi bi-exclamation-circle fs-3 text-warning"></i>
                        <div>
                            <div class="fw-bold">Action Required</div>
                            <div class="small">There are {{ $pendingApprovals }} new farmer(s) waiting for profile approval.</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.farmers', ['status' => 'pending']) }}" class="btn btn-outline-ml w-100 justify-content-center mt-2">Review Applications</a>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-shield-check fs-1 mb-2 d-block text-success"></i>
                        <p class="small mb-0">All farmer applications have been reviewed.</p>
                    </div>
                @endif
                
                <hr class="my-4">
                
                <h6 class="fw-bold mb-3 small text-uppercase text-muted">Top Farmers</h6>
                <div class="d-flex flex-column gap-3">
                    @foreach($topFarmers as $farmer)
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-icon stat-icon-gold rounded-circle" style="width:36px;height:36px;font-size:1rem;">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="flex-grow-1 text-truncate">
                                <div class="fw-bold small text-truncate">{{ $farmer->farmerProfile->stall_name ?? $farmer->name }}</div>
                                <div class="text-muted" style="font-size:0.7rem;">{{ $farmer->order_count }} orders</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
