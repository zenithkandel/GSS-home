<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devices - LifeLine</title>
    <link rel="stylesheet" href="css/shared.css">
    <link rel="stylesheet" href="css/devices.css">
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

    <script src="js/devices.js"></script>
</body>

</html>