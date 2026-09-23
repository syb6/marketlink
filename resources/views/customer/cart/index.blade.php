@extends('layouts.customer')
@section('page_title', 'Shopping Cart')

@section('content')

@if(empty($cart) || $products->isEmpty())
    <div style="text-align: center; padding: 60px 20px; background: var(--paper); border: 1px dashed var(--line); border-radius: 12px; margin-top: 20px;">
        <i class="bi bi-cart-x text-muted" style="font-size: 32px; margin-bottom: 15px; display: block;"></i>
        <h3 style="font-size: 20px; margin-bottom: 10px;">Your cart is empty</h3>
        <p style="color: var(--ink-soft); font-size: 11px; margin-bottom: 20px;">Looks like you haven't added any fresh produce yet.</p>
        <a href="{{ route('customer.products.index') }}" class="primary-button text-decoration-none">Start Shopping</a>
    </div>
@else
    <div class="checkout-layout">
        <div class="checkout-main">
            <div class="checkout-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid var(--soft-line);">
                    <h2 style="font-size: 18px; margin: 0;">Cart Items ({{ array_sum($cart) }})</h2>
                    <form action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?');" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: var(--clay); font-size: 10px; font-weight: 800; cursor: pointer; padding: 0;">
                            <i class="bi bi-trash3"></i> CLEAR CART
                        </button>
                    </form>
                </div>

                <div style="display: flex; flex-direction: column; gap: 15px;">
                    @foreach($cart as $productId => $qty)
                        @php $product = $products->get($productId); @endphp
                        @if($product)
                            <div class="checkout-item" data-cart-row="{{ $product->id }}">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                <div>
                                    <h3 style="font-size: 13px; margin: 0 0 4px;"><a href="{{ route('customer.products.show', $product) }}" style="color: var(--ink); text-decoration: none;">{{ $product->name }}</a></h3>
                                    <span style="font-size: 10px; color: var(--muted);"><i class="bi bi-shop"></i> {{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}</span>
                                    @if($qty > $product->stock_quantity)
                                        <div style="font-size: 9px; color: var(--clay); margin-top: 4px;"><i class="bi bi-exclamation-triangle"></i> Only {{ $product->stock_quantity }} available</div>
                                    @endif
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                                    <strong style="font-size: 13px;" data-cart-subtotal="{{ $product->price * $qty }}">${{ number_format($product->price * $qty, 2) }}</strong>
                                    
                                    <div class="quantity-control" style="display: flex; align-items: center; border: 1px solid var(--line); border-radius: 6px; overflow: hidden;">
                                        <button type="button" class="qty-minus" style="padding: 4px 8px; border: none; background: #fff; cursor: pointer; font-size: 14px; color: var(--muted);"><i class="bi bi-dash"></i></button>
                                        <input type="number" class="qty-input" value="{{ $qty }}" min="1" max="{{ $product->stock_quantity }}" data-cart-qty-input="{{ $product->id }}" style="width: 30px; text-align: center; border: none; font-size: 11px; font-weight: 700; outline: none;">
                                        <button type="button" class="qty-plus" style="padding: 4px 8px; border: none; background: #fff; cursor: pointer; font-size: 14px; color: var(--muted);"><i class="bi bi-plus"></i></button>
                                    </div>

                                    <button type="button" class="remove-btn" data-cart-remove="{{ $product->id }}" style="background: none; border: none; color: var(--clay); font-size: 16px; cursor: pointer; margin-top: 5px;">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <aside>
            <div class="checkout-summary" style="position: sticky; top: 100px;">
                <h3 style="font-size: 16px; margin-bottom: 20px;">Order Summary</h3>
                
                <div class="checkout-summary-row">
                    <span>Subtotal</span>
                    <strong id="cart-total">${{ number_format($total, 2) }}</strong>
                </div>
                <div class="checkout-summary-row">
                    <span>Tax & Fees</span>
                    <strong>Calculated at checkout</strong>
                </div>
                
                <div class="checkout-summary-row" style="margin-top: 15px; padding-top: 15px; border-top: 1px solid var(--soft-line); font-size: 14px;">
                    <span style="font-weight: 800; color: var(--ink);">Estimated Total</span>
                    <strong id="cart-grand-total" style="color: var(--forest); font-size: 18px;">${{ number_format($total, 2) }}</strong>
                </div>
                
                <div style="background: var(--sage); border-radius: 8px; padding: 12px; font-size: 9px; color: var(--forest-dark); margin: 20px 0;">
                    <i class="bi bi-info-circle-fill me-1"></i> Orders are processed per farmer. You will pick up directly from them at the market.
                </div>

                <a href="{{ route('customer.orders.checkout') }}" class="primary-button wide" style="text-decoration:none;">
                    Proceed to Checkout <i class="bi bi-arrow-right"></i>
                </a>
                
                <div style="text-align: center; margin-top: 15px;">
                    <a href="{{ route('customer.products.index') }}" style="font-size: 10px; font-weight: 700; color: var(--muted); text-decoration: none;"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
                </div>
            </div>
        </aside>
    </div>
@endif

@endsection
