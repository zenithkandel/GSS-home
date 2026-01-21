// Messages Page JavaScript
const API_BASE = '../API';
let messages = [];
let devices = [];
let messageTypes = {};
let currentPage = 1;
let totalPages = 1;
let deleteId = null;

// Theme sync
function syncTheme() {
    if (window.parent && window.parent.lifelinePortal) {
        const theme = window.parent.lifelinePortal.getTheme();
        document.documentElement.setAttribute('data-theme', theme);
    } else {
        const theme = localStorage.getItem('lifeline-theme') || 'dark';
        document.documentElement.setAttribute('data-theme', theme);
    }
}

// Toast
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');
    toast.className = `toast show ${type}`;
    toastMessage.textContent = message;
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// Load initial data
async function loadInitialData() {
    try {
        const [devicesRes, typesRes] = await Promise.all([
            fetch(`${API_BASE}/Read/device.php?limit=100`),
            fetch(`${API_BASE}/Read/index.php?type=message`)
        ]);

        const devicesData = await devicesRes.json();
        const typesData = await typesRes.json();

        if (devicesData.success) {
            devices = devicesData.data.devices || devicesData.data || [];
            populateDeviceDropdowns();
        }

        if (typesData.success && typesData.data.mapping) {
            messageTypes = typesData.data.mapping;
            populateTypeDropdowns();
        }
    } catch (error) {
        console.error('Error loading initial data:', error);
    }
}

function populateDeviceDropdowns() {
    const filterSelect = document.getElementById('device-filter');
    const formSelect = document.getElementById('msg-device');

    filterSelect.innerHTML = '<option value="">All Devices</option>';
    formSelect.innerHTML = '<option value="">Select Device</option>';

    devices.forEach(device => {
        const name = device.device_name || `Device ${device.DID}`;
        filterSelect.innerHTML += `<option value="${device.DID}">${name}</option>`;
        formSelect.innerHTML += `<option value="${device.DID}">${name} (${device.location_name || 'Unknown'})</option>`;
    });
}

function populateTypeDropdowns() {
    const filterSelect = document.getElementById('message-type-filter');
    const formSelect = document.getElementById('msg-type');

    filterSelect.innerHTML = '<option value="">All Message Types</option>';
    formSelect.innerHTML = '<option value="">Select Message Type</option>';

    Object.entries(messageTypes).forEach(([code, text]) => {
        filterSelect.innerHTML += `<option value="${code}">${text}</option>`;
        formSelect.innerHTML += `<option value="${code}">${text}</option>`;
    });
}

// Load messages
async function loadMessages() {
    const container = document.getElementById('table-container');
    container.innerHTML = '<div class="loading"><span class="spinner"></span>Loading messages...</div>';

    try {
        const did = document.getElementById('device-filter').value;
        const messageCode = document.getElementById('message-type-filter').value;
        const fromDate = document.getElementById('from-date').value;
        const toDate = document.getElementById('to-date').value;

        let url = `${API_BASE}/Read/message.php?page=${currentPage}&limit=20`;
        if (did) url += `&did=${did}`;
        if (messageCode) url += `&message_code=${messageCode}`;
        if (fromDate) url += `&from=${fromDate}`;
        if (toDate) url += `&to=${toDate}`;

        const res = await fetch(url);
        const data = await res.json();

        if (data.success) {
            messages = data.data.messages || data.data || [];
            const pagination = data.data.pagination;

            if (pagination) {
                totalPages = pagination.pages;
                updatePagination(pagination);
            }

            updateStats();
            renderTable();
        } else {
            container.innerHTML = `<div class="empty-state"><div class="empty-state-icon">⚠️</div><div>${data.message}</div></div>`;
        }
    } catch (error) {
        container.innerHTML = `<div class="empty-state"><div class="empty-state-icon">❌</div><div>Error loading messages</div></div>`;
        console.error('Error:', error);
    }
}

function getSeverity(messageCode) {
    const code = parseInt(messageCode);
    const critical = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    const warning = [10, 11, 12, 13, 14];

    if (critical.includes(code)) return 'critical';
    if (warning.includes(code)) return 'warning';
    return 'info';
}

