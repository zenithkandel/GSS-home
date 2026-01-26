<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts - LifeLine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-duotone-solid.css">
    <link rel="stylesheet" href="css/shared.css">
    <link rel="stylesheet" href="css/messages.css">
</head>

<body>
    <div class="header">
        <h1>Alerts</h1>
        <button class="btn btn-primary" onclick="openCreateModal()"><i class="fa-duotone fa-plus"></i> New
            Alert</button>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-number" id="total-count">-</span>
            <span class="stat-label">Total</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-danger" id="active-count">-</span>
            <span class="stat-label">Active</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-success" id="resolved-count">-</span>
            <span class="stat-label">Resolved</span>
        </div>
    </div>

    <div class="toolbar">
        <div class="toolbar-left">
            <div class="search-box">
                <i class="fa-duotone fa-magnifying-glass search-icon"></i>
                <input type="text" id="search-input" placeholder="Search alerts...">
            </div>
            <select class="filter-select" id="status-filter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="resolved">Resolved</option>
            </select>
            <select class="filter-select" id="device-filter">
                <option value="">All Devices</option>
            </select>
            <select class="filter-select" id="message-type-filter">
                <option value="">All Types</option>
            </select>
            <input type="date" class="date-input" id="from-date">
            <input type="date" class="date-input" id="to-date">
        </div>
        <button class="btn" onclick="loadMessages()"><i class="fa-duotone fa-rotate"></i> Refresh</button>
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

    <!-- Create Modal -->
    <div class="modal-overlay" id="create-modal">
        <div class="modal">
            <div class="modal-header">
                <span class="modal-title">New Alert</span>
                <button class="modal-close" onclick="closeCreateModal()"><i class="fa-duotone fa-xmark"></i></button>
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
                        <label for="msg-type">Alert Type *</label>
                        <select id="msg-type" required>
                            <option value="">Select Type</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="msg-rssi">Signal (RSSI)</label>
                        <input type="number" id="msg-rssi" placeholder="-65" min="-120" max="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeCreateModal()" id="cancel-btn">Cancel</button>
                <button class="btn btn-primary" onclick="createMessage()" id="send-btn">
                    <span class="btn-text"><i class="fa-duotone fa-plus"></i> Create</span>
                    <span class="btn-loading" style="display: none;">
                        <i class="fa-duotone fa-spinner fa-spin"></i> Sending...
                    </span>
                </button>
            </div>

            <!-- Progress overlay -->
            <div class="sending-overlay" id="sending-overlay">
                <div class="sending-content">
                    <div class="sending-spinner"></div>
                    <p class="sending-title">Sending Alert</p>
                    <p class="sending-subtitle" id="sending-status">Creating...</p>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                    <p class="sending-info">Notifying all registered receivers</p>
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal-overlay" id="view-modal">
        <div class="modal modal-lg">
            <div class="modal-header">
                <span class="modal-title">Alert Details</span>
                <button class="modal-close" onclick="closeViewModal()"><i class="fa-duotone fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="view-content">
                    <div id="message-details"></div>
                    <div class="view-map-section">
                        <div class="map-container" id="view-map-container">
                            <div class="map-loading">
                                <i class="fa-duotone fa-spinner fa-spin"></i>
                                <span>Loading map...</span>
                            </div>
                        </div>
                        <div class="map-actions">
                            <button class="btn btn-primary" id="open-maps-btn" onclick="openInGoogleMaps()">
                                <i class="fa-duotone fa-map"></i> Open in Google Maps
                            </button>
                        </div>
                    </div>
                </div>
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
                <span class="modal-title">Delete Alert</span>
                <button class="modal-close" onclick="closeDeleteModal()"><i class="fa-duotone fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this alert?</p>
                <p style="color: var(--text-muted); font-size: 13px; margin-top: 8px;">
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn btn-danger" onclick="confirmDelete()"><i class="fa-duotone fa-trash"></i>
                    Delete</button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">
        <i class="fa-duotone fa-check"></i>
        <span id="toast-message"></span>
    </div>

    <script src="js/messages.js"></script>
</body>

</html>