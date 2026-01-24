/* =====================================================
   LifeLine - Main JavaScript
   Organic Visualizations + GSAP ScrollTrigger
   ===================================================== */

// Wait for DOM and libraries to load
document.addEventListener('DOMContentLoaded', () => {
    initLoader();
    initNavigation();
    initTopoHero();
    initRelayCanvas();
    initScrollAnimations();
    initCounters();
    initParallax();
});

/* =====================================================
   Loader
   ===================================================== */
function initLoader() {
    const loader = document.getElementById('loader');

    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.classList.add('hidden');
            document.body.style.overflow = '';
        }, 2200);
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

    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;

        if (currentScroll > 100) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    }, { passive: true });

    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }

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
   Topographic Mountain Contour Hero Background
   Organic, hand-drawn feel - inspired by survey maps
   ===================================================== */
function initTopoHero() {
    const container = document.getElementById('heroCanvas');
    if (!container) return;

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    container.appendChild(canvas);

    let width, height;
    let mouseX = 0.5, mouseY = 0.5;
    let time = 0;

    // Mountain peaks - these form the basis of our topographic map
    const peaks = [];
    const numPeaks = 6;

    function generatePeaks() {
        peaks.length = 0;
        for (let i = 0; i < numPeaks; i++) {
            peaks.push({
                x: 0.15 + Math.random() * 0.7,
                y: 0.2 + Math.random() * 0.6,
                height: 0.3 + Math.random() * 0.7,
                spread: 0.15 + Math.random() * 0.2
            });
        }
    }

    function resize() {
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width * window.devicePixelRatio;
        canvas.height = height * window.devicePixelRatio;
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
        generatePeaks();
    }

    resize();
    window.addEventListener('resize', debounce(resize, 200));

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX / width;
        mouseY = e.clientY / height;
    });

    // Calculate elevation at a point based on all peaks
    function getElevation(x, y, t) {
        let elevation = 0;

        peaks.forEach((peak, i) => {
            // Add subtle movement to peaks
            const px = peak.x + Math.sin(t * 0.5 + i) * 0.02;
            const py = peak.y + Math.cos(t * 0.3 + i * 1.5) * 0.015;

            const dx = x - px;
            const dy = y - py;
            const dist = Math.sqrt(dx * dx + dy * dy);

            // Gaussian-like falloff for natural mountain shape
            const contribution = peak.height * Math.exp(-(dist * dist) / (2 * peak.spread * peak.spread));
            elevation += contribution;
        });

        // Add some noise for natural terrain feel
        const noiseX = Math.sin(x * 15 + t * 0.2) * 0.02;
        const noiseY = Math.cos(y * 12 + t * 0.15) * 0.02;
        elevation += noiseX + noiseY;

        // Mouse influence - subtle terrain deformation
        const mouseDist = Math.sqrt((x - mouseX) ** 2 + (y - mouseY) ** 2);
        elevation += 0.1 * Math.exp(-mouseDist * mouseDist * 8);

        return elevation;
    }

    // Draw a single contour line using marching squares approximation
    function drawContourLine(level, t) {
        const resolution = 80;
        const cellW = width / resolution;
        const cellH = height / resolution;

        ctx.beginPath();

        for (let i = 0; i < resolution; i++) {
            for (let j = 0; j < resolution; j++) {
                const x = i / resolution;
                const y = j / resolution;

                // Sample elevation at four corners
                const e00 = getElevation(x, y, t);
                const e10 = getElevation(x + 1 / resolution, y, t);
                const e01 = getElevation(x, y + 1 / resolution, t);
                const e11 = getElevation(x + 1 / resolution, y + 1 / resolution, t);

                // Determine which corners are above the contour level
                const b00 = e00 > level ? 1 : 0;
                const b10 = e10 > level ? 1 : 0;
                const b01 = e01 > level ? 1 : 0;
                const b11 = e11 > level ? 1 : 0;

                const caseIndex = b00 + b10 * 2 + b01 * 4 + b11 * 8;

                // Skip cases with no contour crossing
                if (caseIndex === 0 || caseIndex === 15) continue;

                const px = i * cellW;
                const py = j * cellH;

                // Draw line segments based on marching squares case
                const points = getMarchingSquaresPoints(caseIndex, px, py, cellW, cellH, e00, e10, e01, e11, level);

                if (points.length >= 2) {
                    ctx.moveTo(points[0].x, points[0].y);
                    ctx.lineTo(points[1].x, points[1].y);
                }
                if (points.length >= 4) {
                    ctx.moveTo(points[2].x, points[2].y);
                    ctx.lineTo(points[3].x, points[3].y);
                }
            }
        }

        ctx.stroke();
    }

    function getMarchingSquaresPoints(caseIndex, x, y, w, h, e00, e10, e01, e11, level) {
        const points = [];

        // Interpolation helpers
        const interpX = (e1, e2) => (level - e1) / (e2 - e1);

        // Edge midpoints with interpolation
        const top = { x: x + w * interpX(e00, e10), y: y };
        const bottom = { x: x + w * interpX(e01, e11), y: y + h };
        const left = { x: x, y: y + h * interpX(e00, e01) };
        const right = { x: x + w, y: y + h * interpX(e10, e11) };

        // Marching squares lookup
        switch (caseIndex) {
            case 1: case 14: points.push(left, top); break;
            case 2: case 13: points.push(top, right); break;
            case 3: case 12: points.push(left, right); break;
            case 4: case 11: points.push(bottom, left); break;
            case 5: points.push(left, top, bottom, right); break;
            case 6: case 9: points.push(top, bottom); break;
            case 7: case 8: points.push(bottom, right); break;
            case 10: points.push(top, left, bottom, right); break;
        }

        return points;
    }

    function draw() {
        requestAnimationFrame(draw);
        time += 0.008;

        // Clear with slight fade for subtle trails
        ctx.fillStyle = 'rgba(10, 10, 10, 0.15)';
        ctx.fillRect(0, 0, width, height);

        // Draw contour lines at different elevations
        const numContours = 12;

        for (let i = 0; i < numContours; i++) {
            const level = 0.1 + (i / numContours) * 0.8;
            const brightness = 25 + (i / numContours) * 35;
            const alpha = 0.3 + (i / numContours) * 0.4;

            ctx.strokeStyle = `rgba(${brightness}, ${brightness}, ${brightness}, ${alpha})`;
            ctx.lineWidth = i === numContours - 1 ? 1.5 : 0.8;

            drawContourLine(level, time);
        }

        // Draw signal nodes on the terrain
        drawSignalNodes(time);
    }

    // Signal nodes that sit on the topographic map
    const signalNodes = [
        { baseX: 0.2, baseY: 0.3 },
        { baseX: 0.35, baseY: 0.55 },
        { baseX: 0.5, baseY: 0.35 },
        { baseX: 0.65, baseY: 0.6 },
        { baseX: 0.8, baseY: 0.4 },
    ];

    let activeSignal = { from: 0, to: 1, progress: 0 };

    function drawSignalNodes(t) {
        // Update signal
        activeSignal.progress += 0.008;
        if (activeSignal.progress >= 1) {
            activeSignal.from = activeSignal.to;
            activeSignal.to = (activeSignal.to + 1) % signalNodes.length;
            activeSignal.progress = 0;
        }

        // Draw connections
        ctx.strokeStyle = 'rgba(220, 38, 38, 0.15)';
        ctx.lineWidth = 1;

        for (let i = 0; i < signalNodes.length - 1; i++) {
            const n1 = signalNodes[i];
            const n2 = signalNodes[i + 1];

            ctx.beginPath();
            ctx.setLineDash([4, 8]);
            ctx.moveTo(n1.baseX * width, n1.baseY * height);
            ctx.lineTo(n2.baseX * width, n2.baseY * height);
            ctx.stroke();
        }
        ctx.setLineDash([]);

        // Draw nodes
        signalNodes.forEach((node, i) => {
            const x = node.baseX * width;
            const y = node.baseY * height;
            const pulse = Math.sin(t * 3 + i * 1.2) * 0.5 + 0.5;

            // Outer glow
            ctx.beginPath();
            ctx.arc(x, y, 12 + pulse * 6, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(220, 38, 38, ${0.1 + pulse * 0.1})`;
            ctx.fill();

            // Core
            ctx.beginPath();
            ctx.arc(x, y, 4, 0, Math.PI * 2);
            ctx.fillStyle = '#dc2626';
            ctx.fill();
        });

        // Draw traveling signal
        const from = signalNodes[activeSignal.from];
        const to = signalNodes[activeSignal.to];
        const sigX = from.baseX + (to.baseX - from.baseX) * activeSignal.progress;
        const sigY = from.baseY + (to.baseY - from.baseY) * activeSignal.progress;

        ctx.beginPath();
        ctx.arc(sigX * width, sigY * height, 6, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(220, 38, 38, 0.9)';
        ctx.fill();

        // Signal trail
        ctx.beginPath();
        ctx.arc(sigX * width, sigY * height, 12, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(220, 38, 38, 0.3)';
        ctx.fill();
    }

    draw();
}

/* =====================================================
   Relay Network Canvas - How It Works Step 2
   Shows signal hopping between mountain stations
   ===================================================== */
function initRelayCanvas() {
    const canvas = document.getElementById('relayCanvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const container = canvas.parentElement;

    function resize() {
        canvas.width = container.clientWidth * window.devicePixelRatio;
        canvas.height = container.clientHeight * window.devicePixelRatio;
        canvas.style.width = container.clientWidth + 'px';
        canvas.style.height = container.clientHeight + 'px';
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
    }
    resize();
    window.addEventListener('resize', debounce(resize, 200));

    const width = () => container.clientWidth;
    const height = () => container.clientHeight;

    // Define relay stations as mountain positions
    const stations = [
        { x: 0.1, y: 0.7, label: 'Origin' },
        { x: 0.3, y: 0.4, label: 'Relay 1' },
        { x: 0.5, y: 0.6, label: 'Relay 2' },
        { x: 0.7, y: 0.35, label: 'Relay 3' },
        { x: 0.9, y: 0.5, label: 'Gateway' },
    ];

    // Signal traveling through the relay chain
    let signal = {
        currentHop: 0,
        progress: 0,
        active: true,
        trail: []
    };

    let time = 0;

    function drawMountainSilhouette() {
        const w = width();
        const h = height();

        ctx.beginPath();
        ctx.moveTo(0, h);

        // Draw jagged mountain silhouette
        const points = [
            { x: 0, y: 0.85 },
            { x: 0.08, y: 0.7 },
            { x: 0.15, y: 0.75 },
            { x: 0.22, y: 0.5 },
            { x: 0.28, y: 0.55 },
            { x: 0.35, y: 0.35 },
            { x: 0.42, y: 0.45 },
            { x: 0.48, y: 0.55 },
            { x: 0.55, y: 0.4 },
            { x: 0.62, y: 0.5 },
            { x: 0.7, y: 0.3 },
            { x: 0.78, y: 0.42 },
            { x: 0.85, y: 0.38 },
            { x: 0.92, y: 0.55 },
            { x: 1, y: 0.6 },
        ];

        points.forEach(p => {
            ctx.lineTo(p.x * w, p.y * h);
        });

        ctx.lineTo(w, h);
        ctx.closePath();

        // Mountain gradient
        const gradient = ctx.createLinearGradient(0, 0, 0, h);
        gradient.addColorStop(0, 'rgba(30, 30, 30, 0.8)');
        gradient.addColorStop(1, 'rgba(20, 20, 20, 0.4)');
        ctx.fillStyle = gradient;
        ctx.fill();
    }

    function drawStation(station, index, t) {
        const w = width();
        const h = height();
        const x = station.x * w;
        const y = station.y * h;

        const isActive = index <= signal.currentHop;
        const pulse = Math.sin(t * 2 + index) * 0.5 + 0.5;

        // Tower structure
        ctx.strokeStyle = isActive ? '#dc2626' : 'rgba(100, 100, 100, 0.6)';
        ctx.lineWidth = 2;

        // Tower base
        ctx.beginPath();
        ctx.moveTo(x - 8, y);
        ctx.lineTo(x, y - 25);
        ctx.lineTo(x + 8, y);
        ctx.stroke();

        // Cross beams
        ctx.beginPath();
        ctx.moveTo(x - 5, y - 8);
        ctx.lineTo(x + 5, y - 8);
        ctx.moveTo(x - 3, y - 16);
        ctx.lineTo(x + 3, y - 16);
        ctx.stroke();

        // Signal rings when active
        if (isActive) {
            for (let r = 0; r < 3; r++) {
                const radius = 10 + r * 12 + pulse * 5;
                const alpha = (1 - r / 3) * 0.3 * (0.5 + pulse * 0.5);

                ctx.beginPath();
                ctx.arc(x, y - 25, radius, -Math.PI * 0.75, -Math.PI * 0.25);
                ctx.strokeStyle = `rgba(220, 38, 38, ${alpha})`;
                ctx.lineWidth = 1.5;
                ctx.stroke();
            }
        }

        // Label
        ctx.fillStyle = isActive ? 'rgba(220, 38, 38, 0.9)' : 'rgba(150, 150, 150, 0.7)';
        ctx.font = '10px "Space Grotesk", sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText(station.label, x, y + 15);
    }

    function drawConnections(t) {
        const w = width();
        const h = height();

        for (let i = 0; i < stations.length - 1; i++) {
            const from = stations[i];
            const to = stations[i + 1];
            const isActive = i < signal.currentHop;

            ctx.beginPath();
            ctx.setLineDash([6, 6]);
            ctx.moveTo(from.x * w, from.y * h - 25);
            ctx.lineTo(to.x * w, to.y * h - 25);
            ctx.strokeStyle = isActive ? 'rgba(220, 38, 38, 0.4)' : 'rgba(80, 80, 80, 0.3)';
            ctx.lineWidth = 1;
            ctx.stroke();
            ctx.setLineDash([]);
        }
    }

    function drawSignal(t) {
        if (!signal.active) return;

        const w = width();
        const h = height();

        const from = stations[signal.currentHop];
        const to = stations[signal.currentHop + 1];

        if (!to) {
            // Reset signal
            signal.currentHop = 0;
            signal.progress = 0;
            signal.trail = [];
            return;
        }

        const x = from.x + (to.x - from.x) * signal.progress;
        const y = (from.y + (to.y - from.y) * signal.progress) - 0.07; // Arc above the line

        // Add to trail
        signal.trail.push({ x, y, alpha: 1 });
        if (signal.trail.length > 15) signal.trail.shift();

        // Draw trail
        signal.trail.forEach((pt, i) => {
            const alpha = (i / signal.trail.length) * 0.6;
            ctx.beginPath();
            ctx.arc(pt.x * w, pt.y * h, 3 + (i / signal.trail.length) * 2, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(220, 38, 38, ${alpha})`;
            ctx.fill();
        });

        // Main signal
        ctx.beginPath();
        ctx.arc(x * w, y * h, 6, 0, Math.PI * 2);
        ctx.fillStyle = '#dc2626';
        ctx.fill();

        // Glow
        ctx.beginPath();
        ctx.arc(x * w, y * h, 12, 0, Math.PI * 2);
        ctx.fillStyle = 'rgba(220, 38, 38, 0.3)';
        ctx.fill();

        // Update
        signal.progress += 0.012;
        if (signal.progress >= 1) {
            signal.currentHop++;
            signal.progress = 0;
            signal.trail = [];
        }
    }

    function draw() {
        requestAnimationFrame(draw);
        time += 0.016;

        const w = width();
        const h = height();

        // Clear
        ctx.fillStyle = 'rgba(15, 15, 15, 1)';
        ctx.fillRect(0, 0, w, h);

        // Draw elements
        drawMountainSilhouette();
        drawConnections(time);
        stations.forEach((s, i) => drawStation(s, i, time));
        drawSignal(time);
    }

    draw();
}

