/* =====================================================
   LifeLine - Main JavaScript
   Three.js + GSAP + ScrollTrigger
   ===================================================== */

// Wait for DOM and libraries to load
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all modules
    initLoader();
    initCursor();
    initNavigation();
    initThreeHero();
    initMeshCanvas();
    initScrollAnimations();
    initCounters();
    initParallax();
});

/* =====================================================
   Loader
   ===================================================== */
function initLoader() {
    const loader = document.getElementById('loader');

    // Simulate loading time for resources
    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.classList.add('hidden');
            document.body.style.overflow = '';
        }, 2200);
    });

    // Prevent scrolling during load
    document.body.style.overflow = 'hidden';
}

/* =====================================================
   Custom Cursor
   ===================================================== */
function initCursor() {
    const cursor = document.getElementById('cursor');
    const follower = document.getElementById('cursorFollower');

    if (!cursor || !follower) return;

    let mouseX = 0, mouseY = 0;
    let cursorX = 0, cursorY = 0;
    let followerX = 0, followerY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    // Smooth cursor animation
    function animateCursor() {
        // Cursor follows instantly
        cursorX += (mouseX - cursorX) * 0.5;
        cursorY += (mouseY - cursorY) * 0.5;
        cursor.style.left = cursorX + 'px';
        cursor.style.top = cursorY + 'px';

        // Follower has lag
        followerX += (mouseX - followerX) * 0.15;
        followerY += (mouseY - followerY) * 0.15;
        follower.style.left = followerX + 'px';
        follower.style.top = followerY + 'px';

        requestAnimationFrame(animateCursor);
    }
    animateCursor();

    // Hover effects
    const interactiveElements = document.querySelectorAll('a, button, .btn');
    interactiveElements.forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursor.classList.add('active');
            follower.classList.add('active');
        });
        el.addEventListener('mouseleave', () => {
            cursor.classList.remove('active');
            follower.classList.remove('active');
        });
    });
}

/* =====================================================
   Navigation
   ===================================================== */
function initNavigation() {
    const nav = document.getElementById('nav');
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');

    // Scroll effect
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

    // Mobile toggle
    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }

    // Smooth scroll for nav links
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

                // Close mobile menu
                navToggle?.classList.remove('active');
                navLinks?.classList.remove('active');
            }
        });
    });
}

/* =====================================================
   Three.js Hero - 3D Mountain Terrain with Nodes
   ===================================================== */
