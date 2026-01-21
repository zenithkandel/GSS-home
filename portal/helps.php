<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responders - LifeLine</title>
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

        .status-badge.available {
            background: rgba(34, 197, 94, 0.15);
            color: var(--success);
        }

        .status-badge.dispatched {
            background: rgba(245, 158, 11, 0.15);
            color: var(--warning);
        }

        .status-badge.busy {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
        }

        .for-messages-list {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            max-width: 250px;
        }

        .message-tag {
            padding: 2px 8px;
            font-size: 11px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            white-space: nowrap;
        }

        .message-tag-more {
            color: var(--text-muted);
            font-size: 11px;
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
            max-width: 600px;
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

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 20px;
            border-top: 1px solid var(--border-color);
        }

        /* Multi-select for messages */
        .multi-select-container {
            position: relative;
        }

        .multi-select-trigger {
            width: 100%;
            min-height: 42px;
            padding: 8px 12px;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }

        .multi-select-trigger:focus {
            border-color: var(--accent);
            outline: none;
        }

        .multi-select-trigger .placeholder {
            color: var(--text-muted);
        }

        .selected-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            background: var(--accent-dim);
            color: var(--accent);
            font-size: 12px;
        }

        .selected-tag .remove-tag {
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
        }

        .selected-tag .remove-tag:hover {
            color: var(--text-primary);
        }

        .multi-select-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-top: none;
            max-height: 250px;
            overflow-y: auto;
            z-index: 100;
            display: none;
        }

        .multi-select-dropdown.open {
            display: block;
        }

        .multi-select-option {
            padding: 10px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .multi-select-option:hover {
            background: var(--bg-tertiary);
        }

        .multi-select-option.selected {
            background: var(--accent-dim);
        }

        .multi-select-option .checkbox {
            width: 16px;
            height: 16px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .multi-select-option.selected .checkbox {
            background: var(--accent);
            border-color: var(--accent);
            color: white;
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

        /* Stats Bar */
        .stats-bar {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            padding: 16px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Responders</h1>
        <button class="btn btn-primary" onclick="openModal('create')">+ Add Responder</button>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-number" id="total-count">-</span>
            <span class="stat-label">Total</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-success" id="available-count">-</span>
            <span class="stat-label">Available</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-warning" id="dispatched-count">-</span>
            <span class="stat-label">Dispatched</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-danger" id="busy-count">-</span>
            <span class="stat-label">Busy</span>
        </div>
    </div>

    <div class="toolbar">
        <div class="toolbar-left">
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" id="search-input" placeholder="Search responders...">
            </div>
            <select class="filter-select" id="status-filter">
                <option value="">All Status</option>
                <option value="available">Available</option>
                <option value="dispatched">Dispatched</option>
                <option value="busy">Busy</option>
            </select>
        </div>
        <button class="btn" onclick="loadHelps()">↻ Refresh</button>
    </div>

    <div id="table-container">
        <div class="loading">
            <span class="spinner"></span>
            Loading responders...
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
                <span class="modal-title" id="modal-title">Add Responder</span>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="help-form">
                    <input type="hidden" id="help-id">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="help-name">Name *</label>
                            <input type="text" id="help-name" placeholder="Nepal Army Rescue Team" required>
                        </div>

                        <div class="form-group">
                            <label for="help-contact">Contact *</label>
                            <input type="text" id="help-contact" placeholder="+977-1-4412345" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="help-location">Location</label>
                            <input type="text" id="help-location" placeholder="Namche Bazaar">
                        </div>

                        <div class="form-group">
                            <label for="help-eta">ETA</label>
                            <input type="text" id="help-eta" placeholder="30 minutes">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="help-status">Status</label>
                        <select id="help-status">
                            <option value="available">Available</option>
                            <option value="dispatched">Dispatched</option>
                            <option value="busy">Busy</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Responds To (Message Types)</label>
                        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">
                            Select which emergency types this responder handles
                        </p>
                        <div class="multi-select-container" id="messages-select">
                            <div class="multi-select-trigger" onclick="toggleDropdown()" tabindex="0">
                                <span class="placeholder" id="select-placeholder">Click to select message
                                    types...</span>
                            </div>
                            <div class="multi-select-dropdown" id="messages-dropdown">
                                <!-- Options populated by JS -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="saveHelp()">Save</button>
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
                <p>Are you sure you want to delete this responder?</p>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 8px;">
                    This action cannot be undone.
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
        let helps = [];
        let messageTypes = {};
        let currentPage = 1;
        let totalPages = 1;
        let deleteId = null;
        let selectedMessages = [];

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

        // Load message types
        async function loadMessageTypes() {
            try {
                const res = await fetch(`${API_BASE}/Read/index.php?type=message`);
                const data = await res.json();
                if (data.success && data.data.mapping) {
                    messageTypes = data.data.mapping;
                    populateMessageDropdown();
                }
            } catch (error) {
                console.error('Error loading message types:', error);
            }
        }

        function populateMessageDropdown() {
            const dropdown = document.getElementById('messages-dropdown');
            dropdown.innerHTML = Object.entries(messageTypes).map(([code, text]) => `
                <div class="multi-select-option" data-value="${code}" onclick="toggleOption(${code})">
                    <span class="checkbox"></span>
                    <span>${text}</span>
                </div>
            `).join('');
        }

        // Multi-select functions
        function toggleDropdown() {
            const dropdown = document.getElementById('messages-dropdown');
            dropdown.classList.toggle('open');
        }

        function toggleOption(code) {
            const index = selectedMessages.indexOf(code);
            if (index > -1) {
                selectedMessages.splice(index, 1);
            } else {
                selectedMessages.push(code);
            }
            updateSelectedDisplay();
        }

        function removeTag(code, event) {
            event.stopPropagation();
            const index = selectedMessages.indexOf(code);
            if (index > -1) {
                selectedMessages.splice(index, 1);
                updateSelectedDisplay();
            }
        }

        function updateSelectedDisplay() {
            const trigger = document.querySelector('.multi-select-trigger');
            const placeholder = document.getElementById('select-placeholder');
            const options = document.querySelectorAll('.multi-select-option');

            // Update option styling
            options.forEach(opt => {
                const value = parseInt(opt.dataset.value);
                if (selectedMessages.includes(value)) {
                    opt.classList.add('selected');
                    opt.querySelector('.checkbox').textContent = '✓';
                } else {
                    opt.classList.remove('selected');
                    opt.querySelector('.checkbox').textContent = '';
                }
            });

            // Update trigger display
            if (selectedMessages.length === 0) {
                placeholder.style.display = 'inline';
                // Remove all tags
                trigger.querySelectorAll('.selected-tag').forEach(t => t.remove());
            } else {
                placeholder.style.display = 'none';
                // Clear and rebuild tags
                trigger.querySelectorAll('.selected-tag').forEach(t => t.remove());
                selectedMessages.forEach(code => {
                    const tag = document.createElement('span');
                    tag.className = 'selected-tag';
                    tag.innerHTML = `
                        ${messageTypes[code] ? messageTypes[code].substring(0, 25) + (messageTypes[code].length > 25 ? '...' : '') : 'Code ' + code}
                        <span class="remove-tag" onclick="removeTag(${code}, event)">×</span>
                    `;
                    trigger.insertBefore(tag, placeholder);
                });
            }
        }

        // Close dropdown on outside click
        document.addEventListener('click', (e) => {
            const container = document.getElementById('messages-select');
            if (!container.contains(e.target)) {
                document.getElementById('messages-dropdown').classList.remove('open');
            }
        });

        // Load helps
        async function loadHelps() {
            const container = document.getElementById('table-container');
            container.innerHTML = '<div class="loading"><span class="spinner"></span>Loading responders...</div>';

            try {
                const status = document.getElementById('status-filter').value;
                const search = document.getElementById('search-input').value;

                let url = `${API_BASE}/Read/helps.php?page=${currentPage}&limit=20`;
                if (status) url += `&status=${status}`;
                if (search) url += `&search=${encodeURIComponent(search)}`;

                const res = await fetch(url);
                const data = await res.json();

                if (data.success) {
                    helps = data.data.helps || [];
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
                container.innerHTML = `<div class="empty-state"><div class="empty-state-icon">❌</div><div>Error loading responders</div></div>`;
                console.error('Error:', error);
            }
        }

        function updateStats() {
            document.getElementById('total-count').textContent = helps.length;
            document.getElementById('available-count').textContent = helps.filter(h => h.status === 'available').length;
            document.getElementById('dispatched-count').textContent = helps.filter(h => h.status === 'dispatched').length;
            document.getElementById('busy-count').textContent = helps.filter(h => h.status === 'busy').length;
        }

        function renderTable() {
            const container = document.getElementById('table-container');

            if (helps.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">⛑</div>
                        <div class="empty-state-text">No responders found</div>
                        <button class="btn btn-primary" onclick="openModal('create')">+ Add First Responder</button>
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
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>ETA</th>
                            <th>Responds To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${helps.map(help => {
                const forMessages = help.for_messages || [];
                return `
                            <tr>
                                <td>#${help.HID}</td>
                                <td><strong>${help.name}</strong></td>
                                <td>${help.contact}</td>
                                <td>${help.location || '-'}</td>
                                <td>
                                    <span class="status-badge ${help.status || 'available'}">
                                        <span class="status-dot ${help.status || 'available'}"></span>
                                        ${(help.status || 'available').charAt(0).toUpperCase() + (help.status || 'available').slice(1)}
                                    </span>
                                </td>
                                <td>${help.eta || '-'}</td>
                                <td>
                                    <div class="for-messages-list">
                                        ${forMessages.length > 0 ?
                        forMessages.slice(0, 2).map(code =>
                            `<span class="message-tag" title="${messageTypes[code] || 'Code ' + code}">${getMessageShortName(code)}</span>`
                        ).join('') +
                        (forMessages.length > 2 ? `<span class="message-tag-more">+${forMessages.length - 2} more</span>` : '')
                        : '<span style="color: var(--text-muted); font-size: 12px;">All types</span>'
                    }
                                    </div>
                                </td>
                                <td class="actions">
                                    <button class="btn btn-icon edit" onclick="openModal('edit', ${help.HID})" title="Edit">✎</button>
                                    <button class="btn btn-icon delete" onclick="openDeleteModal(${help.HID})" title="Delete">🗑</button>
                                </td>
                            </tr>
                        `;
            }).join('')}
                    </tbody>
                </table>
            `;

            document.getElementById('pagination').style.display = 'flex';
        }

        function getMessageShortName(code) {
            const fullName = messageTypes[code] || 'Unknown';
            // Get short version (first 2 words or 15 chars)
            const words = fullName.split(' ').slice(0, 2).join(' ');
            return words.length > 15 ? words.substring(0, 15) + '...' : words;
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
            loadHelps();
        }

        // Modal functions
        function openModal(mode, id = null) {
            const overlay = document.getElementById('modal-overlay');
            const title = document.getElementById('modal-title');
            const form = document.getElementById('help-form');

            form.reset();
            document.getElementById('help-id').value = '';
            selectedMessages = [];
            updateSelectedDisplay();

            if (mode === 'edit' && id) {
                title.textContent = 'Edit Responder';
                const help = helps.find(h => h.HID == id);
                if (help) {
                    document.getElementById('help-id').value = help.HID;
                    document.getElementById('help-name').value = help.name || '';
                    document.getElementById('help-contact').value = help.contact || '';
                    document.getElementById('help-location').value = help.location || '';
                    document.getElementById('help-eta').value = help.eta || '';
                    document.getElementById('help-status').value = help.status || 'available';

                    // Set for_messages
                    if (help.for_messages && Array.isArray(help.for_messages)) {
                        selectedMessages = help.for_messages.map(Number);
                        updateSelectedDisplay();
                    }
                }
            } else {
                title.textContent = 'Add Responder';
            }

            overlay.classList.add('active');
        }

        function closeModal() {
            document.getElementById('modal-overlay').classList.remove('active');
            document.getElementById('messages-dropdown').classList.remove('open');
        }

        async function saveHelp() {
            const id = document.getElementById('help-id').value;
            const name = document.getElementById('help-name').value.trim();
            const contact = document.getElementById('help-contact').value.trim();
            const location = document.getElementById('help-location').value.trim();
            const eta = document.getElementById('help-eta').value.trim();
            const status = document.getElementById('help-status').value;

            if (!name || !contact) {
                showToast('Please fill in name and contact', 'error');
                return;
            }

            try {
                let url, method, body;

                const payload = {
                    name: name,
                    contact: contact,
                    location: location || null,
                    eta: eta || null,
                    status: status,
                    for_messages: selectedMessages
                };

                if (id) {
                    // Update
                    url = `${API_BASE}/Update/helps.php`;
                    method = 'PUT';
                    payload.HID = parseInt(id);
                } else {
                    // Create
                    url = `${API_BASE}/Create/helps.php`;
                    method = 'POST';
                }

                const res = await fetch(url, {
                    method: method,
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();

                if (data.success) {
                    showToast(id ? 'Responder updated successfully' : 'Responder created successfully');
                    closeModal();
                    loadHelps();
                } else {
                    showToast(data.message || 'Error saving responder', 'error');
                }
            } catch (error) {
                showToast('Error saving responder', 'error');
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
                const res = await fetch(`${API_BASE}/Delete/helps.php?id=${deleteId}`, {
                    method: 'DELETE'
                });

                const data = await res.json();

                if (data.success) {
                    showToast('Responder deleted successfully');
                    closeDeleteModal();
                    loadHelps();
                } else {
                    showToast(data.message || 'Error deleting responder', 'error');
                }
            } catch (error) {
                showToast('Error deleting responder', 'error');
                console.error('Error:', error);
            }
        }

        // Event listeners
        document.getElementById('search-input').addEventListener('input', debounce(loadHelps, 300));
        document.getElementById('status-filter').addEventListener('change', () => { currentPage = 1; loadHelps(); });

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
            loadMessageTypes().then(() => loadHelps());
        });
    </script>
</body>

</html>