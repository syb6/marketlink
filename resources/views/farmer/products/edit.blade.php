@extends('layouts.farmer')
@section('page_title', 'Edit Product')

@section('content')

<a href="{{ route('farmer.products.index') }}" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Products</a>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-ml border-0 p-4 p-md-5">
            <h4 class="fw-bold mb-4">Edit {{ $product->name }}</h4>
            
            <form action="{{ route('farmer.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="form-ml">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Product Name *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label fw-bold">Category *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            @foreach(\App\Models\ProductCategory::where('is_active', true)->get() as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="image" class="form-label fw-bold">Update Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @if($product->image)
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img src="{{ $product->image_url }}" alt="Current Image" class="rounded border" width="40" height="40" style="object-fit: cover;">
                                <span class="small text-muted">Current image</span>
                            </div>
                        @endif
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <hr class="my-4">
                
                <h5 class="fw-bold mb-4">Pricing & Inventory</h5>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label for="price" class="form-label fw-bold">Price ($) *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                        </div>
                        @error('price')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="unit" class="form-label fw-bold">Unit *</label>
                        <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit" name="unit" value="{{ old('unit', $product->unit) }}" required>
                        @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="stock_quantity" class="form-label fw-bold">Stock Available *</label>
                        <input type="number" min="0" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                        @error('stock_quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light border-0 p-3 h-100">
                            <div class="form-check form-switch d-flex align-items-center gap-2 m-0 p-0">
                                <label class="form-check-label fw-bold ms-0 w-100 cursor-pointer" for="is_available">List on Storefront?</label>
                                <input class="form-check-input flex-shrink-0 m-0" type="checkbox" role="switch" id="is_available" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }} style="width: 40px; height: 20px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light border-0 p-3 h-100">
                            <div class="form-check form-switch d-flex align-items-center gap-2 m-0 p-0">
                                <label class="form-check-label fw-bold ms-0 w-100 cursor-pointer" for="is_recurring">Recurring Supply?</label>
                                <input class="form-check-input flex-shrink-0 m-0" type="checkbox" role="switch" id="is_recurring" name="is_recurring" value="1" {{ old('is_recurring', $product->is_recurring) ? 'checked' : '' }} style="width: 40px; height: 20px;">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-5">
                    <a href="{{ route('farmer.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary-ml px-4">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
