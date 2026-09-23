@extends('layouts.customer')
@section('page_title', 'Order Details')

@section('content')

<a href="{{ route('customer.orders.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Orders</a>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-ml border-0 p-0 overflow-hidden mb-4">
            <div class="bg-light p-4 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-1">Order #ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h5>
                    <div class="text-muted small">Placed on {{ $order->created_at->format('M d, Y g:i A') }}</div>
                </div>
                <div class="text-end">
                    <span class="status-badge status-{{ $order->status }} d-block mb-1">{{ $order->status }}</span>
                    @if($order->status === 'placed')
                        <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger p-0 small text-decoration-none">Cancel Order</button>
                        </form>
                    @endif
                </div>
            </div>
            
            <div class="p-4">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="text-muted small text-uppercase fw-bold border-bottom">
                            <tr>
                                <th class="ps-0">Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-end pe-0">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-bottom">
                                    <td class="ps-0 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="rounded shadow-sm" width="50" height="50" style="object-fit: cover;">
                                            <a href="{{ route('customer.products.show', $item->product) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $item->product->name }}</a>
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
                                <td colspan="3" class="text-end pt-4">Total Amount</td>
                                <td class="text-end pt-4 fs-5 text-primary">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            @if($order->status === 'completed' && !$order->review)
                <div class="p-4 bg-light border-top d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">Leave a Review</h6>
                        <p class="small text-muted mb-0">Help others by sharing your experience.</p>
                    </div>
                    <a href="{{ route('customer.reviews.create', $order) }}" class="btn btn-accent-ml">Write Review</a>
                </div>
            @endif
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Farmer Info -->
        <div class="card-ml border-0 p-4 mb-4">
            <h6 class="fw-bold text-uppercase text-muted small mb-3">Seller Details</h6>
            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="{{ $order->farmer->profile_photo_url }}" class="rounded-circle border border-primary" width="56" height="56" style="object-fit:cover;">
                <div>
                    <h6 class="fw-bold mb-1">{{ $order->farmer->farmerProfile->stall_name ?? $order->farmer->name }}</h6>
                    <a href="{{ route('customer.products.index', ['farmer' => $order->farmer_id]) }}" class="small text-primary text-decoration-none">View Store</a>
                </div>
            </div>
            @if($order->farmer->phone)
                <div class="d-flex align-items-center gap-2 small text-muted">
                    <i class="bi bi-telephone"></i> {{ $order->farmer->phone }}
                </div>
            @endif
            <div class="d-flex align-items-center gap-2 small text-muted mt-1">
                <i class="bi bi-envelope"></i> {{ $order->farmer->email }}
            </div>
        </div>

        <!-- Pickup Info -->
        <div class="card-ml border-0 p-4 mb-4">
            <h6 class="fw-bold text-uppercase text-muted small mb-3">Pickup Instructions</h6>
            
            <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon stat-icon-green rounded-circle" style="width:36px;height:36px;"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Date</div>
                    <div class="fw-medium">{{ $order->pickup_date->format('l, F j, Y') }}</div>
                </div>
            </div>
            
            <div class="d-flex align-items-start gap-3 mb-3">
                <div class="stat-icon stat-icon-blue rounded-circle" style="width:36px;height:36px;"><i class="bi bi-clock"></i></div>
                <div>
                    <div class="small text-muted fw-bold">Time Window</div>
                    <div class="fw-medium">{{ $order->pickup_slot }}</div>
                </div>
            </div>
            
            <div class="alert alert-warning border-0 p-3 mb-0 small rounded">
                <i class="bi bi-info-circle-fill me-1"></i> Please pay the farmer directly via cash or their preferred payment method upon pickup.
            </div>
        </div>

        @if($order->notes)
            <div class="card-ml border-0 p-4">
                <h6 class="fw-bold text-uppercase text-muted small mb-2">Order Notes</h6>
                <p class="small mb-0 text-dark">{{ $order->notes }}</p>
            </div>
        @endif
    </div>
</div>

@endsection
