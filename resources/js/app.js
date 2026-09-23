/**
 * MarketLink – Main JavaScript
 * Cart management, Leaflet maps, favorites AJAX, UI helpers
 */

// ── Cart Management ────────────────────────────────────────────────────────

const Cart = {
    async add(productId, quantity = 1) {
        try {
            const res = await fetch('/customer/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity }),
            });

            const data = await res.json();

            if (!res.ok) throw new Error(data.error || 'Failed to add to cart');

            Cart.updateCount(data.cart_count);
            Toast.show(data.message, 'success');
            return data;
        } catch (e) {
            Toast.show(e.message, 'error');
        }
    },

    async update(productId, quantity) {
        try {
            const res = await fetch('/customer/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity }),
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.error || 'Update failed');

            Cart.updateCount(data.cart_count);
            return data;
        } catch (e) {
            Toast.show(e.message, 'error');
        }
    },

    async remove(productId) {
        try {
            const res = await fetch('/customer/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId }),
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.error || 'Remove failed');

            Cart.updateCount(data.cart_count);
            // Remove row from cart table
            document.querySelector(`[data-cart-row="${productId}"]`)?.remove();
            Cart.refreshTotal();
            return data;
        } catch (e) {
            Toast.show(e.message, 'error');
        }
    },

    updateCount(count) {
        const badge = document.getElementById('cart-count');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
            badge.classList.add('animate__bounceIn');
            setTimeout(() => badge.classList.remove('animate__bounceIn'), 400);
        }
    },

    refreshTotal() {
        let total = 0;
        document.querySelectorAll('[data-cart-subtotal]').forEach(el => {
            total += parseFloat(el.dataset.cartSubtotal || 0);
        });
        const totalEl = document.getElementById('cart-total');
        if (totalEl) totalEl.textContent = '$' + total.toFixed(2);
    }
};

// ── Favorites ──────────────────────────────────────────────────────────────

const Favorites = {
    async toggle(type, id, btn) {
        try {
            const res = await fetch('/customer/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ type, id }),
            });

            const data = await res.json();
            if (!res.ok) throw new Error('Failed');

            if (data.status === 'added') {
                btn.classList.add('active');
                btn.querySelector('i').className = 'bi bi-heart-fill';
                Toast.show('Added to favorites!', 'success');
            } else {
                btn.classList.remove('active');
                btn.querySelector('i').className = 'bi bi-heart';
                Toast.show('Removed from favorites.', 'success');
            }
        } catch (e) {
            Toast.show('Please log in to use favorites.', 'error');
        }
    }
};

// ── Toast Notifications ────────────────────────────────────────────────────

const Toast = {
    container: null,

    init() {
        if (!document.getElementById('toast-container-ml')) {
            const el = document.createElement('div');
            el.id = 'toast-container-ml';
            el.className = 'toast-container-ml';
            document.body.appendChild(el);
        }
        Toast.container = document.getElementById('toast-container-ml');
    },

    show(message, type = 'success', duration = 3500) {
        Toast.init();
        const toast = document.createElement('div');
        toast.className = `toast-ml toast-ml-${type}`;
        const icon = type === 'success' ? '✓' : '✕';
        toast.innerHTML = `<span>${icon}</span><span>${message}</span>`;
        Toast.container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            toast.style.transition = '0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
};

// ── Leaflet Maps ───────────────────────────────────────────────────────────

const MapHelper = {
    init(containerId, lat, lng, zoom = 14) {
        const container = document.getElementById(containerId);
        if (!container) return null;

        const map = L.map(containerId).setView([lat, lng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);

        return map;
    },

    greenIcon() {
        return L.divIcon({
            html: `<div style="background:#2d6a4f;width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.3);">
                   <div style="transform:rotate(45deg);display:flex;align-items:center;justify-content:center;height:100%;font-size:14px;">🌿</div></div>`,
            className: '',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32],
        });
    },

    addMarker(map, lat, lng, popupContent) {
        return L.marker([lat, lng], { icon: MapHelper.greenIcon() })
            .addTo(map)
            .bindPopup(popupContent);
    }
};

// ── Sidebar Toggle (mobile) ────────────────────────────────────────────────

function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.classList.toggle('show');
    }
}

