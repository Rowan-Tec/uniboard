<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | UniBoard</title>

  <!-- Vuexy CSS -->
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
          <li class="menu-item active">
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
          <li class="menu-item">
            <a href="#" class="menu-link">
              <i class="menu-icon tf-icons ti ti-search"></i>
              <div>Lost & Found</div>
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

        <!-- TOP NAVBAR -->
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-info">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="ti ti-menu-2 ti-lg"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">

              <!-- Dark Mode Toggle -->
              <li class="nav-item me-3">
                <a href="javascript:void(0);" id="dark-mode-toggle" class="nav-link px-3 text-white">
                  <i class="ti ti-moon ti-lg me-1" id="moon-icon"></i>
                  <i class="ti ti-sun ti-lg me-1 d-none" id="sun-icon"></i>
                  <span id="mode-text">Dark Mode</span>
                </a>
              </li>

              <!-- Profile Dropdown -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="{{ auth()->user()->profile_photo_path 
                        ? Storage::disk('public')->url(auth()->user()->profile_photo_path) 
                        : asset('assets/img/avatars/1.png') }}" 
                         alt="Avatar" class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="{{ auth()->user()->profile_photo_path 
                                ? Storage::disk('public')->url(auth()->user()->profile_photo_path) 
                                : asset('assets/img/avatars/1.png') }}" 
                                 alt class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-medium d-block">{{ auth()->user()->name }}</span>
                          <small class="text-muted">{{ ucwords(auth()->user()->role) }}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="#">Edit Profile</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item">
                        <i class="ti ti-logout me-2"></i> Logout
                      </button>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>

        <!-- MAIN CONTENT -->
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">

            <h4 class="fw-bold py-3 mb-4">
              Welcome back, {{ auth()->user()->name }}! 👋
            </h4>

            <div class="row">
              <!-- Profile Card -->
              <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                  <div class="card-body text-center">
                    <img src="{{ auth()->user()->profile_photo_path 
                        ? Storage::disk('public')->url(auth()->user()->profile_photo_path) 
                        : asset('assets/img/avatars/1.png') }}" 
                         alt="Profile" class="rounded-circle w-px-150 mb-4" />
                    <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-3">
                      <strong>ID:</strong> {{ auth()->user()->id_number }}<br>
                      <strong>Department:</strong> {{ ucwords(auth()->user()->department) }}<br>
                      <strong>Year/Role:</strong> {{ ucwords(auth()->user()->year) }}
                    </p>
                    <span class="badge bg-label-{{ auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'staff' ? 'warning' : 'primary') }}">
                      {{ ucwords(auth()->user()->role) }}
                    </span>
                  </div>
                </div>
              </div>

              <!-- Stats & Role Message -->
              <div class="col-lg-8 col-md-6">
                <div class="row mb-4">
                  <div class="col-6">
                    <div class="card">
                      <div class="card-body text-center">
                        <h4 class="mb-1">42</h4>
                        <p class="text-muted">Active Notices</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="card">
                      <div class="card-body text-center">
                        <h4 class="mb-1">3</h4>
                        <p class="text-muted">Your Lost Items</p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card">
                  <div class="card-body">
                    @if(auth()->user()->role === 'admin')
                      <h5>Admin Dashboard</h5>
                      <p>You have full control over notices, users, and campus content.</p>
                      <a href="{{ route('notices.approval') }}" class="btn btn-primary">Approve Notices</a>
                    @elseif(auth()->user()->role === 'staff')
                      <h5>Staff Dashboard</h5>
                      <p>Post official notices and help manage campus information.</p>
                      <a href="{{ route('notices.index') }}" class="btn btn-primary">Post Notice</a>
                    @else
                      <h5>Student Dashboard</h5>
                      <p>Stay updated with campus notices and report lost items.</p>
                      <a href="{{ route('notices.index') }}" class="btn btn-primary">View Notices</a>
                    @endif
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="content-backdrop fade"></div>
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

  <!-- Dark Mode Toggle Script -->
  <script>
    document.getElementById('dark-mode-toggle').addEventListener('click', function () {
      const html = document.documentElement;
      const moon = document.getElementById('moon-icon');
      const sun = document.getElementById('sun-icon');
      const text = document.getElementById('mode-text');

      if (html.getAttribute('data-bs-theme') === 'dark') {
        html.setAttribute('data-bs-theme', 'light');
        moon.classList.remove('d-none');
        sun.classList.add('d-none');
        text.textContent = 'Dark Mode';
        localStorage.setItem('theme', 'light');
      } else {
        html.setAttribute('data-bs-theme', 'dark');
        moon.classList.add('d-none');
        sun.classList.remove('d-none');
        text.textContent = 'Light Mode';
        localStorage.setItem('theme', 'dark');
      }
    });

    if (localStorage.getItem('theme') === 'dark') {
      document.documentElement.setAttribute('data-bs-theme', 'dark');
      document.getElementById('moon-icon').classList.add('d-none');
      document.getElementById('sun-icon').classList.remove('d-none');
      document.getElementById('mode-text').textContent = 'Light Mode';
    }
  </script>
</body>
</html>