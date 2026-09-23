@extends('layouts.customer')
@section('page_title', 'Checkout')

@section('content')

@if(empty($cart) || $ordersByFarmer->isEmpty())
    <div style="text-align: center; padding: 60px 20px; background: var(--paper); border: 1px dashed var(--line); border-radius: 12px; margin-top: 20px;">
        <i class="bi bi-cart-x text-muted" style="font-size: 32px; margin-bottom: 15px; display: block;"></i>
        <h3 style="font-size: 20px; margin-bottom: 10px;">Your cart is empty or invalid</h3>
        <a href="{{ route('customer.products.index') }}" class="primary-button text-decoration-none" style="margin-top: 15px;">Go shop</a>
    </div>
@else
    
<div class="checkout-layout">
    <div class="checkout-main">
        <div style="margin-bottom: 25px;">
            <h2 style="font-size: 24px; margin-bottom: 5px;">Checkout Details</h2>
            <p style="color: var(--muted); font-size: 11px;">Complete your pre-order. You will pay the farmers directly at pickup.</p>
        </div>
        
        <form action="{{ route('customer.orders.store') }}" method="POST" id="checkout-form">
            @csrf
            
            <div class="data-card" style="margin-bottom: 20px;">
                <h3 style="font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-calendar-event text-primary"></i> Pickup Information
                </h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <label style="display: flex; flex-direction: column; gap: 6px; font-size: 10px; font-weight: 700; color: var(--ink);">
                        Pickup Date *
                        <input type="date" name="pickup_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" style="padding: 10px; border: 1px solid var(--line); border-radius: 6px; font-family: inherit; font-size: 12px; outline: none;">
                    </label>
                    <label style="display: flex; flex-direction: column; gap: 6px; font-size: 10px; font-weight: 700; color: var(--ink);">
                        Estimated Time *
                        <select name="pickup_slot" required style="padding: 10px; border: 1px solid var(--line); border-radius: 6px; font-family: inherit; font-size: 12px; outline: none; background: #fff;">
                            <option value="">Select a time window</option>
                            <option value="Morning (8:00 AM - 11:00 AM)">Morning (8:00 AM - 11:00 AM)</option>
                            <option value="Mid-day (11:00 AM - 2:00 PM)">Mid-day (11:00 AM - 2:00 PM)</option>
                            <option value="Afternoon (2:00 PM - 5:00 PM)">Afternoon (2:00 PM - 5:00 PM)</option>
                        </select>
                    </label>
                </div>
            </div>

            <div class="data-card" style="margin-bottom: 20px;">
                <h3 style="font-size: 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <i class="bi bi-chat-text text-primary"></i> Additional Notes
                </h3>
                <label style="display: flex; flex-direction: column; gap: 6px; font-size: 10px; font-weight: 700; color: var(--ink);">
                    Any special requests for the farmers? (Optional)
                    <textarea name="notes" rows="3" placeholder="E.g., I will arrive closer to 10 AM..." style="padding: 10px; border: 1px solid var(--line); border-radius: 6px; font-family: inherit; font-size: 12px; outline: none; resize: vertical;"></textarea>
                </label>
            </div>

            <div style="background: #fff0c5; border: 1px solid #e2d2a4; border-radius: 12px; padding: 20px; display: flex; gap: 15px;">
                <i class="bi bi-cash-coin" style="font-size: 24px; color: #876a18;"></i>
                <div>
                    <strong style="display: block; font-size: 13px; color: #876a18; margin-bottom: 5px;">Pay at Pickup</strong>
                    <p style="margin: 0; font-size: 11px; color: #876a18; line-height: 1.5;">
                        MarketLink does not process payments online. You will pay the farmers directly when you pick up your order at the market. Cash or local payments are typically accepted.
                    </p>
                </div>
            </div>
        </form>
    </div>

    <aside>
        <div class="checkout-summary" style="position: sticky; top: 100px;">
            <h3 style="font-size: 16px; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid var(--soft-line);">Order Summary</h3>
            
            @php $grandTotal = 0; @endphp
            
            @foreach($ordersByFarmer as $farmerId => $data)
                <div style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid var(--soft-line);">
                    <strong style="display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--ink); margin-bottom: 10px;">
                        <i class="bi bi-shop text-muted"></i> 
                        {{ $data['farmer']->farmerProfile->stall_name ?? $data['farmer']->name }}
                    </strong>
                    
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($data['items'] as $item)
                            @php $grandTotal += $item['subtotal']; @endphp
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11px;">
                                <span style="color: var(--ink-soft); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <strong style="color: var(--muted); margin-right: 5px;">{{ $item['quantity'] }}x</strong> 
                                    {{ $item['product']->name }}
                                </span>
                                <strong style="color: var(--ink); margin-left: 10px;">${{ number_format($item['subtotal'], 2) }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; font-size: 14px;">
                <strong style="color: var(--ink);">Total Due</strong>
                <strong style="color: var(--forest); font-size: 18px;">${{ number_format($grandTotal, 2) }}</strong>
            </div>
            <div style="text-align: right; font-size: 9px; color: var(--muted); margin-top: 5px;">To be paid at pickup</div>
            
            <button type="submit" form="checkout-form" class="primary-button wide" style="margin-top: 25px;">
                Place Order <i class="bi bi-check-circle"></i>
            </button>
        </div>
    </aside>
</div>

@endif
@endsection
