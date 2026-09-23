@extends('layouts.app')

@section('title', 'About Us - MarketLink')

@section('content')
<div class="page-header">
    <div class="container text-center">
        <h1 class="fw-bold">About MarketLink</h1>
        <p class="lead max-w-lg mx-auto">Connecting communities with their local food sources.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=1000" alt="Farmers Market" class="img-fluid rounded-ml-lg shadow-lg">
        </div>
        <div class="col-md-6 ps-md-5 mt-4 mt-md-0">
            <span class="section-pill">Our Mission</span>
            <h2 class="section-title mb-4">Strengthening Local Food Systems</h2>
            <p class="text-muted fs-5">Local farmers markets are growing in popularity as shoppers look for fresh, seasonal, and locally grown produce. However, customers rarely know in advance which Farmers will be at a market, what stock they have, or at what price.</p>
            <p class="text-muted fs-5">MarketLink bridges this gap. We provide a platform where Farmers can publish their inventory, take pre-orders, and build relationships, while giving Customers the convenience of discovering local food and securing their favorites before they sell out.</p>
        </div>
    </div>

    <div class="row g-4 mt-4">
        <div class="col-md-4">
            <div class="card-ml p-4 text-center h-100">
                <div class="stat-icon stat-icon-green mx-auto mb-3"><i class="bi bi-basket"></i></div>
                <h4 class="fw-bold">Fresh & Local</h4>
                <p class="text-muted mb-0">We believe in food that doesn't travel thousands of miles to reach your plate. Support local agriculture.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-ml p-4 text-center h-100">
                <div class="stat-icon stat-icon-gold mx-auto mb-3"><i class="bi bi-clock-history"></i></div>
                <h4 class="fw-bold">Convenience</h4>
                <p class="text-muted mb-0">No more waking up at dawn just to get the good tomatoes. Pre-order online and pick up at your leisure.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-ml p-4 text-center h-100">
                <div class="stat-icon stat-icon-blue mx-auto mb-3"><i class="bi bi-people"></i></div>
                <h4 class="fw-bold">Community</h4>
                <p class="text-muted mb-0">Build real relationships with the people who grow your food through direct connection and feedback.</p>
            </div>
        </div>
    </div>
</div>
@endsection