function initThreeHero() {
    const container = document.getElementById('heroCanvas');
    if (!container || typeof THREE === 'undefined') return;

    // Scene setup
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.1, 1000);

    const renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true
    });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    // Materials
    const nodeMaterial = new THREE.MeshBasicMaterial({ color: 0xdc2626 });
    const lineMaterial = new THREE.LineBasicMaterial({
        color: 0x404040,
        transparent: true,
        opacity: 0.3
    });
    const signalMaterial = new THREE.MeshBasicMaterial({
        color: 0xdc2626,
        transparent: true,
        opacity: 0.8
    });

    // Create terrain (simplified mountain points)
    const terrainPoints = [];
    const gridSize = 20;
    const gridSpacing = 2;

    for (let i = 0; i < gridSize; i++) {
        for (let j = 0; j < gridSize; j++) {
            const x = (i - gridSize / 2) * gridSpacing;
            const z = (j - gridSize / 2) * gridSpacing;

            // Create mountain-like height using multiple sine waves
            const height =
                Math.sin(i * 0.3) * Math.cos(j * 0.3) * 3 +
                Math.sin(i * 0.15 + 1) * 2 +
                Math.cos(j * 0.2) * 2 +
                Math.random() * 0.5;

            terrainPoints.push(new THREE.Vector3(x, height, z));
        }
    }

    // Create dots for terrain
    const dotGeometry = new THREE.SphereGeometry(0.05, 8, 8);
    const dotMaterial = new THREE.MeshBasicMaterial({ color: 0x2a2a2a });

    terrainPoints.forEach(point => {
        const dot = new THREE.Mesh(dotGeometry, dotMaterial);
        dot.position.copy(point);
        scene.add(dot);
    });

    // Create grid lines
    const lineGeometry = new THREE.BufferGeometry();
    const linePositions = [];

    for (let i = 0; i < gridSize; i++) {
        for (let j = 0; j < gridSize - 1; j++) {
            const idx = i * gridSize + j;
            const p1 = terrainPoints[idx];
            const p2 = terrainPoints[idx + 1];
            linePositions.push(p1.x, p1.y, p1.z, p2.x, p2.y, p2.z);
        }
    }

    for (let i = 0; i < gridSize - 1; i++) {
        for (let j = 0; j < gridSize; j++) {
            const idx = i * gridSize + j;
            const p1 = terrainPoints[idx];
            const p2 = terrainPoints[idx + gridSize];
            linePositions.push(p1.x, p1.y, p1.z, p2.x, p2.y, p2.z);
        }
    }

    lineGeometry.setAttribute('position', new THREE.Float32BufferAttribute(linePositions, 3));
    const grid = new THREE.LineSegments(lineGeometry, lineMaterial);
    scene.add(grid);

    // Create network nodes (LoRa devices)
    const nodes = [];
    const nodePositions = [
        { x: -8, z: -6 },
        { x: -4, z: 2 },
        { x: 0, z: -4 },
        { x: 4, z: 0 },
        { x: 8, z: -2 },
        { x: -2, z: 6 },
        { x: 6, z: 6 },
        { x: -6, z: 4 },
    ];

    const nodeGeometry = new THREE.SphereGeometry(0.2, 16, 16);

    nodePositions.forEach((pos, i) => {
        // Find height at this position
        const terrainIndex = Math.floor((pos.x / gridSpacing + gridSize / 2)) * gridSize +
            Math.floor((pos.z / gridSpacing + gridSize / 2));
        const height = terrainPoints[Math.min(terrainIndex, terrainPoints.length - 1)]?.y || 2;

        const node = new THREE.Mesh(nodeGeometry, nodeMaterial.clone());
        node.position.set(pos.x, height + 1.5, pos.z);
        scene.add(node);
        nodes.push(node);

        // Add glow ring
        const ringGeometry = new THREE.RingGeometry(0.3, 0.35, 32);
        const ringMaterial = new THREE.MeshBasicMaterial({
            color: 0xdc2626,
            transparent: true,
            opacity: 0.5,
            side: THREE.DoubleSide
        });
        const ring = new THREE.Mesh(ringGeometry, ringMaterial);
        ring.position.copy(node.position);
        ring.rotation.x = -Math.PI / 2;
        ring.userData = { baseOpacity: 0.5, phase: Math.random() * Math.PI * 2 };
        scene.add(ring);
        nodes.push(ring);
    });

    // Create connections between nearby nodes
    const connectionLines = [];
    const actualNodes = nodes.filter((_, i) => i % 2 === 0); // Only spheres, not rings

    for (let i = 0; i < actualNodes.length; i++) {
        for (let j = i + 1; j < actualNodes.length; j++) {
            const dist = actualNodes[i].position.distanceTo(actualNodes[j].position);
            if (dist < 12) {
                const connectionGeometry = new THREE.BufferGeometry().setFromPoints([
                    actualNodes[i].position,
                    actualNodes[j].position
                ]);
                const connectionMaterial = new THREE.LineBasicMaterial({
                    color: 0xdc2626,
                    transparent: true,
                    opacity: 0.2
                });
                const line = new THREE.Line(connectionGeometry, connectionMaterial);
                scene.add(line);
                connectionLines.push(line);
            }
        }
    }

    // Signal particles
    const signals = [];

    function createSignal() {
        if (actualNodes.length < 2) return;

        const startIdx = Math.floor(Math.random() * actualNodes.length);
        let endIdx = Math.floor(Math.random() * actualNodes.length);
        while (endIdx === startIdx) {
            endIdx = Math.floor(Math.random() * actualNodes.length);
        }

        const signalGeometry = new THREE.SphereGeometry(0.1, 8, 8);
        const signal = new THREE.Mesh(signalGeometry, signalMaterial.clone());
        signal.position.copy(actualNodes[startIdx].position);
        signal.userData = {
            start: actualNodes[startIdx].position.clone(),
            end: actualNodes[endIdx].position.clone(),
            progress: 0,
            speed: 0.008 + Math.random() * 0.005
        };
        scene.add(signal);
        signals.push(signal);
    }

    // Create initial signals
    for (let i = 0; i < 3; i++) {
        setTimeout(() => createSignal(), i * 500);
    }

    // Camera position
    camera.position.set(0, 15, 25);
    camera.lookAt(0, 0, 0);

    // Mouse interaction
    let mouseX = 0, mouseY = 0;
    let targetRotationX = 0, targetRotationY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = (e.clientX / window.innerWidth) * 2 - 1;
        mouseY = (e.clientY / window.innerHeight) * 2 - 1;
    });

    // Animation loop
    let time = 0;
    function animate() {
        requestAnimationFrame(animate);
        time += 0.01;

        // Rotate scene based on mouse
        targetRotationY = mouseX * 0.2;
        targetRotationX = mouseY * 0.1;

        scene.rotation.y += (targetRotationY - scene.rotation.y) * 0.02;
        scene.rotation.x += (targetRotationX - scene.rotation.x) * 0.02;

        // Animate node rings (pulse effect)
        nodes.forEach((node, i) => {
            if (i % 2 === 1) { // Rings
                const phase = node.userData.phase;
                node.material.opacity = node.userData.baseOpacity * (0.5 + 0.5 * Math.sin(time * 2 + phase));
                node.scale.setScalar(1 + 0.1 * Math.sin(time * 2 + phase));
            }
        });

        // Animate signals
        signals.forEach((signal, index) => {
            signal.userData.progress += signal.userData.speed;

            if (signal.userData.progress >= 1) {
                // Reset signal
                scene.remove(signal);
                signals.splice(index, 1);
                setTimeout(createSignal, Math.random() * 1000);
            } else {
                // Lerp position
                signal.position.lerpVectors(
                    signal.userData.start,
                    signal.userData.end,
                    signal.userData.progress
                );

                // Fade out at end
                signal.material.opacity = signal.userData.progress < 0.8 ? 0.8 : (1 - signal.userData.progress) * 4;
            }
        });

        // Pulse connection lines
        connectionLines.forEach((line, i) => {
            line.material.opacity = 0.1 + 0.1 * Math.sin(time + i);
        });

        renderer.render(scene, camera);
    }
    animate();

    // Resize handler
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });
}

