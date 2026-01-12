<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <!-- Same head as other pages -->
  <title>Messages | UniBoard</title>
  <!-- CSS links -->
</head>
<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Sidebar with "Messages" active -->
      <!-- Navbar -->

      <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
          <h4 class="fw-bold py-3 mb-4">Private Messages</h4>

          @if($conversations->count() == 0)
            <div class="card">
              <div class="card-body text-center py-6">
                <p>No messages yet. Start a conversation from Lost & Found!</p>
              </div>
            </div>
          @else
            <div class="row">
              @foreach($conversations as $partnerId => $chat)
                @php $partner = $chat->first()->sender_id === Auth::id() ? $chat->first()->receiver : $chat->first()->sender; @endphp
                <div class="col-md-6 mb-4">
                  <div class="card">
                    <div class="card-body d-flex align-items-center">
                      <img src="{{ $partner->profile_photo_path ? asset('storage/' . $partner->profile_photo_path) : asset('assets/img/avatars/1.png') }}" class="rounded-circle w-px-60 me-3" />
                      <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $partner->name }}</h6>
                        <small class="text-muted">{{ $chat->first()->message }}</small>
                      </div>
                      <a href="{{ route('messages.show', $partner) }}" class="btn btn-primary btn-sm">Open Chat</a>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  <!-- JS -->
</body>
</html>