document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('site-header');
    const toggle = document.querySelector('[data-toggle-nav]');
    const mobileNav = document.getElementById('mobile-nav');

    if (header) {
        const onScroll = () => {
            header.classList.toggle('is-scrolled', window.scrollY > 12);
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    toggle?.addEventListener('click', () => {
        mobileNav?.classList.toggle('hidden');
    });

    document.querySelectorAll('[data-payment-option]').forEach((option) => {
        option.addEventListener('click', () => {
            document.querySelectorAll('[data-payment-option]').forEach((el) => {
                el.classList.remove('ring-2', 'ring-forest/40', 'border-forest/40');
            });
            option.classList.add('ring-2', 'ring-forest/40', 'border-forest/40');
            const input = option.querySelector('input[type="radio"]');
            if (input) {
                input.checked = true;
            }
        });
    });
});
