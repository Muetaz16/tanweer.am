document.addEventListener('DOMContentLoaded', () => {
    const drawer = document.getElementById('public-drawer');
    const backdrop = document.getElementById('drawer-backdrop');
    const trigger = document.getElementById('drawer-trigger');
    const closeButtons = document.querySelectorAll('[data-drawer-close]');
    const dropdowns = document.querySelectorAll('[data-drawer-dropdown]');

    if (!drawer || !trigger) {
        return;
    }

    const setDrawerOpen = (open) => {
        if (open) {
            drawer.classList.add('is-open');
            backdrop.classList.add('is-open');
            trigger.setAttribute('aria-expanded', 'true');
            drawer.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden'; // Prevents scrolling background content

            // Set focus inside the drawer on open (delay slightly for transition)
            setTimeout(() => {
                const firstFocusable = drawer.querySelector('a, button');
                firstFocusable?.focus();
            }, 100);
        } else {
            drawer.classList.remove('is-open');
            backdrop.classList.remove('is-open');
            trigger.setAttribute('aria-expanded', 'false');
            drawer.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = ''; // Restores body scrolling

            // Return focus to the trigger button
            trigger.focus();
        }
    };

    // Toggle drawer on trigger click
    trigger.addEventListener('click', () => {
        const isOpen = drawer.classList.contains('is-open');
        setDrawerOpen(!isOpen);
    });

    // Close drawer on click of close buttons or backdrop overlay
    closeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            setDrawerOpen(false);
        });
    });

    // Escape key press closes the drawer
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
            setDrawerOpen(false);
        }
    });

    // Focus Trapping within the active drawer for accessibility (a11y)
    drawer.addEventListener('keydown', (e) => {
        if (e.key !== 'Tab') {
            return;
        }

        const focusables = drawer.querySelectorAll('a, button, [tabindex="0"]');
        if (focusables.length === 0) return;

        const firstEl = focusables[0];
        const lastEl = focusables[focusables.length - 1];

        if (e.shiftKey) { // Shift + Tab
            if (document.activeElement === firstEl) {
                lastEl.focus();
                e.preventDefault();
            }
        } else { // Tab
            if (document.activeElement === lastEl) {
                firstEl.focus();
                e.preventDefault();
            }
        }
    });

    // Drawer Dropdowns Toggle
    dropdowns.forEach((dropdown) => {
        const dropTrigger = dropdown.querySelector('[data-drawer-dropdown-trigger]');

        if (!dropTrigger) return;

        dropTrigger.addEventListener('click', (e) => {
            e.preventDefault();
            const isOpen = dropdown.classList.contains('is-open');
            dropdown.classList.toggle('is-open', !isOpen);
            dropTrigger.setAttribute('aria-expanded', String(!isOpen));
        });

        // Key bindings for dropdown trigger
        dropTrigger.addEventListener('keydown', (e) => {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                const isOpen = dropdown.classList.contains('is-open');
                dropdown.classList.toggle('is-open', !isOpen);
                dropTrigger.setAttribute('aria-expanded', String(!isOpen));
            }
        });
    });
});
