<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Approve Notices | UniBoard</title>
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
</head>
<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <!-- Sidebar -->
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
            <a href="{{ route('notices.index') }}" class="menu-link">
              <i class="menu-icon tf-icons ti ti-bell"></i>
              <div>Notices</div>
            </a>
          </li>
          <li class="menu-item active">
            <a href="{{ route('notices.approval') }}" class="menu-link">
              <i class="menu-icon tf-icons ti ti-shield-check"></i>
              <div>Approve Notices</div>
            </a>
          </li>
        </ul>
      </aside>

      <div class="layout-page">
        <!-- Navbar -->
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-info">
          <!-- Same navbar as before -->
          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <li class="nav-item me-3">
                <a href="javascript:void(0);" id="dark-mode-toggle" class="nav-link px-3 text-white">
                  <i class="ti ti-moon ti-lg me-1" id="moon-icon"></i>
                  <i class="ti ti-sun ti-lg me-1 d-none" id="sun-icon"></i>
                  <span id="mode-text">Dark Mode</span>
                </a>
              </li>
              <!-- Profile dropdown -->
            </ul>
          </div>
        </nav>

        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">Pending Notices for Approval</h4>

            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            @if($pendingNotices->count() == 0)
              <div class="card">
                <div class="card-body text-center py-6">
                  <p class="mb-0">No pending notices. All clear! 🎉</p>
                </div>
              </div>
            @else
              <div class="row">
                @foreach($pendingNotices as $notice)
                  <div class="col-md-6 mb-4">
                    <div class="card h-100">
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $notice->title }}</h5>
                        <p class="text-muted small mb-3">
                          Posted by <strong>{{ $notice->user->name }}</strong> • {{ $notice->created_at->diffForHumans() }}
                        </p>
                        <p class="flex-grow-1">{{ $notice->content }}</p>
                        <div class="mt-auto">
                          <form method="POST" action="{{ route('notices.approve', $notice) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                          </form>
                          <form method="POST" action="{{ route('notices.reject', $notice) }}" class="d-inline ms-2" onsubmit="return confirm('Reject this notice?')">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                          </form>
                        </div>
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
  </div>

  <!-- JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Dark Mode Script -->
  <script>
    // Paste your dark mode script here
  </script>
</body>
</html>