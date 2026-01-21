<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devices - LifeLine</title>
    <link rel="stylesheet" href="css/shared.css">
    <style>
        /* Page specific styles */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .toolbar-left {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            padding: 0 12px;
        }

        .search-box input {
            border: none;
            background: transparent;
            padding: 10px 8px;
            width: 250px;
        }

        .search-icon {
            color: var(--text-muted);
        }

        .filter-select {
            width: auto;
            min-width: 150px;
        }

        /* Table */
        .data-table {
            width: 100%;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        .data-table th {
            background: var(--bg-secondary);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
        }

        .data-table tr:hover td {
            background: var(--bg-tertiary);
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.active {
            background: rgba(34, 197, 94, 0.15);
            color: var(--success);
        }

        .status-badge.inactive {
            background: rgba(136, 136, 136, 0.15);
            color: var(--text-secondary);
        }

        .status-badge.maintenance {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning);
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .btn-icon.edit:hover {
            border-color: var(--warning);
            color: var(--warning);
        }

        .btn-icon.delete:hover {
            border-color: var(--danger);
            color: var(--danger);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-title {
            font-size: 16px;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--text-muted);
            font-size: 20px;
            cursor: pointer;
            padding: 4px;
        }

        .modal-close:hover {
            color: var(--text-primary);
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 20px;
            border-top: 1px solid var(--border-color);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 12px 0;
        }

        .pagination-info {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .pagination-controls {
            display: flex;
            gap: 8px;
        }

        .page-btn {
            min-width: 36px;
            height: 36px;
        }

        .page-btn.active {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .empty-state-text {
            font-size: 14px;
            margin-bottom: 20px;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 14px 20px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            display: none;
            align-items: center;
            gap: 10px;
            z-index: 1001;
            animation: slideIn 0.3s ease;
        }

        .toast.show {
            display: flex;
        }

        .toast.success {
            border-left: 3px solid var(--success);
        }

        .toast.error {
            border-left: 3px solid var(--danger);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Devices</h1>
        <button class="btn btn-primary" onclick="openModal('create')">+ Add Device</button>
    </div>

    <div class="toolbar">
        <div class="toolbar-left">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="search-input" placeholder="Search devices...">
            </div>
            <select class="filter-select" id="status-filter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="maintenance">Maintenance</option>
            </select>
            <select class="filter-select" id="location-filter">
                <option value="">All Locations</option>
            </select>
        </div>
        <button class="btn" onclick="loadDevices()">↻ Refresh</button>
    </div>

    <div id="table-container">
        <div class="loading">
            <span class="spinner"></span>
            Loading devices...
        </div>
    </div>

    <div class="pagination" id="pagination" style="display: none;">
        <div class="pagination-info" id="pagination-info">Showing 0 of 0</div>
        <div class="pagination-controls" id="pagination-controls"></div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title" id="modal-title">Add Device</span>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="device-form">
                    <input type="hidden" id="device-id">

                    <div class="form-group">
                        <label for="device-name">Device Name</label>
                        <input type="text" id="device-name" placeholder="ESP-Node-01">
                    </div>

                    <div class="form-group">
                        <label for="device-location">Location *</label>
                        <select id="device-location" required>
                            <option value="">Select Location</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="device-status">Status</label>
                        <select id="device-status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="saveDevice()">Save</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <span class="modal-title">Confirm Delete</span>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this device?</p>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 8px;">
                    This will also delete all associated messages.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn btn-primary" style="background: var(--danger); border-color: var(--danger);"
                    onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">
        <span id="toast-message"></span>
    </div>

    <script>
        const API_BASE = '../API';
        let devices = [];
        let locations = {};
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

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            toast.className = `toast show ${type}`;
            toastMessage.textContent = message;
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // Load locations for dropdowns
        async function loadLocations() {
            try {
                const res = await fetch(`${API_BASE}/Read/index.php?type=location`);
                const data = await res.json();
                if (data.success && data.data.mapping) {
                    locations = data.data.mapping;
                    populateLocationDropdowns();
                }
            } catch (error) {
                console.error('Error loading locations:', error);
            }
        }

        function populateLocationDropdowns() {
            const filterSelect = document.getElementById('location-filter');
            const formSelect = document.getElementById('device-location');

            // Clear existing options (keep first)
            filterSelect.innerHTML = '<option value="">All Locations</option>';
            formSelect.innerHTML = '<option value="">Select Location</option>';

            Object.entries(locations).forEach(([code, name]) => {
                filterSelect.innerHTML += `<option value="${code}">${name}</option>`;
                formSelect.innerHTML += `<option value="${code}">${name}</option>`;
            });
        }

        // Load devices
        async function loadDevices() {
            const container = document.getElementById('table-container');
            container.innerHTML = '<div class="loading"><span class="spinner"></span>Loading devices...</div>';

            try {
                const status = document.getElementById('status-filter').value;
                const lid = document.getElementById('location-filter').value;
                const search = document.getElementById('search-input').value;

                let url = `${API_BASE}/Read/device.php?page=${currentPage}&limit=20`;
                if (status) url += `&status=${status}`;
                if (lid) url += `&lid=${lid}`;

                const res = await fetch(url);
                const data = await res.json();

                if (data.success) {
                    devices = data.data.devices || data.data || [];
                    const pagination = data.data.pagination;

                    if (pagination) {
                        totalPages = pagination.pages;
                        updatePagination(pagination);
                    }

                    // Client-side search filter
                    if (search) {
                        devices = devices.filter(d =>
                            (d.device_name && d.device_name.toLowerCase().includes(search.toLowerCase())) ||
                            (d.location_name && d.location_name.toLowerCase().includes(search.toLowerCase()))
                        );
                    }

                    renderTable();
                } else {
                    container.innerHTML = `<div class="empty-state"><div class="empty-state-icon">⚠️</div><div>${data.message}</div></div>`;
                }
            } catch (error) {
                container.innerHTML = `<div class="empty-state"><div class="empty-state-icon">❌</div><div>Error loading devices</div></div>`;
                console.error('Error:', error);
            }
        }

        function renderTable() {
            const container = document.getElementById('table-container');

            if (devices.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">◎</div>
                        <div class="empty-state-text">No devices found</div>
                        <button class="btn btn-primary" onclick="openModal('create')">+ Add First Device</button>
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
                            <th>Device Name</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Last Ping</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${devices.map(device => `
                            <tr>
                                <td>#${device.DID}</td>
                                <td>${device.device_name || 'Unnamed Device'}</td>
                                <td>📍 ${device.location_name || locations[device.LID] || 'Unknown'}</td>
                                <td>
                                    <span class="status-badge ${device.status || 'inactive'}">
                                        <span class="status-dot ${device.status || 'inactive'}"></span>
                                        ${(device.status || 'inactive').charAt(0).toUpperCase() + (device.status || 'inactive').slice(1)}
                                    </span>
                                </td>
                                <td>${formatTime(device.last_ping)}</td>
                                <td class="actions">
                                    <button class="btn btn-icon edit" onclick="openModal('edit', ${device.DID})" title="Edit">✎</button>
                                    <button class="btn btn-icon delete" onclick="openDeleteModal(${device.DID})" title="Delete">🗑</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            document.getElementById('pagination').style.display = 'flex';
        }

        function formatTime(timestamp) {
            if (!timestamp) return 'Never';
            const date = new Date(timestamp);
            const now = new Date();
            const diff = (now - date) / 1000;

            if (diff < 60) return 'Just now';
            if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
            return date.toLocaleDateString();
        }

        function updatePagination(pagination) {
            const info = document.getElementById('pagination-info');
            const controls = document.getElementById('pagination-controls');

            const start = (pagination.page - 1) * pagination.limit + 1;
            const end = Math.min(pagination.page * pagination.limit, pagination.total);
            info.textContent = `Showing ${start}-${end} of ${pagination.total}`;

            let html = '';
            html += `<button class="btn page-btn" onclick="goToPage(${pagination.page - 1})" ${pagination.page <= 1 ? 'disabled' : ''}>←</button>`;

            for (let i = 1; i <= pagination.pages; i++) {
                if (i <= 3 || i > pagination.pages - 2 || Math.abs(i - pagination.page) <= 1) {
                    html += `<button class="btn page-btn ${i === pagination.page ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
                } else if (i === 4 || i === pagination.pages - 2) {
                    html += `<span style="padding: 0 8px; color: var(--text-muted);">...</span>`;
                }
            }

            html += `<button class="btn page-btn" onclick="goToPage(${pagination.page + 1})" ${pagination.page >= pagination.pages ? 'disabled' : ''}>→</button>`;
            controls.innerHTML = html;
        }

        function goToPage(page) {
            if (page < 1 || page > totalPages) return;
            currentPage = page;
            loadDevices();
        }

        // Modal functions
        function openModal(mode, id = null) {
            const overlay = document.getElementById('modal-overlay');
            const title = document.getElementById('modal-title');
            const form = document.getElementById('device-form');

            form.reset();
            document.getElementById('device-id').value = '';

            if (mode === 'edit' && id) {
                title.textContent = 'Edit Device';
                const device = devices.find(d => d.DID == id);
                if (device) {
                    document.getElementById('device-id').value = device.DID;
                    document.getElementById('device-name').value = device.device_name || '';
                    document.getElementById('device-location').value = device.LID;
                    document.getElementById('device-status').value = device.status || 'active';
                }
            } else {
                title.textContent = 'Add Device';
            }

            overlay.classList.add('active');
        }

        function closeModal() {
            document.getElementById('modal-overlay').classList.remove('active');
        }

        async function saveDevice() {
            const id = document.getElementById('device-id').value;
            const deviceName = document.getElementById('device-name').value;
            const lid = document.getElementById('device-location').value;
            const status = document.getElementById('device-status').value;

            if (!lid) {
                showToast('Please select a location', 'error');
                return;
            }

            try {
                let url, method, body;

                if (id) {
                    // Update
                    url = `${API_BASE}/Update/device.php`;
                    method = 'PUT';
                    body = JSON.stringify({
                        DID: parseInt(id),
                        device_name: deviceName,
                        LID: parseInt(lid),
                        status: status
                    });
                } else {
                    // Create
                    url = `${API_BASE}/Create/device.php`;
                    method = 'POST';
                    body = JSON.stringify({
                        device_name: deviceName,
                        LID: parseInt(lid),
                        status: status
                    });
                }

                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: body
                });

                const data = await res.json();

                if (data.success) {
                    showToast(id ? 'Device updated successfully' : 'Device created successfully');
                    closeModal();
                    loadDevices();
                } else {
                    showToast(data.message || 'Error saving device', 'error');
                }
            } catch (error) {
                showToast('Error saving device', 'error');
                console.error('Error:', error);
            }
        }

        // Delete functions
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
                const res = await fetch(`${API_BASE}/Delete/device.php?id=${deleteId}`, {
                    method: 'DELETE'
                });

                const data = await res.json();

                if (data.success) {
                    showToast('Device deleted successfully');
                    closeDeleteModal();
                    loadDevices();
                } else {
                    showToast(data.message || 'Error deleting device', 'error');
                }
            } catch (error) {
                showToast('Error deleting device', 'error');
                console.error('Error:', error);
            }
        }

        // Event listeners
        document.getElementById('search-input').addEventListener('input', debounce(loadDevices, 300));
        document.getElementById('status-filter').addEventListener('change', () => { currentPage = 1; loadDevices(); });
        document.getElementById('location-filter').addEventListener('change', () => { currentPage = 1; loadDevices(); });

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

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            syncTheme();
            loadLocations().then(() => loadDevices());
        });
    </script>
</body>

</html>