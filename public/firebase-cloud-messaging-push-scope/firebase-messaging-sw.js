self.addEventListener('install', event => {
  event.waitUntil(self.skipWaiting());
});

self.addEventListener('activate', event => {
  event.waitUntil(self.clients.claim());
});

function readPushPayload(event) {
  if (!event.data) return {};

  try {
    return event.data.json();
  } catch (error) {
    return {};
  }
}

function notificationUrl(payload) {
  return payload?.data?.url
    || payload?.fcmOptions?.link
    || payload?.notification?.click_action
    || '/';
}

self.addEventListener('push', event => {
  const payload = readPushPayload(event);
  const notification = payload.notification || {};
  const data = payload.data || {};

  event.waitUntil(
    self.registration.showNotification(notification.title || data.title || 'Notifikasi', {
      body: notification.body || data.body || '',
      icon: notification.icon || data.icon || '/images/icons/icon-192x192.png',
      badge: data.badge || '/images/icons/icon-72x72.png',
      data: {
        url: notificationUrl(payload)
      }
    })
  );
});

self.addEventListener('notificationclick', event => {
  event.notification.close();

  const targetUrl = event.notification.data?.url || '/';

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
      for (const client of clientList) {
        if ('focus' in client) {
          client.navigate(targetUrl);
          return client.focus();
        }
      }

      if (clients.openWindow) {
        return clients.openWindow(targetUrl);
      }
    })
  );
});
