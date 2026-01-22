<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UniBoard') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ★★★ PUSH NOTIFICATIONS SUBSCRIPTION (already good) ★★★ -->
    @auth
    <script>
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(reg => {
                    console.log('Service Worker registered');
                    return reg.pushManager.getSubscription()
                        .then(sub => sub ? sub : reg.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: urlBase64ToUint8Array('{{ env('VAPID_PUBLIC_KEY') }}')
                        }));
                })
                .then(sub => {
                    fetch('{{ route('push.subscribe') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(sub.toJSON())
                    })
                    .then(res => res.json())
                    .then(data => console.log('Push subscribed:', data));
                })
                .catch(err => console.error('Push subscription failed:', err));
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const rawData = window.atob(base64);
            return Uint8Array.from([...rawData].map(char => char.charCodeAt(0)));
        }
    </script>
    @endauth
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
    @yield('content')
</main>
    </div>

    <!-- ★★★ REAL-TIME NOTIFICATIONS SCRIPT – ADD HERE ★★★ -->
 @auth
<script>
  console.log('=== NOTIFICATION DEBUG START ===');

  function loadNotifications() {
    console.log('Starting fetch...');
    fetch('{{ route('notifications.index') }}', {
  method: 'GET',
  credentials: 'include', // ← this sends cookies/session
  headers: {
    'Accept': 'application/json',
    'X-CSRF-TOKEN': '{{ csrf_token() }}',
    'X-Requested-With': 'XMLHttpRequest'
  }
})
    .then(response => {
      console.log('Fetch response:', response.status, response.ok);
      if (!response.ok) throw new Error('Fetch failed: ' + response.status);
      return response.json();
    })
    .then(data => {
      console.log('Data from server:', data);

      // Badge
      const countEl = document.getElementById('unreadCount');
      if (countEl) {
        countEl.textContent = data.unread_count || 0;
        console.log('Badge updated to:', data.unread_count);
      } else {
        console.error('Element #unreadCount NOT FOUND in DOM');
      }

      const textEl = document.getElementById('unreadText');
      if (textEl) {
        textEl.textContent = `${data.unread_count || 0} unread`;
      }

      // List
      const list = document.getElementById('notificationList');
      if (list) {
        list.innerHTML = '';
        if (!data.notifications || data.notifications.length === 0) {
          list.innerHTML = '<div class="text-center py-5 text-muted">No new notifications</div>';
        } else {
          data.notifications.forEach(notif => {
            const item = `
              <div class="dropdown-item ${notif.read ? 'text-muted' : 'fw-bold bg-light'} py-3 border-bottom">
                <div class="d-flex">
                  <div class="me-3">
                    <i class="ti ti-bell ti-lg text-primary"></i>
                  </div>
                  <div class="flex-grow-1">
                    <strong>${notif.title || notif.data?.title || 'New Notification'}</strong>
                    <p class="mb-1 small">${notif.message || notif.data?.message || 'New update'}</p>
                    <small class="text-muted">${notif.created_at}</small>
                  </div>
                </div>
              </div>
            `;
            list.insertAdjacentHTML('afterbegin', item);
          });
          console.log('List populated with', data.notifications.length, 'items');
        }
      } else {
        console.error('Element #notificationList NOT FOUND in DOM');
      }
    })
    .catch(err => console.error('Fetch error:', err));
  }

  // Run on load
  document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM ready - loading notifications');
    loadNotifications();
  });

  // Optional: reload when dropdown opens
  document.querySelector('.notification-dropdown')?.addEventListener('show.bs.dropdown', loadNotifications);
</script>
@endauth

</body>
</html>