/* =====================================================
   Scroll Animations with GSAP ScrollTrigger
   ===================================================== */
function initScrollAnimations() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    // Section headers
    gsap.utils.toArray('.section-header').forEach(header => {
        gsap.from(header.children, {
            scrollTrigger: {
                trigger: header,
                start: 'top 80%',
                toggleActions: 'play none none reverse'
            },
            y: 40,
            opacity: 0,
            duration: 0.8,
            stagger: 0.15,
            ease: 'power3.out'
        });
    });

    // Problem stats
    gsap.utils.toArray('.problem-stat').forEach((stat, i) => {
        gsap.from(stat, {
            scrollTrigger: {
                trigger: stat,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            },
            x: -40,
            opacity: 0,
            duration: 0.8,
            delay: i * 0.15,
            ease: 'power3.out'
        });
    });

    // Flow steps
    gsap.utils.toArray('.flow-step').forEach((step, i) => {
        const isOdd = i % 2 === 0;

        gsap.from(step.querySelector('.step-visual'), {
            scrollTrigger: {
                trigger: step,
                start: 'top 75%',
                toggleActions: 'play none none reverse'
            },
            x: isOdd ? 60 : -60,
            opacity: 0,
            duration: 1,
            ease: 'power3.out'
        });

        gsap.from(step.querySelector('.step-content'), {
            scrollTrigger: {
                trigger: step,
                start: 'top 75%',
                toggleActions: 'play none none reverse'
            },
            x: isOdd ? -60 : 60,
            opacity: 0,
            duration: 1,
            delay: 0.2,
            ease: 'power3.out'
        });
    });

    // Impact cards
    gsap.utils.toArray('.impact-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            },
            y: 40,
            opacity: 0,
            duration: 0.6,
            delay: i * 0.1,
            ease: 'power3.out'
        });
    });

    // Tech cards
    gsap.utils.toArray('.tech-card').forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: 'top 85%',
                toggleActions: 'play none none reverse'
            },
            y: 40,
            opacity: 0,
            duration: 0.6,
            delay: i * 0.08,
            ease: 'power3.out'
        });
    });

    // CTA section
    gsap.from('.cta-content', {
        scrollTrigger: {
            trigger: '.section-cta',
            start: 'top 70%',
            toggleActions: 'play none none reverse'
        },
        y: 60,
        opacity: 0,
        duration: 1,
        ease: 'power3.out'
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
    const duration = 2000;
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
   Parallax Effects
   ===================================================== */
function initParallax() {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.to('.hero-content', {
        scrollTrigger: {
            trigger: '.hero',
            start: 'top top',
            end: 'bottom top',
            scrub: 1
        },
        y: 100,
        opacity: 0.5,
        ease: 'none'
    });

    gsap.to('.hero-illustration', {
        scrollTrigger: {
            trigger: '.hero',
            start: 'top top',
            end: 'bottom top',
            scrub: 1
        },
        y: 150,
        ease: 'none'
    });

    gsap.to('.problem-visual', {
        scrollTrigger: {
            trigger: '.section-problem',
            start: 'top bottom',
            end: 'bottom top',
            scrub: 1
        },
        y: -50,
        ease: 'none'
    });

    gsap.utils.toArray('.section').forEach(section => {
        gsap.to(section, {
            scrollTrigger: {
                trigger: section,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1
            },
            backgroundPosition: '50% 100%',
            ease: 'none'
        });
    });
}

/* =====================================================
   Utility: Debounce
   ===================================================== */
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

/* =====================================================
   Accessibility: Reduce Motion
   ===================================================== */
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

if (prefersReducedMotion.matches) {
    if (typeof gsap !== 'undefined') {
        gsap.globalTimeline.timeScale(0);
    }

    document.documentElement.style.setProperty('--transition-fast', '0ms');
    document.documentElement.style.setProperty('--transition-base', '0ms');
    document.documentElement.style.setProperty('--transition-slow', '0ms');
}
