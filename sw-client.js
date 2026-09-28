// sw-client.js
// This Service Worker should be placed in the root directory of each client's subdomain.

self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    if (event.data) {
        const payload = event.data.json();
        
        // Silent push (Background Sync / Data updates without showing a visual notification)
        if (payload.silent) {
            console.log('Received silent push:', payload);
            // You can do background data sync here
            return;
        }

        const notificationOptions = {
            body: payload.body,
            icon: payload.icon || 'favicon-32x32.png',
            requireInteraction: payload.requireInteraction || false,
            data: {
                url: payload.url,
                actions: {}
            }
        };

        if (payload.image) {
            notificationOptions.image = payload.image;
        }
        if (payload.badge) {
            notificationOptions.badge = payload.badge;
        }
        if (payload.tag) {
            notificationOptions.tag = payload.tag;
        }
        if (payload.vibrate) {
            notificationOptions.vibrate = payload.vibrate;
        }

        // Store action URLs in data so we can retrieve them on click
        if (payload.actions && payload.actions.length > 0) {
            notificationOptions.actions = payload.actions;
            payload.actions.forEach(action => {
                notificationOptions.data.actions[action.action] = action.url;
            });
        }

        event.waitUntil(
            self.registration.showNotification(payload.title, notificationOptions)
        );
    }
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    // Determine the URL to open
    let targetUrl = event.notification.data.url;

    // If the user clicked an action button (e.g., "action1" or "action2")
    if (event.action && event.notification.data.actions && event.notification.data.actions[event.action]) {
        targetUrl = event.notification.data.actions[event.action];
    }
    
    if (targetUrl) {
        event.waitUntil(
            clients.openWindow(targetUrl)
        );
    }
});
