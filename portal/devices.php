<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devices - LifeLine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-duotone-solid.css">
    <link rel="stylesheet" href="css/shared.css?v=2.0">
    <link rel="stylesheet" href="css/devices.css?v=2.0">
</head>

<body>
    <div class="header">
        <h1>Devices</h1>
        <button class="btn btn-primary" onclick="openModal('create')">
            <i class="fa-duotone fa-plus"></i> Add Device
        </button>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-number" id="total-count">-</span>
            <span class="stat-label">Total</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-success" id="active-count">-</span>
            <span class="stat-label">Active</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-warning" id="inactive-count">-</span>
            <span class="stat-label">Inactive</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-muted" id="maintenance-count">-</span>
            <span class="stat-label">Maintenance</span>
        </div>
    </div>

    <div class="toolbar">
        <div class="toolbar-left">
            <div class="search-box">
                <i class="fa-duotone fa-magnifying-glass"></i>
                <input type="text" id="search-input" placeholder="Search...">
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
        <button class="btn" onclick="loadDevices()">
            <i class="fa-duotone fa-rotate"></i> Refresh
        </button>
    </div>

    <!-- Bulk Action Bar -->
    <div class="bulk-action-bar" id="bulk-action-bar">
        <div class="bulk-info">
            <span class="bulk-count"><span id="selected-count">0</span> selected</span>
            <button class="btn btn-sm" onclick="clearSelection()"><i class="fa-solid fa-xmark"></i> Clear</button>
        </div>
        <div class="bulk-actions">
            <button class="btn btn-sm btn-success" onclick="bulkUpdateStatus('active')"><i
                    class="fa-solid fa-circle-check"></i> Set Active</button>
            <button class="btn btn-sm" onclick="bulkUpdateStatus('inactive')"><i class="fa-solid fa-circle-minus"></i>
                Set Inactive</button>
            <button class="btn btn-sm" onclick="bulkUpdateStatus('maintenance')"><i class="fa-solid fa-wrench"></i>
                Maintenance</button>
            <button class="btn btn-sm btn-danger" onclick="openBulkDeleteModal()"><i class="fa-solid fa-trash-can"></i>
                Delete</button>
        </div>
    </div>

    <div id="table-container">
        <div class="loading">
            <span class="spinner"></span>
            Loading...
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
                <button class="modal-close" onclick="closeModal()">
                    <i class="fa-duotone fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="device-form">
                    <input type="hidden" id="device-id">

                    <div class="form-group" id="did-group">
                        <label for="device-did" id="did-label">Device ID (DID)</label>
                        <input type="number" id="device-did" placeholder="Auto-generated if empty" min="1">
                        <small id="did-hint" class="form-hint">Leave empty to auto-generate</small>
                    </div>

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
                <button class="btn btn-primary" onclick="saveDevice()">
                    <i class="fa-duotone fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <span class="modal-title">Delete Device</span>
                <button class="modal-close" onclick="closeDeleteModal()">
                    <i class="fa-duotone fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this device?</p>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 8px;">
                    This will also delete all associated alerts.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fa-duotone fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Modal -->
    <div class="modal-overlay" id="bulk-delete-modal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <span class="modal-title"><i class="fa-solid fa-triangle-exclamation" style="color: var(--danger);"></i>
                    Delete Multiple Devices</span>
                <button class="modal-close" onclick="closeBulkDeleteModal()">
                    <i class="fa-duotone fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong><span id="bulk-delete-count">0</span> devices</strong>?</p>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 8px;">
                    This action cannot be undone. All associated alerts will also be deleted.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeBulkDeleteModal()">Cancel</button>
                <button class="btn btn-danger" onclick="confirmBulkDelete()">
                    <i class="fa-duotone fa-trash"></i> Delete All
                </button>
            </div>
        </div>
    </div>

    <!-- Map Modal -->
    <div class="modal-overlay" id="map-modal">
        <div class="modal" style="max-width: 600px;">
            <div class="modal-header">
                <span class="modal-title" id="map-modal-title">Location</span>
                <button class="modal-close" onclick="closeMapModal()">
                    <i class="fa-duotone fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body" style="padding: 0;">
                <div class="map-container" id="map-container">
                    <div class="map-loading">
                        <i class="fa-duotone fa-spinner fa-spin"></i>
                        <span>Loading...</span>
                    </div>
                </div>
                <div class="map-info" id="map-info">
                    <span class="map-location-name" id="map-location-name"></span>
                    <button class="btn btn-primary" id="open-gmaps-btn" onclick="openInGoogleMaps()">
                        <i class="fa-duotone fa-map"></i> Open in Maps
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">
        <i class="fa-duotone fa-check"></i>
        <span id="toast-message"></span>
    </div>

    <script src="js/devices.js?v=2.0"></script>
</body>

</html>