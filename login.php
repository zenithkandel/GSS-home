<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LifeLine Emergency Response System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="CSS/login.css">
</head>

<body>
    <!-- Background canvas for subtle animation -->
    <canvas id="bgCanvas"></canvas>

    <!-- Main container -->
    <div class="login-wrapper">
        <!-- Left side - Branding -->
        <div class="login-brand">
            <div class="brand-content">
                <a href="index.html" class="brand-logo">
                    <svg class="logo-icon" viewBox="0 0 40 40" fill="none">
                        <circle cx="20" cy="20" r="4" fill="currentColor" />
                        <circle cx="20" cy="20" r="10" stroke="currentColor" stroke-width="2" fill="none"
                            opacity="0.6" />
                        <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="1.5" fill="none"
                            opacity="0.3" />
                    </svg>
                    <span>LifeLine</span>
                </a>

                <div class="brand-hero">
                    <h1>Command & Control Portal</h1>
                    <p>Monitor emergencies, dispatch rescuers, save lives.</p>
                </div>

                <!-- Network illustration -->
                <div class="brand-illustration">
                    <svg viewBox="0 0 300 200" class="network-svg">
                        <!-- Connection lines -->
                        <g class="connections">
                            <line x1="50" y1="100" x2="150" y2="60" stroke="currentColor" stroke-width="1"
                                opacity="0.2" />
                            <line x1="150" y1="60" x2="250" y2="100" stroke="currentColor" stroke-width="1"
                                opacity="0.2" />
                            <line x1="50" y1="100" x2="150" y2="140" stroke="currentColor" stroke-width="1"
                                opacity="0.2" />
                            <line x1="150" y1="140" x2="250" y2="100" stroke="currentColor" stroke-width="1"
                                opacity="0.2" />
                            <line x1="150" y1="60" x2="150" y2="140" stroke="currentColor" stroke-width="1"
                                opacity="0.2" />
                        </g>

                        <!-- Nodes -->
                        <g class="nodes">
                            <circle cx="50" cy="100" r="8" fill="currentColor" class="node node-1" />
                            <circle cx="150" cy="60" r="8" fill="currentColor" class="node node-2" />
                            <circle cx="250" cy="100" r="10" fill="currentColor" class="node node-3" />
                            <circle cx="150" cy="140" r="8" fill="currentColor" class="node node-4" />
                        </g>

                        <!-- Signal pulses -->
                        <g class="pulses">
                            <circle cx="50" cy="100" r="8" fill="none" stroke="currentColor" class="pulse pulse-1" />
                            <circle cx="150" cy="60" r="8" fill="none" stroke="currentColor" class="pulse pulse-2" />
                            <circle cx="250" cy="100" r="10" fill="none" stroke="currentColor" class="pulse pulse-3" />
                            <circle cx="150" cy="140" r="8" fill="none" stroke="currentColor" class="pulse pulse-4" />
                        </g>

                        <!-- Labels -->
                        <text x="50" y="125" text-anchor="middle" class="node-label">Node A</text>
                        <text x="150" y="45" text-anchor="middle" class="node-label">Node B</text>
                        <text x="250" y="130" text-anchor="middle" class="node-label">Gateway</text>
                        <text x="150" y="165" text-anchor="middle" class="node-label">Node C</text>
                    </svg>
                </div>

                <div class="brand-footer">
                    <span class="status-indicator">
                        <span class="status-dot"></span>
                        System Online
                    </span>
                    <span class="node-count">10 nodes active</span>
                </div>
            </div>
        </div>

        <!-- Right side - Login form -->
        <div class="login-form-wrapper">
            <div class="login-form-container">
                <div class="form-header">
                    <h2>Sign In</h2>
                    <p>Enter your credentials to access the portal</p>
                </div>

                <form id="loginForm" class="login-form" novalidate>
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" class="form-input"
                                placeholder="admin@lifeline.np" autocomplete="email" required>
                            <span class="input-focus"></span>
                        </div>
                        <span class="error-message" id="emailError"></span>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" class="form-input"
                                placeholder="••••••••" autocomplete="current-password" required>
                            <button type="button" class="toggle-password" id="togglePassword"
                                aria-label="Toggle password visibility">
                                <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </button>
                            <span class="input-focus"></span>
                        </div>
                        <span class="error-message" id="passwordError"></span>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="checkbox-custom"></span>
                            <span class="checkbox-label">Remember me</span>
                        </label>
                    </div>

                    <!-- Alert message -->
                    <div class="alert" id="alertMessage" role="alert"></div>

                    <!-- Submit button -->
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <span class="btn-text">Sign In</span>
                        <span class="btn-loader">
                            <svg class="spinner" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none"
                                    stroke-dasharray="31.4 31.4" stroke-linecap="round" />
                            </svg>
                        </span>
                        <svg class="btn-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </button>
                </form>

                <div class="form-footer">
                    <a href="index.html" class="back-link">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        <span>Back to homepage</span>
                    </a>
                </div>

                <!-- Demo credentials hint -->
                <div class="demo-hint">
                    <span class="hint-label">Demo Credentials</span>
                    <code>admin@lifeline.np / password</code>
                </div>
            </div>
        </div>
    </div>

    <script src="JS/login.js"></script>
</body>

</html>