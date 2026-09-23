@extends('layouts.app')

@section('content')

<div class="home-hero">
    <div class="app-container">
        <div class="home-hero-grid">
            <div class="home-hero-copy">
                <div class="eyebrow"><span class="eyebrow-dot"></span> Fresh & Local</div>
                <h1>Discover the <em>best</em> local harvest</h1>
                <p>Connect with farmers, pre-order seasonal produce, and pick up fresh goods at a market near you.</p>

                <div class="hero-search-card">
                    <div class="day-toggle">
                        <button type="button" class="active">Today</button>
                        <button type="button">Tomorrow</button>
                        <button type="button">This Weekend</button>
                    </div>
                    <form action="{{ route('products.index') }}" method="GET" class="hero-search-input">
                        <i class="bi bi-search"></i>
                        <input type="text" name="q" placeholder="What are you looking for? (e.g., Organic Tomatoes)">
                        <button type="submit"><i class="bi bi-arrow-right"></i></button>
                    </form>
                    <div class="hero-search-meta">
                        <span><i class="bi bi-geo-alt"></i> Auto-detect location</span>
                        <span><i class="bi bi-clock"></i> Pickup available today</span>
                    </div>
                </div>

                <div class="hero-proof-row">
                    <div class="avatar-stack">
                        <i>A</i>
                        <i>J</i>
                        <i>S</i>
                        <i>M</i>
                    </div>
                    Join {{ number_format($customerCount) }}+ happy customers 
                    <span class="verified-inline"><i class="bi bi-patch-check-fill"></i> Verified Local</span>
                </div>
            </div>
            <div class="home-hero-image" style="background-image: url('{{ asset('storage/default-hero.jpg') }}'); background-color: #dcebd8;">
                <div class="hero-image-overlay"></div>
                
                <div class="floating-note note-one">
                    <div class="note-icon"><i class="bi bi-check2"></i></div>
                    <div>
                        <b>Fresh Basil</b><br>
                        Added to bag
                    </div>
                </div>

                <div class="floating-note note-two">
                    <div class="note-icon clay"><i class="bi bi-star-fill"></i></div>
                    <div>
                        <b>Top Rated</b><br>
                        Local Farm
                    </div>
                </div>

                <div class="harvest-stamp">
                    <span>Fresh</span>
                    <b>2024</b>
                    <span>Harvest</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="hero-rail">
        <div class="app-container">
            <span><b>*</b> ORGANIC</span>
            <span><b>*</b> SEASONAL</span>
            <span><b>*</b> LOCALLY Sourced</span>
            <span><b>*</b> SUSTAINABLE</span>
            <span><b>*</b> FARM FRESH</span>
            <span><b>*</b> NO PRESERVATIVES</span>
            <span><b>*</b> HAND PICKED</span>
        </div>
    </div>
</div>

<div class="trust-strip">
    <div class="app-container">
        <div class="trust-grid">
            <div class="trust-item">
                <span><i class="bi bi-shield-check"></i></span>
                <div>
                    <strong>Verified Farmers</strong>
                    <small>100% local producers</small>
                </div>
            </div>
            <div class="trust-item">
                <span><i class="bi bi-basket"></i></span>
                <div>
                    <strong>Secure Pre-order</strong>
                    <small>Reserve before they sell out</small>
                </div>
            </div>
            <div class="trust-item">
                <span><i class="bi bi-geo-alt"></i></span>
                <div>
                    <strong>Local Pickup</strong>
                    <small>Convenient market locations</small>
                </div>
            </div>
            <div class="trust-item">
                <span><i class="bi bi-arrow-repeat"></i></span>
                <div>
                    <strong>Zero Waste</strong>
                    <small>Direct farm-to-table system</small>
                </div>
            </div>
        </div>
    </div>
</div>

@if($markets->count() > 0)
<div class="section-space soft-section">
    <div class="app-container">
        <div class="section-header">
            <div>
                <div class="eyebrow"><span class="eyebrow-dot"></span> Locations</div>
                <h2>Explore <em>nearby</em> markets</h2>
            </div>
            <a href="{{ route('markets.index') }}" class="outline-button text-decoration-none">View All Markets</a>
        </div>

        <div class="market-card-grid">
            @foreach($markets as $market)
            <div class="market-card">
                <div class="market-card-top green">
                    <div class="market-live">
                        <span class="live-dot"></span> Active Now
                    </div>
                    <button type="button" class="market-select"><i class="bi bi-heart"></i></button>
                    <div class="market-map-lines"></div>
                    <div class="market-card-icon"><i class="bi bi-shop fs-1"></i></div>
                </div>
                <div class="market-card-body">
                    <div class="market-day">
                        <span><i class="bi bi-geo-alt-fill text-primary"></i> {{ $market->city }}</span>
                        @if(!empty($market->operating_days))
                            <b>{{ $market->operating_days[0] }}s</b>
                        @endif
                    </div>
                    <h3>{{ $market->name }}</h3>
                    <p>{{ Str::limit($market->description, 60) }}</p>
                    
                    <div class="market-stats">
                        <div>
                            <span>Stalls</span>
                            <strong>12+</strong>
                        </div>
                        <div>
                            <span>Distance</span>
                            <strong>2.4m</strong>
                        </div>
                        <div>
                            <span>Status</span>
                            <strong class="text-success">Open</strong>
                        </div>
                    </div>
                    
                    <a href="{{ route('markets.show', $market) }}" class="card-link text-decoration-none">
                        Explore Market <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@if($featuredProducts->count() > 0)
<div class="section-space">
    <div class="app-container">
        <div class="section-header">
            <div>
                <div class="eyebrow"><span class="eyebrow-dot"></span> Fresh Arrivals</div>
                <h2>Farm <em>fresh</em> picks</h2>
            </div>
            <a href="{{ route('products.index') }}" class="outline-button text-decoration-none">Browse Catalog</a>
        </div>

        <div class="market-card-grid">
            @foreach($featuredProducts as $product)
            <div class="catalog-product-card">
                <div class="catalog-product-image">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    <button class="favorite-button"><i class="bi bi-heart"></i></button>
                    @if($product->is_recurring)
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
                    <h3>{{ $product->name }}</h3>
                    <a href="#" class="farmer-link text-decoration-none"><i class="bi bi-person-circle"></i> {{ $product->farmer->farmerProfile->stall_name ?? 'Local Farm' }}</a>
                    
                    <div class="catalog-product-footer">
                        <div>
                            <strong>${{ number_format($product->price, 2) }}</strong>
                            <small>per {{ $product->unit }}</small>
                        </div>
                        <div class="product-add-row">
                            <div class="mini-stepper">
                                <button type="button"><i class="bi bi-dash"></i></button>
                                <b>1</b>
                                <button type="button"><i class="bi bi-plus"></i></button>
                            </div>
                            <button class="add-preorder" type="button" onclick="window.location='{{ route('products.show', $product) }}'">View</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="home-cta">
    <div class="app-container">
        <div class="home-cta-card">
            <div>
                <div class="eyebrow"><span class="eyebrow-dot"></span> For Farmers</div>
                <h2>Grow your <em>local</em> business</h2>
                <p>Join MarketLink to reach more customers, reduce waste through pre-orders, and manage your market stall with ease.</p>
                <div class="d-flex gap-2">
                    <a href="{{ route('register') }}" class="primary-button text-decoration-none">Register as a Farmer</a>
                    <a href="{{ route('about') }}" class="light-button text-decoration-none">Learn More</a>
                </div>
            </div>
            <div>
                <!-- Decorative element or illustration can go here -->
            </div>
        </div>
    </div>
</div>

@endsection

