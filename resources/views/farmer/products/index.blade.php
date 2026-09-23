@extends('layouts.farmer')
@section('page_title', 'My Products')

@section('content')

<div class="card-ml border-0 p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <form action="{{ route('farmer.products.index') }}" method="GET" class="d-flex gap-2 w-100 form-ml" style="max-width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary-ml px-3"><i class="bi bi-search"></i></button>
        </form>
        
        <a href="{{ route('farmer.products.create') }}" class="btn btn-accent-ml">
            <i class="bi bi-plus-lg me-1"></i> Add Product
        </a>
    </div>
</div>

<div class="card-ml border-0 p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-ml mb-0 border-0 shadow-none">
            <thead>
                <tr>
                    <th class="ps-4">Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td class="ps-4 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded shadow-sm" width="50" height="50" style="object-fit: cover;">
                                <div>
                                    <div class="fw-bold text-dark">{{ $product->name }}</div>
                                    <div class="small text-muted">{{ $product->is_recurring ? 'Weekly' : 'Seasonal' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->category->name }}</td>
                        <td class="fw-medium">${{ number_format($product->price, 2) }} <span class="text-muted small">/{{ $product->unit }}</span></td>
                        <td>
                            @if($product->stock_quantity <= 5)
                                <span class="badge bg-danger rounded-pill">{{ $product->stock_quantity }} Low</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">{{ $product->stock_quantity }} Available</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('farmer.products.toggle', $product) }}" method="POST">
                                @csrf
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" role="switch" onchange="this.form.submit()" {{ $product->is_available ? 'checked' : '' }}>
                                    <label class="form-check-label small text-muted ms-1">{{ $product->is_available ? 'Active' : 'Hidden' }}</label>
                                </div>
                            </form>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('farmer.products.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('farmer.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-box-seam empty-state-icon"></i>
                                <h6 class="fw-bold">No products found</h6>
                                <p class="small text-muted mb-3">You haven't added any products yet, or none match your search.</p>
                                <a href="{{ route('farmer.products.create') }}" class="btn btn-primary-ml">Add Your First Product</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
</div>

@endsection
