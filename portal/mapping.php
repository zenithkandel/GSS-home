<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapping - LifeLine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/fontawesome.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/solid.css">
    <link rel="stylesheet" href="css/shared.css">
    <link rel="stylesheet" href="css/mapping.css">
</head>

<body>
    <div class="header">
        <h1>Index Mapping</h1>
        <button class="btn btn-primary" onclick="openCreateModal()"><i class="fa-solid fa-plus"></i> New
            Mapping</button>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-number" id="total-count">-</span>
            <span class="stat-label">Total</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-info" id="location-count">-</span>
            <span class="stat-label">Location</span>
        </div>
        <div class="stat-item">
            <span class="stat-number text-warning" id="message-count">-</span>
            <span class="stat-label">Message</span>
        </div>
    </div>

    <div class="toolbar">
        <div class="toolbar-left">
            <select class="filter-select" id="type-filter">
                <option value="">All Types</option>
                <option value="location">Location</option>
                <option value="message">Message</option>
            </select>
            <input type="text" class="search-input" id="search-input" placeholder="Search...">
        </div>
        <button class="btn" onclick="loadIndexes()"><i class="fa-solid fa-rotate"></i> Refresh</button>
    </div>

    <!-- Index Cards Container -->
    <div id="indexes-container">
        <div class="loading">
            <span class="spinner"></span>
            Loading...
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal-overlay" id="edit-modal">
        <div class="modal modal-lg">
            <div class="modal-header">
                <span class="modal-title" id="modal-title">New Index Mapping</span>
                <button class="modal-close" onclick="closeEditModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="index-form">
                    <input type="hidden" id="edit-id">

                    <div class="form-group">
                        <label for="index-type">Type *</label>
                        <select id="index-type" required>
                            <option value="">Select Type</option>
                            <option value="location">Location</option>
                            <option value="message">Message</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="index-description">Description</label>
                        <textarea id="index-description" rows="2" placeholder="Optional description..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Mapping Entries</label>
                        <div class="mapping-entries" id="mapping-entries">
                            <!-- Dynamic entries will be added here -->
                        </div>
                        <button type="button" class="btn btn-sm" onclick="addMappingEntry()">
                            <i class="fa-solid fa-plus"></i> Add Entry
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeEditModal()">Cancel</button>
                <button class="btn btn-primary" onclick="saveIndex()"><i class="fa-solid fa-check"></i> Save</button>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal-overlay" id="view-modal">
        <div class="modal modal-lg">
            <div class="modal-header">
                <span class="modal-title"><span id="view-title">Index Details</span></span>
                <button class="modal-close" onclick="closeViewModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" id="view-content">
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeViewModal()">Close</button>
                <button class="btn btn-primary" id="view-edit-btn" onclick="editFromView()"><i
                        class="fa-solid fa-pen"></i> Edit</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal modal-sm">
            <div class="modal-header">
                <span class="modal-title">Delete Mapping</span>
                <button class="modal-close" onclick="closeDeleteModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p class="delete-warning">
                    Are you sure you want to delete this index mapping?
                </p>
                <p class="delete-info">
                    Type: <strong id="delete-type">-</strong><br>
                    <span style="color: var(--text-muted);">This may affect message/device decoding.</span>
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn btn-danger" onclick="confirmDelete()"><i class="fa-solid fa-trash"></i>
                    Delete</button>
            </div>
        </div>
    </div>

    <!-- Add Single Entry Modal -->
    <div class="modal-overlay" id="add-entry-modal">
        <div class="modal modal-sm">
            <div class="modal-header">
                <span class="modal-title">Add Entry</span>
                <button class="modal-close" onclick="closeAddEntryModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="add-entry-iid">
                <div class="form-group">
                    <label for="new-entry-code">Code *</label>
                    <input type="number" id="new-entry-code" placeholder="16" required min="1">
                </div>
                <div class="form-group">
                    <label for="new-entry-value">Value *</label>
                    <input type="text" id="new-entry-value" placeholder="Name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeAddEntryModal()">Cancel</button>
                <button class="btn btn-primary" onclick="saveNewEntry()"><i class="fa-solid fa-plus"></i> Add</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <i class="fa-solid fa-check"></i>
        <span id="toast-message">Success</span>
    </div>

    <script src="js/mapping.js"></script>
</body>

</html>