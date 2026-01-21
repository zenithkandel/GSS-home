<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeLine Portal</title>
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <nav class="sidebar">
        <div class="logo">
            <span class="logo-icon">◈</span>
            <span class="logo-text">LifeLine</span>
        </div>

        <ul class="nav-links">
            <li>
                <a href="dashboard.php" class="nav-item active" data-page="dashboard">
                    <span class="nav-icon">⌂</span>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="messages.php" class="nav-item" data-page="messages">
                    <span class="nav-icon">✉</span>
                    <span class="nav-label">Messages</span>
                </a>
            </li>
            <li>
                <a href="devices.php" class="nav-item" data-page="devices">
                    <span class="nav-icon">◎</span>
                    <span class="nav-label">Devices</span>
                </a>
            </li>
            <li>
                <a href="helps.php" class="nav-item" data-page="helps">
                    <span class="nav-icon">⛑</span>
                    <span class="nav-label">Responders</span>
                </a>
            </li>
            <li>
                <a href="settings.php" class="nav-item" data-page="settings">
                    <span class="nav-icon">⚙</span>
                    <span class="nav-label">Settings</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <button id="theme-toggle" class="theme-btn" title="Toggle theme">
                <span class="theme-icon-dark">☀</span>
                <span class="theme-icon-light">☾</span>
            </button>
            <div class="connection-status">
                <span class="status-dot" id="connection-dot"></span>
                <span class="status-text" id="connection-text">Connecting...</span>
            </div>
        </div>
    </nav>

    <main class="content">
        <iframe id="content-frame" src="dashboard.php" frameborder="0"></iframe>
    </main>

    <script src="js/index.js"></script>
</body>

</html>