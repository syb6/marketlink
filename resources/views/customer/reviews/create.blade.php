@extends('layouts.customer')
@section('page_title', 'Write Review')

@section('content')

<a href="{{ route('customer.orders.show', $order) }}" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Order</a>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-ml border-0 p-4 p-md-5">
            <h4 class="fw-bold mb-4">Rate Your Experience</h4>
            <p class="text-muted mb-4">You are reviewing items from order <strong>#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</strong> purchased from <strong>{{ $order->farmer->farmerProfile->stall_name ?? $order->farmer->name }}</strong>.</p>
            
            <form action="{{ route('customer.reviews.store', $order) }}" method="POST" class="form-ml">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label fw-bold">Overall Rating</label>
                    <div class="star-rating-input d-flex gap-2 fs-2 cursor-pointer" style="cursor: pointer;">
                        @for($i=1; $i<=5; $i++)
                            <span class="star-btn" data-value="{{ $i }}"><i class="bi bi-star text-muted"></i></span>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="0" required>
                    @error('rating')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                
                <div class="mb-4">
                    <label for="product_id" class="form-label fw-bold">Which product are you reviewing?</label>
                    <select class="form-select" id="product_id" name="product_id" required>
                        <option value="">Select product...</option>
                        @foreach($order->items as $item)
                            <option value="{{ $item->product_id }}">{{ $item->product->name }}</option>
                        @endforeach
                    </select>
                    @error('product_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="comment" class="form-label fw-bold">Review Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="4" required placeholder="What did you like about this product? How was the quality?"></textarea>
                    @error('comment')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary-ml px-4">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
