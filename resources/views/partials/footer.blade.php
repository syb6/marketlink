<footer class="site-footer">
    <div class="app-container">
        <div class="footer-grid">
            <div>
                <a class="brand" href="{{ route('home') }}" style="color: #fff; text-decoration: none;">
                    <span class="brand-mark" style="background: var(--clay);"><i class="bi bi-basket-fill" style="color: #fff;"></i></span>
                    <span>Market<span style="color: #fff;">Link</span></span>
                </a>
                <p>Connecting local farmers with the community. Discover fresh, seasonal produce at farmers markets near you.</p>
                
                <div class="footer-trust">
                    <i class="bi bi-shield-check" style="font-size: 14px;"></i> Secure Platform
                </div>
            </div>
            
            <div>
                <strong>Quick Links</strong>
                <button type="button" onclick="window.location='{{ route('home') }}'">Home</button>
                <button type="button">About Us</button>
                <button type="button">Contact</button>
            </div>
            
            <div>
                <strong>Platform</strong>
                <button type="button" onclick="window.location='{{ route('register') }}'">Sign Up</button>
                <button type="button" onclick="window.location='{{ route('login') }}'">Login</button>
                <button type="button">For Farmers</button>
            </div>
            
            <div>
                <strong>Newsletter</strong>
                <p style="margin-top: 0;">Subscribe to get updates on new markets and seasonal produce.</p>
                <form style="display: flex; gap: 8px;">
                    <input type="email" placeholder="Email address" style="flex: 1; padding: 10px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); color: #fff; font-size: 10px; outline: none;">
                    <button type="button" class="primary-button" style="background: var(--clay); color: #fff;">Join</button>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} MarketLink. All rights reserved.</span>
            <div style="display: flex; gap: 15px;">
                <button type="button" style="background: none; border: none; color: inherit; padding: 0;">Terms of Service</button>
                <button type="button" style="background: none; border: none; color: inherit; padding: 0;">Privacy Policy</button>
            </div>
        </div>
    </div>
</footer>
