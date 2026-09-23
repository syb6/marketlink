@extends('layouts.customer')
@section('page_title', 'My Orders')

@section('content')

<div class="card-ml border-0 p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <ul class="nav nav-pills gap-2" id="order-tabs">
            <li class="nav-item">
                <a class="nav-link rounded-pill {{ request('status') == '' ? 'active bg-primary-ml' : 'text-dark bg-light' }}" href="{{ route('customer.orders.index') }}">All Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill {{ request('status') == 'placed' ? 'active bg-primary-ml' : 'text-dark bg-light' }}" href="{{ route('customer.orders.index', ['status' => 'placed']) }}">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill {{ request('status') == 'ready' ? 'active bg-primary-ml' : 'text-dark bg-light' }}" href="{{ route('customer.orders.index', ['status' => 'ready']) }}">Ready</a>
            </li>
            <li class="nav-item">
                <a class="nav-link rounded-pill {{ request('status') == 'completed' ? 'active bg-primary-ml' : 'text-dark bg-light' }}" href="{{ route('customer.orders.index', ['status' => 'completed']) }}">Completed</a>
            </li>
        </ul>
    </div>
</div>

<div class="row g-4">
    @forelse($orders as $order)
        <div class="col-md-6 col-xl-4">
            <div class="card-ml border-0 h-100 d-flex flex-column">
                <div class="p-4 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-muted fw-bold mb-1">Order #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                        <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
                    </div>
                    <div class="fs-5 fw-bold text-dark">${{ number_format($order->total_amount, 2) }}</div>
                </div>
                
                <div class="p-4 flex-grow-1">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ $order->farmer->profile_photo_url }}" class="rounded-circle" width="48" height="48" style="object-fit:cover;">
                        <div>
                            <div class="small text-muted text-uppercase fw-bold">Farmer</div>
                            <h6 class="fw-bold mb-0">{{ $order->farmer->farmerProfile->stall_name ?? $order->farmer->name }}</h6>
                        </div>
                    </div>
                    
                    <div class="bg-light rounded p-3 mb-4">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-calendar-event text-primary mt-1"></i>
                            <div>
                                <div class="small text-muted fw-bold">Pickup Date</div>
                                <div class="fw-medium">{{ $order->pickup_date->format('l, M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-clock text-primary mt-1"></i>
                            <div>
                                <div class="small text-muted fw-bold">Time Window</div>
                                <div class="fw-medium">{{ $order->pickup_slot }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="small text-muted mb-2 fw-bold text-uppercase">Items ({{ $order->items->count() }})</div>
                    <ul class="list-unstyled mb-0 small">
                        @foreach($order->items->take(3) as $item)
                            <li class="mb-1 d-flex justify-content-between">
                                <span><span class="text-muted">{{ $item->quantity }}x</span> {{ $item->product->name }}</span>
                                <span class="text-muted">${{ number_format($item->unit_price * $item->quantity, 2) }}</span>
                            </li>
                        @endforeach
                        @if($order->items->count() > 3)
                            <li class="text-muted fst-italic">...and {{ $order->items->count() - 3 }} more items</li>
                        @endif
                    </ul>
                </div>
                
                <div class="p-3 border-top">
                    <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-ml w-100 justify-content-center">View Order Details</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-receipt empty-state-icon"></i>
                <h5 class="fw-bold">No orders found</h5>
                <p>You don't have any orders matching this status.</p>
                @if(request()->has('status'))
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-ml mt-2">View All Orders</a>
                @else
                    <a href="{{ route('customer.products.index') }}" class="btn btn-primary-ml mt-2">Start Shopping</a>
                @endif
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>

@endsection
