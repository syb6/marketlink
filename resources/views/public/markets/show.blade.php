@extends('layouts.app')
@section('title', $market->name . ' - MarketLink')

@section('content')

<div class="app-container">
    <div class="page-title" style="padding-bottom: 20px;">
        <div>
            <div class="eyebrow"><span class="eyebrow-dot"></span> Market Details</div>
            <h1 style="margin-bottom: 15px;">{{ $market->name }}</h1>
            <div style="display: flex; gap: 15px; color: var(--ink-soft); font-size: 11px;">
                <span><i class="bi bi-geo-alt-fill text-primary"></i> {{ $market->address }}, {{ $market->city }}</span>
                <span><i class="bi bi-clock"></i> {{ $market->opening_time }} - {{ $market->closing_time }}</span>
            </div>
        </div>
        <div class="catalog-context">
            <span class="context-label">Farmers</span>
            <button type="button">{{ $farmers->count() }} Local Producers</button>
            <small>Active at this market</small>
        </div>
    </div>
</div>

<div class="trust-strip">
    <div class="app-container">
        <div class="trust-grid">
            <div class="trust-item">
                <span><i class="bi bi-info-circle"></i></span>
                <div>
                    <strong>About this Market</strong>
                    <small style="max-width: 150px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;" title="{{ $market->description }}">{{ $market->description }}</small>
                </div>
            </div>
            <div class="trust-item">
                <span><i class="bi bi-calendar-check"></i></span>
                <div>
                    <strong>Operating Days</strong>
                    <small>{{ implode(', ', $market->operating_days ?? []) }}</small>
                </div>
            </div>
            <div class="trust-item">
                <span><i class="bi bi-clock-history"></i></span>
                <div>
                    <strong>Hours</strong>
                    <small>{{ $market->opening_time }} - {{ $market->closing_time }}</small>
                </div>
            </div>
            <div class="trust-item">
                <span><i class="bi bi-geo-alt"></i></span>
                <div>
                    <strong>Location</strong>
                    <small>{{ $market->city }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section-space soft-section">
    <div class="app-container">
        <div class="section-header">
            <div>
                <h2>Farmers at this market</h2>
            </div>
        </div>

        <div class="farmer-grid">
            @forelse($farmers as $profile)
                <div class="farmer-card">
                    <div class="farmer-photo" style="background-image: url('{{ $profile->user->profile_photo_url }}');">
                        <span><i class="bi bi-patch-check-fill verified-icon"></i> Verified Farmer</span>
                    </div>
                    <div class="farmer-card-body">
                        <div>
                            <h3>{{ $profile->stall_name ?? $profile->user->name }}</h3>
                            <p>{{ Str::limit($profile->bio, 80) }}</p>
                        </div>
                        <div style="text-align:right;">
                            <span style="font-size: 10px; font-weight: 800; color: var(--clay);">{{ $profile->user->products->count() }}</span>
                            <br><small style="font-size: 8px; color: var(--muted);">Products</small>
                        </div>
                    </div>
                    <a href="{{ route('products.index', ['farmer' => $profile->user_id]) }}" class="farmer-card-link text-decoration-none">
                        View Stall & Products <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: var(--paper); border-radius: 12px; border: 1px solid var(--line);">
                    <i class="bi bi-people text-muted mb-2 d-block" style="font-size: 24px;"></i>
                    <strong>No farmers listed yet</strong>
                    <p class="text-muted" style="font-size: 11px;">This market is looking for local farmers. Check back soon!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
