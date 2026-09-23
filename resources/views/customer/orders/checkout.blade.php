@extends('layouts.customer')
@section('page_title', 'Checkout')

@section('content')

@if(empty($cart) || $ordersByFarmer->isEmpty())
    <div class="alert alert-warning">Your cart is empty or invalid. <a href="{{ route('customer.products.index') }}">Go shop</a></div>
@else
    
<div class="row g-5">
    <div class="col-lg-7">
        <h4 class="fw-bold mb-4">Checkout Details</h4>
        
        <form action="{{ route('customer.orders.store') }}" method="POST" id="checkout-form" class="form-ml">
            @csrf
            
            <div class="card-ml border-0 p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-calendar-event text-primary me-2"></i> Pickup Information</h5>
                <p class="text-muted small mb-4">Select when you want to pick up your order. Orders are grouped by farmer.</p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="pickup_date" class="form-label">Pickup Date *</label>
                        <input type="date" class="form-control" id="pickup_date" name="pickup_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="pickup_slot" class="form-label">Estimated Time *</label>
                        <select class="form-select" id="pickup_slot" name="pickup_slot" required>
                            <option value="">Select a time window</option>
                            <option value="Morning (8:00 AM - 11:00 AM)">Morning (8:00 AM - 11:00 AM)</option>
                            <option value="Mid-day (11:00 AM - 2:00 PM)">Mid-day (11:00 AM - 2:00 PM)</option>
                            <option value="Afternoon (2:00 PM - 5:00 PM)">Afternoon (2:00 PM - 5:00 PM)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-ml border-0 p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-chat-text text-primary me-2"></i> Additional Notes</h5>
                <label for="notes" class="form-label text-muted small">Any special requests for the farmers?</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="E.g., I will arrive closer to 10 AM..."></textarea>
            </div>

            <div class="alert alert-warning border-0 p-4 rounded-ml d-flex gap-3 mb-0">
                <i class="bi bi-cash-coin fs-2"></i>
                <div>
                    <h6 class="fw-bold mb-1">Pay at Pickup</h6>
                    <p class="small mb-0">MarketLink does not process payments online. You will pay the farmers directly when you pick up your order at the market. Cash or local payments are typically accepted.</p>
                </div>
            </div>
        </form>
    </div>

    <div class="col-lg-5">
        <div class="card-ml border-0 p-0 overflow-hidden sticky-top" style="top: 100px;">
            <div class="p-4 border-bottom bg-light">
                <h5 class="fw-bold mb-0">Order Summary</h5>
            </div>
            
            <div class="p-4">
                @php $grandTotal = 0; @endphp
                
                @foreach($ordersByFarmer as $farmerId => $data)
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="fw-bold d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-shop text-muted"></i> 
                            {{ $data['farmer']->farmerProfile->stall_name ?? $data['farmer']->name }}
                        </h6>
                        
                        <div class="d-flex flex-column gap-2">
                            @foreach($data['items'] as $item)
                                @php $grandTotal += $item['subtotal']; @endphp
                                <div class="d-flex justify-content-between align-items-center small">
                                    <div class="text-truncate pe-2">
                                        <span class="text-muted">{{ $item['quantity'] }}x</span> 
                                        {{ $item['product']->name }}
                                    </div>
                                    <div class="fw-medium">${{ number_format($item['subtotal'], 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                
                <div class="d-flex justify-content-between align-items-center fs-5 mt-2">
                    <span class="fw-bold text-dark">Total Due</span>
                    <span class="fw-bold text-primary">${{ number_format($grandTotal, 2) }}</span>
                </div>
                <div class="small text-muted text-end mt-1">To be paid at pickup</div>
            </div>
            
            <div class="p-4 bg-light border-top">
                <button type="submit" form="checkout-form" class="btn btn-primary-ml w-100 justify-content-center py-3 fs-6">
                    <i class="bi bi-check-circle me-1"></i> Place Order
                </button>
            </div>
        </div>
    </div>
</div>

@endif
@endsection
