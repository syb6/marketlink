@extends('layouts.customer')
@section('page_title', 'Market Details')

@section('content')

<a href="{{ route('customer.markets.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Markets</a>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-ml border-0 overflow-hidden">
            @if($market->image)
                <img src="{{ asset('storage/'.$market->image) }}" class="w-100 img-cover" style="height: 250px;" alt="{{ $market->name }}">
            @else
                <div class="bg-light d-flex align-items-center justify-content-center" style="height:250px;">
                    <i class="bi bi-shop fs-1 text-muted opacity-50"></i>
                </div>
            @endif
            <div class="p-4 p-md-5">
                <h2 class="fw-bold mb-3">{{ $market->name }}</h2>
                <div class="d-flex flex-wrap gap-4 mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-green rounded-circle" style="width:40px;height:40px;font-size:1.1rem;"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <div class="small text-muted fw-bold text-uppercase">Location</div>
                            <div class="fw-medium">{{ $market->address }}, {{ $market->city }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-gold rounded-circle" style="width:40px;height:40px;font-size:1.1rem;"><i class="bi bi-calendar-event"></i></div>
                        <div>
                            <div class="small text-muted fw-bold text-uppercase">Operating Days</div>
                            <div class="fw-medium">{{ $market->operating_days_text }}</div>
                        </div>
                    </div>
                    @if($market->opening_time)
                    <div class="d-flex align-items-center gap-2">
                        <div class="stat-icon stat-icon-blue rounded-circle" style="width:40px;height:40px;font-size:1.1rem;"><i class="bi bi-clock"></i></div>
                        <div>
                            <div class="small text-muted fw-bold text-uppercase">Hours</div>
                            <div class="fw-medium">{{ \Carbon\Carbon::parse($market->opening_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($market->closing_time)->format('g:i A') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <h6 class="fw-bold">About this market</h6>
                <p class="text-muted">{{ $market->description ?? 'No description provided.' }}</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-ml border-0 h-100 map-container p-0">
            @if($market->latitude && $market->longitude)
                <div id="market-map" style="width:100%; height:100%; min-height: 300px;"></div>
            @else
                <div class="h-100 d-flex flex-column align-items-center justify-content-center bg-light text-muted p-4 text-center">
                    <i class="bi bi-map fs-1 mb-2"></i>
                    <p>Map location not provided for this market.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<h4 class="fw-bold mb-4 mt-5">Farmers at this Market ({{ $farmers->count() }})</h4>

<div class="row g-4">
    @forelse($farmers as $farmerProfile)
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
                    <button class="fav-btn" data-fav-type="farmer" data-fav-id="{{ $farmerProfile->id }}" title="Save Farmer">
                        <i class="bi bi-heart"></i>
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
                    <span class="text-muted">({{ $farmerProfile->review_count }} reviews)</span>
                </div>
                
                <p class="small text-muted mb-4 text-truncate">{{ $farmerProfile->bio }}</p>
                
                <div class="bg-light rounded p-3 mb-4">
                    <div class="small fw-bold text-dark mb-2">Available Products ({{ $farmerProfile->user->products->count() }})</div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($farmerProfile->user->products->take(5) as $product)
                            <span class="badge bg-white text-dark border">{{ $product->name }}</span>
                        @endforeach
                        @if($farmerProfile->user->products->count() > 5)
                            <span class="badge bg-white text-dark border">+{{ $farmerProfile->user->products->count() - 5 }} more</span>
                        @endif
                    </div>
                </div>

                <a href="{{ route('customer.products.index', ['farmer' => $farmerProfile->user_id]) }}" class="btn btn-outline-ml w-100 justify-content-center">View Store</a>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 bg-light p-5 text-center text-muted rounded-ml">
                No active farmers are currently listed for this market.
            </div>
        </div>
    @endforelse
</div>

@endsection

@push('scripts')
@if($market->latitude && $market->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const map = MapHelper.init('market-map', {{ $market->latitude }}, {{ $market->longitude }}, 15);
    if(map) {
        MapHelper.addMarker(map, {{ $market->latitude }}, {{ $market->longitude }}, '<strong>{{ $market->name }}</strong>');
    }
});
</script>
@endif
@endpush
