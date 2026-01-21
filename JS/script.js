/* ========================================
   LifeLine Landing Page - Interactive JS
   ======================================== */

document.addEventListener('DOMContentLoaded', () => {
    // Initialize all modules
    initCursorGlow();
    initNavigation();
    initScrollAnimations();
    initWorkflowProgress();
    initMeshAnimation();
    initSmoothScroll();
    initStatsCounter();
    initCardHover();
});

/**
 * Cursor Glow Effect - follows mouse with smooth interpolation
 */
function initCursorGlow() {
    const glow = document.querySelector('.cursor-glow');
    if (!glow) return;

    let mouseX = 0, mouseY = 0;
    let glowX = 0, glowY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateGlow() {
        // Smooth interpolation for fluid movement
        glowX += (mouseX - glowX) * 0.08;
        glowY += (mouseY - glowY) * 0.08;

        glow.style.left = glowX + 'px';
        glow.style.top = glowY + 'px';

        requestAnimationFrame(animateGlow);
    }

    animateGlow();
}

/**
 * Navigation - scroll effects and mobile toggle
 */
function initNavigation() {
    const nav = document.querySelector('.nav');
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    // Add scrolled class for styling changes
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav?.classList.add('scrolled');
        } else {
            nav?.classList.remove('scrolled');
        }
    });

    // Mobile menu toggle
    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Close mobile menu on link click
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    }

    // Update active nav link based on scroll position
    const sections = document.querySelectorAll('section[id]');

    window.addEventListener('scroll', () => {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            const sectionHeight = section.offsetHeight;

            if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
}

/**
 * Scroll Animations - Intersection Observer for fade-in effects
 */
function initScrollAnimations() {
    const elements = document.querySelectorAll('[data-animate]');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                // Get stagger delay from data attribute or use default
                const delay = parseInt(entry.target.dataset.delay) || 0;

                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, delay);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    elements.forEach(el => observer.observe(el));
}

/**
 * Workflow Progress Animation - sequential step highlighting
 */
function initWorkflowProgress() {
    const workflow = document.querySelector('.workflow');
    if (!workflow) return;

    const steps = workflow.querySelectorAll('.workflow-step');
    const progressBar = workflow.querySelector('.workflow-progress');
    let hasAnimated = false;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !hasAnimated) {
                hasAnimated = true;
                animateWorkflow();
            }
        });
    }, { threshold: 0.2 });

    observer.observe(workflow);

    function animateWorkflow() {
        steps.forEach((step, index) => {
            setTimeout(() => {
                step.classList.add('active');

                // Update progress bar height
                if (progressBar) {
                    const progress = ((index + 1) / steps.length) * 100;
                    progressBar.style.height = progress + '%';
                }
            }, index * 400);
        });
    }
}

/**
 * Mesh Diagram Animation - subtle parallax on mouse move
 */
function initMeshAnimation() {
    const meshDiagram = document.querySelector('.mesh-diagram');
    const heroVisual = document.querySelector('.hero-visual');
    if (!meshDiagram || !heroVisual) return;

    // Parallax effect on mouse move
    document.addEventListener('mousemove', (e) => {
        const moveX = (e.clientX - window.innerWidth / 2) * 0.015;
        const moveY = (e.clientY - window.innerHeight / 2) * 0.015;

        meshDiagram.style.transform = `translate(${moveX}px, ${moveY}px)`;
    });

    // Reset on mouse leave
    document.addEventListener('mouseleave', () => {
        meshDiagram.style.transform = 'translate(0, 0)';
    });
}

/**
 * Smooth Scroll - for all anchor links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            e.preventDefault();

            const target = document.querySelector(targetId);
            if (target) {
                const offset = 80; // Account for fixed nav
                const targetPosition = target.getBoundingClientRect().top + window.scrollY - offset;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Stats Counter Animation - count up numbers when visible
 */
function initStatsCounter() {
    const statValues = document.querySelectorAll('.stat-value');
    if (statValues.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');

                // Parse target value and suffix
                const text = entry.target.textContent;
                const match = text.match(/^([\d.]+)(.*)$/);

                if (match) {
                    const targetValue = parseFloat(match[1]);
                    const suffix = match[2] || '';

                    animateCounter(entry.target, targetValue, suffix);
                }
            }
        });
    }, { threshold: 0.5 });

    statValues.forEach(stat => observer.observe(stat));
}

/**
 * Counter Animation Helper
 */
function animateCounter(element, target, suffix = '', duration = 2000) {
    const start = 0;
    const startTime = performance.now();
    const isDecimal = target % 1 !== 0;

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Ease out quad for smooth deceleration
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = start + (target - start) * eased;

        // Format the number
        let formattedValue;
        if (isDecimal) {
            formattedValue = current.toFixed(1);
        } else if (target >= 1000) {
            formattedValue = Math.floor(current).toLocaleString();
        } else {
            formattedValue = Math.floor(current);
        }

        element.textContent = formattedValue + suffix;

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

/**
 * Card Hover Effects - enhanced hover states
 */
function initCardHover() {
    const cards = document.querySelectorAll('.problem-card, .tech-card');

    cards.forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.style.transition = 'transform 0.3s ease, border-color 0.3s ease';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transition = 'transform 0.5s ease, border-color 0.3s ease';
        });
    });
}

/**
 * Utility: Debounce function for performance
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Utility: Throttle function for scroll events
 */
function throttle(func, limit) {
    let inThrottle;
    return function (...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}
