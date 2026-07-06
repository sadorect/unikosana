// Public site interactions

// Mobile navigation toggle
document.addEventListener('click', (e) => {
    const toggle = e.target.closest('[data-nav-toggle]');
    if (toggle) {
        const menu = document.querySelector('[data-nav-menu]');
        if (menu) menu.classList.toggle('hidden');
    }

    // Donation quick-amount buttons
    const amountBtn = e.target.closest('[data-amount]');
    if (amountBtn) {
        const input = document.querySelector('[data-amount-input]');
        if (input) input.value = amountBtn.getAttribute('data-amount');
    }

    // Captcha refresh
    const reload = e.target.closest('[data-captcha-reload]');
    if (reload) {
        const img = document.querySelector('[data-captcha]');
        if (img) {
            const base = img.src.split('?')[0];
            img.src = `${base}?t=${Date.now()}`;
        }
    }
});