/* =====================================================
   Mesh Canvas - 2D Network Visualization
   ===================================================== */
function initMeshCanvas() {
    const canvas = document.getElementById('meshCanvas');
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
    window.addEventListener('resize', resize);

    // Nodes
    const nodes = [];
    const numNodes = 8;

    for (let i = 0; i < numNodes; i++) {
        nodes.push({
            x: 50 + Math.random() * (container.clientWidth - 100),
            y: 50 + Math.random() * (container.clientHeight - 100),
            vx: (Math.random() - 0.5) * 0.5,
            vy: (Math.random() - 0.5) * 0.5,
            radius: 6,
            pulsePhase: Math.random() * Math.PI * 2
        });
    }

    // Signals traveling between nodes
    const signals = [];

    function createSignal() {
        if (nodes.length < 2) return;
        const startIdx = Math.floor(Math.random() * nodes.length);
        let endIdx = Math.floor(Math.random() * nodes.length);
        while (endIdx === startIdx) endIdx = Math.floor(Math.random() * nodes.length);

        signals.push({
            startNode: startIdx,
            endNode: endIdx,
            progress: 0,
            speed: 0.01 + Math.random() * 0.01
        });
    }

    // Create initial signals
    for (let i = 0; i < 3; i++) {
        setTimeout(createSignal, i * 300);
    }

    let time = 0;

    function animate() {
        requestAnimationFrame(animate);
        time += 0.02;

        const width = container.clientWidth;
        const height = container.clientHeight;

        ctx.clearRect(0, 0, width, height);

        // Update node positions
        nodes.forEach(node => {
            node.x += node.vx;
            node.y += node.vy;

            // Bounce off edges
            if (node.x < 50 || node.x > width - 50) node.vx *= -1;
            if (node.y < 50 || node.y > height - 50) node.vy *= -1;

            // Keep in bounds
            node.x = Math.max(50, Math.min(width - 50, node.x));
            node.y = Math.max(50, Math.min(height - 50, node.y));
        });

        // Draw connections
        ctx.strokeStyle = 'rgba(60, 60, 60, 0.3)';
        ctx.lineWidth = 1;

        for (let i = 0; i < nodes.length; i++) {
            for (let j = i + 1; j < nodes.length; j++) {
                const dx = nodes[j].x - nodes[i].x;
                const dy = nodes[j].y - nodes[i].y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < 200) {
                    const opacity = (1 - dist / 200) * 0.3;
                    ctx.strokeStyle = `rgba(80, 80, 80, ${opacity})`;
                    ctx.beginPath();
                    ctx.moveTo(nodes[i].x, nodes[i].y);
                    ctx.lineTo(nodes[j].x, nodes[j].y);
                    ctx.stroke();
                }
            }
        }

        // Draw and update signals
        signals.forEach((signal, idx) => {
            signal.progress += signal.speed;

            if (signal.progress >= 1) {
                signals.splice(idx, 1);
                setTimeout(createSignal, Math.random() * 500);
                return;
            }

            const start = nodes[signal.startNode];
            const end = nodes[signal.endNode];
            const x = start.x + (end.x - start.x) * signal.progress;
            const y = start.y + (end.y - start.y) * signal.progress;

            // Signal glow
            const gradient = ctx.createRadialGradient(x, y, 0, x, y, 15);
            gradient.addColorStop(0, 'rgba(220, 38, 38, 0.8)');
            gradient.addColorStop(1, 'rgba(220, 38, 38, 0)');

            ctx.fillStyle = gradient;
            ctx.beginPath();
            ctx.arc(x, y, 15, 0, Math.PI * 2);
            ctx.fill();

            // Signal core
            ctx.fillStyle = '#dc2626';
            ctx.beginPath();
            ctx.arc(x, y, 4, 0, Math.PI * 2);
            ctx.fill();
        });

        // Draw nodes
        nodes.forEach((node, i) => {
            const pulse = 0.5 + 0.5 * Math.sin(time * 2 + node.pulsePhase);

            // Outer ring
            ctx.strokeStyle = `rgba(220, 38, 38, ${0.2 + pulse * 0.3})`;
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.arc(node.x, node.y, node.radius + 4 + pulse * 4, 0, Math.PI * 2);
            ctx.stroke();

            // Node fill
            ctx.fillStyle = '#dc2626';
            ctx.beginPath();
            ctx.arc(node.x, node.y, node.radius, 0, Math.PI * 2);
            ctx.fill();

            // Node highlight
            ctx.fillStyle = 'rgba(255, 255, 255, 0.5)';
            ctx.beginPath();
            ctx.arc(node.x - 2, node.y - 2, 2, 0, Math.PI * 2);
            ctx.fill();
        });
    }

    animate();
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

    // Hero content parallax
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

    // Hero illustration parallax
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

    // Problem visual parallax
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

    // Section backgrounds subtle movement
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
    // Disable GSAP animations
    if (typeof gsap !== 'undefined') {
        gsap.globalTimeline.timeScale(0);
    }

    // Disable CSS animations
    document.documentElement.style.setProperty('--transition-fast', '0ms');
    document.documentElement.style.setProperty('--transition-base', '0ms');
    document.documentElement.style.setProperty('--transition-slow', '0ms');
}
