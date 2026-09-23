@extends('layouts.customer')
@section('page_title', 'Browse Products')

@section('content')

<div class="row g-4">
    <!-- Filter Sidebar -->
    <div class="col-lg-3">
        <div class="filter-card border-0">
            <h6 class="fw-bold mb-4">Filters</h6>
            
            <form action="{{ route('customer.products.index') }}" method="GET" class="form-ml">
                @if(request('farmer'))
                    <input type="hidden" name="farmer" value="{{ request('farmer') }}">
                    <div class="alert alert-info py-2 px-3 small d-flex justify-content-between align-items-center mb-4">
                        <span>Viewing specific farmer's store</span>
                        <a href="{{ route('customer.products.index') }}" class="text-danger"><i class="bi bi-x-circle-fill"></i></a>
                    </div>
                @endif
                
                <div class="mb-4">
                    <label class="form-label">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Product name..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Category</label>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="category" id="cat_all" value="" {{ empty(request('category')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="cat_all">All Categories</label>
                        </div>
                        @foreach(\App\Models\ProductCategory::where('is_active', true)->get() as $cat)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" id="cat_{{ $cat->id }}" value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat_{{ $cat->id }}">
                                    @if($cat->icon)<i class="bi bi-{{ $cat->icon }} me-1 text-muted"></i>@endif
                                    {{ $cat->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Price Range</label>
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

                <button type="submit" class="btn btn-primary-ml w-100 justify-content-center">Apply Filters</button>
                <a href="{{ route('customer.products.index') }}" class="btn btn-link w-100 text-muted mt-2">Clear All</a>
            </form>
        </div>
    </div>
    
    <!-- Products Grid -->
    <div class="col-lg-9">
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-md-6 col-xl-4">
                    <div class="product-card h-100">
                        <div class="product-card-img-wrapper position-relative">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-card-img" style="height: 220px;">
                            
                            @if(!$product->is_available || $product->stock_quantity <= 0)
                                <span class="product-badge product-badge-sold">Sold Out</span>
                            @elseif($product->is_recurring)
                                <span class="product-badge">Weekly</span>
                            @else
                                <span class="product-badge product-badge-new">Seasonal</span>
                            @endif

                            <button class="fav-btn {{ auth()->user()->favorites()->where('favoritable_type', 'App\Models\Product')->where('favoritable_id', $product->id)->exists() ? 'active' : '' }}" 
                                    data-fav-type="product" 
                                    data-fav-id="{{ $product->id }}">
                                <i class="bi bi-heart{{ auth()->user()->favorites()->where('favoritable_type', 'App\Models\Product')->where('favoritable_id', $product->id)->exists() ? '-fill' : '' }}"></i>
                            </button>
                        </div>
                        
                        <div class="p-4 d-flex flex-column" style="height: calc(100% - 220px);">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="small text-primary fw-bold">{{ $product->category->name }}</div>
                            </div>
                            
                            <h5 class="fw-bold mb-1"><a href="{{ route('customer.products.show', $product) }}" class="text-dark">{{ $product->name }}</a></h5>
                            <a href="{{ route('customer.products.index', ['farmer' => $product->farmer_id]) }}" class="small text-muted mb-3 d-inline-block text-decoration-none hover-primary">
                                <i class="bi bi-person-circle"></i> {{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}
                            </a>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fs-4 fw-bold text-dark">${{ number_format($product->price, 2) }}<span class="fs-6 text-muted fw-normal">/{{ $product->unit }}</span></span>
                                    <span class="small text-muted">{{ $product->stock_quantity }} in stock</span>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="{{ route('customer.products.show', $product) }}" class="btn btn-outline-ml flex-grow-1 justify-content-center">Details</a>
                                    @if($product->is_available && $product->stock_quantity > 0)
                                        <button class="btn btn-primary-ml px-3" data-add-to-cart="{{ $product->id }}" title="Add to Cart">
                                            <i class="bi bi-cart-plus"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-search empty-state-icon"></i>
                        <h5 class="fw-bold">No products found</h5>
                        <p>Try adjusting your search criteria or changing categories.</p>
                        <a href="{{ route('customer.products.index') }}" class="btn btn-primary-ml mt-2">Clear Filters</a>
                    </div>
                </div>
            @endforelse
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
