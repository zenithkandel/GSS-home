/* ========================================
   LifeLine - Minimal Flat JS
   ======================================== */

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initNavScroll();
    initSmoothScroll();
    initCounters();
    initImpactBars();
});

/**
 * Theme toggle functionality
 */
function initTheme() {
    const toggle = document.getElementById('themeToggle');
    const html = document.documentElement;

    // Check for saved theme preference or default to dark
    const savedTheme = localStorage.getItem('theme') || 'dark';
    html.setAttribute('data-theme', savedTheme);

    if (toggle) {
        toggle.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }
}

/**
 * Navigation scroll effect
 */
function initNavScroll() {
    const nav = document.querySelector('.nav');
    if (!nav) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    }, { passive: true });
}

/**
 * Smooth scroll for anchor links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);

            if (target) {
                const offset = 64;
                const position = target.getBoundingClientRect().top + window.scrollY - offset;

                window.scrollTo({
                    top: position,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Animate counters when visible
 */
function initCounters() {
    const counters = document.querySelectorAll('.stat-number, .problem-stat, .impact-number');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                animateValue(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
}

/**
 * Animate a number value
 */
function animateValue(element) {
    const text = element.textContent;
    const match = text.match(/^([<>]?)(\d+\.?\d*)(.*)/);

    if (!match) return;

    const prefix = match[1];
    const target = parseFloat(match[2]);
    const suffix = match[3];
    const duration = 1500;
    const start = performance.now();
    const isDecimal = text.includes('.');

    function update(currentTime) {
        const elapsed = currentTime - start;
        const progress = Math.min(elapsed / duration, 1);

        // Ease out cubic
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = target * eased;

        if (isDecimal) {
            element.textContent = prefix + current.toFixed(1) + suffix;
        } else {
            element.textContent = prefix + Math.floor(current) + suffix;
        }

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            element.textContent = text;
        }
    }

    requestAnimationFrame(update);
}

/**
 * Animate impact bars
 */
function initImpactBars() {
    const bars = document.querySelectorAll('.impact-fill');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                const width = entry.target.style.width;
                entry.target.style.width = '0';

                setTimeout(() => {
                    entry.target.style.transition = 'width 1s ease-out';
                    entry.target.style.width = width;
                }, 100);
            }
        });
    }, { threshold: 0.3 });

    bars.forEach(bar => observer.observe(bar));
}
