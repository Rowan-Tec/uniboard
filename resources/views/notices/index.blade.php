<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notices | UniBoard</title>
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
                <div>Approve Notices ({{ $pendingNotices ?? 0 }})</div>
              </a>
            </li>
          @endif
        </ul>
      </aside>

      <div class="layout-page">
        <!-- Navbar -->
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
                  <img src="{{ auth()->user()->profile_photo_path ? Storage::disk('public')->url(auth()->user()->profile_photo_path) : asset('assets/img/avatars/1.png') }}" alt class="rounded-circle w-px-40" />
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

        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex align-items-center justify-content-between mb-6">
              <h4 class="fw-bold mb-0">Campus Notices</h4>
              @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postNoticeModal">
                  <i class="ti ti-plus me-1"></i> Post Notice
                </button>
              @endif
            </div>

            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            <div class="row">
              @forelse($notices as $notice)
                <div class="col-md-6 mb-4">
                  <div class="card h-100">
                    <div class="card-body">
                      <div class="d-flex align-items-start">
                        <div class="badge bg-label-primary rounded p-2 me-3">
                          <i class="ti ti-bell ti-lg"></i>
                        </div>
                        <div class="flex-grow-1">
                          <h5 class="card-title mb-1">{{ $notice->title }}</h5>
                          <p class="text-muted small mb-2">
                            Posted by {{ $notice->user->name }} • {{ $notice->created_at->diffForHumans() }}
                          </p>
                          <p class="card-text">{{ Str::limit($notice->content, 150) }}</p>
                          <a href="{{ route('notices.show', $notice) }}" class="btn btn-sm btn-outline-primary mt-2">
                    Read More <i class="ti ti-arrow-right ms-1"></i>
                  </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <div class="col-12">
                  <div class="card">
                    <div class="card-body text-center py-6">
                      <p class="mb-0">No approved notices yet.</p>
                    </div>
                  </div>
                </div>
              @endforelse
            </div>

            <div class="mt-4">
              {{ $notices->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Post Notice Modal -->
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
      <div class="modal fade" id="postNoticeModal">
        <div class="modal-dialog">
          <form method="POST" action="{{ route('notices.store') }}">
            @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Post New Notice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Title</label>
                  <input type="text" name="title" class="form-control" required />
                </div>
                <div class="mb-3">
                  <label class="form-label">Category</label>
                  <select name="category" class="form-select" required>
                    <option value="general">General</option>
                    <option value="events">Events</option>
                    <option value="academic">Academic</option>
                    <option value="exam">Exam</option>
                    <option value="scholarship">Scholarship</option>
                    <option value="emergency">Emergency</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Content</label>
                  <textarea name="content" class="form-control" rows="5" required></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Post Notice</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endif

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