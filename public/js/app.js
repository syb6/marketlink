/**
 * MarketLink – Main JavaScript
 * Cart management, Leaflet maps, favorites AJAX, UI helpers
 */

// ── Helper: Safe API Response Parser ───────────────────────────────────────

async function parseResponse(res) {
    const contentType = res.headers.get('content-type');
    if (contentType && contentType.includes('application/json')) {
        return await res.json();
    }
    throw new Error(`Server error (${res.status})`);
}

// ── Cart Management ────────────────────────────────────────────────────────

const Cart = {
    async add(productId, quantity = 1) {
        try {
            const res = await fetch('/customer/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity }),
            });

            const data = await parseResponse(res);
            if (!res.ok) throw new Error(data.error || 'Failed to add to cart');

            Cart.updateCount(data.cart_count);
            // #region agent log
            fetch('http://127.0.0.1:7352/ingest/e12f38a0-c229-46ed-b632-bf476e0caca6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'22f056'},body:JSON.stringify({sessionId:'22f056',hypothesisId:'C',location:'app.js:Cart.add',message:'cart add badge state',data:{cartCount:data.cart_count,badgeExists:!!document.getElementById('cart-count'),badgeText:document.getElementById('cart-count')?.textContent||null},timestamp:Date.now()})}).catch(()=>{});
            // #endregion
            Toast.show(data.message || 'Item added to cart!', 'success');
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity }),
            });

            const data = await parseResponse(res);
            // #region agent log
            fetch('http://127.0.0.1:7352/ingest/e12f38a0-c229-46ed-b632-bf476e0caca6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'22f056'},body:JSON.stringify({sessionId:'22f056',hypothesisId:'B',location:'app.js:Cart.update',message:'cart update client payload',data:{ok:res.ok,hasSubtotal:data.subtotal!==undefined,subtotal:data.subtotal,cartCount:data.cart_count,qtySteppers:document.querySelectorAll('.qty-stepper').length,qtyControls:document.querySelectorAll('.quantity-control').length,cartCountEl:!!document.getElementById('cart-count')},timestamp:Date.now()})}).catch(()=>{});
            // #endregion
            if (!res.ok) throw new Error(data.error || 'Update failed');

            Cart.updateCount(data.cart_count);

            // Update subtotal for line item in DOM if provided by API
            if (data.subtotal !== undefined) {
                const subtotalEl = document.querySelector(`[data-cart-row="${productId}"] [data-cart-subtotal]`) ||
                                   document.querySelector(`[data-cart-subtotal-for="${productId}"]`);
                if (subtotalEl) {
                    subtotalEl.dataset.cartSubtotal = data.subtotal;
                    subtotalEl.textContent = '$' + parseFloat(data.subtotal).toFixed(2);
                }
            }

            Cart.refreshTotal();
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId }),
            });

            const data = await parseResponse(res);
            if (!res.ok) throw new Error(data.error || 'Remove failed');

            Cart.updateCount(data.cart_count);
            // Remove row from cart table
            document.querySelector(`[data-cart-row="${productId}"]`)?.remove();
            Cart.refreshTotal();
            Toast.show(data.message || 'Item removed.', 'success');
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
            const rawVal = el.dataset.cartSubtotal || el.textContent || '0';
            const val = parseFloat(rawVal.replace(/[^0-9.-]+/g, '')) || 0;
            total += val;
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ type, id }),
            });

            const data = await parseResponse(res);
            if (!res.ok) throw new Error(data.error || 'Failed to update favorites');

            const icon = btn?.querySelector('i');

            if (data.status === 'added') {
                btn?.classList.add('active');
                if (icon) icon.className = 'bi bi-heart-fill';
                Toast.show(data.message || 'Added to favorites!', 'success');
            } else {
                btn?.classList.remove('active');
                if (icon) icon.className = 'bi bi-heart';
                Toast.show(data.message || 'Removed from favorites.', 'success');
            }
        } catch (e) {
            Toast.show(e.message || 'Please log in to use favorites.', 'error');
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
        if (!Toast.container) return;

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
        // #region agent log
        fetch('http://127.0.0.1:7352/ingest/e12f38a0-c229-46ed-b632-bf476e0caca6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'22f056'},body:JSON.stringify({sessionId:'22f056',hypothesisId:'E',location:'app.js:MapHelper.init',message:'map init',data:{containerId,hasContainer:!!container,leafletLoaded:typeof L!=='undefined'},timestamp:Date.now()})}).catch(()=>{});
        // #endregion
        if (!container || typeof L === 'undefined') return null;

        const map = L.map(containerId).setView([lat, lng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);

        return map;
    },

    greenIcon() {
        if (typeof L === 'undefined') return null;
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
        if (!map || typeof L === 'undefined') return null;
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
    document.querySelectorAll('.star-rating-input').forEach(container => {
        const input = container.querySelector('input') || document.getElementById('rating-input');
        const stars = container.querySelectorAll('.star-btn');

        stars.forEach(star => {
            star.addEventListener('mouseenter', () => {
                const val = parseInt(star.dataset.value, 10);
                stars.forEach((s, i) => {
                    const icon = s.querySelector('i');
                    if (icon) icon.className = i < val ? 'bi bi-star-fill text-warning' : 'bi bi-star text-muted';
                });
            });

            star.addEventListener('click', () => {
                const val = parseInt(star.dataset.value, 10);
                if (input) input.value = val;
                stars.forEach((s, i) => {
                    const icon = s.querySelector('i');
                    if (icon) icon.className = i < val ? 'bi bi-star-fill text-warning' : 'bi bi-star text-muted';
                    s.classList.toggle('selected', i < val);
                });
            });
        });

        container.addEventListener('mouseleave', () => {
            const current = parseInt(input?.value || 0, 10);
            stars.forEach((s, i) => {
                const icon = s.querySelector('i');
                if (icon) icon.className = i < current ? 'bi bi-star-fill text-warning' : 'bi bi-star text-muted';
            });
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
    // #region agent log
    fetch('http://127.0.0.1:7352/ingest/e12f38a0-c229-46ed-b632-bf476e0caca6',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'22f056'},body:JSON.stringify({sessionId:'22f056',hypothesisId:'A',location:'app.js:initQuantitySteppers',message:'qty stepper wrappers found',data:{qtyStepper:document.querySelectorAll('.qty-stepper').length,quantityControl:document.querySelectorAll('.quantity-control').length,leaflet:typeof L!=='undefined',searchForm:!!document.getElementById('header-search-form')},timestamp:Date.now()})}).catch(()=>{});
    // #endregion
    document.querySelectorAll('.qty-stepper').forEach(wrapper => {
        const input    = wrapper.querySelector('.qty-input');
        const minusBtn = wrapper.querySelector('.qty-minus');
        const plusBtn  = wrapper.querySelector('.qty-plus');

        if (!input) return;

        // Ensure user input stays within valid min/max bounds on blur
        input.addEventListener('change', () => {
            let val = parseInt(input.value, 10);
            const min = parseInt(input.min, 10) || 1;
            const max = parseInt(input.max, 10) || 9999;
            if (isNaN(val) || val < min) val = min;
            if (val > max) val = max;
            input.value = val;
        });

        minusBtn?.addEventListener('click', () => {
            const min = parseInt(input.min, 10) || 1;
            const val = Math.max(min, parseInt(input.value || 1, 10) - 1);
            input.value = val;
            input.dispatchEvent(new Event('change'));
        });

        plusBtn?.addEventListener('click', () => {
            const max = parseInt(input.max, 10) || 9999;
            const val = Math.min(max, parseInt(input.value || 1, 10) + 1);
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
            const qty = qtyInput ? (parseInt(qtyInput.value, 10) || 1) : 1;
            const originalHTML = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            await Cart.add(productId, qty);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
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
            const qty = parseInt(input.value, 10) || 1;
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

    // Confirm dialogs (Hooked to custom modal)
    document.querySelectorAll('[data-confirm]').forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            const msg = btn.dataset.confirm;
            if (window.showCustomConfirm) {
                window.showCustomConfirm(msg, () => {
                    if (btn.tagName === 'A') {
                        window.location.href = btn.href;
                    } else {
                        const form = btn.closest('form');
                        if (form) form.submit();
                    }
                });
            } else if (confirm(msg)) {
                if (btn.tagName === 'A') window.location.href = btn.href;
                else btn.closest('form')?.submit();
            }
        });
    });
});

// Expose to window for inline use
window.Cart = Cart;
window.MapHelper = MapHelper;
window.Favorites = Favorites;
window.Toast = Toast;

// ── Custom Responsive Confirm Modal Polyfill ──────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    const modalHTML = `
        <div id="ml-custom-confirm" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.4); backdrop-filter:blur(3px); align-items:center; justify-content:center; padding:16px; opacity:0; transition:opacity 0.2s;">
            <div style="background:#fff; border-radius:12px; width:100%; max-width:380px; padding:24px; box-shadow:0 10px 25px rgba(0,0,0,0.1); transform:scale(0.95); transition:transform 0.2s;">
                <h3 style="margin:0 0 10px; font-size:18px; color:#1c1917; font-weight:700;">Are you sure?</h3>
                <p id="ml-confirm-msg" style="margin:0 0 24px; font-size:14px; color:#444; line-height:1.5;"></p>
                <div style="display:flex; justify-content:flex-end; gap:12px;">
                    <button id="ml-confirm-cancel" class="btn btn-outline-secondary" style="min-height:36px; padding:0 16px; font-size:14px;">Cancel</button>
                    <button id="ml-confirm-ok" class="btn btn-danger" style="min-height:36px; padding:0 16px; font-size:14px; border:none;">Yes, proceed</button>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    const modal = document.getElementById('ml-custom-confirm');
    const msgEl = document.getElementById('ml-confirm-msg');
    const btnCancel = document.getElementById('ml-confirm-cancel');
    const btnOk = document.getElementById('ml-confirm-ok');
    const modalContent = modal?.querySelector('div');

    let confirmCallback = null;

    window.showCustomConfirm = function(message, onConfirm) {
        if (!modal || !msgEl || !modalContent) return;
        msgEl.textContent = message;
        modal.style.display = 'flex';
        // Trigger reflow for CSS animation transition
        void modal.offsetWidth;
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
        confirmCallback = onConfirm;
    };

    function closeModal() {
        if (!modal || !modalContent) return;
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.style.display = 'none';
            confirmCallback = null;
        }, 200);
    }

    btnCancel?.addEventListener('click', closeModal);
    modal?.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    btnOk?.addEventListener('click', () => {
        const cb = confirmCallback;
        closeModal();
        if (cb) cb();
    });

    // Intercept inline onsubmit="return confirm('...')" handlers
    document.querySelectorAll('[onsubmit*="confirm"]').forEach(form => {
        const match = form.getAttribute('onsubmit').match(/confirm\(['"](.*?)['"]\)/);
        if (match && match[1]) {
            const msg = match[1];
            form.removeAttribute('onsubmit');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                showCustomConfirm(msg, () => form.submit());
            });
        }
    });

    // Intercept inline onclick="return confirm('...')" handlers
    document.querySelectorAll('[onclick*="confirm"]').forEach(btn => {
        const match = btn.getAttribute('onclick').match(/confirm\(['"](.*?)['"]\)/);
        if (match && match[1]) {
            const msg = match[1];
            btn.removeAttribute('onclick');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                showCustomConfirm(msg, () => {
                    if (btn.tagName === 'A') window.location.href = btn.href;
                    else btn.closest('form')?.submit();
                });
            });
        }
    });
});