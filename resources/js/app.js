window.MapHelper = MapHelper;
window.Favorites = Favorites;
window.Toast = Toast;

// --- Custom Responsive Confirm Modal Polyfill ---
document.addEventListener('DOMContentLoaded', () => {
    const modalHTML = 
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
    ;
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    const modal = document.getElementById('ml-custom-confirm');
    const msgEl = document.getElementById('ml-confirm-msg');
    const btnCancel = document.getElementById('ml-confirm-cancel');
    const btnOk = document.getElementById('ml-confirm-ok');
    const modalContent = modal.querySelector('div');

    let confirmCallback = null;

    window.showCustomConfirm = function(message, onConfirm) {
        msgEl.textContent = message;
        modal.style.display = 'flex';
        // Trigger reflow
        void modal.offsetWidth;
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
        confirmCallback = onConfirm;
    };

    function closeModal() {
        modal.style.opacity = '0';
        modalContent.style.transform = 'scale(0.95)';
        setTimeout(() => modal.style.display = 'none', 200);
    }

    btnCancel.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => {
        if(e.target === modal) closeModal();
    });

    btnOk.addEventListener('click', () => {
        closeModal();
        if(confirmCallback) confirmCallback();
    });

    // Intercept native confirm()
    document.querySelectorAll('[onsubmit*="return confirm"]').forEach(form => {
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

    document.querySelectorAll('[onclick*="return confirm"]').forEach(btn => {
        const match = btn.getAttribute('onclick').match(/confirm\(['"](.*?)['"]\)/);
        if (match && match[1]) {
            const msg = match[1];
            btn.removeAttribute('onclick');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                showCustomConfirm(msg, () => {
                    if (btn.tagName === 'A') window.location.href = btn.href;
                    else {
                        const form = btn.closest('form');
                        if (form) form.submit();
                    }
                });
            });
        }
    });
});