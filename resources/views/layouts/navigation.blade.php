<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-info">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="ti ti-menu-2 ti-lg"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <ul class="navbar-nav flex-row align-items-center ms-auto">

      <!-- Notifications Bell + Badge + Dropdown -->
      <li class="nav-item dropdown notification-dropdown">
  <a class="nav-link dropdown-toggle hide-arrow position-relative" href="javascript:void(0);" data-bs-toggle="dropdown">
    <i class="ti ti-bell ti-lg"></i>
    <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle" 
          id="unreadCount" style="font-size: 0.75rem; min-width: 18px; height: 18px; line-height: 1.2;">
      0
    </span>
  </a>
  <ul class="dropdown-menu dropdown-menu-end p-0 shadow-lg" style="width: 340px; max-height: 420px; overflow-y: auto; border-radius: 12px;">
    <li class="dropdown-header bg-primary text-white py-3 px-4 rounded-top">
      <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Notifications</h6>
        <small id="unreadText">0 unread</small>
      </div>
    </li>
    <div id="notificationList" class="p-2">
      <div class="text-center py-5 text-muted small">
        <i class="ti ti-loader ti-spin me-2"></i> Loading...
      </div>
    </div>
    <li class="dropdown-footer text-center py-3 border-top">
      <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary w-100">
        View All
      </a>
    </li>
  </ul>
</li>

      <!-- Dark Mode Toggle -->
      <li class="nav-item me-3">
        <a href="javascript:void(0);" id="dark-mode-toggle" class="nav-link px-3 text-white">
          <i class="ti ti-moon ti-lg me-1" id="moon-icon"></i>
          <i class="ti ti-sun ti-lg me-1 d-none" id="sun-icon"></i>
          <span id="mode-text">Dark Mode</span>
        </a>
      </li>

      <!-- User Profile Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('assets/img/avatars/1.png') }}" 
               alt="user" class="rounded-circle w-px-40 h-px-40 object-fit-cover">
        </a>
        <ul class="dropdown-menu dropdown-menu-end py-2">
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
              <i class="ti ti-user me-2"></i>
              <span>Profile</span>
            </a>
          </li>
          <li><hr class="dropdown-divider my-1"></li>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                <i class="ti ti-logout me-2"></i>
                <span>Logout</span>
              </button>
            </form>
          </li>
        </ul>
      </li>

    </ul>
  </div>
</nav>