<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LifeLine</title>
    <link rel="stylesheet" href="css/shared.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
    <div class="header">
        <h1>Dashboard</h1>
        <div class="live-indicator">
            <div class="pulse-dot" id="live-dot"></div>
            <span class="live-text" id="live-text">Listening...</span>
        </div>
    </div>

    <!-- Alerts Section -->
    <section class="alerts-section">
        <div class="section-header">
            <span class="section-title">Emergency Alerts</span>
            <span class="badge" id="alert-count">0 Active</span>
        </div>
        <div class="alerts-container" id="alerts-container">
            <div class="loading">
                <span class="spinner"></span>
                Loading alerts...
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Devices</div>
            <div class="stat-value" id="stat-total">-</div>
            <div class="stat-sub" id="stat-total-sub">Loading...</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Active Devices</div>
            <div class="stat-value success" id="stat-active">-</div>
            <div class="stat-sub" id="stat-active-sub">Online now</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Offline Devices</div>
            <div class="stat-value warning" id="stat-offline">-</div>
            <div class="stat-sub" id="stat-offline-sub">Needs attention</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Messages Today</div>
            <div class="stat-value" id="stat-messages">-</div>
            <div class="stat-sub" id="stat-messages-sub">Since midnight</div>
        </div>
    </section>

    <!-- Recent Device Activity -->
    <section class="activity-section">
        <div class="section-header">
            <span class="section-title">Device Status</span>
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