@extends('layouts.app')
@section('title', 'Explore Markets - MarketLink')

@section('content')

<div class="app-container">
    <div class="page-title">
        <div>
            <div class="eyebrow"><span class="eyebrow-dot"></span> Local Markets</div>
            <h1>Explore <em>local</em> markets</h1>
            <p>Find and explore farmers markets in your area. See operating days, locations, and the farmers who sell there.</p>
        </div>
        <div class="catalog-context">
            <span class="context-label">Status</span>
            <button type="button">Active Markets <i class="bi bi-chevron-down"></i></button>
            <small>Showing {{ $markets->count() }} locations</small>
        </div>
    </div>

    <div class="catalog-layout">
        <aside class="filter-sidebar">
            <div class="filter-heading">
                <span><i class="bi bi-funnel"></i> Filters</span>
                <a href="{{ route('markets.index') }}" class="text-decoration-none" style="font-size: 9px; font-weight: 800; color: var(--clay);">CLEAR ALL</a>
            </div>

            <form action="{{ route('markets.index') }}" method="GET">
                <div class="filter-block">
                    <strong>Search Markets</strong>
                    <div style="display:flex; align-items:center; border:1px solid var(--line); border-radius:6px; padding:6px; background:#fff;">
                        <i class="bi bi-search text-muted mx-2"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or city..." style="border:none; outline:none; background:transparent; font-size:10px; width:100%;">
                    </div>
                </div>

                <div class="filter-block">
                    <strong>Operating Day</strong>
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                        <label class="filter-check">
                            <input type="radio" name="day" value="{{ $day }}" {{ request('day') == $day ? 'checked' : '' }}>
                            {{ $day }}
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="primary-button wide mt-3">Apply Filters</button>
            </form>

            <div class="map-panel" style="margin-top: 25px;">
                <div class="map-background"></div>
                <div class="map-road road-a"></div>
                <div class="map-road road-b"></div>
                <div class="map-road road-c"></div>
                
                <div class="map-park park-a"><i class="bi bi-tree-fill"></i></div>
                <div class="map-park park-b"><i class="bi bi-tree-fill"></i></div>

                <div class="map-marker" style="top: 40%; left: 35%;">
                    <span><i class="bi bi-shop"></i></span>
                </div>
                <div class="map-marker clay" style="top: 70%; left: 65%;">
                    <span><i class="bi bi-shop"></i></span>
                </div>
                <div class="map-marker gold" style="top: 25%; left: 75%;">
                    <span><i class="bi bi-shop"></i></span>
                </div>

                <div class="map-legend">
                    <span><span class="legend-dot"></span> Open</span>
                    <span><span class="legend-dot clay"></span> Closed</span>
                    <button><i class="bi bi-crosshair"></i> Locate</button>
                </div>
            </div>
        </aside>

        <div>
            <div class="catalog-toolbar">
                <span>Showing {{ $markets->count() }} markets</span>
                <div class="toolbar-actions">
                    <div class="view-toggle">
                        <button class="active"><i class="bi bi-grid"></i></button>
                        <button><i class="bi bi-list"></i></button>
                    </div>
                </div>
            </div>

            <div class="market-card-grid">
                @forelse($markets as $market)
                <div class="market-card">
                    <div class="market-card-top {{ $loop->index % 3 == 0 ? 'green' : ($loop->index % 3 == 1 ? 'terracotta' : 'gold') }}">
                        <div class="market-live">
                            <span class="live-dot"></span> Open Today
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
                                <span>Hours</span>
                                <strong>{{ \Carbon\Carbon::parse($market->opening_time)->format('ga') }}</strong>
                            </div>
                            <div>
                                <span>Farmers</span>
                                <strong>8+</strong>
                            </div>
                            <div>
                                <span>Status</span>
                                <strong class="text-success">Open</strong>
                            </div>
                        </div>
                        
                        <a href="{{ route('markets.show', $market) }}" class="card-link text-decoration-none" style="width: 100%; justify-content: center; background: var(--sage); padding: 10px; border-radius: 8px;">
                            Explore Market <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: var(--paper); border-radius: 12px; border: 1px solid var(--line);">
                        <i class="bi bi-shop text-muted mb-2 d-block" style="font-size: 24px;"></i>
                        <strong>No markets found</strong>
                        <p class="text-muted" style="font-size: 11px;">Try adjusting your filters or search terms.</p>
                        <a href="{{ route('markets.index') }}" class="light-button text-decoration-none mt-2">Reset Filters</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
