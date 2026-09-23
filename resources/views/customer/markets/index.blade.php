@extends('layouts.customer')
@section('page_title', 'Browse Markets')

@section('content')

<div class="card-ml border-0 mb-4 p-4">
    <form action="{{ route('customer.markets.index') }}" method="GET" class="row g-3 form-ml">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by name, city, or address..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="day" class="form-select">
                <option value="">Any Day</option>
                @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $d)
                    <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary-ml w-100 h-100 justify-content-center">Filter</button>
        </div>
    </form>
</div>

<div class="row g-4">
    @forelse($markets as $market)
        <div class="col-md-6 col-xl-4">
            <div class="card-ml h-100 border-0">
                @if($market->image)
                    <img src="{{ asset('storage/'.$market->image) }}" class="card-img-top img-cover" style="height: 180px;" alt="{{ $market->name }}">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px;">
                        <i class="bi bi-shop fs-1 text-muted opacity-50"></i>
                    </div>
                @endif
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold mb-0">{{ $market->name }}</h5>
                    </div>
                    <p class="small text-muted mb-3"><i class="bi bi-geo-alt-fill text-primary"></i> {{ $market->address }}, {{ $market->city }}</p>
                    <p class="mb-3 small text-truncate">{{ $market->description }}</p>
                    
                    <div class="d-flex flex-wrap gap-1 mb-4">
                        @foreach($market->operating_days ?? [] as $day)
                            <span class="badge bg-light text-dark border"><i class="bi bi-calendar2-week text-primary"></i> {{ $day }}</span>
                        @endforeach
                        @if($market->opening_time && $market->closing_time)
                            <span class="badge bg-light text-dark border"><i class="bi bi-clock text-primary"></i> {{ \Carbon\Carbon::parse($market->opening_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($market->closing_time)->format('g:i A') }}</span>
                        @endif
                    </div>
                    
                    <a href="{{ route('customer.markets.show', $market) }}" class="btn btn-outline-ml w-100 justify-content-center">View Farmers</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <i class="bi bi-search empty-state-icon"></i>
                <h5 class="fw-bold">No markets found</h5>
                <p>Try adjusting your search or filters.</p>
                <a href="{{ route('customer.markets.index') }}" class="btn btn-outline-ml mt-2">Clear Filters</a>
            </div>
        </div>
    @endforelse
</div>

@endsection
