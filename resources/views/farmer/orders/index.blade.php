@extends('layouts.farmer')
@section('page_title', 'Manage Orders')

@section('content')

<div class="card-ml border-0 p-4 mb-4">
    <ul class="nav nav-pills gap-2 flex-wrap">
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ request('status') == '' ? 'active bg-primary-ml' : 'text-dark bg-light' }}" href="{{ route('farmer.orders.index') }}">All</a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ request('status') == 'placed' ? 'active bg-primary-ml position-relative' : 'text-dark bg-light position-relative' }}" href="{{ route('farmer.orders.index', ['status' => 'placed']) }}">
                New Requests
                @php $placedCount = \App\Models\Order::where('farmer_id', auth()->id())->where('status', 'placed')->count(); @endphp
                @if($placedCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size:0.6rem;">{{ $placedCount }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ request('status') == 'accepted' ? 'active bg-primary-ml' : 'text-dark bg-light' }}" href="{{ route('farmer.orders.index', ['status' => 'accepted']) }}">Preparing</a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ request('status') == 'ready' ? 'active bg-primary-ml' : 'text-dark bg-light' }}" href="{{ route('farmer.orders.index', ['status' => 'ready']) }}">Ready for Pickup</a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill {{ request('status') == 'completed' ? 'active bg-primary-ml' : 'text-dark bg-light' }}" href="{{ route('farmer.orders.index', ['status' => 'completed']) }}">Completed</a>
        </li>
    </ul>
</div>

<div class="row g-4">
    @forelse($orders as $order)
        <div class="col-md-6 col-xl-4">
            <div class="card-ml border-0 h-100 d-flex flex-column {{ $order->status === 'placed' ? 'border border-2 border-warning shadow-sm' : '' }}">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small text-muted fw-bold mb-1">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                        <div class="fs-5 fw-bold text-dark">${{ number_format($order->total_amount, 2) }}</div>
                    </div>
                    <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
                </div>
                
                <div class="p-4 flex-grow-1">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="{{ $order->customer->profile_photo_url }}" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                        <div>
                            <div class="small text-muted text-uppercase fw-bold">Customer</div>
                            <h6 class="fw-bold mb-0">{{ $order->customer->name }}</h6>
                        </div>
                    </div>
                    
                    <div class="bg-light rounded p-3 mb-4">
                        <div class="fw-bold small text-muted mb-1 text-uppercase">Pickup Window</div>
                        <div class="fw-medium text-dark"><i class="bi bi-calendar-event text-primary me-1"></i> {{ $order->pickup_date->format('M d, Y') }}</div>
                        <div class="fw-medium text-dark mt-1"><i class="bi bi-clock text-primary me-1"></i> {{ $order->pickup_slot }}</div>
                    </div>
                    
                    <ul class="list-unstyled mb-0 small">
                        @foreach($order->items->take(3) as $item)
                            <li class="mb-1 d-flex justify-content-between">
                                <span class="text-truncate pe-2"><span class="text-muted">{{ $item->quantity }}x</span> {{ $item->product->name }}</span>
                            </li>
                        @endforeach
                        @if($order->items->count() > 3)
                            <li class="text-muted fst-italic mt-1">+{{ $order->items->count() - 3 }} more items</li>
                        @endif
                    </ul>
                </div>
                
                <div class="p-3 border-top bg-light d-flex gap-2">
                    <a href="{{ route('farmer.orders.show', $order) }}" class="btn btn-outline-ml flex-grow-1 justify-content-center">Manage Order</a>
                    
                    @if($order->status == 'placed')
                        <form action="{{ route('farmer.orders.accept', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success h-100 px-3 rounded-ml shadow-sm" title="Accept Order"><i class="bi bi-check-lg"></i></button>
                        </form>
                    @elseif($order->status == 'accepted')
                        <form action="{{ route('farmer.orders.ready', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info text-white h-100 px-3 rounded-ml shadow-sm" title="Mark as Ready"><i class="bi bi-box-seam"></i></button>
                        </form>
                    @elseif($order->status == 'ready')
                        <form action="{{ route('farmer.orders.complete', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-dark h-100 px-3 rounded-ml shadow-sm" title="Mark Completed (Picked Up)"><i class="bi bi-cash"></i></button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-inbox empty-state-icon"></i>
                <h5 class="fw-bold">No orders found</h5>
                <p>You don't have any orders matching this criteria.</p>
                @if(request()->has('status'))
                    <a href="{{ route('farmer.orders.index') }}" class="btn btn-outline-ml mt-2">View All Orders</a>
                @endif
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $orders->links('pagination::bootstrap-5') }}
</div>

@endsection
