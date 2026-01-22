<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ $notice->title }} | UniBoard</title>

  <!-- Vuexy / Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
</head>
<body>

  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      <!-- SIDEBAR -->
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
          <li class="menu-item active">
            <a href="{{ route('notices.index') }}" class="menu-link">
              <i class="menu-icon tf-icons ti ti-bell"></i>
              <div>Notices</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{ route('lostfound.index') }}" class="menu-link">
              <i class="menu-icon tf-icons ti ti-search"></i>
              <div>Lost & Found</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="{{ route('profile.edit') }}" class="menu-link">
              <i class="menu-icon tf-icons ti ti-user-edit"></i>
              <div>Edit Profile</div>
            </a>
          </li>

          @if(auth()->user()->role === 'admin')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Admin Tools</span></li>
            <li class="menu-item">
              <a href="{{ route('notices.approval') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-shield-check"></i>
                <div>Approve Notices</div>
              </a>
            </li>
          @endif
        </ul>
      </aside>

      <div class="layout-page">

        <!-- NAVBAR -->
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-info">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="ti ti-menu-2 ti-lg"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <li class="nav-item me-3">
                <a href="javascript:void(0);" id="dark-mode-toggle" class="nav-link px-3 text-white">
                  <i class="ti ti-moon ti-lg me-1" id="moon-icon"></i>
                  <i class="ti ti-sun ti-lg me-1 d-none" id="sun-icon"></i>
                  <span id="mode-text">Dark Mode</span>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <img src="{{ auth()->user()->profile_photo_path 
                      ? Storage::disk('public')->url(auth()->user()->profile_photo_path) 
                      : asset('assets/img/avatars/1.png') }}" 
                       alt="Avatar" class="rounded-circle w-px-40" />
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="#"><strong>{{ auth()->user()->name }}</strong></a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>

        <!-- CONTENT -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">

            <div class="card">
              <div class="card-header">
                <h5 class="mb-0">{{ $notice->title }}</h5>
                <small class="text-muted">
                  Posted by {{ $notice->user->name }} • {{ $notice->published_at->diffForHumans() }}
                </small>
              </div>
              <div class="card-body">
                <div class="mb-4">
                  <span class="badge bg-label-{{ $notice->category === 'emergency' ? 'danger' : 'primary' }}">
                    {{ ucfirst($notice->category) }}
                  </span>
                </div>
                <div class="notice-content">
                  {!! nl2br(e($notice->content)) !!}
                </div>
              </div>
            </div>

            <!-- Optional: Back button -->
            <div class="mt-4">
              <a href="{{ route('notices.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Notices
              </a>
            </div>

          </div>
        </div>

      </div>
    </div>

    <div class="layout-overlay layout-menu-toggle"></div>
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
    // Your dark mode script here
  </script>
</body>
</html>