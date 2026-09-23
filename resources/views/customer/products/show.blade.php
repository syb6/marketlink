@extends('layouts.customer')
@section('page_title', 'Product Details')

@section('content')

<a href="{{ route('customer.products.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Products</a>

<div class="row g-5">
    <div class="col-lg-6">
        <div class="card-ml border-0 overflow-hidden position-relative">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 img-cover" style="height: 500px; border-radius: var(--radius-md);">
            
            <button class="fav-btn {{ auth()->user()->favorites()->where('favoritable_type', 'App\Models\Product')->where('favoritable_id', $product->id)->exists() ? 'active' : '' }}" 
                    style="top: 1.5rem; right: 1.5rem; width: 44px; height: 44px; font-size: 1.2rem;"
                    data-fav-type="product" 
                    data-fav-id="{{ $product->id }}">
                <i class="bi bi-heart{{ auth()->user()->favorites()->where('favoritable_type', 'App\Models\Product')->where('favoritable_id', $product->id)->exists() ? '-fill' : '' }}"></i>
            </button>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">{{ $product->category->name }}</span>
            @if($product->is_recurring)
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">Weekly Supply</span>
            @else
                <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-3 py-2 rounded-pill">Seasonal Only</span>
            @endif
        </div>
        
        <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
        
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="fs-2 fw-bold text-dark">${{ number_format($product->price, 2) }}<span class="fs-5 text-muted fw-normal">/{{ $product->unit }}</span></div>
            <div class="vr"></div>
            @if($product->is_available && $product->stock_quantity > 0)
                <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> In Stock ({{ $product->stock_quantity }} available)</span>
            @else
                <span class="text-danger fw-bold"><i class="bi bi-x-circle-fill"></i> Sold Out</span>
            @endif
        </div>
        
        <div class="card bg-light border-0 rounded-ml mb-4">
            <div class="card-body p-4 d-flex align-items-center gap-3">
                <img src="{{ $product->farmer->profile_photo_url }}" class="rounded-circle" width="64" height="64" style="object-fit: cover;">
                <div>
                    <div class="small text-muted text-uppercase fw-bold mb-1">Grown By</div>
                    <h5 class="fw-bold mb-0">{{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}</h5>
                    <a href="{{ route('customer.products.index', ['farmer' => $product->farmer_id]) }}" class="small text-primary">View all products from this farmer</a>
                </div>
            </div>
        </div>
        
        <h6 class="fw-bold text-uppercase text-muted small mb-2">Description</h6>
        <p class="text-body mb-5">{{ $product->description ?? 'No description provided for this product.' }}</p>
        
        @if($product->is_available && $product->stock_quantity > 0)
            <div class="d-flex align-items-end gap-3 p-4 border rounded-ml bg-white shadow-sm">
                <div>
                    <label class="form-label small fw-bold text-muted text-uppercase mb-2">Quantity</label>
                    <div class="input-group qty-stepper" style="width: 140px;">
                        <button class="btn btn-outline-secondary qty-minus" type="button"><i class="bi bi-dash"></i></button>
                        <input type="number" class="form-control text-center qty-input" value="1" min="1" max="{{ $product->stock_quantity }}" data-qty-for="{{ $product->id }}">
                        <button class="btn btn-outline-secondary qty-plus" type="button"><i class="bi bi-plus"></i></button>
                    </div>
                </div>
                <button class="btn btn-primary-ml flex-grow-1 justify-content-center py-3 fs-6" data-add-to-cart="{{ $product->id }}">
                    <i class="bi bi-cart-plus fs-5"></i> Add to Cart
                </button>
            </div>
        @else
            <div class="alert alert-secondary border-0 p-4 rounded-ml d-flex align-items-center gap-3">
                <i class="bi bi-emoji-frown fs-2 text-muted"></i>
                <div>
                    <h6 class="fw-bold mb-1">Currently Unavailable</h6>
                    <p class="small mb-0 text-muted">This product is either sold out or out of season. Check back later!</p>
                </div>
            </div>
        @endif
    </div>
</div>

@if($reviews->count() > 0)
<hr class="my-5">
<div class="row">
    <div class="col-lg-8">
        <h4 class="fw-bold mb-4">Customer Reviews</h4>
        
        <div class="d-flex align-items-center gap-4 mb-5 bg-white p-4 rounded-ml shadow-sm border">
            <div class="text-center border-end pe-4">
                <div class="display-4 fw-bold text-dark">{{ number_format($product->average_rating, 1) }}</div>
                <div class="stars fs-4">
                    @for($i=1; $i<=5; $i++)
                        <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }}"></i>
                    @endfor
                </div>
                <div class="small text-muted mt-1">{{ $reviews->total() }} reviews</div>
            </div>
            <div class="flex-grow-1">
                <p class="text-muted mb-0">Ratings are only accepted from verified customers who have purchased this product.</p>
            </div>
        </div>

        <div class="d-flex flex-column gap-4">
            @foreach($reviews as $review)
                <div class="card-ml border-0 p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $review->customer->profile_photo_url }}" class="rounded-circle" width="48" height="48">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $review->customer->name }}</h6>
                                <div class="small text-muted">{{ $review->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="stars">
                            @for($i=1; $i<=5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                            @endfor
                        </div>
                    </div>
                    
                    <p class="mb-0">{{ $review->comment }}</p>
                    
                    @if($review->reply)
                        <div class="mt-4 ms-4 ms-md-5 p-3 bg-light rounded-ml border-start border-4 border-primary">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-person-badge text-primary"></i>
                                <span class="fw-bold small">Farmer Response</span>
                            </div>
                            <p class="small mb-0 text-muted">{{ $review->reply }}</p>
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

@endsection
