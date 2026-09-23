<header class="site-header">
    <div class="app-container header-inner">
        <a class="brand" href="{{ route('home') }}" style="text-decoration: none;">
            <span class="brand-mark"><i class="bi bi-basket-fill"></i></span>
            <span>Market<span>Link</span></span>
        </a>

        <div class="header-search">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Search for fresh produce...">
        </div>

        <nav class="main-nav">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('products.index') }}" class="nav-link">Products</a>
            <a href="{{ route('markets.index') }}" class="nav-link">Markets</a>
            <a href="{{ route('about') }}" class="nav-link">About</a>
            <a href="{{ route('contact') }}" class="nav-link">Contact</a>
        </nav>

        <div class="header-actions">
            @auth
                @if(auth()->user()->isCustomer())
                    <a href="{{ route('customer.cart') }}" class="header-icon text-decoration-none" title="View Cart">
                        <i class="bi bi-cart3"></i>
                        @php
                            $cartCount = is_array(session('cart')) ? array_sum(session('cart')) : 0;
                        @endphp
                        @if($cartCount > 0)
                            <b id="cart-count">{{ $cartCount }}</b>
                        @endif
                    </a>
                @endif
                
                <div class="nav-dropdown-wrapper" style="position: relative;">
                    <button type="button" class="outline-button nav-dropdown-trigger" onclick="this.nextElementSibling.classList.toggle('d-none')" style="gap: 8px;">
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" style="width:18px;height:18px;border-radius:50%;object-fit:cover;">
                        {{ auth()->user()->name }}
                        <i class="bi bi-chevron-down" style="font-size: 10px;"></i>
                    </button>
                    
                    <div class="nav-dropdown-menu d-none" style="position: absolute; top: calc(100% + 8px); right: 0; width: 180px; background: var(--surface-glass); backdrop-filter: blur(12px); border: 1px solid var(--border-glass); border-radius: var(--radius-lg); box-shadow: var(--shadow-card); display: flex; flex-direction: column; padding: 6px; z-index: 100;">
                        <a href="{{ route(auth()->user()->dashboard_route) }}" class="dropdown-item" style="padding: 8px 12px; font-size: 11px; font-weight: 600; color: var(--stone-700); text-decoration: none; border-radius: 6px; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <hr style="margin: 4px 0; border: none; border-top: 1px solid var(--border-glass);">
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-item" style="width: 100%; padding: 8px 12px; font-size: 11px; font-weight: 600; color: #dc2626; background: none; border: none; text-align: left; border-radius: 6px; display: flex; align-items: center; gap: 8px; transition: all 0.2s;">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Close dropdown when clicking outside -->
                <script>
                    document.addEventListener('click', function(e) {
                        if(!e.target.closest('.nav-dropdown-wrapper')) {
                            document.querySelectorAll('.nav-dropdown-menu').forEach(menu => menu.classList.add('d-none'));
                        }
                    });
                </script>
            @else
                <a href="{{ route('login') }}" class="login-button text-decoration-none">Login</a>
                <a href="{{ route('register') }}" class="primary-button text-decoration-none">Sign Up</a>
            @endauth
        </div>
    </div>
</header>

<style>
    .nav-link {
        padding: 5px 0;
        color: var(--stone-700);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s var(--ease);
    }
    .nav-link:hover {
        color: var(--emerald-600);
    }
    .dropdown-item:hover {
        background: var(--emerald-50);
        color: var(--emerald-700) !important;
    }
</style>

