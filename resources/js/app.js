import './bootstrap';
import './nav';

// Dark mode toggle (in drawer)
const drawerDarkModeToggle = document.getElementById('drawer-dark-mode-toggle');
const drawerDarkModeState = document.getElementById('drawer-dark-mode-state');

const syncDrawerDarkModeState = isDarkMode => {
    if (!drawerDarkModeToggle) return;

    drawerDarkModeToggle.setAttribute('aria-pressed', String(isDarkMode));
    drawerDarkModeToggle.classList.toggle('is-dark', isDarkMode);

    if (drawerDarkModeState) {
        drawerDarkModeState.textContent = isDarkMode ? 'ليل' : 'نهار';
    }
};

// Load saved dark mode preference
const savedDarkMode = localStorage.getItem('darkMode');
const isSavedDarkMode = savedDarkMode === 'true';
document.body.classList.toggle('dark-mode', isSavedDarkMode);
syncDrawerDarkModeState(isSavedDarkMode);

// Toggle dark mode
if (drawerDarkModeToggle) {
    drawerDarkModeToggle.addEventListener('click', () => {
        drawerDarkModeToggle.classList.add('is-animating');
        document.body.classList.toggle('dark-mode');
        const isDarkMode = document.body.classList.contains('dark-mode');

        syncDrawerDarkModeState(isDarkMode);
        localStorage.setItem('darkMode', String(isDarkMode));

        window.setTimeout(() => {
            drawerDarkModeToggle.classList.remove('is-animating');
        }, 450);
    });
}

// Header scroll effect
const header = document.querySelector('.tw-header');
if (header) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
}

// Image lazy loading with fade-in effect
document.addEventListener('DOMContentLoaded', () => {
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.addEventListener('load', () => {
                    img.classList.add('loaded');
                });
                observer.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// Add staggered animation to elements when they come into view
const animateOnScroll = () => {
    const elements = document.querySelectorAll('.tw-card, .tw-widget, .tw-section-title');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, {
        threshold: 0.1
    });

    elements.forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
};

document.addEventListener('DOMContentLoaded', animateOnScroll);
