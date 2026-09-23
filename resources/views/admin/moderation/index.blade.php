@extends('layouts.admin')
@section('page_title', 'Content Moderation')

@section('content')

<ul class="nav nav-tabs mb-4 border-bottom-0 gap-2" id="modTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold text-dark bg-white border-bottom-0 shadow-sm rounded-top-ml" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">Customer Reviews</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link text-muted bg-light border-0" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">Flagged Products</button>
    </li>
</ul>

<div class="tab-content" id="modTabContent">
    <!-- Reviews Tab -->
    <div class="tab-pane fade show active" id="reviews" role="tabpanel">
        <div class="card-ml border-0 p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-ml mb-0 border-0 shadow-none">
                    <thead>
                        <tr>
                            <th class="ps-4">Review Content</th>
                            <th>Rating</th>
                            <th>Customer & Farmer</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr class="{{ !$review->is_visible ? 'bg-light text-muted' : '' }}">
                                <td class="ps-4 py-3" style="max-width: 300px;">
                                    <div class="small fw-bold mb-1">Product: {{ $review->product->name }}</div>
                                    <p class="small mb-0 text-wrap">{{ Str::limit($review->comment, 100) }}</p>
                                </td>
                                <td>
                                    <div class="stars small text-warning">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-bold">By: {{ $review->customer->name }}</div>
                                    <div class="small text-muted">To: {{ $review->farmer->farmerProfile->stall_name ?? $review->farmer->name }}</div>
                                </td>
                                <td>
                                    @if($review->is_visible)
                                        <span class="badge bg-success">Visible</span>
                                    @else
                                        <span class="badge bg-danger">Hidden</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($review->is_visible)
                                        <form action="{{ route('admin.moderation.reviews.hide', $review) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hide</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.moderation.reviews.restore', $review) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">No reviews found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $reviews->links('pagination::bootstrap-5') }}</div>
    </div>

    <!-- Products Tab -->
    <div class="tab-pane fade" id="products" role="tabpanel">
        <div class="card-ml border-0 p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-ml mb-0 border-0 shadow-none">
                    <thead>
                        <tr>
                            <th class="ps-4">Product</th>
                            <th>Farmer</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($flaggedProducts as $product)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $product->image_url }}" class="rounded shadow-sm" width="40" height="40">
                                        <div>
                                            <div class="fw-bold text-dark">{{ $product->name }}</div>
                                            <div class="small text-muted">{{ $product->category->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->farmer->farmerProfile->stall_name ?? $product->farmer->name }}</td>
                                <td>
                                    @if($product->is_available)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Hidden by Farmer</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($product->is_available)
                                        <form action="{{ route('admin.moderation.products.remove', $product) }}" method="POST" onsubmit="return confirm('Hide this product from public view?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Force Hide</button>
                                        </form>
                                    @else
                                        <span class="small text-muted">Already hidden</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 text-muted">No products found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $flaggedProducts->links('pagination::bootstrap-5') }}</div>
    </div>
</div>

@endsection
