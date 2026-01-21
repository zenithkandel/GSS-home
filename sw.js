// Firebase Messaging Service Worker for LifeLine
self.addEventListener('push', function (event) {
    const payload = event.data.json();
    const notif = payload.notification || {};
    const data = payload.data || {};
    
    const options = {
        body: notif.body || data.body || 'New emergency alert',
        icon: notif.icon || data.icon || '/res/lifeline.png',
        badge: '/res/lifeline.png',
        vibrate: [200, 100, 200, 100, 200],
        tag: 'lifeline-emergency',
        renotify: true,
        requireInteraction: true,
        data: { 
            url: notif.click_action || data.click_action || '/portal/dashboard.php' 
        }
    };
    
    event.waitUntil(
        self.registration.showNotification(
            notif.title || data.title || '🚨 LifeLine Emergency',
            options
        )
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            // If a window is already open, focus it
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url.includes('/portal/') && 'focus' in client) {
                    return client.focus();
                }
            }
            // Otherwise open a new window
            if (clients.openWindow) {
                return clients.openWindow(event.notification.data.url);
            }
        })
    );
});