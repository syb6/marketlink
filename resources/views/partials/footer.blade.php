<footer class="bg-dark text-white-50 py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="text-white"><i class="bi bi-basket-fill text-success"></i> MarketLink</h5>
                <p class="small">Connecting local farmers with the community. Discover fresh, seasonal produce at markets near you.</p>
            </div>
            <div class="col-md-2">
                <h6 class="text-white text-uppercase small">Links</h6>
                <ul class="list-unstyled small">
                    <li><a class="link-light text-decoration-none" href="{{ route('home') }}">Home</a></li>
                    <li><a class="link-light text-decoration-none" href="{{ route('about') }}">About</a></li>
                    <li><a class="link-light text-decoration-none" href="{{ route('contact') }}">Contact</a></li>
                    <li><a class="link-light text-decoration-none" href="{{ route('products.index') }}">Products</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white text-uppercase small">Account</h6>
                <ul class="list-unstyled small">
                    <li><a class="link-light text-decoration-none" href="{{ route('register') }}">Sign Up</a></li>
                    <li><a class="link-light text-decoration-none" href="{{ route('login') }}">Login</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="text-white text-uppercase small">Stay in touch</h6>
                <p class="small">Browse markets and pre-order produce for pickup.</p>
                <a href="{{ route('markets.index') }}" class="btn btn-success btn-sm">Find a market</a>
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="small mb-0">&copy; {{ date('Y') }} MarketLink. All rights reserved.</p>
    </div>
</footer>
