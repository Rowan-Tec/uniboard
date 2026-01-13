<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>Chat with {{ $user->name }} | UniBoard</title>

  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

  <style>
    .chat-body {
      background: #e5ddd5 url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png') repeat;
      background-size: 100% auto;
      padding: 10px;
    }
    .message {
      margin-bottom: 12px;
      max-width: 75%;
    }
    .message-bubble {
      position: relative;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 14.2px;
      line-height: 1.35;
    }
    .incoming .message-bubble {
      background: #fff;
      border-bottom-left-radius: 0;
    }
    .outgoing .message-bubble {
      background: #dcf8c6;
      border-bottom-right-radius: 0;
    }
    .message-meta {
      font-size: 11px;
      color: #555;
      margin-top: 3px;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    .read-status {
      color: #4fc3f7;
    }
    #typingIndicator {
      padding: 12px 16px;
      font-style: italic;
      color: #555;
    }
  </style>
</head>
<body>

  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <!-- Sidebar -->
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <!-- Your sidebar code here -->
      </aside>

      <div class="layout-page">

        <!-- Navbar -->
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-info">
          <!-- Your navbar code here -->
        </nav>

        <!-- Content -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="card">

                  <!-- Header -->
                  <div class="card-header d-flex align-items-center justify-content-between border-bottom">
                    <div class="d-flex align-items-center">
                      <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('assets/img/avatars/1.png') }}" 
                           class="rounded-circle w-px-48 me-3" alt="{{ $user->name }}" />
                      <div>
                        <h5 class="mb-0">{{ $user->name }}</h5>
                        <small class="text-success">Active now</small>
                      </div>
                    </div>
                    <a href="{{ route('messages.inbox') }}" class="btn btn-sm btn-outline-secondary">
                      <i class="ti ti-arrow-left me-1"></i> Back to Inbox
                    </a>
                  </div>

                  <!-- Chat Body -->
                  <div class="card-body chat-body p-0" id="chatBody" style="height: 65vh; overflow-y: auto;">
                    <div class="p-4 d-flex flex-column" id="messagesList">
                      <!-- Messages appear here -->
                    </div>

                    <!-- Typing Indicator -->
                    <div id="typingIndicator" class="p-4 text-muted small d-none">
                      <div class="typing-dots d-inline-block me-2">
                        <span></span><span></span><span></span>
                      </div>
                      {{ $user->name }} is typing...
                    </div>
                  </div>

                  <!-- Input -->
                  <div class="card-footer border-top p-3 bg-white">
                    <form id="messageForm">
                      <div class="input-group">
                        <textarea id="messageInput" class="form-control rounded-pill px-4 py-3" 
                                  rows="1" placeholder="Type a message..." required 
                                  style="resize:none; min-height:48px;"></textarea>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                          <i class="ti ti-send"></i>
                        </button>
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

  <!-- Chat Logic -->
  <script>
    const receiverId = {{ $user->id }};
    const currentUserId = {{ Auth::id() }};
    const typingIndicator = document.getElementById('typingIndicator');

    // Load initial messages
    loadMessages();

    // Poll fallback every 5 seconds
    setInterval(loadMessages, 5000);

    function loadMessages() {
      fetch(`/messages/${receiverId}/poll`)
        .then(response => response.json())
        .then(data => {
          const list = document.getElementById('messagesList');
          list.innerHTML = '';

          data.messages.forEach(msg => {
            const isMine = msg.sender_id === currentUserId;
            const bubbleClass = isMine ? 'outgoing' : 'incoming';
            const read = isMine && msg.is_read ? '✓✓' : '';

            const html = `
              <div class="message d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'}">
                <div class="message-bubble ${bubbleClass}">
                  <p class="mb-1">${msg.message.replace(/\n/g, '<br>')}</p>
                  <div class="message-meta">
                    <span>${msg.created_at}</span>
                    <span class="read-status">${read}</span>
                  </div>
                </div>
              </div>
            `;
            list.insertAdjacentHTML('beforeend', html);
          });

          list.scrollTop = list.scrollHeight;
        })
        .catch(err => console.error('Poll error:', err));
    }

    // Send message - appears instantly on same page for sender
    document.getElementById('messageForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const input = document.getElementById('messageInput');
      const message = input.value.trim();

      if (!message) return;

      // Show message immediately (optimistic UI)
      const list = document.getElementById('messagesList');
      const html = `
        <div class="message d-flex justify-content-end">
          <div class="message-bubble outgoing">
            <p class="mb-1">${message.replace(/\n/g, '<br>')}</p>
            <div class="message-meta">
              <span>just now</span>
            </div>
          </div>
        </div>
      `;
      list.insertAdjacentHTML('beforeend', html);
      list.scrollTop = list.scrollHeight;

      // Send to server
      fetch(`/messages/${receiverId}`, {
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
          input.value = '';
        } else {
          alert('Failed to send');
        }
      })
      .catch(err => console.error('Send error:', err));
    });

    // Listen for new messages from other user (real-time)
    Echo.private(`chat.${currentUserId}`)
        .listen('MessageSent', (e) => {
          const msg = e.message;
          const isMine = msg.sender_id === currentUserId;
          const bubbleClass = isMine ? 'outgoing' : 'incoming';
          const read = isMine && msg.is_read ? '✓✓' : '';

          const html = `
            <div class="message d-flex ${isMine ? 'justify-content-end' : 'justify-content-start'}">
              <div class="message-bubble ${bubbleClass}">
                <p class="mb-1">${msg.message.replace(/\n/g, '<br>')}</p>
                <div class="message-meta">
                  <span>just now</span>
                  <span class="read-status">${read}</span>
                </div>
              </div>
            </div>
          `;
          document.getElementById('messagesList').insertAdjacentHTML('beforeend', html);
          document.getElementById('messagesList').scrollTop = document.getElementById('messagesList').scrollHeight;
        });

    // Typing indicator
    let typingTimer;
    document.getElementById('messageInput').addEventListener('input', function() {
      clearTimeout(typingTimer);
      fetch(`/messages/${receiverId}/typing`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
      });

      typingTimer = setTimeout(() => {}, 2000);
    });

    Echo.private(`chat.${currentUserId}`)
        .listen('UserTyping', (e) => {
          if (e.sender_id === receiverId) {
            typingIndicator.classList.remove('d-none');
            clearTimeout(window.typingHide);
            window.typingHide = setTimeout(() => typingIndicator.classList.add('d-none'), 3000);
          }
        });
  </script>
</body>
</html>