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

        <!-- NAVBAR -->
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

      <!-- Notifications Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <i class="ti ti-bell ti-lg"></i>
          <span class="badge bg-danger rounded-pill badge-notification" id="unreadCount">0</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end p-0" style="width: 320px;">
          <li class="dropdown-header border-bottom py-3">
            <h6 class="mb-0">Notifications</h6>
            <small id="unreadText">You have <span id="unreadCountText">0</span> unread</small>
          </li>
          <div class="notification-list p-2" id="notificationList" style="max-height: 300px; overflow-y: auto;">
            <!-- Notifications loaded here via JS -->
            <div class="text-center py-4 text-muted">
              <i class="ti ti-loader ti-spin me-2"></i> Loading...
            </div>
          </div>
          <li class="dropdown-footer border-top text-center py-3">
            <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary w-100">
              View All Notifications
            </a>
          </li>
        </ul>
      </li>

      <!-- User Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <img src="http://127.0.0.1:8000/assets/img/avatars/1.png" alt="" class="rounded-circle w-px-40">
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="#"><strong>Admin</strong></a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <form method="POST" action="http://127.0.0.1:8000/logout">
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
              Welcome back, {{ auth()->user()->name }}
            </h4>

            <!-- LIVE STATS — ONLY FOR ADMIN & STAFF -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
              <div class="row mb-6">

                @if(auth()->user()->role === 'admin')
                  <!-- Admin Global Stats -->
                  <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-bell ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $totalNotices ?? 0 }}</h5>
                            <small>Total Notices</small>
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
                            <h5 class="mb-0">{{ $approvedNotices ?? 0 }}</h5>
                            <small>Approved Notices</small>
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
                            <span class="avatar-initial rounded bg-label-danger"><i class="ti ti-package-off ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $totalLostItems ?? 0 }}</h5>
                            <small>Lost Items</small>
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
                            <span class="avatar-initial rounded bg-label-info"><i class="ti ti-package ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $totalFoundItems ?? 0 }}</h5>
                            <small>Found Items</small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @else
                  <!-- Staff Personal Stats -->
                  <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-file-text ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $userTotalNotices ?? 0 }}</h5>
                            <small>Your Notices</small>
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
                            <span class="avatar-initial rounded bg-label-danger"><i class="ti ti-package-off ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $userLostItems ?? 0 }}</h5>
                            <small>Your Lost Reports</small>
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
                            <span class="avatar-initial rounded bg-label-info"><i class="ti ti-package ti-lg"></i></span>
                          </div>
                          <div>
                            <h5 class="mb-0">{{ $userFoundItems ?? 0 }}</h5>
                            <small>Your Found Reports</small>
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
                        ? asset('storage/' . auth()->user()->profile_photo_path) 
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
                      <h5>Administrator Dashboard</h5>
                      <p>You have full control over campus content.</p>
                      <div class="d-flex gap-3">
                        <a href="{{ route('notices.approval') }}" class="btn btn-primary">Approve Notices</a>
                        <a href="{{ route('lostfound.index') }}" class="btn btn-outline-primary">Manage Lost & Found</a>
                      </div>
                    @elseif(auth()->user()->role === 'staff')
                      <h5>Staff Dashboard</h5>
                      <p>Post notices and report found items.</p>
                      <a href="{{ route('lostfound.index') }}" class="btn btn-primary">Report Lost/Found Item</a>
                    @else
                      <h5>Welcome, Student!</h5>
                      <p>Report lost items and check found items.</p>
                      <a href="{{ route('lostfound.index') }}" class="btn btn-primary">Lost & Found</a>
                    @endif
                  </div>
                </div>
              </div>
            </div>

          <!-- ADMIN-ONLY USER MANAGEMENT TABLE -->
           @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(auth()->user()->role === 'admin' && isset($users))
  <div class="card mt-6">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Manage Users</h5>
      <form method="GET" action="{{ route('dashboard') }}" class="d-flex">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm me-2" placeholder="Search by name..." />
        <button type="submit" class="btn btn-sm btn-primary">Search</button>
      </form>
      <small class="text-muted ms-3">Total: {{ $users->total() }}</small>
    </div>

    <div class="card-body">
      @if ($users->isEmpty())
        <div class="text-center py-5 text-muted">
          <i class="ti ti-users ti-4x mb-3"></i>
          <p>No users found.</p>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover table-borderless">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>ID Number</th>
                <th>Role</th>
                <th>Department</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Year</th>
                <th>Joined</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->id_number }}</td>
                  <td>
                    <span class="badge bg-label-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'staff' ? 'warning' : 'success') }}">
                      {{ ucfirst($user->role) }}
                    </span>
                  </td>
                  <td>{{ $user->department ?? '-' }}</td>
                  <td>{{ $user->phone ?? '-' }}</td>
                  <td>{{ ucfirst($user->gender ?? '-') }}</td>
                  <td>{{ $user->year ?? '-' }}</td>
                  <td>{{ $user->created_at->diffForHumans() }}</td>
                  <td>
                    <!-- Edit Button (opens modal) -->
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                      <i class="ti ti-edit"></i> Edit
                    </button>

                    @if ($user->id !== auth()->id())
                      <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete {{ $user->name }}? This cannot be undone.')">
                          <i class="ti ti-trash"></i> Delete
                        </button>
                      </form>
                    @else
                      <span class="text-muted small">Cannot delete self</span>
                    @endif
                  </td>
                </tr>

                <!-- Edit Modal -->
               <!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel{{ $user->id }}">Edit User: {{ $user->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PATCH')  <!-- This is critical for PATCH requests -->

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">ID Number</label>
            <input type="text" name="id_number" class="form-control" value="{{ old('id_number', $user->id_number) }}" required>
            @error('id_number') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
              <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
              <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
              <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Department</label>
            <input type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
              <option value="">Select</option>
              <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
              <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
              <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Year</label>
            <input type="text" name="year" class="form-control" value="{{ old('year', $user->year) }}">
          </div>
          <div class="mb-3">
            <label class="form-label">New Password (optional)</label>
            <input type="password" name="password" class="form-control">
            @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-4">
          {{ $users->links() }}
        </div>
      @endif
    </div>
  </div>
@endif

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