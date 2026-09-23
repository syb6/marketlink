@extends('layouts.app')
@section('title', $product->name . ' - MarketLink')

@section('content')

<div class="app-container" style="padding-top: 40px;">
    <!-- Breadcrumb -->
    <div style="font-size: 11px; font-weight: 700; color: var(--muted); margin-bottom: 20px;">
        <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--muted);">Home</a> / 
        <a href="{{ route('products.index') }}" class="text-decoration-none" style="color: var(--muted);">Shop</a> / 
        <span style="color: var(--ink);">{{ $product->name }}</span>
    </div>

    <div class="product-detail-layout">
        <div class="product-detail-image">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @if($product->is_organic)
                <span class="product-badge green" style="position: absolute; top: 15px; left: 15px;">Organic</span>
            @endif
        </div>

        <div class="product-detail-copy">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="font-size: 11px; font-weight: 800; color: var(--clay); text-transform: uppercase;">{{ $product->category->name }}</span>
                    <h1>{{ $product->name }}</h1>
                    
                    @if($product->average_rating > 0)
                        <div style="display: flex; align-items: center; gap: 5px; margin-top: 5px;">
                            <span style="color: var(--gold);"><i class="bi bi-star-fill"></i></span>
                            <strong style="font-size: 12px;">{{ number_format($product->average_rating, 1) }}</strong>
                            <small style="color: var(--muted); font-size: 11px;">({{ $reviews->total() }} reviews)</small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="detail-price" style="font-size: 32px; font-weight: 800; color: var(--forest); margin: 20px 0;">
                ${{ number_format($product->price, 2) }} <span style="font-size: 14px; font-weight: 500; color: var(--muted);">/ {{ $product->unit }}</span>
            </div>

            <p style="color: var(--ink-soft); line-height: 1.6; margin-bottom: 25px;">
                {{ $product->description ?? 'No description provided.' }}
            </p>

            <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                <div>
                    <span style="display: block; font-size: 10px; color: var(--muted); margin-bottom: 5px;">Availability</span>
                    @if($product->is_available && $product->stock_quantity > 0)
                        <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: var(--leaf); padding: 4px 8px; background: var(--sage); border-radius: 4px;">
                            <i class="bi bi-check-circle-fill"></i> In Stock ({{ $product->stock_quantity }})
                        </span>
                    @else
                        <span style="display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: var(--clay); padding: 4px 8px; background: var(--clay-light); border-radius: 4px;">
                            <i class="bi bi-x-circle-fill"></i> Sold Out
                        </span>
                    @endif
                </div>
                <div>
                    <span style="display: block; font-size: 10px; color: var(--muted); margin-bottom: 5px;">Grown By</span>
                    <a href="{{ route('products.index', ['farmer' => $product->farmer_id]) }}" style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: var(--ink);">
                        <img src="{{ $product->farmer->profile_photo_url }}" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover;">
                        <span style="font-size: 11px; font-weight: 700;">{{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}</span>
                    </a>
                </div>
            </div>

            @auth
                @if(auth()->user()->isCustomer() && $product->is_available && $product->stock_quantity > 0)
                    <div class="product-detail-controls">
                        <div class="quantity-control" style="display: flex; align-items: center; border: 1px solid var(--line); border-radius: 8px; overflow: hidden; width: max-content;">
                            <button type="button" class="qty-minus" style="padding: 10px 15px; border: none; background: none; font-size: 16px; cursor: pointer; color: var(--muted);"><i class="bi bi-dash"></i></button>
                            <input type="number" class="qty-input" value="1" min="1" max="{{ $product->stock_quantity }}" data-qty-for="{{ $product->id }}" style="width: 40px; text-align: center; border: none; font-size: 14px; font-weight: 700; outline: none; background: transparent;">
                            <button type="button" class="qty-plus" style="padding: 10px 15px; border: none; background: none; font-size: 16px; cursor: pointer; color: var(--muted);"><i class="bi bi-plus"></i></button>
                        </div>
                        <button type="button" class="primary-button" data-add-to-cart="{{ $product->id }}" style="flex: 1; justify-content: center; font-size: 14px;">
                            Add to Pre-Order
                        </button>
                        @php
                            $isFavorite = auth()->user()->favorites()->where('product_id', $product->id)->exists();
                        @endphp
                        <button type="button" class="favorite-btn {{ $isFavorite ? 'active' : '' }}" data-product-id="{{ $product->id }}" style="width: 46px; height: 46px; border-radius: 8px; border: 1px solid var(--line); background: #fff; display: grid; place-items: center; cursor: pointer; color: {{ $isFavorite ? 'var(--clay)' : 'var(--muted)' }}; font-size: 18px;">
                            <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                        </button>
                    </div>
                @endif
            @else
                <div style="padding: 20px; background: var(--paper); border: 1px solid var(--line); border-radius: 8px; text-align: center;">
                    <p style="font-size: 11px; margin-bottom: 15px;"><i class="bi bi-lock"></i> Sign in to add items to your cart</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <a href="{{ route('login') }}" class="primary-button text-decoration-none">Login</a>
                        <a href="{{ route('register') }}" class="alternate-button text-decoration-none">Create Account</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- Reviews Section -->
    @if($reviews->count() > 0)
    <div class="section-space soft-section" style="margin-top: 60px; border-radius: 16px;">
        <div class="app-container">
            <h2 style="font-size: 24px; margin-bottom: 25px;"><i class="bi bi-star-fill text-warning me-2"></i>Customer Reviews</h2>

            <div style="display: grid; gap: 20px;">
                @foreach($reviews as $review)
                    <div style="background: #fff; padding: 25px; border-radius: 12px; border: 1px solid var(--line);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <img src="{{ $review->customer->profile_photo_url }}" style="width: 40px; height: 40px; border-radius: 50%;">
                                <div>
                                    <strong style="display: block; font-size: 12px;">{{ $review->customer->name }}</strong>
                                    <span style="font-size: 10px; color: var(--muted);">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div style="color: var(--gold); font-size: 14px;">
                                @for($i=1; $i<=5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                        </div>

                        @if($review->comment)
                            <p style="font-size: 13px; color: var(--ink-soft); line-height: 1.5; margin: 0;">{{ $review->comment }}</p>
                        @endif

                        @if($review->farmer_reply)
                            <div style="margin-top: 15px; padding: 15px; background: var(--sage); border-radius: 8px; border-left: 3px solid var(--forest);">
                                <strong style="display: block; font-size: 10px; color: var(--forest); margin-bottom: 5px;"><i class="bi bi-reply-fill"></i> Farmer Response</strong>
                                <p style="font-size: 12px; color: var(--forest-dark); margin: 0;">{{ $review->farmer_reply }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4">
                {{ $reviews->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    @endif

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="section-space">
        <h2 style="font-size: 24px; margin-bottom: 25px;">Related Products</h2>
        <div class="catalog-product-grid">
            @foreach($relatedProducts as $rp)
                <div class="catalog-product-card">
                    <div class="catalog-product-image">
                        <img src="{{ $rp->image_url }}" alt="{{ $rp->name }}">
                        @if($rp->is_organic)
                            <span class="product-badge green">Organic</span>
                        @endif
                    </div>
                    <div class="catalog-product-info">
                        <span>{{ $rp->category->name }}</span>
                        <h3><a href="{{ route('products.show', $rp) }}">{{ $rp->name }}</a></h3>
                        <p>{{ Str::limit($rp->description, 50) }}</p>
                    </div>
                    <div class="catalog-product-bottom">
                        <div class="price">
                            <strong>${{ number_format($rp->price, 2) }}</strong>
                            <span>/{{ $rp->unit }}</span>
                        </div>
                        <a href="{{ route('products.show', $rp) }}" class="add-button" style="text-decoration:none;"><i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const minusBtns = document.querySelectorAll('.qty-minus');
        const plusBtns = document.querySelectorAll('.qty-plus');
        
        minusBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.nextElementSibling;
                if (input.value > 1) {
                    input.value = parseInt(input.value) - 1;
                }
            });
        });
        
        plusBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const max = parseInt(input.getAttribute('max')) || 99;
                if (input.value < max) {
                    input.value = parseInt(input.value) + 1;
                }
            });
        });
    });
</script>
@endpush
@endsection