// ── Star Rating ────────────────────────────────────────────────────────────

function initStarRating() {
    const container = document.querySelector('.star-rating-input');
    if (!container) return;

    const input = document.getElementById('rating-input');
    const stars = container.querySelectorAll('.star-btn');

    stars.forEach(star => {
        star.addEventListener('mouseenter', () => {
            const val = parseInt(star.dataset.value);
            stars.forEach((s, i) => {
                s.querySelector('i').className = i < val ? 'bi bi-star-fill text-warning' : 'bi bi-star text-muted';
            });
        });

        star.addEventListener('click', () => {
            const val = parseInt(star.dataset.value);
            if (input) input.value = val;
            stars.forEach((s, i) => {
                s.querySelector('i').className = i < val ? 'bi bi-star-fill text-warning' : 'bi bi-star text-muted';
                s.classList.toggle('selected', i < val);
            });
        });
    });

    container.addEventListener('mouseleave', () => {
        const current = parseInt(input?.value || 0);
        stars.forEach((s, i) => {
            s.querySelector('i').className = i < current ? 'bi bi-star-fill text-warning' : 'bi bi-star text-muted';
        });
    });
}

// ── Live Search Debounce ───────────────────────────────────────────────────

function debounce(fn, delay) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

// ── Quantity Stepper ───────────────────────────────────────────────────────

function initQuantitySteppers() {
    document.querySelectorAll('.qty-stepper').forEach(wrapper => {
        const input  = wrapper.querySelector('.qty-input');
        const minusBtn = wrapper.querySelector('.qty-minus');
        const plusBtn  = wrapper.querySelector('.qty-plus');

        if (!input) return;

        minusBtn?.addEventListener('click', () => {
            const val = Math.max(1, parseInt(input.value || 1) - 1);
            input.value = val;
            input.dispatchEvent(new Event('change'));
        });

        plusBtn?.addEventListener('click', () => {
            const max = parseInt(input.max || 9999);
            const val = Math.min(max, parseInt(input.value || 1) + 1);
            input.value = val;
            input.dispatchEvent(new Event('change'));
        });
    });
}

// ── DOM Ready ─────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    Toast.init();
    initStarRating();
    initQuantitySteppers();

    // Add to cart buttons
    document.querySelectorAll('[data-add-to-cart]').forEach(btn => {
        btn.addEventListener('click', async () => {
            const productId = btn.dataset.addToCart;
            const qtyInput  = document.querySelector(`[data-qty-for="${productId}"]`);
            const qty = qtyInput ? parseInt(qtyInput.value) : 1;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            await Cart.add(productId, qty);
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-cart-plus"></i> Add to Cart';
        });
    });

    // Favorite buttons
    document.querySelectorAll('[data-fav-type]').forEach(btn => {
        btn.addEventListener('click', () => {
            Favorites.toggle(btn.dataset.favType, btn.dataset.favId, btn);
        });
    });

    // Cart quantity change on cart page
    document.querySelectorAll('[data-cart-qty-input]').forEach(input => {
        input.addEventListener('change', async () => {
            const productId = input.dataset.cartQtyInput;
            const qty = parseInt(input.value);
            await Cart.update(productId, qty);
        });
    });

    // Cart remove buttons
    document.querySelectorAll('[data-cart-remove]').forEach(btn => {
        btn.addEventListener('click', async () => {
            const productId = btn.dataset.cartRemove;
            await Cart.remove(productId);
        });
    });

    // Auto-hide alerts
    document.querySelectorAll('.alert-auto-hide').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // Confirm dialogs
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', e => {
            if (!confirm(btn.dataset.confirm)) e.preventDefault();
        });
    });
});

// Expose to window for inline use
window.Cart = Cart;
window.MapHelper = MapHelper;
window.Favorites = Favorites;
window.Toast = Toast;
