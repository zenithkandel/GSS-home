// Dashboard Page JavaScript
const API_BASE = '../API';
let pollTimer = null;
let lastMessageId = 0;

// Sync theme from parent
function syncTheme() {
    if (window.parent && window.parent.lifelinePortal) {
        const theme = window.parent.lifelinePortal.getTheme();
        document.documentElement.setAttribute('data-theme', theme);
    } else {
        // Fallback to localStorage
        const theme = localStorage.getItem('lifeline-theme') || 'dark';
        document.documentElement.setAttribute('data-theme', theme);
    }
}

// Update live indicator
function updateLiveIndicator(status, text) {
    const dot = document.getElementById('live-dot');
    const label = document.getElementById('live-text');

    if (status === 'ok') {
        dot.className = 'pulse-dot';
        label.textContent = text || 'Listening...';
    } else {
        dot.className = 'pulse-dot error';
        label.textContent = text || 'Connection lost';
    }
}

// Format timestamp
function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = (now - date) / 1000;

    if (diff < 60) return 'Just now';
    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
    return date.toLocaleDateString();
}

// Get severity class
function getSeverity(messageCode) {
    const critical = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    const warning = [10, 11, 12, 13, 14];

    if (critical.includes(messageCode)) return 'critical';
    if (warning.includes(messageCode)) return 'warning';
    return 'info';
}

// Get alert icon
function getAlertIcon(messageCode) {
    const icons = {
        1: '🏥', 2: '🤕', 3: '🤒', 4: '🔍', 5: '❄️',
        6: '⛰️', 7: '🔥', 8: '🌊', 9: '👤', 10: '⚠️',
        11: '🌪️', 12: '🏚️', 13: '💧', 14: '📡', 15: '✅'
    };
    return icons[messageCode] || '⚡';
}

// Render alerts
function renderAlerts(messages) {
    const container = document.getElementById('alerts-container');
    const badge = document.getElementById('alert-count');

    if (!messages || messages.length === 0) {
        container.innerHTML = `
            <div class="no-alerts">
                <div class="no-alerts-icon">✓</div>
                <div>No active alerts</div>
            </div>
        `;
        badge.textContent = '0 Active';
        return;
    }

    badge.textContent = `${messages.length} Active`;

    container.innerHTML = messages.map(msg => `
        <div class="alert-card ${getSeverity(msg.message_code)}">
            <div class="alert-icon">${getAlertIcon(msg.message_code)}</div>
            <div class="alert-content">
                <div class="alert-title">${msg.message_text || 'Unknown Alert'}</div>
                <div class="alert-meta">
                    <span>📍 ${msg.location_name || 'Unknown Location'}</span>
                    <span>📟 ${msg.device_name || 'Device ' + msg.DID}</span>
                    ${msg.RSSI ? `<span>📶 ${msg.RSSI} dBm</span>` : ''}
                </div>
            </div>
            <div class="alert-time">${formatTime(msg.timestamp)}</div>
        </div>
    `).join('');
}

// Render device list
function renderDevices(devices) {
    const container = document.getElementById('device-list');

    if (!devices || devices.length === 0) {
        container.innerHTML = `
            <div class="no-alerts">
                No devices registered
            </div>
        `;
        return;
    }

    container.innerHTML = devices.slice(0, 10).map(device => `
        <div class="activity-item">
            <div class="activity-info">
                <div class="activity-dot ${device.status || 'inactive'}"></div>
                <div>
                    <div class="activity-name">${device.device_name || 'Device ' + device.DID}</div>
                    <div class="activity-location">📍 ${device.location_name || 'Location ' + device.LID}</div>
                </div>
            </div>
            <div class="activity-time">${formatTime(device.last_ping)}</div>
        </div>
    `).join('');
}

// Update stats
function updateStats(devices, messages) {
    const total = devices.length;
    const active = devices.filter(d => d.status === 'active').length;
    const offline = devices.filter(d => d.status === 'inactive').length;
    const maintenance = devices.filter(d => d.status === 'maintenance').length;

    document.getElementById('stat-total').textContent = total;
    document.getElementById('stat-total-sub').textContent = `${maintenance} in maintenance`;

    document.getElementById('stat-active').textContent = active;
    document.getElementById('stat-active-sub').textContent = `${Math.round((active / total) * 100) || 0}% of fleet`;

    document.getElementById('stat-offline').textContent = offline;
    document.getElementById('stat-offline-sub').textContent = offline > 0 ? 'Needs attention' : 'All good';

    document.getElementById('stat-messages').textContent = messages.length;
    document.getElementById('stat-messages-sub').textContent = 'Recent alerts';
}

// Fetch data
async function fetchData() {
    try {
        const [messagesRes, devicesRes] = await Promise.all([
            fetch(`${API_BASE}/Read/message.php?limit=20`),
            fetch(`${API_BASE}/Read/device.php?limit=50`)
        ]);

        const messagesData = await messagesRes.json();
        const devicesData = await devicesRes.json();

        if (messagesRes.ok && devicesRes.ok) {
            const messages = messagesData.data?.messages || [];
            const devices = devicesData.data?.devices || devicesData.data || [];

            renderAlerts(messages);
            renderDevices(devices);
            updateStats(devices, messages);
            updateLiveIndicator('ok', 'Listening...');

            // Check for new messages
            if (messages.length > 0 && messages[0].MID > lastMessageId) {
                if (lastMessageId > 0) {
                    // New message received - could trigger notification
                    console.log('New alert received!');
                }
                lastMessageId = messages[0].MID;
            }
        } else {
            throw new Error('API Error');
        }
    } catch (error) {
        console.error('Fetch error:', error);
        updateLiveIndicator('error', 'Connection lost');
    }
}

// Start polling
function startPolling() {
    fetchData();
    pollTimer = setInterval(fetchData, 5000); // Poll every 5 seconds
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    syncTheme();
    startPolling();

    // Listen for theme changes
    window.addEventListener('message', (e) => {
        if (e.data.type === 'theme-change') {
            document.documentElement.setAttribute('data-theme', e.data.theme);
        }
    });
});

// Cleanup on unload
window.addEventListener('beforeunload', () => {
    if (pollTimer) clearInterval(pollTimer);
});
