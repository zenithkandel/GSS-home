<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>LifeLine Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v7.1.0/css/sharp-duotone-solid.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>
    <!-- Mobile Header -->
    <header class="mobile-header">
        <div class="logo">
            <div class="logo-icon">
                <img src="../res/lifeline.png" alt="LifeLine">
            </div>
            <span class="logo-text">LifeLine</span>
        </div>
        <button class="mobile-theme-btn" id="mobile-theme-toggle">
            <span class="theme-icon-dark"><i class="fa-duotone fa-sun-bright"></i></span>
            <span class="theme-icon-light"><i class="fa-duotone fa-moon-stars"></i></span>
        </button>
    </header>

    <!-- Desktop Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-top">
            <div class="logo">
                <div class="logo-icon">
                    <img src="../res/lifeline.png" alt="LifeLine">
                </div>
                <span class="logo-text">LifeLine</span>
            </div>
            <button class="collapse-btn" id="collapse-btn" title="Collapse sidebar">
                <i class="fa-duotone fa-sidebar-flip"></i>
            </button>
        </div>

        <ul class="nav-links">
            <li>
                <a href="dashboard.php" class="nav-item active" data-page="dashboard">
                    <span class="nav-icon"><i class="fa-duotone fa-objects-column"></i></span>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="messages.php" class="nav-item" data-page="messages">
                    <span class="nav-icon"><i class="fa-duotone fa-bell"></i></span>
                    <span class="nav-label">Alerts</span>
                </a>
            </li>
            <li>
                <a href="devices.php" class="nav-item" data-page="devices">
                    <span class="nav-icon"><i class="fa-duotone fa-microchip"></i></span>
                    <span class="nav-label">Devices</span>
                </a>
            </li>
            <li>
                <a href="helps.php" class="nav-item" data-page="helps">
                    <span class="nav-icon"><i class="fa-duotone fa-user-helmet-safety"></i></span>
                    <span class="nav-label">Responders</span>
                </a>
            </li>
            <li>
                <a href="mapping.php" class="nav-item" data-page="mapping">
                    <span class="nav-icon"><i class="fa-duotone fa-diagram-project"></i></span>
                    <span class="nav-label">Mapping</span>
                </a>
            </li>
            <li>
                <a href="emails.php" class="nav-item" data-page="emails">
                    <span class="nav-icon"><i class="fa-duotone fa-envelope"></i></span>
                    <span class="nav-label">Emails</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <button id="theme-toggle" class="theme-btn" title="Toggle theme">
                <span class="theme-icon-dark"><i class="fa-duotone fa-sun-bright"></i></span>
                <span class="theme-icon-light"><i class="fa-duotone fa-moon-stars"></i></span>
            </button>
            <div class="connection-status">
                <span class="status-dot" id="connection-dot"></span>
                <span class="status-text" id="connection-text">Connecting...</span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="content">
        <iframe id="content-frame" src="dashboard.php" frameborder="0"></iframe>
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-nav">
        <div class="mobile-nav-inner">
            <a href="dashboard.php" class="mobile-nav-item active" data-page="dashboard">
                <i class="fa-solid fa-grid-2"></i>
                <span>Home</span>
            </a>
            <a href="messages.php" class="mobile-nav-item" data-page="messages">
                <i class="fa-solid fa-bell"></i>
                <span>Alerts</span>
            </a>
            <a href="devices.php" class="mobile-nav-item" data-page="devices">
                <i class="fa-solid fa-microchip"></i>
                <span>Devices</span>
            </a>
            <button class="mobile-more-btn" id="mobile-more-btn">
                <i class="fa-solid fa-ellipsis"></i>
                <span>More</span>
            </button>
        </div>
    </nav>

    <!-- Mobile More Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobile-menu-overlay">
        <div class="mobile-menu">
            <a href="helps.php" class="mobile-menu-item" data-page="helps">
                <i class="fa-solid fa-users"></i>
                <span>Responders</span>
            </a>
            <a href="mapping.php" class="mobile-menu-item" data-page="mapping">
                <i class="fa-solid fa-diagram-project"></i>
                <span>Mapping</span>
            </a>
            <a href="emails.php" class="mobile-menu-item" data-page="emails">
                <i class="fa-solid fa-envelope"></i>
                <span>Emails</span>
            </a>
        </div>
    </div>

    <script src="js/index.js"></script>
    <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.8.0/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.8.0/firebase-analytics.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/12.8.0/firebase-messaging.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyDq8x0Gb-1lBAVBmPZzfCOYmF6gqB1NstA",
            authDomain: "lifeline-notification.firebaseapp.com",
            projectId: "lifeline-notification",
            storageBucket: "lifeline-notification.firebasestorage.app",
            messagingSenderId: "1080725502217",
            appId: "1:1080725502217:web:ee84c49d6dc3c745d6fbd8",
            measurementId: "G-HV7P4TF0G3"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
        const messaging = getMessaging(app);

        navigator.serviceWorker.register("../sw.js").then((registration) => {

            getToken(messaging, {
                serviceWorkerRegistration: registration,
                vapidKey: 'BJTRqCBgTVqr0ZTEWGJ8D2kiN1d5Lpz1ODthM_4zHf4KbZcj1Vv6kZaB-JZyVsZmEWIruN0qA7zqIXmco-fgngc'
            }).then((currentToken) => {
                if (currentToken) {
                    console.log('Registration token retrieved: ', currentToken);

                    // Save token to database
                    fetch('../API/Create/fcm_token.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ token: currentToken })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('FCM token saved successfully');
                            } else {
                                console.error('Failed to save FCM token:', data.error);
                            }
                        })
                        .catch(err => {
                            console.error('Error saving FCM token:', err);
                        });
                } else {
                    // Show permission request UI
                    console.log('No registration token available. Request permission to generate one.');
                    // ...
                }
            }).catch((err) => {
                console.log('An error occurred while retrieving token. ', err);
                // ...
            });

        });

    </script>
</body>

</html>