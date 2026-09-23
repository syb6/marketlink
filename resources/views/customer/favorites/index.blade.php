@extends('layouts.customer')
@section('page_title', 'Saved Favorites')

@section('content')

<!-- Tabs -->
<ul class="nav nav-tabs mb-4 border-bottom-0 gap-2" id="favoritesTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab') != 'farmers' ? 'active fw-bold text-dark bg-white border-bottom-0 shadow-sm rounded-top-ml' : 'text-muted bg-light border-0' }}" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Favorite Products</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ request('tab') == 'farmers' ? 'active fw-bold text-dark bg-white border-bottom-0 shadow-sm rounded-top-ml' : 'text-muted bg-light border-0' }}" id="farmers-tab" data-bs-toggle="tab" data-bs-target="#farmers" type="button" role="tab">Favorite Farmers</button>
    </li>
</ul>

<div class="tab-content" id="favoritesTabContent">
    <!-- Products Tab -->
    <div class="tab-pane fade {{ request('tab') != 'farmers' ? 'show active' : '' }}" id="products" role="tabpanel">
        <div class="row g-4">
            @forelse($favoriteProducts as $product)
                <div class="col-md-4 col-xl-3">
                    <div class="product-card h-100">
                        <div class="product-card-img-wrapper position-relative">
                            <img src="{{ $product->image_url }}" class="product-card-img" style="height: 180px;">
                            
                            @if(!$product->is_available || $product->stock_quantity <= 0)
                                <span class="product-badge product-badge-sold">Sold Out</span>
                            @endif

                            <button class="fav-btn active" data-fav-type="product" data-fav-id="{{ $product->id }}">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>
                        
                        <div class="p-3 d-flex flex-column h-100">
                            <div class="small text-primary fw-bold">{{ $product->category->name }}</div>
                            <h6 class="fw-bold mb-1 text-truncate"><a href="{{ route('customer.products.show', $product) }}" class="text-dark">{{ $product->name }}</a></h6>
                            <a href="{{ route('customer.products.index', ['farmer' => $product->farmer_id]) }}" class="small text-muted mb-3 d-inline-block text-decoration-none">
                                <i class="bi bi-shop"></i> {{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}
                            </a>
                            
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-dark">${{ number_format($product->price, 2) }}</span>
                                @if($product->is_available && $product->stock_quantity > 0)
                                    <button class="btn btn-sm btn-primary-ml" data-add-to-cart="{{ $product->id }}"><i class="bi bi-cart-plus"></i> Add</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state bg-white rounded-ml">
                        <i class="bi bi-heart empty-state-icon text-muted"></i>
                        <h5 class="fw-bold">No favorite products yet</h5>
                        <p>Save products you love so you can find them easily later.</p>
                        <a href="{{ route('customer.products.index') }}" class="btn btn-outline-ml mt-2">Browse Products</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Farmers Tab -->
    <div class="tab-pane fade {{ request('tab') == 'farmers' ? 'show active' : '' }}" id="farmers" role="tabpanel">
        <div class="row g-4">
            @forelse($favoriteFarmers as $farmerProfile)
                <div class="col-md-6 col-xl-4">
                    <div class="card-ml border-0 h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $farmerProfile->stall_image ? asset('storage/'.$farmerProfile->stall_image) : $farmerProfile->user->profile_photo_url }}" class="rounded-circle border border-2 border-primary" width="56" height="56" style="object-fit:cover;">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $farmerProfile->stall_name }}</h6>
                                    <div class="small text-muted"><i class="bi bi-person"></i> {{ $farmerProfile->contact_person ?? $farmerProfile->user->name }}</div>
                                </div>
                            </div>
                            <button class="fav-btn active position-static shadow-none" data-fav-type="farmer" data-fav-id="{{ $farmerProfile->id }}">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>
                        
                        <div class="mb-3 d-flex align-items-center gap-1 small">
                            <div class="stars me-1">
                                @php $rating = $farmerProfile->average_rating; @endphp
                                @for($i=1; $i<=5; $i++)
                                    <i class="bi bi-star{{ $i <= round($rating) ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <span class="text-dark fw-bold">{{ number_format($rating, 1) }}</span>
                        </div>
                        
                        <p class="small text-muted mb-4 text-truncate">{{ $farmerProfile->bio }}</p>
                        
                        <a href="{{ route('customer.products.index', ['farmer' => $farmerProfile->user_id]) }}" class="btn btn-outline-ml w-100 justify-content-center">View Store</a>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state bg-white rounded-ml">
                        <i class="bi bi-heart empty-state-icon text-muted"></i>
                        <h5 class="fw-bold">No favorite farmers yet</h5>
                        <p>Save farmers you love to quickly see their fresh inventory.</p>
                        <a href="{{ route('customer.markets.index') }}" class="btn btn-outline-ml mt-2">Browse Markets</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
