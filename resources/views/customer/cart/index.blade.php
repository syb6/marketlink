@extends('layouts.customer')
@section('page_title', 'Shopping Cart')

@section('content')

@if(empty($cart) || $products->isEmpty())
    <div class="empty-state">
        <i class="bi bi-cart-x empty-state-icon"></i>
        <h3 class="fw-bold">Your cart is empty</h3>
        <p class="text-muted fs-5 mb-4">Looks like you haven't added any fresh produce yet.</p>
        <a href="{{ route('customer.products.index') }}" class="btn btn-primary-ml btn-lg rounded-pill px-5">Start Shopping</a>
    </div>
@else
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-ml border-0 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                    <h5 class="fw-bold mb-0">Cart Items ({{ array_sum($cart) }})</h5>
                    <form action="{{ route('customer.cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your cart?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                            <i class="bi bi-trash3"></i> Clear Cart
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead class="text-muted small text-uppercase fw-bold border-bottom">
                            <tr>
                                <th class="ps-0" style="width: 50%;">Product</th>
                                <th>Price</th>
                                <th style="width: 140px;">Quantity</th>
                                <th class="text-end">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $productId => $qty)
                                @php $product = $products->get($productId); @endphp
                                @if($product)
                                    <tr class="border-bottom" data-cart-row="{{ $product->id }}">
                                        <td class="ps-0 py-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded shadow-sm" width="70" height="70" style="object-fit: cover;">
                                                <div>
                                                    <h6 class="fw-bold mb-1"><a href="{{ route('customer.products.show', $product) }}" class="text-dark text-decoration-none hover-primary">{{ $product->name }}</a></h6>
                                                    <div class="small text-muted mb-1"><i class="bi bi-shop"></i> {{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}</div>
                                                    @if($qty > $product->stock_quantity)
                                                        <div class="small text-danger"><i class="bi bi-exclamation-triangle"></i> Only {{ $product->stock_quantity }} available</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-medium">${{ number_format($product->price, 2) }}</td>
                                        <td>
                                            <div class="input-group input-group-sm qty-stepper" style="width: 110px;">
                                                <button class="btn btn-outline-secondary qty-minus" type="button"><i class="bi bi-dash"></i></button>
                                                <input type="number" class="form-control text-center bg-white qty-input" value="{{ $qty }}" min="1" max="{{ $product->stock_quantity }}" data-cart-qty-input="{{ $product->id }}">
                                                <button class="btn btn-outline-secondary qty-plus" type="button"><i class="bi bi-plus"></i></button>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold" data-cart-subtotal="{{ $product->price * $qty }}">
                                            ${{ number_format($product->price * $qty, 2) }}
                                        </td>
                                        <td class="text-end pe-0">
                                            <button class="btn btn-link text-danger p-0" data-cart-remove="{{ $product->id }}" title="Remove item">
                                                <i class="bi bi-x-circle fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-ml border-0 p-4 sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">Order Summary</h5>
                
                <div class="d-flex justify-content-between mb-3 text-muted">
                    <span>Subtotal</span>
                    <span id="cart-total" class="text-dark fw-medium">${{ number_format($total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 text-muted pb-3 border-bottom">
                    <span>Tax & Fees</span>
                    <span>Calculated at checkout</span>
                </div>
                
                <div class="d-flex justify-content-between mb-4 fs-5">
                    <span class="fw-bold text-dark">Estimated Total</span>
                    <span class="fw-bold text-primary" id="cart-grand-total">${{ number_format($total, 2) }}</span>
                </div>
                
                <div class="alert alert-info border-0 bg-info-subtle small mb-4">
                    <i class="bi bi-info-circle-fill me-1"></i> Orders are processed per farmer. You will pick up directly from them at the market.
                </div>

                <a href="{{ route('customer.orders.checkout') }}" class="btn btn-primary-ml w-100 justify-content-center py-3 fs-6">
                    Proceed to Checkout <i class="bi bi-arrow-right"></i>
                </a>
                
                <div class="text-center mt-3">
                    <a href="{{ route('customer.products.index') }}" class="text-muted small text-decoration-none"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection
