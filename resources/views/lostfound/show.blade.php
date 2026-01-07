<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $item->title }} | UniBoard</title>
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
</head>
<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <!-- SIDEBAR (same as index) -->
      <!-- Paste sidebar from index -->

      <div class="layout-page">
        <!-- NAVBAR (same as index) -->
        <!-- Paste navbar from index -->

        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <a href="{{ route('lostfound.index') }}" class="btn btn-outline-secondary mb-4">
              <i class="ti ti-arrow-left me-1"></i> Back to List
            </a>

            <div class="row">
              <div class="col-lg-8 mx-auto">
                <div class="card">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                      <span class="badge bg-label-{{ $item->type === 'lost' ? 'danger' : 'success' }} fs-5 px-3 py-2 me-3">
                        {{ ucfirst($item->type) }} Item
                      </span>
                      @if($item->is_resolved)
                        <span class="badge bg-success fs-5 px-3 py-2">Resolved</span>
                      @endif
                    </div>

                    <h2 class="card-title mb-3">{{ $item->title }}</h2>

                    <p class="text-muted mb-4">
                      Reported by <strong>{{ $item->user->name }}</strong> • {{ $item->created_at->diffForHumans() }}
                      <br>
                      Date: {{ $item->date_lost_found->format('M d, Y') }} • Location: <strong>{{ $item->location }}</strong>
                    </p>

                    <div class="mb-5">
                      <h5>Description</h5>
                      <p class="lead">{!! nl2br(e($item->description)) !!}</p>
                    </div>

                    @if($item->images)
                      <h5>Photos</h5>
                      <div class="row g-3 mb-4">
                        @foreach(json_decode($item->images, true) as $image)
                          <div class="col-md-6">
                            <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Item photo" />
                          </div>
                        @endforeach
                      </div>
                    @endif

                    @if(auth()->id() === $item->user_id || auth()->user()->role === 'admin')
                      <div class="mt-5">
                        <form method="POST" action="{{ route('lostfound.resolve', $item) }}" class="d-inline">
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="btn btn-success" {{ $item->is_resolved ? 'disabled' : '' }}>
                            Mark as Resolved
                          </button>
                        </form>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>