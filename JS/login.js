/* =====================================================
   LifeLine - Login Page JavaScript
   ===================================================== */

document.addEventListener('DOMContentLoaded', () => {
    initBackgroundCanvas();
    initPasswordToggle();
    initFormValidation();
    initFormSubmission();
});

/* =====================================================
   Background Canvas - Subtle particle animation
   ===================================================== */
function initBackgroundCanvas() {
    const canvas = document.getElementById('bgCanvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    
    function resize() {
        canvas.width = window.innerWidth * window.devicePixelRatio;
        canvas.height = window.innerHeight * window.devicePixelRatio;
        canvas.style.width = window.innerWidth + 'px';
        canvas.style.height = window.innerHeight + 'px';
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
    }
    resize();
    window.addEventListener('resize', resize);
    
    // Particles
    const particles = [];
    const particleCount = 50;
    
    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * window.innerWidth,
            y: Math.random() * window.innerHeight,
            vx: (Math.random() - 0.5) * 0.3,
            vy: (Math.random() - 0.5) * 0.3,
            radius: Math.random() * 2 + 1,
            opacity: Math.random() * 0.3 + 0.1
        });
    }
    
    function animate() {
        requestAnimationFrame(animate);
        
        ctx.clearRect(0, 0, window.innerWidth, window.innerHeight);
        
        // Update and draw particles
        particles.forEach(p => {
            p.x += p.vx;
            p.y += p.vy;
            
            // Wrap around edges
            if (p.x < 0) p.x = window.innerWidth;
            if (p.x > window.innerWidth) p.x = 0;
            if (p.y < 0) p.y = window.innerHeight;
            if (p.y > window.innerHeight) p.y = 0;
            
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(220, 38, 38, ${p.opacity})`;
            ctx.fill();
        });
        
        // Draw connections
        ctx.strokeStyle = 'rgba(60, 60, 60, 0.1)';
        ctx.lineWidth = 1;
        
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[j].x - particles[i].x;
                const dy = particles[j].y - particles[i].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                
                if (dist < 150) {
                    const opacity = (1 - dist / 150) * 0.1;
                    ctx.strokeStyle = `rgba(80, 80, 80, ${opacity})`;
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.stroke();
                }
            }
        }
    }
    
    animate();
}

/* =====================================================
   Password Toggle
   ===================================================== */
function initPasswordToggle() {
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (!toggleBtn || !passwordInput) return;
    
    toggleBtn.addEventListener('click', () => {
        const type = passwordInput.getAttribute('type');
        passwordInput.setAttribute('type', type === 'password' ? 'text' : 'password');
        toggleBtn.classList.toggle('active');
    });
}

/* =====================================================
   Form Validation
   ===================================================== */
function initFormValidation() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    
    if (emailInput) {
        emailInput.addEventListener('blur', () => validateEmail(emailInput));
        emailInput.addEventListener('input', () => clearError('emailError'));
    }
    
    if (passwordInput) {
        passwordInput.addEventListener('blur', () => validatePassword(passwordInput));
        passwordInput.addEventListener('input', () => clearError('passwordError'));
    }
}

function validateEmail(input) {
    const errorEl = document.getElementById('emailError');
    const value = input.value.trim();
    
    if (!value) {
        showError(errorEl, 'Email is required');
        return false;
    }
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
        showError(errorEl, 'Please enter a valid email');
        return false;
    }
    
    clearError('emailError');
    return true;
}

function validatePassword(input) {
    const errorEl = document.getElementById('passwordError');
    const value = input.value;
    
    if (!value) {
        showError(errorEl, 'Password is required');
        return false;
    }
    
    if (value.length < 6) {
        showError(errorEl, 'Password must be at least 6 characters');
        return false;
    }
    
    clearError('passwordError');
    return true;
}

function showError(element, message) {
    if (element) {
        element.textContent = message;
        element.style.opacity = '1';
    }
}

function clearError(id) {
    const element = document.getElementById(id);
    if (element) {
        element.textContent = '';
        element.style.opacity = '0';
    }
}

/* =====================================================
   Form Submission
   ===================================================== */
function initFormSubmission() {
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const alertEl = document.getElementById('alertMessage');
    
    if (!form) return;
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Validate all fields
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        const isEmailValid = validateEmail(emailInput);
        const isPasswordValid = validatePassword(passwordInput);
        
        if (!isEmailValid || !isPasswordValid) {
            return;
        }
        
        // Show loading state
        submitBtn.classList.add('loading');
        submitBtn.disabled = true;
        hideAlert(alertEl);
        
        // Prepare form data
        const formData = new FormData(form);
        const data = {
            email: formData.get('email'),
            password: formData.get('password'),
            remember: formData.get('remember') === 'on'
        };
        
        try {
            const response = await fetch('API/auth/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                showAlert(alertEl, 'Login successful! Redirecting...', 'success');
                
                // Redirect after short delay
                setTimeout(() => {
                    window.location.href = 'portal/dashboard.php';
                }, 1000);
            } else {
                showAlert(alertEl, result.message || 'Invalid credentials', 'error');
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            }
        } catch (error) {
            console.error('Login error:', error);
            showAlert(alertEl, 'Connection error. Please try again.', 'error');
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;
        }
    });
}

function showAlert(element, message, type) {
    if (!element) return;
    
    element.textContent = message;
    element.className = 'alert show ' + type;
}

function hideAlert(element) {
    if (!element) return;
    element.className = 'alert';
}

/* =====================================================
   Enter key handling
   ===================================================== */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (form && submitBtn && !submitBtn.disabled) {
            const activeEl = document.activeElement;
            if (activeEl.tagName === 'INPUT') {
                form.dispatchEvent(new Event('submit'));
            }
        }
    }
});
