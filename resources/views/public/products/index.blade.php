@extends('layouts.app')
@section('title', 'Browse Products - MarketLink')

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="mb-2"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Browse Fresh Products</h1>
                <p class="mb-0 opacity-75">Discover farm-fresh produce from verified local farmers. Everything here is locally grown and seasonally sourced.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 fs-6">
                    <i class="bi bi-box-seam me-1"></i> {{ $products->total() }} Products
                </span>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">
    <div class="row g-4">
        <!-- Filter Sidebar -->
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <!-- Mobile Filter Toggle -->
                <button class="btn btn-outline-ml w-100 d-lg-none mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="bi bi-funnel me-2"></i>Filters & Sort
                </button>

                <div class="collapse d-lg-block" id="filterCollapse">
                    <div class="card-ml border-0 p-4">
                        <h6 class="fw-bold mb-4 d-flex align-items-center gap-2">
                            <i class="bi bi-funnel text-primary"></i> Filters
                        </h6>

                        <form action="{{ route('products.index') }}" method="GET" class="form-ml">
                            <!-- Search -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search products..." value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Categories -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Category</label>
                                <div class="d-flex flex-column gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="cat_all" value="" {{ empty(request('category')) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cat_all">All Categories</label>
                                    </div>
                                    @foreach($categories as $cat)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="category" id="cat_{{ $cat->id }}" value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat_{{ $cat->id }}">
                                                {{ $cat->icon }} {{ $cat->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Price Range</label>
                                <div class="d-flex gap-2">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                                    </div>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">$</span>
                                        <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Sort -->
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Sort By</label>
                                <select name="sort" class="form-select form-select-sm">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary-ml w-100 justify-content-center mb-2">
                                <i class="bi bi-filter me-1"></i> Apply Filters
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-link w-100 text-muted small">Clear All</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            @if(request('farmer'))
                @php $farmerName = $farmers->firstWhere('user_id', request('farmer')); @endphp
                <div class="alert alert-info border-0 rounded-ml d-flex justify-content-between align-items-center mb-4 shadow-sm">
                    <span><i class="bi bi-shop me-2"></i>Showing products from <strong>{{ $farmerName ? ($farmerName->stall_name ?? $farmerName->user->name) : 'selected farmer' }}</strong></span>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-danger rounded-pill"><i class="bi bi-x-lg"></i></a>
                </div>
            @endif

            <div class="row g-3 g-md-4">
                @forelse($products as $product)
                    <div class="col-6 col-md-4">
                        <div class="product-card h-100">
                            <div class="product-card-img-wrapper position-relative">
                                <a href="{{ route('products.show', $product) }}">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-card-img" style="height: 200px;">
                                </a>

                                @if(!$product->is_available || $product->stock_quantity <= 0)
                                    <span class="product-badge product-badge-sold">Sold Out</span>
                                @elseif($product->is_recurring)
                                    <span class="product-badge">Weekly</span>
                                @else
                                    <span class="product-badge product-badge-new">Seasonal</span>
                                @endif
                            </div>

                            <div class="p-3 d-flex flex-column" style="min-height: 160px;">
                                <div class="small text-primary fw-bold mb-1">{{ $product->category->icon ?? '' }} {{ $product->category->name }}</div>
                                <h6 class="fw-bold mb-1 text-truncate">
                                    <a href="{{ route('products.show', $product) }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
                                </h6>
                                <a href="{{ route('products.index', ['farmer' => $product->farmer_id]) }}" class="small text-muted mb-2 d-inline-block text-decoration-none">
                                    <i class="bi bi-person-circle"></i> {{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}
                                </a>

                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fs-5 fw-bold text-dark">${{ number_format($product->price, 2) }}<span class="fs-6 text-muted fw-normal">/{{ $product->unit }}</span></span>
                                        <span class="small text-muted d-none d-md-inline">{{ $product->stock_quantity }} left</span>
                                    </div>

                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-ml w-100 justify-content-center btn-sm">
                                        <i class="bi bi-eye me-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-search empty-state-icon"></i>
                            <h5 class="fw-bold">No products found</h5>
                            <p class="text-muted">Try adjusting your filters or search terms.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary-ml mt-2"><i class="bi bi-arrow-clockwise me-1"></i> Reset Filters</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- CTA Banner -->
<section class="py-5 bg-primary-ml text-white text-center mt-4">
    <div class="container py-3">
        <h3 class="fw-bold mb-3">Want to add to cart or place orders?</h3>
        <p class="opacity-75 mb-4">Create a free account to pre-order products and pick them up at the market.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 rounded-pill fw-bold text-primary">Sign Up Free</a>
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">Login</a>
        </div>
    </div>
</section>

@endsection
