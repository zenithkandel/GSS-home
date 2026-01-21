<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - LifeLine</title>
    <link rel="stylesheet" href="css/shared.css">
    <link rel="stylesheet" href="css/messages.css">
</head>

<body>
    <div class="header">
        <h1>Emergency Messages</h1>
        <button class="btn btn-primary" onclick="openCreateModal()">+ New Message</button>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-number" id="total-count">-</span>
            <span class="stat-label">Total</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-danger" id="critical-count">-</span>
            <span class="stat-label">Critical</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-warning" id="warning-count">-</span>
            <span class="stat-label">Warnings</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-success" id="info-count">-</span>
            <span class="stat-label">Info</span>
        </div>
    </div>

    <div class="toolbar">
        <div class="toolbar-left">
            <select class="filter-select" id="device-filter">
                <option value="">All Devices</option>
            </select>
            <select class="filter-select" id="message-type-filter">
                <option value="">All Message Types</option>
            </select>
            <input type="date" class="date-input" id="from-date" placeholder="From">
            <input type="date" class="date-input" id="to-date" placeholder="To">
        </div>
        <button class="btn" onclick="loadMessages()">↻ Refresh</button>
    </div>

    <div id="table-container">
        <div class="loading">
            <span class="spinner"></span>
            Loading messages...
        </div>
    </div>

    <div class="pagination" id="pagination" style="display: none;">
        <div class="pagination-info" id="pagination-info">Showing 0 of 0</div>
        <div class="pagination-controls" id="pagination-controls"></div>
    </div>

    <!-- Create Modal -->
    <div class="modal-overlay" id="create-modal">
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title">New Emergency Message</span>
                <button class="modal-close" onclick="closeCreateModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="message-form">
                    <div class="form-group">
                        <label for="msg-device">Device *</label>
                        <select id="msg-device" required>
                            <option value="">Select Device</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="msg-type">Message Type *</label>
                        <select id="msg-type" required>
                            <option value="">Select Message Type</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="msg-rssi">Signal Strength (RSSI)</label>
                        <input type="number" id="msg-rssi" placeholder="-65" min="-120" max="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeCreateModal()">Cancel</button>
                <button class="btn btn-primary" onclick="createMessage()">Create Message</button>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal-overlay" id="view-modal">
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title">Message Details</span>
                <button class="modal-close" onclick="closeViewModal()">&times;</button>
            </div>
            <div class="modal-body" id="message-details">
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <span class="modal-title">Confirm Delete</span>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this message?</p>
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

    <script src="js/messages.js"></script>
</body>

</html>