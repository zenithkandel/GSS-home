/* =====================================================
   LifeLine - Main JavaScript
   Clean, performant animations with GSAP
   ===================================================== */

document.addEventListener('DOMContentLoaded', () => {
    initLoader();
    initNavigation();
    initScrollAnimations();
    initCounters();
    initParallax();
});

/* =====================================================
   Loader - Quick and clean
   ===================================================== */
function initLoader() {
    const loader = document.getElementById('loader');
    
    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.classList.add('hidden');
            document.body.style.overflow = '';
        }, 1500);
    });

    document.body.style.overflow = 'hidden';
}

/* =====================================================
   Navigation
   ===================================================== */
function initNavigation() {
    const nav = document.getElementById('nav');
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 100) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    }, { passive: true });

    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);

            if (target) {
                const offset = 72;
                const position = target.getBoundingClientRect().top + window.scrollY - offset;

                window.scrollTo({
                    top: position,
                    behavior: 'smooth'
                });

                navToggle?.classList.remove('active');
                navLinks?.classList.remove('active');
            }
        });
    });
}

/* =====================================================
   Scroll Animations with GSAP ScrollTrigger
   ===================================================== */
function initScrollAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    // Hero content entrance
    gsap.from('.hero-content > *', {
        y: 30,
        opacity: 0,
        duration: 0.8,
        stagger: 0.1,
        ease: 'power2.out',
        delay: 1.6
    });

    // Section headers
    gsap.utils.toArray('.section-header').forEach(header => {
        gsap.from(header.children, {
            scrollTrigger: {
                trigger: header,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            },
            y: 30,
            opacity: 0,
            duration: 0.6,
            stagger: 0.1,
            ease: 'power2.out'
        });
    });

    // Problem stats
    gsap.utils.toArray('.problem-stat').forEach((stat, i) => {
        gsap.from(stat, {
            scrollTrigger: {
                trigger: stat,
                start: 'top 90%',
                toggleActions: 'play none none reverse'
            },
            x: -30,
            opacity: 0,
            duration: 0.5,
            delay: i * 0.1,
            ease: 'power2.out'
        });
    });

    // Flow steps
    gsap.utils.toArray('.flow-step').forEach((step, i) => {
        const isOdd = i % 2 === 0;
        
        gsap.from(step, {
            scrollTrigger: {
                trigger: step,
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            },
            y: 40,
            opacity: 0,
            duration: 0.7,
            ease: 'power2.out'
        });
    });

    // Impact cards
    gsap.utils.toArray('.impact-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 90%',
                toggleActions: 'play none none reverse'
            },
            y: 30,
            opacity: 0,
            duration: 0.5,
            delay: i * 0.08,
            ease: 'power2.out'
        });
    });

    // Tech cards
    gsap.utils.toArray('.tech-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 90%',
                toggleActions: 'play none none reverse'
            },
            y: 30,
            opacity: 0,
            duration: 0.5,
            delay: i * 0.05,
            ease: 'power2.out'
        });
    });

    // CTA section
    gsap.from('.cta-content', {
        scrollTrigger: {
            trigger: '.section-cta',
            start: 'top 75%',
            toggleActions: 'play none none reverse'
        },
        y: 40,
        opacity: 0,
        duration: 0.7,
        ease: 'power2.out'
    });
}

/* =====================================================
   Counter Animations
   ===================================================== */
function initCounters() {
    const counters = document.querySelectorAll('[data-count]');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                animateCounter(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
}

function animateCounter(element) {
    const target = parseFloat(element.dataset.count);
    const prefix = element.dataset.prefix || '';
    const suffix = element.dataset.suffix || '';
    const duration = 1500;
    const start = performance.now();
    const isDecimal = target % 1 !== 0;

    function update(currentTime) {
        const elapsed = currentTime - start;
        const progress = Math.min(elapsed / duration, 1);
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
            element.textContent = prefix + target + suffix;
        }
    }

    requestAnimationFrame(update);
}

/* =====================================================
   Parallax - Subtle, performant
   ===================================================== */
function initParallax() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    // Hero parallax
    gsap.to('.hero-content', {
        scrollTrigger: {
            trigger: '.hero',
            start: 'top top',
            end: 'bottom top',
            scrub: 1
        },
        y: 80,
        opacity: 0.3,
        ease: 'none'
    });
}

/* =====================================================
   Reduce Motion Support
   ===================================================== */
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    if (typeof gsap !== 'undefined') {
        gsap.globalTimeline.timeScale(0);
    }
}
