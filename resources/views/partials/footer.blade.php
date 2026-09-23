<footer class="footer-main">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">
                    <div class="footer-brand-icon">
                        <i class="bi bi-basket-fill"></i>
                    </div>
                    MarketLink
                </div>
                <p class="mb-4">Connecting local farmers with the community. Discover fresh, seasonal produce at farmers markets near you.</p>
                <div class="d-flex gap-3 fs-5">
                    <a href="#" class="text-white opacity-75 text-decoration-none"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white opacity-75 text-decoration-none"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="text-white opacity-75 text-decoration-none"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-heading">Quick Links</h5>
                <a href="{{ route('home') }}" class="footer-link">Home</a>
                <a href="{{ route('about') }}" class="footer-link">About Us</a>
                <a href="{{ route('contact') }}" class="footer-link">Contact</a>
            </div>
            <div class="col-lg-2 col-md-6">
                <h5 class="footer-heading">Platform</h5>
                <a href="{{ route('register') }}" class="footer-link">Sign Up</a>
                <a href="{{ route('login') }}" class="footer-link">Login</a>
                <a href="#" class="footer-link">Farmers</a>
            </div>
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-heading">Newsletter</h5>
                <p>Subscribe to get updates on new markets and seasonal produce.</p>
                <form class="d-flex gap-2">
                    <input type="email" class="form-control bg-dark border-secondary text-white" placeholder="Email address">
                    <button class="btn btn-accent-ml" type="button">Subscribe</button>
                </form>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="text-center">
            <p class="mb-0 text-white-50 small">&copy; {{ date('Y') }} MarketLink. All rights reserved.</p>
        </div>
    </div>
</footer>
