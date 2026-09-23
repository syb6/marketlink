@extends('layouts.app')
@section('title', 'Browse Products - MarketLink')

@section('content')

<div class="app-container">
    <div class="page-title">
        <div>
            <div class="eyebrow"><span class="eyebrow-dot"></span> Fresh Catalog</div>
            <h1>Browse <em>fresh</em> produce</h1>
            <p>Discover farm-fresh produce from verified local farmers. Everything here is locally grown and seasonally sourced.</p>
        </div>
        <div class="catalog-context">
            <span class="context-label">Location Context</span>
            <button type="button">All Markets <i class="bi bi-chevron-down"></i></button>
            <small>Showing {{ $products->total() }} available items</small>
        </div>
    </div>

    <div class="catalog-layout">
        <aside class="filter-sidebar">
            <div class="filter-heading">
                <span><i class="bi bi-funnel"></i> Filters</span>
                <a href="{{ route('products.index') }}" class="text-decoration-none" style="font-size: 9px; font-weight: 800; color: var(--clay);">CLEAR ALL</a>
            </div>

            <form action="{{ route('products.index') }}" method="GET">
                <div class="filter-block">
                    <strong>Search</strong>
                    <div style="display:flex; align-items:center; border:1px solid var(--line); border-radius:6px; padding:6px; background:#fff;">
                        <i class="bi bi-search text-muted mx-2"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." style="border:none; outline:none; background:transparent; font-size:10px; width:100%;">
                    </div>
                </div>

                <div class="filter-block">
                    <strong>Category</strong>
                    <label class="filter-check">
                        <input type="radio" name="category" value="" {{ empty(request('category')) ? 'checked' : '' }}>
                        All Categories
                    </label>
                    @foreach($categories as $cat)
                        <label class="filter-check">
                            <input type="radio" name="category" value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'checked' : '' }}>
                            {{ $cat->name }}
                        </label>
                    @endforeach
                </div>

                <div class="filter-block">
                    <strong>Sort By</strong>
                    <select name="sort" class="sort-control" style="width: 100%; border-color: var(--line);" onchange="this.form.submit()">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low → High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High → Low</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>

                <button type="submit" class="primary-button wide mt-3">Apply Filters</button>
            </form>

            <div class="filter-trust">
                <i class="bi bi-shield-check" style="font-size: 14px;"></i>
                <strong>100% Quality Guarantee</strong>
                <span>All farmers are verified local producers committed to sustainable practices.</span>
            </div>
        </aside>

        <div>
            @if(request('farmer'))
                @php $farmerName = $farmers->firstWhere('user_id', request('farmer')); @endphp
                <div class="catalog-toolbar">
                    <strong>Showing products from {{ $farmerName ? ($farmerName->stall_name ?? $farmerName->user->name) : 'selected farmer' }}</strong>
                    <a href="{{ route('products.index') }}" class="text-decoration-none text-danger"><i class="bi bi-x-circle"></i> Clear Farmer Filter</a>
                </div>
            @endif

            <div class="catalog-toolbar">
                <span>Showing {{ $products->count() }} of {{ $products->total() }} results</span>
                <div class="toolbar-actions">
                    <div class="view-toggle">
                        <button class="active"><i class="bi bi-grid"></i></button>
                        <button><i class="bi bi-list"></i></button>
                    </div>
                </div>
            </div>

            <div class="market-card-grid catalog-products">
                @forelse($products as $product)
                    <div class="catalog-product-card">
                        <div class="catalog-product-image">
                            <a href="{{ route('products.show', $product) }}">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            </a>
                            <button class="favorite-button"><i class="bi bi-heart"></i></button>
                            
                            @if(!$product->is_available || $product->stock_quantity <= 0)
                                <div class="stock-badge sold-out">Sold Out</div>
                            @elseif($product->is_recurring)
                                <div class="stock-badge">Weekly</div>
                            @else
                                <div class="stock-badge" style="background:#fff2ea;color:var(--clay);">Seasonal</div>
                            @endif
                        </div>
                        <div class="catalog-product-body">
                            <div class="catalog-product-meta">
                                <span><i class="bi bi-tag-fill"></i> {{ $product->category->name }}</span>
                                <span><i class="bi bi-star-fill text-warning"></i> 4.9</span>
                            </div>
                            <h3>
                                <a href="{{ route('products.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
                            </h3>
                            <a href="{{ route('products.index', ['farmer' => $product->farmer_id]) }}" class="farmer-link text-decoration-none">
                                <i class="bi bi-person-circle"></i> {{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}
                            </a>
                            
                            <div class="catalog-product-footer">
                                <div>
                                    <strong>${{ number_format($product->price, 2) }}</strong>
                                    <small>per {{ $product->unit }}</small>
                                </div>
                                <div class="product-add-row">
                                    <span class="text-muted" style="font-size: 9px; margin-right: 5px;">{{ $product->stock_quantity }} left</span>
                                    <button class="add-preorder" type="button" onclick="window.location='{{ route('products.show', $product) }}'">View</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: var(--paper); border-radius: 12px; border: 1px solid var(--line);">
                        <i class="bi bi-search text-muted mb-2 d-block" style="font-size: 24px;"></i>
                        <strong>No products found</strong>
                        <p class="text-muted" style="font-size: 11px;">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('products.index') }}" class="light-button text-decoration-none mt-2">Reset Filters</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
            
            @guest
            <div class="catalog-ai">
                <div class="ai-spark"><i class="bi bi-stars"></i></div>
                <div>
                    <strong>Want to pre-order?</strong>
                    <p>Create an account to reserve products and pick them up at the market.</p>
                </div>
                <a href="{{ route('register') }}" class="text-decoration-none" style="margin-left: auto; color: var(--clay); font-weight: 800; font-size: 9px;">SIGN UP <i class="bi bi-arrow-right"></i></a>
            </div>
            @endguest
        </div>
    </div>
</div>

@endsection
