<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emails - LifeLine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/fontawesome.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/solid.css">
    <link rel="stylesheet" href="css/shared.css?v=2.0">
    <link rel="stylesheet" href="css/emails.css?v=2.0">
</head>

<body>
    <div class="header">
        <h1>Email Receivers</h1>
        <button class="btn btn-primary" onclick="openModal()"><i class="fa-solid fa-plus"></i> Add Email</button>
    </div>

    <div class="info-banner">
        <i class="fa-solid fa-info-circle"></i>
        <span>These emails receive alerts when new emergencies are created.</span>
    </div>

    <div class="stats-bar">
        <div class="stat-item">
            <span class="stat-number" id="total-count">-</span>
            <span class="stat-label">Total</span>
        </div>
    </div>

    <div id="table-container">
        <div class="loading">
            <span class="spinner"></span>
            Loading...
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal" style="max-width: 420px;">
            <div class="modal-header">
                <span class="modal-title" id="modal-title">Add Email</span>
                <button class="modal-close" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <form id="email-form">
                    <input type="hidden" id="email-sn">
                    <div class="form-group">
                        <label for="email-input">Email Address *</label>
                        <input type="email" id="email-input" placeholder="email@example.com" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="saveEmail()"><i class="fa-solid fa-check"></i> Save</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="delete-modal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <span class="modal-title">Delete Email</span>
                <button class="modal-close" onclick="closeDeleteModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this email?</p>
                <p id="delete-email-text" style="font-weight: 500; margin-top: 8px; color: var(--text-secondary);"></p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn btn-danger" onclick="confirmDelete()"><i class="fa-solid fa-trash"></i>
                    Delete</button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">
        <i class="fa-solid fa-check"></i>
        <span id="toast-message"></span>
    </div>

    <script src="js/emails.js?v=2.0"></script>
</body>

</html>