function getIcon(messageCode) {
    const icons = {
        1: '🏥', 2: '🤕', 3: '🤒', 4: '🔍', 5: '❄️',
        6: '⛰️', 7: '🔥', 8: '🌊', 9: '👤', 10: '⚠️',
        11: '🌪️', 12: '🏚️', 13: '💧', 14: '📡', 15: '✅'
    };
    return icons[messageCode] || '⚡';
}

function getRssiBars(rssi) {
    if (!rssi) return [false, false, false, false];
    const level = rssi > -50 ? 4 : rssi > -70 ? 3 : rssi > -85 ? 2 : 1;
    return [level >= 1, level >= 2, level >= 3, level >= 4];
}

function updateStats() {
    document.getElementById('total-count').textContent = messages.length;
    document.getElementById('critical-count').textContent = messages.filter(m => getSeverity(m.message_code) === 'critical').length;
    document.getElementById('warning-count').textContent = messages.filter(m => getSeverity(m.message_code) === 'warning').length;
    document.getElementById('info-count').textContent = messages.filter(m => getSeverity(m.message_code) === 'info').length;
}

function renderTable() {
    const container = document.getElementById('table-container');

    if (messages.length === 0) {
        container.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">✉</div>
                <div class="empty-state-text">No messages found</div>
            </div>
        `;
        document.getElementById('pagination').style.display = 'none';
        return;
    }

    container.innerHTML = `
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Severity</th>
                    <th>Message</th>
                    <th>Device</th>
                    <th>Location</th>
                    <th>Signal</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                ${messages.map(msg => {
                    const severity = getSeverity(msg.message_code);
                    const bars = getRssiBars(msg.RSSI);
                    return `
                    <tr>
                        <td>#${msg.MID}</td>
                        <td>
                            <span class="severity-badge ${severity}">
                                ${getIcon(msg.message_code)}
                                ${severity.charAt(0).toUpperCase() + severity.slice(1)}
                            </span>
                        </td>
                        <td class="message-text">
                            <div class="message-type">${msg.message_text || messageTypes[msg.message_code] || 'Unknown'}</div>
                            <div class="message-meta">Code: ${msg.message_code}</div>
                        </td>
                        <td>${msg.device_name || 'Device ' + msg.DID}</td>
                        <td>📍 ${msg.location_name || 'Unknown'}</td>
                        <td>
                            <div class="rssi-indicator">
                                <div class="rssi-bars">
                                    <div class="rssi-bar ${bars[0] ? 'active' : ''}"></div>
                                    <div class="rssi-bar ${bars[1] ? 'active' : ''}"></div>
                                    <div class="rssi-bar ${bars[2] ? 'active' : ''}"></div>
                                    <div class="rssi-bar ${bars[3] ? 'active' : ''}"></div>
                                </div>
                                <span style="font-size: 11px; color: var(--text-muted);">${msg.RSSI || '-'}</span>
                            </div>
                        </td>
                        <td>${formatTime(msg.timestamp)}</td>
                        <td class="actions">
                            <button class="btn btn-icon view" onclick="viewMessage(${msg.MID})" title="View">👁</button>
                            <button class="btn btn-icon delete" onclick="openDeleteModal(${msg.MID})" title="Delete">🗑</button>
                        </td>
                    </tr>
                `;
                }).join('')}
            </tbody>
        </table>
    `;

    document.getElementById('pagination').style.display = 'flex';
}

function formatTime(timestamp) {
    if (!timestamp) return 'Unknown';
    const date = new Date(timestamp);
    const now = new Date();
    const diff = (now - date) / 1000;

    if (diff < 60) return 'Just now';
    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
    return date.toLocaleString();
}

function updatePagination(pagination) {
    const info = document.getElementById('pagination-info');
    const controls = document.getElementById('pagination-controls');

    const start = (pagination.page - 1) * pagination.limit + 1;
    const end = Math.min(pagination.page * pagination.limit, pagination.total);
    info.textContent = `Showing ${start}-${end} of ${pagination.total}`;

    let html = '';
    html += `<button class="btn page-btn" onclick="goToPage(${pagination.page - 1})" ${pagination.page <= 1 ? 'disabled' : ''}>←</button>`;

    for (let i = 1; i <= Math.min(pagination.pages, 5); i++) {
        html += `<button class="btn page-btn ${i === pagination.page ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
    }

    html += `<button class="btn page-btn" onclick="goToPage(${pagination.page + 1})" ${pagination.page >= pagination.pages ? 'disabled' : ''}>→</button>`;
    controls.innerHTML = html;
}

