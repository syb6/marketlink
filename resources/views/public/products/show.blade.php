@extends('layouts.app')
@section('title', $product->name . ' - MarketLink')

@section('content')

<!-- Breadcrumb -->
<div class="bg-white border-bottom">
    <div class="container py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Products</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4 py-md-5">
    <div class="row g-4 g-lg-5">
        <!-- Product Image -->
        <div class="col-lg-6">
            <div class="card-ml border-0 overflow-hidden position-relative">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 img-cover" style="height: 350px; border-radius: var(--radius-md);">
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">{{ $product->category->icon ?? '' }} {{ $product->category->name }}</span>
                @if($product->is_recurring)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">Weekly Supply</span>
                @else
                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-3 py-2 rounded-pill">Seasonal</span>
                @endif
            </div>

            <h1 class="fw-bold mb-3 fs-2 fs-md-1">{{ $product->name }}</h1>

            <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                <div class="fs-2 fw-bold text-dark">${{ number_format($product->price, 2) }}<span class="fs-5 text-muted fw-normal">/{{ $product->unit }}</span></div>
                <div class="vr d-none d-sm-block"></div>
                @if($product->is_available && $product->stock_quantity > 0)
                    <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> In Stock ({{ $product->stock_quantity }})</span>
                @else
                    <span class="text-danger fw-bold"><i class="bi bi-x-circle-fill"></i> Sold Out</span>
                @endif
            </div>

            <!-- Farmer Card -->
            <div class="card bg-light border-0 rounded-ml mb-4">
                <div class="card-body p-3 p-md-4 d-flex align-items-center gap-3">
                    <img src="{{ $product->farmer->profile_photo_url }}" class="rounded-circle" width="56" height="56" style="object-fit: cover;">
                    <div>
                        <div class="small text-muted text-uppercase fw-bold mb-1">Grown By</div>
                        <h6 class="fw-bold mb-0">{{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}</h6>
                        <a href="{{ route('products.index', ['farmer' => $product->farmer_id]) }}" class="small text-primary text-decoration-none">View all products →</a>
                    </div>
                </div>
            </div>

            <h6 class="fw-bold text-uppercase text-muted small mb-2">Description</h6>
            <p class="text-body mb-4">{{ $product->description ?? 'No description provided.' }}</p>

            <!-- CTA -->
            @auth
                @if(auth()->user()->isCustomer() && $product->is_available && $product->stock_quantity > 0)
                    <div class="d-flex align-items-end gap-3 p-3 p-md-4 border rounded-ml bg-white shadow-sm">
                        <div>
                            <label class="form-label small fw-bold text-muted text-uppercase mb-2">Quantity</label>
                            <div class="input-group qty-stepper" style="width: 130px;">
                                <button class="btn btn-outline-secondary qty-minus" type="button"><i class="bi bi-dash"></i></button>
                                <input type="number" class="form-control text-center qty-input" value="1" min="1" max="{{ $product->stock_quantity }}" data-qty-for="{{ $product->id }}">
                                <button class="btn btn-outline-secondary qty-plus" type="button"><i class="bi bi-plus"></i></button>
                            </div>
                        </div>
                        <button class="btn btn-primary-ml flex-grow-1 justify-content-center py-3" data-add-to-cart="{{ $product->id }}">
                            <i class="bi bi-cart-plus fs-5"></i> Add to Cart
                        </button>
                    </div>
                @endif
            @else
                <div class="p-4 border rounded-ml bg-white shadow-sm text-center">
                    <p class="mb-3 fw-medium"><i class="bi bi-lock me-1"></i> Sign in to add items to your cart and place orders</p>
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <a href="{{ route('login') }}" class="btn btn-primary-ml px-4"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-ml px-4">Create Account</a>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- Reviews Section -->
    @if($reviews->count() > 0)
    <hr class="my-5">
    <div class="row">
        <div class="col-lg-8">
            <h4 class="fw-bold mb-4"><i class="bi bi-star-fill text-warning me-2"></i>Customer Reviews</h4>

            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 gap-sm-4 mb-4 bg-white p-4 rounded-ml shadow-sm border">
                <div class="text-center border-end-0 border-sm-end pe-0 pe-sm-4">
                    <div class="display-5 fw-bold text-dark">{{ number_format($product->average_rating, 1) }}</div>
                    <div class="stars fs-5">
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star{{ $i <= round($product->average_rating) ? '-fill' : '' }} text-warning"></i>
                        @endfor
                    </div>
                    <div class="small text-muted mt-1">{{ $reviews->total() }} {{ Str::plural('review', $reviews->total()) }}</div>
                </div>
                <div class="flex-grow-1">
                    <p class="text-muted mb-0 small">Ratings are from verified buyers who completed an order for this product.</p>
                </div>
            </div>

            <div class="d-flex flex-column gap-3">
                @foreach($reviews as $review)
                    <div class="card-ml border-0 p-3 p-md-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mb-3 gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $review->customer->profile_photo_url }}" class="rounded-circle" width="40" height="40">
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $review->customer->name }}</h6>
                                    <div class="small text-muted">{{ $review->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="stars">
                                @for($i=1; $i<=5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                @endfor
                            </div>
                        </div>

                        @if($review->comment)
                            <p class="mb-0">{{ $review->comment }}</p>
                        @endif

                        @if($review->farmer_reply)
                            <div class="mt-3 ms-3 ms-md-5 p-3 bg-light rounded-ml border-start border-4 border-primary">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-person-badge text-primary"></i>
                                    <span class="fw-bold small">Farmer Response</span>
                                </div>
                                <p class="small mb-0 text-muted">{{ $review->farmer_reply }}</p>
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
    <hr class="my-5">
    <h4 class="fw-bold mb-4"><i class="bi bi-collection me-2 text-primary"></i>Related Products</h4>
    <div class="row g-3 g-md-4">
        @foreach($relatedProducts as $rp)
            <div class="col-6 col-md-3">
                <div class="product-card h-100">
                    <div class="product-card-img-wrapper">
                        <a href="{{ route('products.show', $rp) }}">
                            <img src="{{ $rp->image_url }}" alt="{{ $rp->name }}" class="product-card-img" style="height: 180px;">
                        </a>
                    </div>
                    <div class="p-3">
                        <div class="small text-primary fw-bold mb-1">{{ $rp->category->name }}</div>
                        <h6 class="fw-bold mb-1 text-truncate">
                            <a href="{{ route('products.show', $rp) }}" class="text-dark text-decoration-none">{{ $rp->name }}</a>
                        </h6>
                        <div class="fs-6 fw-bold text-dark">${{ number_format($rp->price, 2) }}<span class="text-muted fw-normal">/{{ $rp->unit }}</span></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
