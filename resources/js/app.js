import './bootstrap';

// Mobile menu toggle
document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-mobile-menu-toggle]');
    if (toggle) {
        const menu = document.querySelector('[data-mobile-menu]');
        menu?.classList.remove('hidden');
        return;
    }

    const close = event.target.closest('[data-mobile-menu-close]');
    if (close) {
        const menu = document.querySelector('[data-mobile-menu]');
        menu?.classList.add('hidden');
    }
});

// Auto-dismiss toasts after 5s and handle close clicks
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-toast]').forEach((toast) => {
        const hide = () => {
            toast.style.transition = 'opacity 200ms ease, transform 200ms ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(1rem)';
            setTimeout(() => toast.remove(), 220);
        };
        setTimeout(hide, 5000);
        toast.querySelector('[data-toast-close]')?.addEventListener('click', hide);
    });
});
