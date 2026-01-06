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
            <h4 class="fw-bold py-3 mb-4">Notice Approval Center</h4>

            @if(session('success'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-6" role="tablist">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending-tab" type="button">
                  <i class="ti ti-clock me-1"></i> Pending ({{ $pendingNotices->count() }})
                </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rejected-tab" type="button">
                  <i class="ti ti-x me-1"></i> Rejected ({{ $rejectedNotices->count() }})
                </button>
              </li>
            </ul>

            <div class="tab-content">

              <!-- Pending Tab -->
              <div class="tab-pane fade show active" id="pending-tab">
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

                            <div class="mt-auto d-flex gap-2">
                              <form method="POST" action="{{ route('notices.approve', $notice) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                  <i class="ti ti-check me-1"></i> Approve
                                </button>
                              </form>

                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $notice->id }}">
                                <i class="ti ti-x me-1"></i> Reject
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Reject Modal -->
                      <div class="modal fade" id="rejectModal{{ $notice->id }}" tabindex="-1">
                        <div class="modal-dialog">
                          <form method="POST" action="{{ route('notices.reject', $notice) }}">
                            @csrf
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title">Reject Notice</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">
                                <p><strong>Title:</strong> {{ $notice->title }}</p>
                                <div class="mb-3">
                                  <label class="form-label">Reason for rejection <span class="text-danger">*</span></label>
                                  <textarea name="reject_reason" class="form-control" rows="4" required placeholder="e.g. Inappropriate content, duplicate, etc."></textarea>
                                  @error('reject_reason')
                                    <small class="text-danger">{{ $message }}</small>
                                  @enderror
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Reject Notice</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>

              <!-- Rejected Tab -->
              <div class="tab-pane fade" id="rejected-tab">
                @if($rejectedNotices->count() == 0)
                  <div class="card">
                    <div class="card-body text-center py-6">
                      <p class="mb-0">No rejected notices.</p>
                    </div>
                  </div>
                @else
                  <div class="row">
                    @foreach($rejectedNotices as $notice)
                      <div class="col-md-6 mb-4">
                        <div class="card border-danger">
                          <div class="card-body">
                            <h5 class="card-title text-danger">{{ $notice->title }}</h5>
                            <p class="text-muted small mb-3">
                              Posted by <strong>{{ $notice->user->name }}</strong> • {{ $notice->rejected_at->diffForHumans() }}
                            </p>
                            <p class="mb-3">{{ $notice->content }}</p>

                            <div class="alert alert-danger py-2">
                              <strong>Rejection Reason:</strong><br>
                              {{ $notice->reject_reason ?: 'No reason provided.' }}
                            </div>

                            <small class="text-muted">
                              Rejected by {{ $notice->approver?->name ?? 'Admin' }}
                            </small>
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