function goToPage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadMessages();
}

// View message
function viewMessage(id) {
    const msg = messages.find(m => m.MID == id);
    if (!msg) return;

    const severity = getSeverity(msg.message_code);
    const details = document.getElementById('message-details');

    details.innerHTML = `
        <div class="detail-row">
            <span class="detail-label">Message ID</span>
            <span class="detail-value">#${msg.MID}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Severity</span>
            <span class="detail-value">
                <span class="severity-badge ${severity}">
                    ${getIcon(msg.message_code)} ${severity.charAt(0).toUpperCase() + severity.slice(1)}
                </span>
            </span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Message</span>
            <span class="detail-value">${msg.message_text || messageTypes[msg.message_code] || 'Unknown'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Message Code</span>
            <span class="detail-value">${msg.message_code}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Device</span>
            <span class="detail-value">${msg.device_name || 'Device ' + msg.DID} (ID: ${msg.DID})</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Location</span>
            <span class="detail-value">📍 ${msg.location_name || 'Unknown'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Signal (RSSI)</span>
            <span class="detail-value">${msg.RSSI ? msg.RSSI + ' dBm' : 'Not recorded'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Timestamp</span>
            <span class="detail-value">${new Date(msg.timestamp).toLocaleString()}</span>
        </div>
    `;

    document.getElementById('view-modal').classList.add('active');
}

function closeViewModal() {
    document.getElementById('view-modal').classList.remove('active');
}

// Create message
function openCreateModal() {
    document.getElementById('message-form').reset();
    document.getElementById('create-modal').classList.add('active');
}

function closeCreateModal() {
    document.getElementById('create-modal').classList.remove('active');
}

async function createMessage() {
    const did = document.getElementById('msg-device').value;
    const messageCode = document.getElementById('msg-type').value;
    const rssi = document.getElementById('msg-rssi').value;

    if (!did || !messageCode) {
        showToast('Please fill in all required fields', 'error');
        return;
    }

    try {
        const res = await fetch(`${API_BASE}/Create/message.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                DID: parseInt(did),
                message_code: parseInt(messageCode),
                RSSI: rssi ? parseInt(rssi) : null
            })
        });

        const data = await res.json();

        if (data.success) {
            showToast('Message created successfully');
            closeCreateModal();
            loadMessages();
        } else {
            showToast(data.message || 'Error creating message', 'error');
        }
    } catch (error) {
        showToast('Error creating message', 'error');
        console.error('Error:', error);
    }
}

// Delete
function openDeleteModal(id) {
    deleteId = id;
    document.getElementById('delete-modal').classList.add('active');
}

function closeDeleteModal() {
    deleteId = null;
    document.getElementById('delete-modal').classList.remove('active');
}

async function confirmDelete() {
    if (!deleteId) return;

    try {
        const res = await fetch(`${API_BASE}/Delete/message.php?id=${deleteId}`, {
            method: 'DELETE'
        });

        const data = await res.json();

        if (data.success) {
            showToast('Message deleted successfully');
            closeDeleteModal();
            loadMessages();
        } else {
            showToast(data.message || 'Error deleting message', 'error');
        }
    } catch (error) {
        showToast('Error deleting message', 'error');
        console.error('Error:', error);
    }
}

// Filters
document.getElementById('device-filter').addEventListener('change', () => { currentPage = 1; loadMessages(); });
document.getElementById('message-type-filter').addEventListener('change', () => { currentPage = 1; loadMessages(); });
document.getElementById('from-date').addEventListener('change', () => { currentPage = 1; loadMessages(); });
document.getElementById('to-date').addEventListener('change', () => { currentPage = 1; loadMessages(); });

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    syncTheme();
    loadInitialData().then(() => loadMessages());
});
