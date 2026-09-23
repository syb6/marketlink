@extends('layouts.farmer')
@section('page_title', 'Order Management')

@section('content')

<a href="{{ route('farmer.orders.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Orders</a>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-ml border-0 p-0 overflow-hidden mb-4">
            <div class="bg-light p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Order #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h5>
                    <div class="text-muted small">Placed on {{ $order->created_at->format('M d, Y g:i A') }}</div>
                </div>
                <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
            </div>
            
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="text-muted small text-uppercase fw-bold border-bottom">
                            <tr>
                                <th class="ps-0">Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th class="text-end pe-0">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-bottom">
                                    <td class="ps-0 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item->product->image_url }}" class="rounded shadow-sm" width="50" height="50" style="object-fit: cover;">
                                            <span class="fw-bold text-dark">{{ $item->product->name }}</span>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end pe-0 fw-bold">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="fw-bold text-dark">
                            <tr>
                                <td colspan="3" class="text-end pt-4">Total Amount Due</td>
                                <td class="text-end pt-4 fs-4 text-primary">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Order Actions -->
            <div class="p-4 bg-light border-top">
                <h6 class="fw-bold mb-3">Update Order Status</h6>
                <div class="d-flex flex-wrap gap-2">
                    @if($order->status == 'placed')
                        <form action="{{ route('farmer.orders.accept', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Accept Order</button>
                        </form>
                        <form action="{{ route('farmer.orders.decline', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to decline this order? The customer will be notified.');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-x-lg me-1"></i> Decline Order</button>
                        </form>
                    @endif

                    @if($order->status == 'accepted')
                        <form action="{{ route('farmer.orders.ready', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-info text-white"><i class="bi bi-box-seam me-1"></i> Mark as Ready for Pickup</button>
                        </form>
                    @endif

                    @if($order->status == 'ready')
                        <form action="{{ route('farmer.orders.complete', $order) }}" method="POST" onsubmit="return confirm('Did the customer pick up and pay for this order?');">
                            @csrf
                            <button type="submit" class="btn btn-dark"><i class="bi bi-cash-coin me-1"></i> Confirm Pickup & Payment</button>
                        </form>
                    @endif
                    
                    @if(in_array($order->status, ['completed', 'cancelled', 'declined']))
                        <div class="alert alert-secondary mb-0 w-100 py-2 border-0">
                            <i class="bi bi-info-circle me-1"></i> This order is finalized and can no longer be updated.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Customer Info -->
        <div class="card-ml border-0 p-4 mb-4">
            <h6 class="fw-bold text-uppercase text-muted small mb-3">Customer Details</h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="{{ $order->customer->profile_photo_url }}" class="rounded-circle border border-primary" width="56" height="56" style="object-fit:cover;">
                <div>
                    <h6 class="fw-bold mb-1">{{ $order->customer->name }}</h6>
                    <div class="small text-muted">{{ $order->customer->email }}</div>
                </div>
            </div>
            @if($order->customer->phone)
                <div class="d-flex align-items-center gap-2 small text-muted">
                    <i class="bi bi-telephone"></i> {{ $order->customer->phone }}
                </div>
            @endif
        </div>

        <!-- Pickup Info -->
        <div class="card-ml border-0 p-4 mb-4">
            <h6 class="fw-bold text-uppercase text-muted small mb-3">Pickup Commitment</h6>
            
            <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon stat-icon-green rounded-circle" style="width:36px;height:36px;"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Date</div>
                    <div class="fw-medium text-dark">{{ $order->pickup_date->format('l, F j, Y') }}</div>
                </div>
            </div>
            
            <div class="d-flex align-items-start gap-3">
                <div class="stat-icon stat-icon-blue rounded-circle" style="width:36px;height:36px;"><i class="bi bi-clock"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Time Window</div>
                    <div class="fw-medium text-dark">{{ $order->pickup_slot }}</div>
                </div>
            </div>
        </div>

        @if($order->notes)
            <div class="card-ml border-0 p-4">
                <h6 class="fw-bold text-uppercase text-muted small mb-2">Customer Notes</h6>
                <div class="bg-light p-3 rounded fst-italic text-dark small">
                    "{{ $order->notes }}"
                </div>
            </div>
        @endif
    </div>
</div>

@endsection
