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
                <div>Approve Notices ({{ $pendingNotices }})</div>
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
                  <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profile</a></li>
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

            <h4 class="fw-bold py-3 mb-4">
              Welcome back, {{ auth()->user()->name }}! 👋
            </h4>

            <!-- LIVE STATS — ONLY FOR ADMIN & STAFF -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
              <div class="row mb-6">
                @if(auth()->user()->role === 'admin')
                  <!-- Admin sees global stats -->
                  <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-bell ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $totalNotices }}</h5>
                            <small class="text-muted">Total Notices</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success"><i class="ti ti-check ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $approvedNotices }}</h5>
                            <small class="text-muted">Approved</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-clock ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $pendingNotices }}</h5>
                            <small class="text-muted">Pending</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info"><i class="ti ti-users ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $totalUsers }}</h5>
                            <small class="text-muted">Total Users</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @else
                  <!-- Staff sees personal stats -->
                  <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-file-text ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $userTotalNotices }}</h5>
                            <small class="text-muted">Your Total Notices</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success"><i class="ti ti-check ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $userApprovedNotices }}</h5>
                            <small class="text-muted">Your Approved</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-clock ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $userPendingNotices }}</h5>
                            <small class="text-muted">Your Pending</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            @endif

            <!-- Profile & Role Message -->
            <div class="row">
              <div class="col-lg-4 mb-4">
                <div class="card h-100">
                  <div class="card-body text-center">
                    <img src="{{ auth()->user()->profile_photo_path 
                        ? Storage::url(auth()->user()->profile_photo_path) 
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

              <div class="col-lg-8">
                <div class="card h-100">
                  <div class="card-body">
                    @if(auth()->user()->role === 'admin')
                      <h5>Administrator Control Panel</h5>
                      <p>You have full control over campus notices and user management.</p>
                      <a href="{{ route('notices.approval') }}" class="btn btn-primary">
                        Review Pending Notices ({{ $pendingNotices }})
                      </a>
                    @elseif(auth()->user()->role === 'staff')
                      <h5>Staff Dashboard</h5>
                      <p>You can post official campus notices. They will be reviewed by admin.</p>
                      <a href="{{ route('notices.index') }}" class="btn btn-primary">Post New Notice</a>
                    @else
                      <h5>Welcome, Student!</h5>
                      <p>Stay updated with the latest campus announcements and notices.</p>
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

  <!-- Dark Mode Script -->
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