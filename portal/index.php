<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeLine Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/all.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <nav class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <img src="../res/lifeline.png" alt="LifeLine">
            </div>
            <span class="logo-text">LifeLine</span>
        </div>

        <ul class="nav-links">
            <li>
                <a href="dashboard.php" class="nav-item active" data-page="dashboard">
                    <span class="nav-icon"><i class="fa-solid fa-grid-2"></i></span>
                    <span class="nav-label">Dashboard</span>
                    <span class="nav-indicator"></span>
                </a>
            </li>
            <li>
                <a href="messages.php" class="nav-item" data-page="messages">
                    <span class="nav-icon"><i class="fa-solid fa-message-exclamation"></i></span>
                    <span class="nav-label">Messages</span>
                    <span class="nav-indicator"></span>
                </a>
            </li>
            <li>
                <a href="devices.php" class="nav-item" data-page="devices">
                    <span class="nav-icon"><i class="fa-solid fa-microchip"></i></span>
                    <span class="nav-label">Devices</span>
                    <span class="nav-indicator"></span>
                </a>
            </li>
            <li>
                <a href="helps.php" class="nav-item" data-page="helps">
                    <span class="nav-icon"><i class="fa-solid fa-user-helmet-safety"></i></span>
                    <span class="nav-label">Responders</span>
                    <span class="nav-indicator"></span>
                </a>
            </li>
            <li>
                <a href="mapping.php" class="nav-item" data-page="mapping">
                    <span class="nav-icon"><i class="fa-solid fa-diagram-project"></i></span>
                    <span class="nav-label">Mapping</span>
                    <span class="nav-indicator"></span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <button id="theme-toggle" class="theme-btn" title="Toggle theme">
                <span class="theme-icon-dark"><i class="fa-solid fa-sun-bright"></i></span>
                <span class="theme-icon-light"><i class="fa-solid fa-moon"></i></span>
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
    <script type="module" src="../firebase.js"></script>
</body>

</html>