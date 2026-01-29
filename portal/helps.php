<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responders - LifeLine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-duotone-solid.css">
    <link rel="stylesheet" href="css/shared.css?v=2.0">
    <link rel="stylesheet" href="css/helps.css?v=2.0">
</head>

<body>
    <div class="header">
        <h1>Responders</h1>
        <button class="btn btn-primary" onclick="openModal('create')"><i class="fa-duotone fa-plus"></i> Add
            Responder</button>
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
                <i class="fa-duotone fa-magnifying-glass search-icon"></i>
                <input type="text" id="search-input" placeholder="Search...">
            </div>
            <select class="filter-select" id="status-filter">
                <option value="">All Status</option>
                <option value="available">Available</option>
                <option value="dispatched">Dispatched</option>
                <option value="busy">Busy</option>
            </select>
        </div>
        <button class="btn" onclick="loadHelps()"><i class="fa-duotone fa-rotate"></i> Refresh</button>
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
                <span class="modal-title" id="modal-title">Add Responder</span>
                <button class="modal-close" onclick="closeModal()"><i class="fa-duotone fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="help-form">
                    <input type="hidden" id="help-id">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="help-name">Name *</label>
                            <input type="text" id="help-name" placeholder="Rescue Team Alpha" required>
                        </div>

                        <div class="form-group">
                            <label for="help-contact">Contact *</label>
                            <input type="text" id="help-contact" placeholder="+1234567890" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="help-location">Location</label>
                            <input type="text" id="help-location" placeholder="Base Camp">
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
                        <label>Responds To</label>
                        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">
                            Select alert types this responder handles
                        </p>
                        <div class="multi-select-container" id="messages-select">
                            <div class="multi-select-trigger" onclick="toggleDropdown()" tabindex="0">
                                <span class="placeholder" id="select-placeholder">Select alert types...</span>
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
                <button class="btn btn-primary" onclick="saveHelp()"><i class="fa-duotone fa-check"></i> Save</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <span class="modal-title">Delete Responder</span>
                <button class="modal-close" onclick="closeDeleteModal()"><i class="fa-duotone fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this responder?</p>
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

    <script src="js/helps.js?v=2.0"></script>
</body>

</html>