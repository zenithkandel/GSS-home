// Import the functions you need from the SDKs you need
import { initializeApp } from "https://www.gstatic.com/firebasejs/12.8.0/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.8.0/firebase-analytics.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/12.8.0/firebase-messaging.js";

// Your web app's Firebase configuration
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

// VAPID Key - Generate from Firebase Console > Project Settings > Cloud Messaging > Web Push certificates
// This must be the FULL public key (usually ~88 characters starting with 'B')
const VAPID_KEY = 'BES5fRlcZH3MqhhoHIKYxCWttypqYIaisy0blc9v3hbYWhIQo9b8dxCB6YFk4ba4wOxHDZyfzneGPP7vi72Hmkw';

/**
 * Wait for service worker to become active
 */
async function waitForServiceWorkerActive(registration) {
    if (registration.active) {
        return registration;
    }

    const serviceWorker = registration.installing || registration.waiting;

    if (!serviceWorker) {
        throw new Error('No service worker found');
    }

    return new Promise((resolve, reject) => {
        serviceWorker.addEventListener('statechange', () => {
            if (serviceWorker.state === 'activated') {
                resolve(registration);
            } else if (serviceWorker.state === 'redundant') {
                reject(new Error('Service worker became redundant'));
            }
        });

        // Timeout after 10 seconds
        setTimeout(() => reject(new Error('Service worker activation timeout')), 10000);
    });
}

/**
 * Request notification permission and get FCM token
 */
export async function initializePushNotifications(retryCount = 0) {
    const MAX_RETRIES = 3;

    try {
        // Check if notifications are supported
        if (!('Notification' in window)) {
            console.warn('This browser does not support notifications');
            return null;
        }

        // Check if service workers are supported
        if (!('serviceWorker' in navigator)) {
            console.warn('This browser does not support service workers');
            return null;
        }

        // Check for existing registration first
        let swRegistration = await navigator.serviceWorker.getRegistration('/GSS%20home/');

        if (!swRegistration) {
            // Register service worker manually with correct path
            swRegistration = await navigator.serviceWorker.register('/GSS%20home/firebase-messaging-sw.js', {
                scope: '/GSS%20home/'
            });
            console.log('Service Worker registered:', swRegistration);
        } else {
            console.log('Using existing Service Worker registration:', swRegistration);
        }

        // Wait for the service worker to become active
        swRegistration = await waitForServiceWorkerActive(swRegistration);
        console.log('Service Worker is now active');

        // Small delay to ensure SW is fully ready
        await new Promise(resolve => setTimeout(resolve, 500));

        // Request permission
        const permission = await Notification.requestPermission();

        if (permission !== 'granted') {
            console.warn('Notification permission denied');
            return null;
        }

        // Get FCM token with our custom service worker registration
        const token = await getToken(messaging, {
            vapidKey: VAPID_KEY,
            serviceWorkerRegistration: swRegistration
        });

        if (token) {
            console.log('FCM Token:', token);

            // Register token with backend
            await registerToken(token);

            return token;
        } else {
            console.warn('No registration token available');
            return null;
        }

    } catch (error) {
        console.error('Error initializing push notifications:', error);

        // Retry on push service errors
        if (error.message?.includes('push service') && retryCount < MAX_RETRIES) {
            console.log(`Retrying... (${retryCount + 1}/${MAX_RETRIES})`);
            await new Promise(resolve => setTimeout(resolve, 2000 * (retryCount + 1)));
            return initializePushNotifications(retryCount + 1);
        }

        return null;
    }
}

/**
 * Register FCM token with the backend
 */
async function registerToken(token) {
    try {
        const response = await fetch('/GSS%20home/API/fcm_register.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ token })
        });

        const data = await response.json();

        if (data.success) {
            console.log('FCM token registered successfully');
        } else {
            console.error('Failed to register FCM token:', data.error);
        }
    } catch (error) {
        console.error('Error registering FCM token:', error);
    }
}

/**
 * Handle foreground messages
 */
onMessage(messaging, (payload) => {
    console.log('Foreground message received:', payload);

    const notificationTitle = payload.notification?.title || 'LifeLine Alert';
    const notificationOptions = {
        body: payload.notification?.body || 'New emergency notification',
        icon: '/GSS%20home/res/lifeline.png',
        badge: '/GSS%20home/res/lifeline.png',
        vibrate: [200, 100, 200],
        requireInteraction: true,
        tag: 'lifeline-emergency',
        data: payload.data
    };

    // Show notification
    if (Notification.permission === 'granted') {
        const notification = new Notification(notificationTitle, notificationOptions);

        notification.onclick = function (event) {
            event.preventDefault();
            window.focus();
            // Navigate to messages page
            if (window.location.pathname.includes('/portal/')) {
                // If in portal, load messages page
                const messagesFrame = document.getElementById('contentFrame');
                if (messagesFrame) {
                    messagesFrame.src = 'messages.php';
                }
            } else {
                // Redirect to portal
                window.location.href = '/GSS%20home/portal/index.php';
            }
            notification.close();
        };
    }

    // Dispatch custom event for in-app handling
    window.dispatchEvent(new CustomEvent('lifelineEmergency', {
        detail: payload
    }));
});

/**
 * Unregister FCM token (call on logout)
 */
export async function unregisterPushNotifications() {
    try {
        const token = await getToken(messaging, { vapidKey: VAPID_KEY });

        if (token) {
            await fetch('/GSS%20home/API/fcm_register.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ token })
            });
            console.log('FCM token unregistered');
        }
    } catch (error) {
        console.error('Error unregistering FCM token:', error);
    }
}

// Auto-initialize when script loads (if in portal)
if (window.location.pathname.includes('/portal/')) {
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePushNotifications);
    } else {
        initializePushNotifications();
    }
}

// Export for use in other modules
export { app, analytics, messaging };