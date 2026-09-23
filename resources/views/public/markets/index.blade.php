@extends('layouts.app')
@section('title', 'Explore Markets - MarketLink')

@section('content')

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="mb-2"><i class="bi bi-shop-window me-2"></i>Local Farmers Markets</h1>
                <p class="mb-0 opacity-75">Find and explore farmers markets in your area. See operating days, locations, and the farmers who sell there.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 fs-6">
                    <i class="bi bi-geo-alt me-1"></i> {{ $markets->count() }} Markets
                </span>
            </div>
        </div>
    </div>
</section>

<div class="container py-4">

    <!-- Filters -->
    <div class="card-ml border-0 p-3 p-md-4 mb-4">
        <form action="{{ route('markets.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-bold text-muted">Search Markets</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Market name or city..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Filter by Day</label>
                <select name="day" class="form-select">
                    <option value="">Any Day</option>
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                        <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary-ml flex-grow-1 justify-content-center">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('markets.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>

    <!-- Markets Grid -->
    <div class="row g-3 g-md-4">
        @forelse($markets as $market)
            <div class="col-md-6 col-lg-4">
                <div class="card-ml h-100 border-0 overflow-hidden">
                    @if($market->image)
                        <img src="{{ asset('storage/'.$market->image) }}" class="card-img-top img-cover" style="height: 200px;" alt="{{ $market->name }}">
                    @else
                        <div class="bg-gradient d-flex align-items-center justify-content-center" style="height:200px; background: linear-gradient(135deg, var(--accent-pale), var(--accent-light));">
                            <i class="bi bi-shop fs-1 text-primary opacity-50"></i>
                        </div>
                    @endif
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-2">{{ $market->name }}</h5>
                        <p class="small text-muted mb-3">
                            <i class="bi bi-geo-alt-fill text-primary"></i> {{ $market->address }}, {{ $market->city }}
                        </p>
                        <p class="small mb-3 text-truncate-2">{{ Str::limit($market->description, 100) }}</p>

                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @foreach($market->operating_days ?? [] as $day)
                                <span class="badge bg-light text-dark border small">{{ $day }}</span>
                            @endforeach
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <span class="small text-muted">
                                <i class="bi bi-clock me-1"></i>{{ $market->opening_time }} - {{ $market->closing_time }}
                            </span>
                            <a href="{{ route('markets.show', $market) }}" class="btn btn-sm btn-primary-ml">Explore <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-shop empty-state-icon"></i>
                    <h5 class="fw-bold">No markets found</h5>
                    <p class="text-muted">Try adjusting your search or day filter.</p>
                    <a href="{{ route('markets.index') }}" class="btn btn-primary-ml mt-2">View All Markets</a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
