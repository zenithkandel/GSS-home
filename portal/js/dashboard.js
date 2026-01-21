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

// Get alert icon (FontAwesome)
function getAlertIcon(messageCode) {
    const icons = {
        1: '<i class="fa-solid fa-hospital"></i>',
        2: '<i class="fa-solid fa-user-injured"></i>',
        3: '<i class="fa-solid fa-thermometer-full"></i>',
        4: '<i class="fa-solid fa-binoculars"></i>',
        5: '<i class="fa-solid fa-snowflake"></i>',
        6: '<i class="fa-solid fa-mountain"></i>',
        7: '<i class="fa-solid fa-fire"></i>',
        8: '<i class="fa-solid fa-water"></i>',
        9: '<i class="fa-solid fa-person-walking"></i>',
        10: '<i class="fa-solid fa-triangle-exclamation"></i>',
        11: '<i class="fa-solid fa-tornado"></i>',
        12: '<i class="fa-solid fa-house-crack"></i>',
        13: '<i class="fa-solid fa-droplet"></i>',
        14: '<i class="fa-solid fa-satellite-dish"></i>',
        15: '<i class="fa-solid fa-circle-check"></i>'
    };
    return icons[messageCode] || '<i class="fa-solid fa-bolt"></i>';
}

// Get coordinates from location name using Nominatim
async function getCoordinates(placeName) {
    try {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(placeName + ", Nepal")}`;
        const response = await fetch(url, {
            headers: { "User-Agent": "LifeLine/1.0" }
        });
        const data = await response.json();
        if (data.length > 0) {
            return { lat: data[0].lat, lon: data[0].lon };
        }
    } catch (error) {
        console.error('Geocoding error:', error);
    }
    return null;
}

// Load map for an alert card
async function loadAlertMap(alertId, locationName) {
    const mapContainer = document.getElementById(`map-${alertId}`);
    if (!mapContainer || !locationName) return;

    const coords = await getCoordinates(locationName);
    if (coords) {
        mapContainer.innerHTML = `<iframe src="https://www.openstreetmap.org/export/embed.html?bbox=${coords.lon - 0.01},${coords.lat - 0.01},${parseFloat(coords.lon) + 0.01},${parseFloat(coords.lat) + 0.01}&layer=mapnik&marker=${coords.lat},${coords.lon}" loading="lazy"></iframe>`;
    } else {
        mapContainer.innerHTML = `<div class="alert-map-loading"><i class="fa-solid fa-location-dot"></i><span>${locationName}</span></div>`;
    }
}

// Open location in Google Maps
function openInMaps(locationName) {
    if (!locationName) return;
    getCoordinates(locationName).then(coords => {
        if (coords) {
            window.open(`https://www.google.com/maps?q=${coords.lat},${coords.lon}`, '_blank');
        }
    });
}

// Render alerts
function renderAlerts(messages) {
    const container = document.getElementById('alerts-container');
    const badge = document.getElementById('alert-count');

    if (!messages || messages.length === 0) {
        container.innerHTML = `
            <div class="no-alerts">
                <div class="no-alerts-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div class="no-alerts-text">No active alerts</div>
            </div>
        `;
        badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> 0 Active';
        return;
    }

    badge.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${messages.length} Active`;
    const severity = (code) => getSeverity(code);

    container.innerHTML = messages.map(msg => `
        <div class="alert-card ${severity(msg.message_code)}">
            <div class="alert-map" id="map-${msg.MID}">
                <div class="alert-map-loading"><i class="fa-solid fa-spinner fa-spin"></i><span>Loading map...</span></div>
            </div>
            <div class="alert-body">
                <div class="alert-header">
                    <div class="alert-icon">${getAlertIcon(msg.message_code)}</div>
                    <div class="alert-title-wrap">
                        <div class="alert-title">${msg.message_text || 'Unknown Alert'}</div>
                        <span class="alert-severity ${severity(msg.message_code)}">
                            <i class="fa-solid fa-circle"></i>
                            ${severity(msg.message_code)}
                        </span>
                    </div>
                </div>
                <div class="alert-meta">
                    <span><i class="fa-solid fa-location-dot"></i> ${msg.location_name || 'Unknown Location'}</span>
                    <span><i class="fa-solid fa-microchip"></i> ${msg.device_name || 'Device ' + msg.DID}</span>
                    ${msg.RSSI ? `<span><i class="fa-solid fa-signal"></i> ${msg.RSSI} dBm</span>` : ''}
                </div>
                <div class="alert-footer">
                    <div class="alert-time"><i class="fa-solid fa-clock"></i> ${formatTime(msg.timestamp)}</div>
                    <div class="alert-actions">
                        <button class="alert-btn" onclick="openInMaps('${(msg.location_name || '').replace(/'/g, "\\'")}')">
                            <i class="fa-solid fa-map-location-dot"></i> Open Maps
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');

    // Load maps for each alert
    messages.forEach(msg => {
        if (msg.location_name) {
            loadAlertMap(msg.MID, msg.location_name);
        }
    });
}

// Render device list
function renderDevices(devices) {
    const container = document.getElementById('device-list');

    if (!devices || devices.length === 0) {
        container.innerHTML = `
            <div class="no-alerts">
                <div class="no-alerts-icon"><i class="fa-solid fa-microchip"></i></div>
                <div class="no-alerts-text">No devices registered</div>
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
                    <div class="activity-location"><i class="fa-solid fa-location-dot"></i> ${device.location_name || 'Location ' + device.LID}</div>
                </div>
            </div>
            <div class="activity-time"><i class="fa-solid fa-clock"></i> ${formatTime(device.last_ping)}</div>
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
