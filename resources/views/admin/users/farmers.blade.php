@extends('layouts.admin')
@section('page_title', 'Farmer Management')

@section('content')

<div class="card-ml border-0 p-4 mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-6">
            <form action="{{ route('admin.users.farmers') }}" method="GET" class="form-ml">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                    <button class="btn btn-primary-ml px-4" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="btn-group" role="group">
                <a href="{{ route('admin.users.farmers') }}" class="btn {{ request('status') == '' ? 'btn-dark' : 'btn-outline-dark' }}">All</a>
                <a href="{{ route('admin.users.farmers', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'btn-dark' : 'btn-outline-dark' }}">Pending</a>
                <a href="{{ route('admin.users.farmers', ['status' => 'approved']) }}" class="btn {{ request('status') == 'approved' ? 'btn-dark' : 'btn-outline-dark' }}">Approved</a>
                <a href="{{ route('admin.users.farmers', ['status' => 'suspended']) }}" class="btn {{ request('status') == 'suspended' ? 'btn-dark' : 'btn-outline-dark' }}">Suspended</a>
            </div>
        </div>
    </div>
</div>

<div class="card-ml border-0 p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-ml mb-0 border-0 shadow-none">
            <thead>
                <tr>
                    <th class="ps-4">Farmer / Stall Name</th>
                    <th>Email / Phone</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($farmers as $farmer)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $farmer->profile_photo_url }}" class="rounded-circle" width="40" height="40">
                                <div>
                                    <div class="fw-bold text-dark">{{ $farmer->name }}</div>
                                    <div class="small text-muted">{{ $farmer->farmerProfile->stall_name ?? 'Stall not set' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-medium">{{ $farmer->email }}</div>
                            <div class="small text-muted">{{ $farmer->phone ?? 'No phone' }}</div>
                        </td>
                        <td class="small">{{ $farmer->created_at->format('M d, Y') }}</td>
                        <td>
                            @if(!$farmer->is_active)
                                <span class="badge bg-danger">Suspended</span>
                            @elseif(!$farmer->farmerProfile || !$farmer->farmerProfile->is_approved)
                                <span class="badge bg-warning text-dark">Pending</span>
                            @else
                                <span class="badge bg-success">Approved</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($farmer->is_active)
                                @if($farmer->farmerProfile && !$farmer->farmerProfile->is_approved)
                                    <form action="{{ route('admin.users.approve-farmer', $farmer) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success rounded-pill px-3" onclick="return confirm('Approve this farmer profile?');"><i class="bi bi-check-circle me-1"></i> Approve</button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.users.suspend-farmer', $farmer) }}" method="POST" class="d-inline ms-1">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger" title="Suspend Account" onclick="return confirm('Are you sure you want to suspend this farmer?');"><i class="bi bi-ban"></i></button>
                                </form>
                            @else
                                <span class="small text-danger fw-bold"><i class="bi bi-x-circle"></i> Account Disabled</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">No farmers found matching criteria.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $farmers->withQueryString()->links('pagination::bootstrap-5') }}
</div>

@endsection
