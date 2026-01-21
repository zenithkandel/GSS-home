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
// Replace this with your actual VAPID key
const VAPID_KEY = 'YOUR_VAPID_KEY_HERE';

/**
 * Request notification permission and get FCM token
 */
export async function initializePushNotifications() {
    try {
        // Check if notifications are supported
        if (!('Notification' in window)) {
            console.warn('This browser does not support notifications');
            return null;
        }

        // Request permission
        const permission = await Notification.requestPermission();
        
        if (permission !== 'granted') {
            console.warn('Notification permission denied');
            return null;
        }

        // Get FCM token
        const token = await getToken(messaging, { vapidKey: VAPID_KEY });
        
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
        
        notification.onclick = function(event) {
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