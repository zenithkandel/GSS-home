// Firebase Messaging Service Worker
// This file MUST be at the root of your domain for FCM to work

importScripts('https://www.gstatic.com/firebasejs/12.8.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/12.8.0/firebase-messaging-compat.js');

// Base path configuration - change this if deploying to a different path
const BASE_PATH = '/GSS%20home';

// Initialize Firebase
firebase.initializeApp({
    apiKey: "AIzaSyDq8x0Gb-1lBAVBmPZzfCOYmF6gqB1NstA",
    authDomain: "lifeline-notification.firebaseapp.com",
    projectId: "lifeline-notification",
    storageBucket: "lifeline-notification.firebasestorage.app",
    messagingSenderId: "1080725502217",
    appId: "1:1080725502217:web:ee84c49d6dc3c745d6fbd8",
    measurementId: "G-HV7P4TF0G3"
});

const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[SW] Background message received:', payload);

    const notificationTitle = payload.notification?.title || '🚨 LifeLine Emergency Alert';
    const notificationOptions = {
        body: payload.notification?.body || 'New emergency notification received',
        icon: `${BASE_PATH}/res/lifeline.png`,
        badge: `${BASE_PATH}/res/lifeline.png`,
        vibrate: [200, 100, 200, 100, 200],
        requireInteraction: true,
        tag: 'lifeline-emergency-' + Date.now(),
        data: payload.data,
        actions: [
            {
                action: 'view',
                title: 'View Details'
            },
            {
                action: 'dismiss',
                title: 'Dismiss'
            }
        ]
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});

// Handle notification click
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked:', event);

    event.notification.close();

    if (event.action === 'dismiss') {
        return;
    }

    // Open or focus the portal
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                // Check if portal is already open
                for (const client of clientList) {
                    if (client.url.includes('/portal/') && 'focus' in client) {
                        return client.focus();
                    }
                }
                // Open new window
                if (clients.openWindow) {
                    return clients.openWindow(`${BASE_PATH}/portal/index.php`);
                }
            })
    );
});

// Handle service worker installation
self.addEventListener('install', (event) => {
    console.log('[SW] Service Worker installed');
    self.skipWaiting();
});

// Handle service worker activation
self.addEventListener('activate', (event) => {
    console.log('[SW] Service Worker activated');
    event.waitUntil(clients.claim());
});
