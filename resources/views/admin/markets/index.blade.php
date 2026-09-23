@extends('layouts.admin')
@section('page_title', 'Market Management')

@section('content')

<div class="card-ml border-0 p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Platform Markets</h5>
        <a href="{{ route('admin.markets.create') }}" class="btn btn-accent-ml"><i class="bi bi-plus-lg me-1"></i> Add Market</a>
    </div>
</div>

<div class="card-ml border-0 p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-ml mb-0 border-0 shadow-none">
            <thead>
                <tr>
                    <th class="ps-4">Market</th>
                    <th>City</th>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($markets as $market)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                @if($market->image)
                                    <img src="{{ asset('storage/'.$market->image) }}" class="rounded shadow-sm" width="50" height="50" style="object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width:50px; height:50px;"><i class="bi bi-shop"></i></div>
                                @endif
                                <div class="fw-bold text-dark">{{ $market->name }}</div>
                            </div>
                        </td>
                        <td class="small">{{ $market->city }}</td>
                        <td class="small">
                            <div class="fw-medium text-truncate" style="max-width: 150px;" title="{{ $market->operating_days_text }}">{{ $market->operating_days_text }}</div>
                            @if($market->opening_time)
                                <div class="text-muted" style="font-size:0.7rem;">{{ \Carbon\Carbon::parse($market->opening_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($market->closing_time)->format('g:i A') }}</div>
                            @endif
                        </td>
                        <td>
                            @if($market->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.markets.edit', $market) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.markets.destroy', $market) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this market?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">No markets have been created yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $markets->links('pagination::bootstrap-5') }}
</div>

@endsection
