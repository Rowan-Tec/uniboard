<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register • UniBoard</title>

  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>
</head>
<body>
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-6">
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-6">
              <a href="{{ url('/') }}" class="app-brand-link">
                <span class="app-brand-logo demo">
                  <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M0 0V6.85398C0 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0Z" fill="#696CFF"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="#696CFF"/>
                  </svg>
                </span>
                <span class="app-brand-text demo text-heading fw-bold ms-2">UniBoard</span>
              </a>
            </div>

            <h4 class="mb-1 text-center">Join UniBoard Today! 🚀</h4>
            <p class="mb-6 text-center">Create your campus account</p>

            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
              @csrf

              <!-- Profile Photo Upload & Preview -->
              <div class="text-center mb-6">
                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="avatar" class="rounded-circle w-px-100" id="uploadedAvatar" />
                <div class="mt-3">
                  <label for="profile_photo" class="btn btn-outline-primary btn-sm">
                    <i class="ti ti-upload me-1"></i> Upload Photo (optional)
                  </label>
                  <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*" onchange="loadFile(event)" />
                </div>
              </div>

              <!-- Full Name & ID Number -->
              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label">Full Name</label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                         value="{{ old('name') }}" required autofocus />
                  @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">ID Number</label>
                  <input type="text" name="id_number" class="form-control @error('id_number') is-invalid @enderror" 
                         value="{{ old('id_number') }}" required />
                  @error('id_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Email -->
              <div class="mb-4">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required />
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Department & Gender -->
              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label">Department</label>
                  <select name="department" class="form-select @error('department') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    <option value="cse" {{ old('department') == 'cse' ? 'selected' : '' }}>Computer Science</option>
                    <option value="eee" {{ old('department') == 'eee' ? 'selected' : '' }}>Electrical Engineering</option>
                    <option value="civil" {{ old('department') == 'civil' ? 'selected' : '' }}>Civil Engineering</option>
                    <option value="business" {{ old('department') == 'business' ? 'selected' : '' }}>Business</option>
                    <option value="law" {{ old('department') == 'law' ? 'selected' : '' }}>Law</option>
                    <option value="staff" {{ old('department') == 'staff' ? 'selected' : '' }}>Staff</option>
                  </select>
                  @error('department')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Gender</label>
                  <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                    <option value="">Select...</option>
                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                  </select>
                  @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Year/Role & Phone -->
              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label">Year / Role</label>
                  <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                    <option value="">Select...</option>
                    <option value="1st" {{ old('year') == '1st' ? 'selected' : '' }}>1st Year</option>
                    <option value="2nd" {{ old('year') == '2nd' ? 'selected' : '' }}>2nd Year</option>
                    <option value="3rd" {{ old('year') == '3rd' ? 'selected' : '' }}>3rd Year</option>
                    <option value="4th" {{ old('year') == '4th' ? 'selected' : '' }}>4th Year</option>
                    <option value="staff" {{ old('year') == 'staff' ? 'selected' : '' }}>Staff</option>
                  </select>
                  @error('year')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Phone Number (optional)</label>
                  <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                         value="{{ old('phone') }}" />
                  @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Password & Confirm -->
              <div class="mb-4 form-password-toggle">
                <label class="form-label">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                         required autocomplete="new-password" />
                  <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                </div>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-6 form-password-toggle">
                <label class="form-label">Confirm Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" name="password_confirmation" class="form-control" required />
                  <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                </div>
              </div>

              <button type="submit" class="btn btn-primary d-grid w-100">Sign up</button>
            </form>

            <p class="text-center mt-6">
              <span>Already have an account?</span>
              <a href="{{ route('login') }}"><span class="text-primary fw-medium">Sign in</span></a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Profile Photo Preview Script -->
  <script>
    const loadFile = function(event) {
      const output = document.getElementById('uploadedAvatar');
      output.src = URL.createObjectURL(event.target.files[0]);
    };
  </script>

  <!-- Core JS -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>