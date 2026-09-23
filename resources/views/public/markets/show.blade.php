@extends('layouts.app')
@section('title', $market->name . ' - MarketLink')

@section('content')

<!-- Breadcrumb -->
<div class="bg-white border-bottom">
    <div class="container py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('markets.index') }}" class="text-decoration-none">Markets</a></li>
                <li class="breadcrumb-item active">{{ $market->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Market Header -->
<section class="page-header">
    <div class="container">
        <h1 class="mb-2">{{ $market->name }}</h1>
        <div class="d-flex flex-wrap gap-3 opacity-75">
            <span><i class="bi bi-geo-alt-fill me-1"></i>{{ $market->address }}, {{ $market->city }}</span>
            <span><i class="bi bi-clock me-1"></i>{{ $market->opening_time }} - {{ $market->closing_time }}</span>
        </div>
    </div>
</section>

<div class="container py-4 py-md-5">
    <div class="row g-4 g-lg-5">
        <!-- Market Info -->
        <div class="col-lg-4">
            <div class="card-ml border-0 p-4 mb-4">
                <h5 class="fw-bold mb-3">About this Market</h5>
                <p class="text-muted">{{ $market->description }}</p>

                <h6 class="fw-bold mt-4 mb-2 small text-uppercase text-muted">Operating Days</h6>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach($market->operating_days ?? [] as $day)
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">{{ $day }}</span>
                    @endforeach
                </div>

                <h6 class="fw-bold mt-4 mb-2 small text-uppercase text-muted">Hours</h6>
                <p class="text-dark"><i class="bi bi-clock text-primary me-1"></i>{{ $market->opening_time }} - {{ $market->closing_time }}</p>

                <h6 class="fw-bold mt-4 mb-2 small text-uppercase text-muted">Location</h6>
                <p class="text-dark"><i class="bi bi-geo-alt text-primary me-1"></i>{{ $market->address }}, {{ $market->city }}</p>
            </div>

            @if($market->latitude && $market->longitude)
                <div class="card-ml border-0 overflow-hidden" style="height: 250px;" id="marketMap" data-lat="{{ $market->latitude }}" data-lng="{{ $market->longitude }}" data-name="{{ $market->name }}"></div>
            @endif
        </div>

        <!-- Farmers at this Market -->
        <div class="col-lg-8">
            <h4 class="fw-bold mb-4">
                <i class="bi bi-people-fill text-primary me-2"></i>Farmers at this Market
                <span class="badge bg-light text-dark ms-2">{{ $farmers->count() }}</span>
            </h4>

            @forelse($farmers as $profile)
                <div class="card-ml border-0 p-4 mb-4">
                    <div class="d-flex flex-column flex-sm-row align-items-start gap-3 mb-3">
                        <img src="{{ $profile->user->profile_photo_url }}" class="rounded-circle" width="64" height="64" style="object-fit: cover;">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">{{ $profile->stall_name ?? $profile->user->name }}</h5>
                            <p class="text-muted small mb-2">{{ Str::limit($profile->bio, 120) }}</p>
                            <a href="{{ route('products.index', ['farmer' => $profile->user_id]) }}" class="btn btn-sm btn-outline-ml">View All Products →</a>
                        </div>
                    </div>

                    @if($profile->user->products->count() > 0)
                        <div class="row g-2 g-md-3 mt-2">
                            @foreach($profile->user->products->take(4) as $product)
                                <div class="col-6 col-md-3">
                                    <div class="product-card border-0 shadow-sm">
                                        <a href="{{ route('products.show', $product) }}">
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-card-img" style="height: 100px;">
                                        </a>
                                        <div class="p-2">
                                            <div class="small fw-bold text-truncate">{{ $product->name }}</div>
                                            <div class="small text-primary fw-bold">${{ number_format($product->price, 2) }}/{{ $product->unit }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="empty-state">
                    <i class="bi bi-people empty-state-icon"></i>
                    <h5 class="fw-bold">No farmers listed yet</h5>
                    <p class="text-muted">This market is looking for local farmers. Check back soon!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapEl = document.getElementById('marketMap');
        if (mapEl && typeof L !== 'undefined') {
            const lat = parseFloat(mapEl.dataset.lat);
            const lng = parseFloat(mapEl.dataset.lng);
            const name = mapEl.dataset.name;
            const map = L.map('marketMap').setView([lat, lng], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);
            L.marker([lat, lng]).addTo(map).bindPopup('<strong>' + name + '</strong>').openPopup();
        }
    });
</script>
@endpush
@endsection
