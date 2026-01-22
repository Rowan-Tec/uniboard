<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Lost & Found | UniBoard</title>
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
          <a href="http://127.0.0.1:8000/dashboard" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold">UniBoard</span>
          </a>
        </div>
        <div class="menu-inner-shadow"></div>
        <ul class="menu-inner py-1">
          <li class="menu-item active">
            <a href="http://127.0.0.1:8000/dashboard" class="menu-link">
              <i class="menu-icon tf-icons ti ti-smart-home"></i>
              <div>Dashboard</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="http://127.0.0.1:8000/notices" class="menu-link">
              <i class="menu-icon tf-icons ti ti-bell"></i>
              <div>Notices</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="http://127.0.0.1:8000/lost-found" class="menu-link">
              <i class="menu-icon tf-icons ti ti-search"></i>
              <div>Lost &amp; Found</div>
            </a>
          </li>
          <li class="menu-item">
            <a href="http://127.0.0.1:8000/profile" class="menu-link">
              <i class="menu-icon tf-icons ti ti-user-edit"></i>
              <div>Edit Profile</div>
            </a>
          </li>

                      <li class="menu-header small text-uppercase"><span class="menu-header-text">Admin Tools</span></li>
            <li class="menu-item">
              <a href="http://127.0.0.1:8000/admin/notices/approval" class="menu-link">
                <i class="menu-icon tf-icons ti ti-shield-check"></i>
                <div>Approve Notices (0)</div>
              </a>
            </li>
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
                      ? asset('storage/' . auth()->user()->profile_photo_path) 
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
            <div class="d-flex align-items-center justify-content-between mb-6">
              <h4 class="fw-bold mb-0">Lost & Found</h4>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reportItemModal">
                <i class="ti ti-plus me-1"></i> Report Item
              </button>
            </div>

            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-6" role="tablist">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#lost-tab">
                  <i class="ti ti-package-off me-1"></i> Lost Items ({{ $lostItems->count() }})
                </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#found-tab">
                  <i class="ti ti-package me-1"></i> Found Items ({{ $foundItems->count() }})
                </button>
              </li>
            </ul>

            <div class="tab-content">
              <!-- Lost Items -->
              <div class="tab-pane fade show active" id="lost-tab">
                <div class="row">
                  @forelse($lostItems as $item)
                    <div class="col-md-6 mb-4">
                      <div class="card h-100">
                        <div class="card-body">
                          <div class="d-flex align-items-start">
                            <div class="badge bg-label-danger rounded p-2 me-3">
                              <i class="ti ti-package-off ti-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                              <h5 class="card-title mb-1">{{ $item->title }}</h5>
                              <p class="text-muted small mb-2">
                                Reported by {{ $item->user->name }} • {{ $item->created_at->diffForHumans() }}
                              </p>
                              <p class="card-text">{{ Str::limit($item->description, 150) }}</p>
                              <p><strong>Location:</strong> {{ $item->location }}</p>

                              <!-- Images (Fixed - no json_decode) -->
                              @if($item->images && count($item->images) > 0)
                                <div class="row g-2 mb-3">
                                  @foreach($item->images as $image)
                                    <div class="col-4">
                                      <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Item photo" />
                                    </div>
                                  @endforeach
                                </div>
                              @endif

                              <a href="{{ route('lostfound.show', $item) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @empty
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body text-center py-6">
                          <p class="mb-0">No lost items reported yet.</p>
                        </div>
                      </div>
                    </div>
                  @endforelse
                </div>
              </div>

              <!-- Found Items -->
              <div class="tab-pane fade" id="found-tab">
                <div class="row">
                  @forelse($foundItems as $item)
                    <div class="col-md-6 mb-4">
                      <div class="card h-100">
                        <div class="card-body">
                          <div class="d-flex align-items-start">
                            <div class="badge bg-label-success rounded p-2 me-3">
                              <i class="ti ti-package ti-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                              <h5 class="card-title mb-1">{{ $item->title }}</h5>
                              <p class="text-muted small mb-2">
                                Reported by {{ $item->user->name }} • {{ $item->created_at->diffForHumans() }}
                              </p>
                              <p class="card-text">{{ Str::limit($item->description, 150) }}</p>
                              <p><strong>Location:</strong> {{ $item->location }}</p>

                              <!-- Images (Fixed - no json_decode) -->
                              @if($item->images && count($item->images) > 0)
                                <div class="row g-2 mb-3">
                                  @foreach($item->images as $image)
                                    <div class="col-4">
                                      <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" alt="Item photo" />
                                    </div>
                                  @endforeach
                                </div>
                              @endif

                              <a href="{{ route('lostfound.show', $item) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @empty
                    <div class="col-12">
                      <div class="card">
                        <div class="card-body text-center py-6">
                          <p class="mb-0">No found items reported yet.</p>
                        </div>
                      </div>
                    </div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Report Item Modal -->
    <div class="modal fade" id="reportItemModal">
      <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('lostfound.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Report Lost or Found Item</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label class="form-label">Type</label>
                  <select name="type" class="form-select" required>
                    <option value="lost">I Lost Something</option>
                    <option value="found">I Found Something</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Date</label>
                  <input type="date" name="date_lost_found" class="form-control" required />
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required placeholder="e.g. Lost Black Wallet" />
              </div>

              <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" required placeholder="e.g. Library Building, 2nd Floor" />
              </div>

              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="5" required></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Upload Photos (up to 4)</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*" />
                <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Submit Report</button>
            </div>
          </div>
        </form>
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
      // Your dark mode script
    </script>
  </body>
</html>