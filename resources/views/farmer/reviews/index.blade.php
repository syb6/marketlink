@extends('layouts.farmer')
@section('page_title', 'Customer Reviews')

@section('content')

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card-ml border-0 p-4 h-100 d-flex flex-column justify-content-center align-items-center text-center">
            <h6 class="fw-bold text-muted text-uppercase mb-3">Overall Rating</h6>
            <div class="display-3 fw-bold text-dark mb-2">{{ number_format($averageRating, 1) }}</div>
            <div class="stars fs-3 mb-2">
                @for($i=1; $i<=5; $i++)
                    <i class="bi bi-star{{ $i <= round($averageRating) ? '-fill' : '' }}"></i>
                @endfor
            </div>
            <p class="text-muted small mb-0">Based on {{ $reviews->total() }} verified purchases</p>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-ml border-0 p-4 h-100 d-flex flex-column justify-content-center">
            <h6 class="fw-bold mb-4">Why Reviews Matter</h6>
            <p class="text-muted small mb-3">Customer reviews build trust and help attract new buyers to your stall. Responding to reviews shows you care about customer feedback.</p>
            <ul class="list-unstyled small text-muted mb-0">
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Only verified buyers who picked up their order can leave a review.</li>
                <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Keep responses professional and courteous.</li>
                <li><i class="bi bi-check-circle-fill text-success me-2"></i> Inappropriate reviews can be reported to admins.</li>
            </ul>
        </div>
    </div>
</div>

<div class="card-ml border-0 p-0 overflow-hidden">
    @forelse($reviews as $review)
        <div class="p-4 border-bottom">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="{{ $review->customer->profile_photo_url }}" class="rounded-circle" width="40" height="40">
                        <div>
                            <div class="fw-bold small">{{ $review->customer->name }}</div>
                            <div class="text-muted small" style="font-size: 0.75rem;">{{ $review->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <div class="stars mb-2">
                        @for($i=1; $i<=5; $i++)
                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                        @endfor
                    </div>
                </div>
                
                <div class="col-md-9">
                    <div class="small fw-bold text-primary mb-2">Product: <a href="{{ route('customer.products.show', $review->product) }}" class="text-decoration-none" target="_blank">{{ $review->product->name }}</a></div>
                    <p class="text-dark mb-3">{{ $review->comment }}</p>
                    
                    @if($review->reply)
                        <div class="bg-light p-3 rounded-ml border-start border-4 border-primary">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold small"><i class="bi bi-person-badge text-primary me-1"></i> Your Reply</span>
                                <span class="text-muted" style="font-size: 0.7rem;">{{ $review->updated_at->format('M d, Y') }}</span>
                            </div>
                            <p class="small mb-0 text-muted">{{ $review->reply }}</p>
                        </div>
                    @else
                        <button class="btn btn-sm btn-outline-ml" type="button" data-bs-toggle="collapse" data-bs-target="#replyForm{{ $review->id }}" aria-expanded="false">
                            <i class="bi bi-reply"></i> Reply to Customer
                        </button>
                        
                        <div class="collapse mt-3" id="replyForm{{ $review->id }}">
                            <form action="{{ route('farmer.reviews.reply', $review) }}" method="POST" class="form-ml bg-light p-3 rounded-ml">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">Your Response (Visible publicly)</label>
                                    <textarea class="form-control text-sm" name="reply" rows="3" required placeholder="Thank the customer for their feedback..."></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-sm btn-light me-2" data-bs-toggle="collapse" data-bs-target="#replyForm{{ $review->id }}">Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-primary-ml">Post Reply</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="p-5 text-center">
            <div class="empty-state">
                <i class="bi bi-star empty-state-icon"></i>
                <h5 class="fw-bold">No reviews yet</h5>
                <p class="small text-muted">You haven't received any reviews on your products yet. Deliver great service to get your first 5-star rating!</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $reviews->links('pagination::bootstrap-5') }}
</div>

@endsection
