<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chat with {{ $user->name }} | UniBoard</title>

  <!-- Vuexy CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
</head>
<body>

  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <!-- SIDEBAR (same as other pages) -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold">UniBoard</span>
          </a>
        </div>
        <div class="menu-inner-shadow"></div>
        <ul class="menu-inner py-1">
          <li class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link">
              <i class="menu-icon tf-icons ti ti-smart-home"></i>
              <div>Dashboard</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{ route('messages.inbox') }}" class="menu-link">
              <i class="menu-icon tf-icons ti ti-messages"></i>
              <div>Messages</div>
            </a>
          </li>
          <!-- Other links -->
        </ul>
      </aside>

      <div class="layout-page">

        <!-- NAVBAR -->
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-info">
          <!-- Paste your navbar code -->
        </nav>

        <!-- CONTENT -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="card">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                      <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/img/avatars/1.png') }}" class="rounded-circle w-px-50 me-3" />
                      <h5 class="mb-0">{{ $user->name }}</h5>
                    </div>
                    <a href="{{ route('messages.inbox') }}" class="btn btn-outline-secondary btn-sm">Back to Inbox</a>
                  </div>

                  <!-- CHAT BODY - THIS IS WHERE MESSAGES LOAD -->
                  <div class="card-body chat-container" id="chatBody" style="height: 500px; overflow-y: auto; padding: 20px;">
                    <div id="messagesList">
                      <!-- Messages will be loaded here via JS -->
                    </div>
                    <div id="typingIndicator" class="text-muted small d-none">Typing...</div>
                  </div>

                  <!-- Message Input -->
                  <div class="card-footer">
                    <form id="messageForm">
                      @csrf
                      <div class="input-group">
                        <textarea name="message" id="messageInput" class="form-control" rows="2" placeholder="Type your message..." required></textarea>
                        <button type="submit" class="btn btn-primary">Send</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="layout-overlay layout-menu-toggle"></div>
  </div>

  <!-- Core JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Real-time Chat Script -->
  <script>
    const userId = {{ $user->id }};
    const currentUserId = {{ Auth::id() }};
    let lastMessageId = 0;

    function loadMessages() {
      fetch(`/messages/${userId}/poll`)
        .then(response => response.json())
        .then(data => {
          const messagesList = document.getElementById('messagesList');
          messagesList.innerHTML = ''; // Reload all for simplicity

          data.messages.forEach(msg => {
            const isMine = msg.sender_id === currentUserId;
            const bubbleClass = isMine ? 'bg-primary text-white ms-auto' : 'bg-light';
            const alignClass = isMine ? 'justify-content-end' : 'justify-content-start';

            const messageHtml = `
              <div class="d-flex ${alignClass} mb-3">
                <div class="p-3 rounded ${bubbleClass}" style="max-width: 70%;">
                  <p class="mb-1">${msg.message.replace(/\n/g, '<br>')}</p>
                  <small class="opacity-75">${msg.created_at}</small>
                  ${isMine && msg.is_read ? '<small class="d-block text-end">✓✓ Read</small>' : ''}
                </div>
              </div>
            `;
            messagesList.innerHTML += messageHtml;
          });

          // Auto-scroll to bottom
          messagesList.scrollTop = messagesList.scrollHeight;
        })
        .catch(error => console.error('Error loading messages:', error));
    }

    // Poll every 5 seconds
    setInterval(loadMessages, 5000);

    // Initial load
    loadMessages();

    // Send message
    document.getElementById('messageForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const messageInput = document.getElementById('messageInput');
      const message = messageInput.value.trim();
      if (!message) return;

      fetch(`/messages/${userId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ message })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          messageInput.value = '';
          loadMessages();
        }
      })
      .catch(error => console.error('Error sending message:', error));
    });

    // Optional: Typing indicator (client-side)
    document.getElementById('messageInput').addEventListener('input', function() {
      // You can add server-side typing later
    });
  </script>
</body>
</html>