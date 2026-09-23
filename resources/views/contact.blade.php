@extends('layouts.app')

@section('title', 'Contact Us - MarketLink')

@section('content')
<div class="page-header">
    <div class="container text-center">
        <h1 class="fw-bold">Contact Us</h1>
        <p class="lead max-w-lg mx-auto">Have a question? We're here to help.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-5">
        <div class="col-lg-5">
            <div class="card-ml p-5 h-100 border-0">
                <h3 class="fw-bold mb-4">Get in Touch</h3>
                
                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="stat-icon stat-icon-green fs-4"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Headquarters</h6>
                        <p class="text-muted mb-0">1 Platform HQ, Green City<br>Eco State, 12345</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="stat-icon stat-icon-blue fs-4"><i class="bi bi-envelope"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Email Support</h6>
                        <p class="text-muted mb-0">support@marketlink.com<br>farmers@marketlink.com</p>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="stat-icon stat-icon-gold fs-4"><i class="bi bi-telephone"></i></div>
                    <div>
                        <h6 class="fw-bold mb-1">Call Us</h6>
                        <p class="text-muted mb-0">+1 (555) 0100-2000<br>Mon-Fri, 9am-5pm</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7">
            <div class="card-ml p-0 overflow-hidden h-100 border-0 map-container">
                <div id="contact-map" style="width: 100%; height: 100%; min-height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // HQ Coordinates
    const lat = 34.052235;
    const lng = -118.243683;
    
    const map = MapHelper.init('contact-map', lat, lng, 13);
    if(map) {
        MapHelper.addMarker(map, lat, lng, '<strong>MarketLink HQ</strong><br>1 Platform HQ, Green City');
    }
});
</script>
@endpush
