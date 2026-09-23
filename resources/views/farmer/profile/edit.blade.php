@extends('layouts.farmer')
@section('page_title', 'Stall Profile')

@section('content')

<div class="row g-4">
    <!-- User Profile Details -->
    <div class="col-lg-4">
        <div class="card-ml border-0 p-4 h-100">
            <h5 class="fw-bold mb-4">Account Information</h5>
            <form action="{{ route('farmer.profile.update') }}" method="POST" class="form-ml">
                @csrf
                @method('PUT')
                <input type="hidden" name="update_type" value="user">
                
                <div class="text-center mb-4">
                    <img src="{{ $user->profile_photo_url }}" class="rounded-circle shadow-sm border border-3 border-white mb-3" width="100" height="100" style="object-fit:cover;">
                    <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                    <div class="small text-muted">{{ $user->email }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                    <div class="small text-muted mt-1">Email cannot be changed here.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                </div>
                
                <button class="btn btn-primary-ml w-100 justify-content-center">Update Account</button>
            </form>
        </div>
    </div>
    
    <!-- Stall Details -->
    <div class="col-lg-8">
        <div class="card-ml border-0 p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Public Stall Profile</h5>
                @if($profile->is_approved)
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Approved & Visible</span>
                @else
                    <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending Approval</span>
                @endif
            </div>
            
            <form action="{{ route('farmer.profile.update') }}" method="POST" enctype="multipart/form-data" class="form-ml">
                @csrf
                @method('PUT')
                <input type="hidden" name="update_type" value="stall">
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Stall / Farm Name *</label>
                        <input type="text" class="form-control" name="stall_name" value="{{ old('stall_name', $profile->stall_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Primary Contact Person</label>
                        <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person', $profile->contact_person) }}">
                    </div>
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Stall Image/Logo</label>
                        <input type="file" class="form-control" name="stall_image" accept="image/*">
                        <div class="small text-muted mt-1">Recommended size: 800x800px. Used on the market listing page.</div>
                    </div>
                    <div class="col-md-4">
                        @if($profile->stall_image)
                            <div class="text-center bg-light rounded p-2">
                                <img src="{{ asset('storage/'.$profile->stall_image) }}" class="rounded shadow-sm" width="80" height="80" style="object-fit:cover;">
                                <div class="small text-muted mt-1">Current Image</div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">About Your Farm (Bio)</label>
                    <textarea class="form-control" name="bio" rows="5" placeholder="Tell customers about your growing practices, history, and what makes your products special...">{{ old('bio', $profile->bio) }}</textarea>
                </div>
                
                <hr class="my-4">
                
                <div class="alert alert-info border-0 bg-info-subtle small mb-4">
                    <i class="bi bi-info-circle-fill me-1"></i> Make sure your stall name and bio are filled out. Customers are more likely to buy from farmers they can learn about!
                </div>
                
                <div class="text-end">
                    <button class="btn btn-primary-ml px-5">Save Stall Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
