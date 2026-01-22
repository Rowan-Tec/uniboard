self.addEventListener('push', event => {
  const data = event.data.json();

  const options = {
    body: data.body,
    icon: '/assets/img/favicon/favicon.ico',
    badge: '/assets/img/favicon/favicon.ico',
    data: {
      url: data.url
    }
  };

  event.waitUntil(
    self.registration.showNotification(data.title, options)
  );
});

self.addEventListener('notificationclick', event => {
  event.notification.close();
  event.waitUntil(
    clients.openWindow(event.notification.data.url)
  );
});