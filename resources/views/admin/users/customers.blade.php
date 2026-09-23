@extends('layouts.admin')
@section('page_title', 'Customer Management')

@section('content')

<div class="card-ml border-0 p-4 mb-4">
    <div class="row g-3 align-items-center">
        <div class="col-md-6">
            <form action="{{ route('admin.users.customers') }}" method="GET" class="form-ml">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
                    <button class="btn btn-primary-ml px-4" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card-ml border-0 p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-ml mb-0 border-0 shadow-none">
            <thead>
                <tr>
                    <th class="ps-4">Customer</th>
                    <th>Email</th>
                    <th>Joined Date</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $customer->profile_photo_url }}" class="rounded-circle" width="40" height="40">
                                <div class="fw-bold text-dark">{{ $customer->name }}</div>
                            </div>
                        </td>
                        <td class="fw-medium small">{{ $customer->email }}</td>
                        <td class="small">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($customer->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Deactivated</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <form action="{{ route('admin.users.toggle-customer', $customer) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch m-0 d-inline-block">
                                    <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $customer->is_active ? 'checked' : '' }} title="Toggle Status">
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $customers->withQueryString()->links('pagination::bootstrap-5') }}
</div>

@endsection
