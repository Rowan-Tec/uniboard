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
            <a href="{{ route('lostfound.index') }}" class="btn btn-outline-secondary mb-4">
              <i class="ti ti-arrow-left me-1"></i> Back to Lost & Found
            </a>

            <div class="row">
              <div class="col-lg-9 mx-auto">
                <div class="card mb-6">
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
                      Reported by <strong>{{ $item->user->name }}</strong> • {{ $item->created_at->diffForHumans() }}<br>
                      Date: {{ $item->date_lost_found->format('M d, Y') }} • Location: <strong>{{ $item->location }}</strong>
                    </p>

                    <div class="mb-5">
                      <h5>Description</h5>
                      <p class="lead">{!! nl2br(e($item->description)) !!}</p>
                    </div>

                    @if($item->images && count($item->images) > 0)
                      <h5>Photos</h5>
                      <div class="row g-3 mb-5">
                        @foreach($item->images as $image)
                          <div class="col-md-6">
                            <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded shadow-sm" alt="Item photo" />
                          </div>
                        @endforeach
                      </div>
                    @endif

                    @if(auth()->id() === $item->user_id || auth()->user()->role === 'admin')
                      <form method="POST" action="{{ route('lostfound.resolve', $item) }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success btn-lg" {{ $item->is_resolved ? 'disabled' : '' }}>
                          <i class="ti ti-check me-2"></i> Mark as Resolved
                        </button>
                      </form>
                    @endif
                  </div>
                </div>

                <!-- Smart Matches Section -->
                @if($matches->count() > 0)
                  <div class="card">
                    <div class="card-header">
                      <h5 class="mb-0">
                        <i class="ti ti-brain me-2"></i> Smart Matches ({{ $matches->count() }} possible)
                      </h5>
                      <small class="text-muted">AI-powered suggestions based on description, location, date, and images</small>
                    </div>
                    <div class="card-body">
                      <div class="row g-4">
                        @foreach($matches as $match)
                          <div class="col-md-6">
                            <div class="card h-100 border-primary shadow-sm">
                              <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                  <h6 class="card-title mb-0">{{ $match->title }}</h6>
                                  <span class="badge bg-primary fs-sm">{{ $match->match_score ?? 'High' }}% Match</span>
                                </div>
                                <p class="text-muted small mb-2">
                                  Reported by <strong>{{ $match->user->name }}</strong> • {{ $match->created_at->diffForHumans() }}
                                </p>
                                <p class="flex-grow-1">{{ Str::limit($match->description, 120) }}</p>
                                <p><strong>Location:</strong> {{ $match->location }}</p>

                                <div class="mt-auto">
                                  <div class="d-flex gap-2">
                                    <a href="{{ route('lostfound.show', $match) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                                      View Details
                                    </a>

                                    <!-- Contact Button -->
                                    @if(auth()->id() !== $match->user_id)
                                      <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#contactModal{{ $match->id }}">
                                        <i class="ti ti-message-circle me-1"></i> Contact
                                      </button>
                                    @else
                                      <span class="text-muted small">Your own report</span>
                                    @endif
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <!-- Contact Modal -->
                          <div class="modal fade" id="contactModal{{ $match->id }}" tabindex="-1">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title">Contact {{ $match->user->name }}</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                  <p>You are about to contact the person who reported this {{ $match->type }} item.</p>
                                  <div class="alert alert-info">
                                    <strong>Contact Info:</strong><br>
                                    Name: {{ $match->user->name }}<br>
                                    ID: {{ $match->user->id_number }}<br>
                                    @if($match->user->phone)
                                      Phone: {{ $match->user->phone }}<br>
                                    @endif
                                    Email: {{ $match->user->email }}
                                  </div>
                                  <p class="text-muted">Please contact them politely regarding your {{ $item->type }} item.</p>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                  <a href="{{ route('messages.show', $match->user) }}" class="btn btn-success btn-sm">
  <i class="ti ti-message-circle me-1"></i> Message
</a>
                                </div>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
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