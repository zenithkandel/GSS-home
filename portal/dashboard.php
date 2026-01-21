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
    <link rel="stylesheet" href="css/shared.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <div class="header">
        <h1><i class="fa-solid fa-grid-2"></i> Dashboard</h1>
        <div class="live-indicator">
            <div class="pulse-dot" id="live-dot"></div>
            <span class="live-text" id="live-text">Listening...</span>
        </div>
    </div>

    <!-- Alerts Section -->
    <section class="alerts-section">
        <div class="section-header">
            <span class="section-title"><i class="fa-solid fa-bell"></i> Emergency Alerts</span>
            <span class="badge" id="alert-count"><i class="fa-solid fa-circle-exclamation"></i> 0 Active</span>
        </div>
        <div class="alerts-container" id="alerts-container">
            <div class="loading">
                <span class="spinner"></span>
                Loading alerts...
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-grid stagger-animation">
        <div class="stat-card">
            <div class="stat-card-icon"><i class="fa-solid fa-microchip"></i></div>
            <div class="stat-label">Total Devices</div>
            <div class="stat-value" id="stat-total">-</div>
            <div class="stat-sub" id="stat-total-sub">Loading...</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon"><i class="fa-solid fa-signal-bars"></i></div>
            <div class="stat-label">Active Devices</div>
            <div class="stat-value success" id="stat-active">-</div>
            <div class="stat-sub" id="stat-active-sub">Online now</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon"><i class="fa-solid fa-signal-bars-slash"></i></div>
            <div class="stat-label">Offline Devices</div>
            <div class="stat-value warning" id="stat-offline">-</div>
            <div class="stat-sub" id="stat-offline-sub">Needs attention</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-icon"><i class="fa-solid fa-message-lines"></i></div>
            <div class="stat-label">Messages Today</div>
            <div class="stat-value" id="stat-messages">-</div>
            <div class="stat-sub" id="stat-messages-sub">Since midnight</div>
        </div>
    </section>

    <!-- Recent Device Activity -->
    <section class="activity-section">
        <div class="section-header">
            <span class="section-title"><i class="fa-solid fa-tower-broadcast"></i> Device Status</span>
        </div>
        <div class="activity-list" id="device-list">
            <div class="loading">
                <span class="spinner"></span>
                Loading devices...
            </div>
        </div>
    </section>

    <script src="js/dashboard.js"></script>
</body>

</html>