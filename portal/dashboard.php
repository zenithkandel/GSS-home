<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LifeLine</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-duotone-solid.css">
    <link rel="stylesheet" href="css/shared.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <!-- Alert Priority Banner - Shows when active alerts exist -->
    <div class="alert-banner" id="alert-banner">
        <div class="alert-banner-inner">
            <div class="alert-banner-pulse"></div>
            <i class="fa-duotone fa-triangle-exclamation"></i>
            <span class="alert-banner-text">
                <strong id="banner-count">0</strong> Active Emergency Alert<span id="banner-plural">s</span>
            </span>
            <button class="alert-banner-btn" onclick="scrollToAlerts()">
                View Alerts <i class="fa-duotone fa-angle-down"></i>
            </button>
        </div>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>Dashboard</h1>
        <div class="live-indicator">
            <div class="pulse-dot" id="live-dot"></div>
            <span class="live-text" id="live-text">Live</span>
        </div>
    </div>

    <!-- Stats Overview - Compact -->
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-icon"><i class="fa-duotone fa-microchip"></i></div>
            <div class="stat-content">
                <div class="stat-label">Total Devices</div>
                <div class="stat-value" id="stat-total">-</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon success"><i class="fa-duotone fa-wifi"></i></div>
            <div class="stat-content">
                <div class="stat-label">Online</div>
                <div class="stat-value success" id="stat-active">-</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon warning"><i class="fa-duotone fa-wifi-slash"></i></div>
            <div class="stat-content">
                <div class="stat-label">Offline</div>
                <div class="stat-value warning" id="stat-offline">-</div>
            </div>
        </div>
        <div class="stat-card alert-stat" id="alert-stat-card">
            <div class="stat-card-icon danger"><i class="fa-duotone fa-bell"></i></div>
            <div class="stat-content">
                <div class="stat-label">Active Alerts</div>
                <div class="stat-value danger" id="stat-alerts">-</div>
            </div>
        </div>
    </section>

    <!-- Primary Alert Section - Full Width Priority -->
    <section class="alerts-priority" id="alerts-section">
        <div class="alerts-header">
            <div class="alerts-header-left">
                <i class="fa-duotone fa-bell"></i>
                <span class="alerts-title">Emergency Alerts</span>
                <span class="alerts-badge" id="alert-count">0</span>
            </div>
            <div class="alerts-header-right">
                <span class="refresh-indicator" id="refresh-indicator">
                    <i class="fa-duotone fa-rotate"></i> Auto-refresh
                </span>
            </div>
        </div>

        <!-- Latest Alert Highlight -->
        <div class="latest-alert-container" id="latest-alert-container">
            <!-- Populated by JS -->
        </div>

        <!-- Other Alerts List -->
        <div class="alerts-list" id="alerts-list">
            <div class="loading">
                <span class="spinner"></span>
                Loading alerts...
            </div>
        </div>
    </section>

    <!-- Device Status - Secondary -->
    <section class="devices-section">
        <div class="section-header">
            <span class="section-title"><i class="fa-duotone fa-microchip"></i> Device Status</span>
            <span class="badge neutral" id="device-count">0</span>
        </div>
        <div class="device-grid" id="device-list">
            <div class="loading">
                <span class="spinner"></span>
                Loading...
            </div>
        </div>
    </section>

    <!-- Alert Detail Modal -->
    <div class="modal-overlay" id="alert-modal">
        <div class="modal modal-lg">
            <div class="modal-header">
                <span class="modal-title" id="modal-alert-title">Alert Details</span>
                <button class="modal-close" onclick="closeAlertModal()"><i class="fa-duotone fa-xmark"></i></button>
            </div>
            <div class="modal-body" id="modal-alert-body">
                <!-- Alert details will be inserted here -->
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="closeAlertModal()">Close</button>
                <button class="btn btn-success" onclick="resolveAlert(currentAlert?.MID)">
                    <i class="fa-duotone fa-check"></i> Resolve
                </button>
                <button class="btn btn-primary" id="btn-open-maps" onclick="openCurrentInMaps()">
                    <i class="fa-duotone fa-map"></i> Open Map
                </button>
            </div>
        </div>
    </div>

    <script src="js/dashboard.js"></script>
</body>

</html>