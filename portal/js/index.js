// LifeLine Portal - Main JavaScript

const API_BASE = '../API';
let pollInterval = null;

// Theme Management
function initTheme() {
    const savedTheme = localStorage.getItem('lifeline-theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);

    // Sync theme to iframe
    syncThemeToIframe();
}

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme');
    const newTheme = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('lifeline-theme', newTheme);

    // Sync theme to iframe
    syncThemeToIframe();
}

function syncThemeToIframe() {
    const iframe = document.getElementById('content-frame');
    if (iframe && iframe.contentDocument) {
        const theme = document.documentElement.getAttribute('data-theme');
        iframe.contentDocument.documentElement.setAttribute('data-theme', theme);
    }
}

// Navigation Management
function initNavigation() {
    const navItems = document.querySelectorAll('.nav-item');
    const mobileNavItems = document.querySelectorAll('.mobile-nav-item');
    const mobileMenuItems = document.querySelectorAll('.mobile-menu-item');
    const iframe = document.getElementById('content-frame');

    // Desktop nav
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            navigateToPage(item.getAttribute('href'), item.getAttribute('data-page'));
        });
    });

    // Mobile bottom nav
    mobileNavItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            navigateToPage(item.getAttribute('href'), item.getAttribute('data-page'));
        });
    });

    // Mobile more menu items
    mobileMenuItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            closeMobileMenu();
            navigateToPage(item.getAttribute('href'), item.getAttribute('data-page'));
        });
    });

    // Sync theme when iframe loads
    iframe.addEventListener('load', () => {
        syncThemeToIframe();
    });
}

function navigateToPage(href, page) {
    const iframe = document.getElementById('content-frame');

    // Save current page to sessionStorage
    sessionStorage.setItem('lifeline-current-page', page);
    sessionStorage.setItem('lifeline-current-href', href);

    // Update all nav active states
    document.querySelectorAll('.nav-item').forEach(nav => {
        nav.classList.toggle('active', nav.getAttribute('data-page') === page);
    });
    document.querySelectorAll('.mobile-nav-item').forEach(nav => {
        nav.classList.toggle('active', nav.getAttribute('data-page') === page);
    });

    // Load page
    iframe.src = href;
}

// Restore saved page from sessionStorage
function restoreSavedPage() {
    const savedPage = sessionStorage.getItem('lifeline-current-page');
    const savedHref = sessionStorage.getItem('lifeline-current-href');

    if (savedPage && savedHref) {
        navigateToPage(savedHref, savedPage);
    }
}

// Mobile More Menu
function initMobileMenu() {
    const moreBtn = document.getElementById('mobile-more-btn');
    const overlay = document.getElementById('mobile-menu-overlay');

    if (moreBtn) {
        moreBtn.addEventListener('click', () => {
            overlay.classList.add('active');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeMobileMenu();
            }
        });
    }
}

function closeMobileMenu() {
    const overlay = document.getElementById('mobile-menu-overlay');
    if (overlay) {
        overlay.classList.remove('active');
    }
}

// Sidebar Collapse
function initSidebarCollapse() {
    const collapseBtn = document.getElementById('collapse-btn');
    const sidebar = document.querySelector('.sidebar');
    const content = document.querySelector('.content');

    if (!collapseBtn || !sidebar) return;

    // Load saved state
    const isCollapsed = localStorage.getItem('lifeline-sidebar-collapsed') === 'true';
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
    }

    collapseBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        const collapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('lifeline-sidebar-collapsed', collapsed);
    });
}

// Connection Status
function updateConnectionStatus(connected, message = '') {
    const dot = document.getElementById('connection-dot');
    const text = document.getElementById('connection-text');

    if (dot && text) {
        if (connected) {
            dot.className = 'status-dot connected';
            text.textContent = message || 'Connected';
        } else {
            dot.className = 'status-dot error';
            text.textContent = message || 'Disconnected';
        }
    }
}

// API Health Check
async function checkConnection() {
    try {
        const response = await fetch(`${API_BASE}/Read/index.php?type=location`);
        if (response.ok) {
            updateConnectionStatus(true, 'Connected');
            return true;
        } else {
            updateConnectionStatus(false, 'API Error');
            return false;
        }
    } catch (error) {
        updateConnectionStatus(false, 'Offline');
        return false;
    }
}

// Start polling for connection
function startConnectionPolling() {
    checkConnection();
    pollInterval = setInterval(checkConnection, 10000);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initNavigation();
    initMobileMenu();
    initSidebarCollapse();
    startConnectionPolling();
    restoreSavedPage();

    // Theme toggle buttons
    const themeToggle = document.getElementById('theme-toggle');
    const mobileThemeToggle = document.getElementById('mobile-theme-toggle');

    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    if (mobileThemeToggle) {
        mobileThemeToggle.addEventListener('click', toggleTheme);
    }
});

// Expose functions for iframe communication
window.lifelinePortal = {
    toggleTheme,
    updateConnectionStatus,
    getTheme: () => document.documentElement.getAttribute('data-theme')
};
