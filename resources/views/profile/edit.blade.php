<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed layout-menu-expanded" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Profile | UniBoard</title>

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
            <a href="{{ route('profile.edit') }}" class="menu-link">
              <i class="menu-icon tf-icons ti ti-user"></i>
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
                      ?asset('storage/' . auth()->user()->profile_photo_path)
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
            <h4 class="fw-bold py-3 mb-4">Edit Profile</h4>

            <div class="row">
              <div class="col-xl-8 mx-auto">
                <div class="card">
                  <div class="card-body">

                    @if(session('success'))
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                      @csrf
                      @method('PATCH')

                      <!-- Profile Photo -->
                      <div class="text-center mb-6">
                        <img src="{{ auth()->user()->profile_photo_path 
                            ?Storage::url(auth()->user()->profile_photo_path) 
                            : asset('assets/img/avatars/1.png') }}" 
                             alt="Profile" class="rounded-circle w-px-150 mb-4" id="previewPhoto" />
                        <div>
                          <label for="profile_photo" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-upload me-1"></i> Change Photo
                          </label>
                          <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*" onchange="previewImage(event)" />
                        </div>
                      </div>

                      <!-- Name & ID Number -->
                      <div class="row mb-4">
                        <div class="col-md-6">
                          <label class="form-label">Full Name</label>
                          <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required />
                          @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">ID Number</label>
                          <input type="text" name="id_number" class="form-control" value="{{ old('id_number', auth()->user()->id_number) }}" required />
                          @error('id_number') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                      </div>

                      <!-- Department & Gender -->
                      <div class="row mb-4">
                        <div class="col-md-6">
                          <label class="form-label">Department</label>
                          <select name="department" class="form-select" required>
                            <option value="cse" {{ auth()->user()->department == 'cse' ? 'selected' : '' }}>Computer Science & Engineering</option>
                            <option value="eee" {{ auth()->user()->department == 'eee' ? 'selected' : '' }}>Electrical & Electronics</option>
                            <option value="civil" {{ auth()->user()->department == 'civil' ? 'selected' : '' }}>Civil Engineering</option>
                            <option value="business" {{ auth()->user()->department == 'business' ? 'selected' : '' }}>Business Administration</option>
                            <option value="law" {{ auth()->user()->department == 'law' ? 'selected' : '' }}>Law</option>
                            <option value="staff" {{ auth()->user()->department == 'staff' ? 'selected' : '' }}>Staff / Faculty</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Gender</label>
                          <select name="gender" class="form-select" required>
                            <option value="male" {{ auth()->user()->gender == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ auth()->user()->gender == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ auth()->user()->gender == 'other' ? 'selected' : '' }}>Other / Prefer not to say</option>
                          </select>
                        </div>
                      </div>

                      <!-- Year & Phone -->
                      <div class="row mb-4">
                        <div class="col-md-6">
                          <label class="form-label">Year / Role</label>
                          <select name="year" class="form-select" required>
                            <option value="1st" {{ auth()->user()->year == '1st' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd" {{ auth()->user()->year == '2nd' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd" {{ auth()->user()->year == '3rd' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th" {{ auth()->user()->year == '4th' ? 'selected' : '' }}>4th Year</option>
                            <option value="staff" {{ auth()->user()->year == 'staff' ? 'selected' : '' }}>Staff / Faculty</option>
                          </select>
                        </div>
                        <div class="col-md-6">
                          <label class="form-label">Phone (optional)</label>
                          <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" />
                        </div>
                      </div>

                      <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                      </div>
                    </form>
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

  <script>
    function previewImage(event) {
      const output = document.getElementById('previewPhoto');
      output.src = URL.createObjectURL(event.target.files[0]);
    }
  </script>

  <!-- Core JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Dark Mode Script -->
  <script>
    // Your dark mode toggle script here (same as before)
  </script>
</body>
</html>