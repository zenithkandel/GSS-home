<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LifeLine</title>
    <style>
        /* CSS Variables */
        :root {
            --transition-speed: 0.2s;
        }

        [data-theme="dark"] {
            --bg-primary: #0a0a0a;
            --bg-secondary: #111111;
            --bg-tertiary: #1a1a1a;
            --bg-card: #141414;
            --border-color: #222222;
            --text-primary: #ffffff;
            --text-secondary: #888888;
            --text-muted: #555555;
            --accent: #ff3b3b;
            --accent-dim: #661a1a;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        [data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f5f5f5;
            --bg-tertiary: #eeeeee;
            --bg-card: #ffffff;
            --border-color: #e0e0e0;
            --text-primary: #111111;
            --text-secondary: #666666;
            --text-muted: #999999;
            --accent: #dc2626;
            --accent-dim: #fecaca;
            --success: #16a34a;
            --warning: #d97706;
            --danger: #dc2626;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            padding: 24px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--success);
            animation: pulse 2s infinite;
        }

        .pulse-dot.error {
            background: var(--danger);
            animation: none;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
            }

            50% {
                opacity: 0.7;
                box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
            }
        }

        .live-text {
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
        }

        /* Alerts Section */
        .alerts-section {
            margin-bottom: 32px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
        }

        .badge {
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            background: var(--accent-dim);
            color: var(--accent);
        }

        .alerts-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-height: 400px;
            overflow-y: auto;
        }

        .alert-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 16px 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            transition: all var(--transition-speed);
        }

        .alert-card:hover {
            border-color: var(--accent);
        }

        .alert-card.critical {
            border-left: 3px solid var(--danger);
        }

        .alert-card.warning {
            border-left: 3px solid var(--warning);
        }

        .alert-card.info {
            border-left: 3px solid var(--success);
        }

        .alert-icon {
            font-size: 20px;
            margin-top: 2px;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .alert-meta {
            display: flex;
            gap: 16px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .alert-time {
            font-size: 12px;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .no-alerts {
            padding: 48px;
            text-align: center;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
        }

        .no-alerts-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            padding: 20px;
        }

        .stat-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -1px;
        }

        .stat-sub {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .stat-value.success {
            color: var(--success);
        }

        .stat-value.warning {
            color: var(--warning);
        }

        .stat-value.danger {
            color: var(--danger);
        }

        /* Recent Activity */
        .activity-section {
            margin-bottom: 32px;
        }

        .activity-list {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
        }

        .activity-item {
            padding: 14px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .activity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .activity-dot.active {
            background: var(--success);
        }

        .activity-dot.inactive {
            background: var(--text-muted);
        }

        .activity-dot.maintenance {
            background: var(--warning);
        }

        .activity-name {
            font-size: 14px;
        }

        .activity-location {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .activity-time {
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 24px;
            color: var(--text-muted);
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-color);
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Dashboard</h1>
        <div class="live-indicator">
            <div class="pulse-dot" id="live-dot"></div>
            <span class="live-text" id="live-text">Listening...</span>
        </div>
    </div>

    <!-- Alerts Section -->
    <section class="alerts-section">
        <div class="section-header">
            <span class="section-title">Emergency Alerts</span>
            <span class="badge" id="alert-count">0 Active</span>
        </div>
        <div class="alerts-container" id="alerts-container">
            <div class="loading">
                <span class="spinner"></span>
                Loading alerts...
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Devices</div>
            <div class="stat-value" id="stat-total">-</div>
            <div class="stat-sub" id="stat-total-sub">Loading...</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active Devices</div>
            <div class="stat-value success" id="stat-active">-</div>
            <div class="stat-sub" id="stat-active-sub">Online now</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Offline Devices</div>
            <div class="stat-value warning" id="stat-offline">-</div>
            <div class="stat-sub" id="stat-offline-sub">Needs attention</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Messages Today</div>
            <div class="stat-value" id="stat-messages">-</div>
            <div class="stat-sub" id="stat-messages-sub">Since midnight</div>
        </div>
    </section>

    <!-- Recent Device Activity -->
    <section class="activity-section">
        <div class="section-header">
            <span class="section-title">Device Status</span>
        </div>
        <div class="activity-list" id="device-list">
            <div class="loading">
                <span class="spinner"></span>
                Loading devices...
            </div>
        </div>
    </section>

    <script>
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
    </script>
</body>

</html>