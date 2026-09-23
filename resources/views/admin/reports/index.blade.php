@extends('layouts.admin')
@section('page_title', 'System Reports')

@section('content')

<div class="card-ml border-0 p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Analytics & Reports</h5>
        
        <form action="{{ route('admin.reports.index') }}" method="GET" class="d-flex align-items-center gap-2 form-ml">
            <label class="form-label mb-0 fw-bold small text-muted text-nowrap">Time Period:</label>
            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="7" {{ $period == '7' ? 'selected' : '' }}>Last 7 Days</option>
                <option value="30" {{ $period == '30' ? 'selected' : '' }}>Last 30 Days</option>
                <option value="90" {{ $period == '90' ? 'selected' : '' }}>Last 90 Days</option>
                <option value="365" {{ $period == '365' ? 'selected' : '' }}>Last 1 Year</option>
            </select>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card border border-2 border-primary">
            <div class="stat-icon stat-icon-green"><i class="bi bi-currency-dollar"></i></div>
            <div>
                <div class="stat-number">${{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-label">Revenue Generated</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue"><i class="bi bi-cart-check"></i></div>
            <div>
                <div class="stat-number">{{ $totalOrders }}</div>
                <div class="stat-label">Orders Placed</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-gold"><i class="bi bi-person-plus"></i></div>
            <div>
                <div class="stat-number">{{ $newCustomers }}</div>
                <div class="stat-label">New Customers</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon stat-icon-teal"><i class="bi bi-shop-window"></i></div>
            <div>
                <div class="stat-number">{{ $newFarmers }}</div>
                <div class="stat-label">New Farmers</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-ml border-0 h-100 p-0 overflow-hidden">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">Top Performing Farmers</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4">Farmer</th>
                            <th>Orders</th>
                            <th class="text-end pe-4">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topFarmers as $farmer)
                            <tr class="border-bottom">
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">{{ $farmer->farmerProfile->stall_name ?? $farmer->name }}</div>
                                </td>
                                <td>{{ $farmer->order_count }}</td>
                                <td class="text-end pe-4 fw-medium text-success">${{ number_format($farmer->total_revenue, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">No data available for this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card-ml border-0 h-100 p-0 overflow-hidden">
            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">Most Ordered Products</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase fw-bold">
                        <tr>
                            <th class="ps-4">Product</th>
                            <th>Farmer</th>
                            <th class="text-end pe-4">Times Ordered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $product)
                            <tr class="border-bottom">
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                    <div class="small text-muted">{{ $product->category->name }}</div>
                                </td>
                                <td class="small">{{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}</td>
                                <td class="text-end pe-4 fw-medium">{{ $product->times_ordered }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">No data available for this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
