@extends('layouts.app')

@section('content')
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
      <span class="text-muted fw-light">Dashboard /</span> Notifications
    </h4>

    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">All Notifications</h5>
        <small class="text-muted">Your recent updates and alerts</small>
      </div>

      <div class="card-body">
        @if ($notifications->isEmpty())
          <div class="text-center py-5 text-muted">
            <i class="ti ti-bell-off ti-4x mb-3"></i>
            <h6>No notifications yet</h6>
            <p class="small">When new notices or updates arrive, they'll appear here.</p>
          </div>
        @else
          <div class="list-group list-group-flush">
            @foreach ($notifications as $notif)
              <a href="{{ $notif->data['url'] ?? '#' }}" 
                 class="list-group-item list-group-item-action py-3 {{ $notif->read ? 'text-muted' : 'bg-light fw-bold' }}"
                 data-id="{{ $notif->id }}">
                <div class="d-flex w-100 justify-content-between align-items-start">
                  <div class="me-3">
                    <i class="ti ti-bell ti-lg text-primary"></i>
                  </div>
                  <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                      <h6 class="mb-1">
                        {{ $notif->data['title'] ?? 'Notification' }}
                        @if (!$notif->read)
                          <span class="badge bg-danger rounded-pill ms-2">New</span>
                        @endif
                      </h6>
                      <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1 small">{{ $notif->data['message'] ?? 'You have a new update' }}</p>
                  </div>
                </div>
              </a>
            @endforeach
          </div>

          <!-- Pagination -->
          <div class="mt-4">
            {{ $notifications->links() }}
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection