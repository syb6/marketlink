@extends('layouts.admin')
@section('page_title', 'Edit Market')

@section('content')

<a href="{{ route('admin.markets.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Markets</a>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card-ml border-0 p-4 p-md-5">
            <h4 class="fw-bold mb-4">Edit {{ $market->name }}</h4>
            
            <form action="{{ route('admin.markets.update', $market) }}" method="POST" enctype="multipart/form-data" class="form-ml">
                @csrf
                @method('PUT')
                
                <div class="row g-4 mb-4">
                    <div class="col-md-8">
                        <label class="form-label fw-bold">Market Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $market->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-select" name="is_active">
                            <option value="1" {{ old('is_active', $market->is_active) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $market->is_active) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Address *</label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $market->address) }}" required>
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">City *</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city', $market->city) }}" required>
                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Latitude (Map)</label>
                        <input type="text" class="form-control @error('latitude') is-invalid @enderror" name="latitude" value="{{ old('latitude', $market->latitude) }}">
                        @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Longitude (Map)</label>
                        <input type="text" class="form-control @error('longitude') is-invalid @enderror" name="longitude" value="{{ old('longitude', $market->longitude) }}">
                        @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description', $market->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <hr class="my-4">
                
                <h5 class="fw-bold mb-4">Schedule & Image</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Operating Days</label>
                        <div class="d-flex flex-wrap gap-3">
                            @php $activeDays = old('operating_days', $market->operating_days ?? []); @endphp
                            @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="operating_days[]" value="{{ $day }}" id="day_{{ $day }}" {{ in_array($day, $activeDays) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day_{{ $day }}">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label fw-bold">Opening Time</label>
                                <input type="time" class="form-control @error('opening_time') is-invalid @enderror" name="opening_time" value="{{ old('opening_time', $market->opening_time ? substr($market->opening_time, 0, 5) : '') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label fw-bold">Closing Time</label>
                                <input type="time" class="form-control @error('closing_time') is-invalid @enderror" name="closing_time" value="{{ old('closing_time', $market->closing_time ? substr($market->closing_time, 0, 5) : '') }}">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-5">
                    <label class="form-label fw-bold">Update Market Image</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">
                    @if($market->image)
                        <div class="mt-2 text-muted small">
                            <a href="{{ asset('storage/'.$market->image) }}" target="_blank">View current image</a>
                        </div>
                    @endif
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.markets.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary-ml px-5">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
