@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container position-relative z-1">
        <div class="row justify-content-center">
            <div class="col-lg-8 animate-up">
                <span class="hero-badge"><i class="bi bi-stars text-warning"></i> Fresh from the farm to your table</span>
                <h1 class="hero-title">Connect with your local <span class="hero-highlight">Farmers Market</span></h1>
                <p class="hero-subtitle mx-auto">Discover fresh, seasonal, and locally grown produce. Pre-order your favorites online and pick them up at the market. Never miss out on fresh goods again.</p>
                <div class="d-flex gap-3 justify-content-center mt-4">
                    <a href="{{ route('register') }}" class="btn btn-accent-ml btn-lg px-4 rounded-pill">Get Started</a>
                    <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill fw-semibold">Learn More</a>
                </div>

                <div class="hero-stats justify-content-center animate-up-delay-2 mt-5">
                    <div class="hero-stat">
                        <span class="hero-stat-number">{{ $farmerCount }}+</span>
                        <span class="hero-stat-label">Local Farmers</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">{{ $marketCount }}</span>
                        <span class="hero-stat-label">Active Markets</span>
                    </div>
                    <div class="hero-stat">
                        <span class="hero-stat-number">{{ $customerCount }}+</span>
                        <span class="hero-stat-label">Happy Customers</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Announcements -->
@if($announcements->count() > 0)
<div class="container mt-n4 position-relative z-2">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-glass p-3 shadow-lg">
                <div id="announcementCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($announcements as $index => $announcement)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="d-flex align-items-center gap-3 px-3">
                                    <div class="fs-4 text-{{ $announcement->type == 'info' ? 'primary' : ($announcement->type == 'success' ? 'success' : 'warning') }}">
                                        <i class="bi bi-megaphone-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $announcement->title }}</h6>
                                        <p class="mb-0 small text-muted">{{ Str::limit($announcement->body, 100) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- How It Works -->
<section class="py-5 my-4">
    <div class="container text-center">
        <span class="section-pill">Simple Process</span>
        <h2 class="section-title mb-5">How MarketLink Works</h2>
        
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="card-ml p-4 h-100 border-0 text-center animate-up-delay-1">
                    <div class="stat-icon stat-icon-green mx-auto mb-4" style="width:80px;height:80px;font-size:2rem;">
                        <i class="bi bi-search"></i>
                    </div>
                    <h4>1. Discover</h4>
                    <p class="text-muted mb-0">Find local farmers and markets near you. Browse fresh, seasonal produce online.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-ml p-4 h-100 border-0 text-center animate-up-delay-2">
                    <div class="stat-icon stat-icon-gold mx-auto mb-4" style="width:80px;height:80px;font-size:2rem;">
                        <i class="bi bi-bag-plus"></i>
                    </div>
                    <h4>2. Pre-Order</h4>
                    <p class="text-muted mb-0">Secure your favorites before they sell out. Select a pickup date and time.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card-ml p-4 h-100 border-0 text-center animate-up-delay-3">
                    <div class="stat-icon stat-icon-teal mx-auto mb-4" style="width:80px;height:80px;font-size:2rem;">
                        <i class="bi bi-geo-alt"></i>
                    </div>
                    <h4>3. Pick Up</h4>
                    <p class="text-muted mb-0">Head to the market, pay the farmer directly, and enjoy your fresh goods.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Markets -->
@if($markets->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="section-pill">Locations</span>
                <h2 class="section-title">Nearby Markets</h2>
            </div>
            <a href="{{ route('markets.index') }}" class="btn btn-outline-ml">View All</a>
        </div>

        <div class="row g-4">
            @foreach($markets as $market)
                <div class="col-md-4">
                    <div class="card-ml h-100 border-0">
                        @if($market->image)
                            <img src="{{ asset('storage/'.$market->image) }}" class="card-img-top img-cover" style="height: 200px;" alt="{{ $market->name }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px;">
                                <i class="bi bi-shop fs-1 text-muted opacity-50"></i>
                            </div>
                        @endif
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-1">{{ $market->name }}</h5>
                            <p class="small text-muted mb-3"><i class="bi bi-geo-alt-fill text-primary"></i> {{ $market->city }}</p>
                            <p class="mb-3 small">{{ Str::limit($market->description, 100) }}</p>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($market->operating_days ?? [] as $day)
                                    <span class="badge bg-light text-dark border"><i class="bi bi-calendar2-check text-primary"></i> {{ $day }}</span>
                                @endforeach
                            </div>
                            <a href="{{ route('markets.show', $market) }}" class="btn btn-primary-ml w-100 justify-content-center">Explore Market</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="py-5 mb-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <span class="section-pill">Fresh Arrivals</span>
                <h2 class="section-title">Fresh from the Farm</h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-ml">Browse Catalog</a>
        </div>

        <div class="row g-4">
            @foreach($featuredProducts as $product)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card">
                        <div class="product-card-img-wrapper">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-card-img">
                            @if($product->is_recurring)
                                <span class="product-badge">Weekly</span>
                            @else
                                <span class="product-badge product-badge-new">Seasonal</span>
                            @endif
                        </div>
                        <div class="p-3">
                            <div class="small text-primary fw-bold mb-1">{{ $product->category->name }}</div>
                            <h6 class="fw-bold mb-1 text-truncate">{{ $product->name }}</h6>
                            <p class="small text-muted mb-2 text-truncate"><i class="bi bi-person-circle"></i> {{ $product->farmer->farmerProfile->stall_name }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-5 fw-bold text-dark">${{ number_format($product->price, 2) }}<span class="fs-6 text-muted fw-normal">/{{ $product->unit }}</span></span>
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary-ml w-100 justify-content-center btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA -->
<section class="py-5 bg-primary-ml text-white text-center">
    <div class="container py-4">
        <h2 class="fw-bold mb-3">Are you a local farmer?</h2>
        <p class="fs-5 mb-4 opacity-75 max-w-lg mx-auto">Join MarketLink to reach more customers, reduce waste through pre-orders, and grow your local business.</p>
        <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 rounded-pill fw-bold text-primary">Register as a Farmer</a>
    </div>
</section>

@endsection
