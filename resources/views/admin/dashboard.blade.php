@extends('layouts.admin')
@section('page_title', 'Admin Dashboard')

@section('content')

<div class="dashboard-header">
    <div>
        <h1>System Overview</h1>
        <p>Monitor platform activity, pending approvals, and top performers.</p>
    </div>
</div>

<div class="metrics-grid mb-4">
    <div class="metric-card sage">
        <div class="metric-top">
            <span style="color:var(--forest); background:rgba(255,255,255,0.65)"><i class="bi bi-people"></i></span>
        </div>
        <small>Total Customers</small>
        <strong style="color:var(--forest);">{{ $totalCustomers }}</strong>
    </div>
    <div class="metric-card gold">
        <div class="metric-top">
            <span style="color:#97741f; background:rgba(255,255,255,0.65)"><i class="bi bi-shop-window"></i></span>
        </div>
        <small>Registered Farmers</small>
        <strong style="color:#97741f;">{{ $totalFarmers }}</strong>
    </div>
    <div class="metric-card clay">
        <div class="metric-top">
            <span style="color:var(--clay); background:rgba(255,255,255,0.65)"><i class="bi bi-geo-alt"></i></span>
        </div>
        <small>Active Markets</small>
        <strong style="color:var(--clay);">{{ $totalMarkets }}</strong>
    </div>
    <div class="metric-card green">
        <div class="metric-top">
            <span style="color:var(--leaf); background:rgba(255,255,255,0.65)"><i class="bi bi-currency-dollar"></i></span>
        </div>
        <small>Platform Volume</small>
        <strong style="color:var(--leaf);">${{ number_format($totalRevenue, 0) }}</strong>
    </div>
</div>

<div class="admin-two-col">
    <div>
        <div class="data-card h-100">
            <div class="data-card-toolbar">
                <div class="data-card-header mb-0">
                    <h2 style="font-size: 20px; line-height: 1; margin: 0;">Recent Platform Orders</h2>
                </div>
            </div>
            
            <div class="orders-grid" style="grid-template-columns: 1fr; gap: 14px;">
                @forelse($recentOrders as $order)
                    <div class="order-card" style="border: 1px solid var(--line);">
                        <div class="order-card-top">
                            <strong class="order-number">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong>
                            <span class="status-pill {{ $order->status == 'completed' ? 'green' : ($order->status == 'placed' ? 'gold' : 'neutral') }}">{{ ucfirst($order->status) }}</span>
                        </div>
                        <div class="order-market" style="border:none; padding:10px 0; background:transparent;">
                            <span>
                                <div>
                                    <strong>{{ $order->customer->name }}</strong>
                                    <small>from {{ $order->farmer->farmerProfile->stall_name ?? $order->farmer->name }}</small>
                                </div>
                            </span>
                            <strong>${{ number_format($order->total_amount, 2) }}</strong>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 40px; border: 1px dashed var(--line); border-radius: 12px; margin-top: 15px;">
                        <p class="text-muted" style="font-size: 11px;">No orders yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div style="display: flex; flex-direction: column; gap: 14px;">
        <div class="data-card">
            <h6 class="fw-bold mb-3" style="font-size: 14px; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-shield-lock text-primary"></i> Pending Approvals
            </h6>
            
            @if($pendingApprovals > 0)
                <div style="background: #fff2ea; border-left: 3px solid var(--clay); padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <i class="bi bi-exclamation-circle text-warning fs-3" style="color: var(--clay) !important;"></i>
                        <div>
                            <strong style="display: block; font-size: 11px; color: var(--ink);">Action Required</strong>
                            <span style="font-size: 10px; color: var(--ink-soft);">There are {{ $pendingApprovals }} new farmer(s) waiting for profile approval.</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.users.farmers', ['status' => 'pending']) }}" class="primary-button wide" style="justify-content: center;">Review Applications</a>
            @else
                <div style="text-align: center; padding: 20px;">
                    <i class="bi bi-shield-check mb-2 d-block text-success" style="font-size: 20px;"></i>
                    <span style="font-size: 11px; color: var(--muted);">All farmer applications have been reviewed.</span>
                </div>
            @endif
        </div>
        
        <div class="data-card">
            <h6 class="fw-bold mb-3" style="font-size: 14px; display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-trophy text-primary"></i> Top Farmers
            </h6>
            
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach($topFarmers as $farmer)
                    <div style="display: flex; align-items: center; gap: 12px; padding: 10px; border: 1px solid var(--line); border-radius: 8px;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: #fff0c5; color: #876a18; display: grid; place-items: center; font-size: 12px;">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <div>
                            <strong style="display: block; font-size: 11px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 150px;">{{ $farmer->farmerProfile->stall_name ?? $farmer->name }}</strong>
                            <span style="font-size: 9px; color: var(--muted);">{{ $farmer->order_count }} orders</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
