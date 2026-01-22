<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr"
      data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register • UniBoard</title>

  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

  <style>
    body {
      background: linear-gradient(135deg, #6a11cb 100%) !important;
      min-height: 100vh;
      color: white;
    }

    .authentication-inner {
      max-width: 520px;
      margin: auto;
    }

    .card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      color: #333;
    }

    .card-body {
      padding: 2.5rem;
    }

    .btn-primary {
      background: #6a11cb;
      border-color: #6a11cb;
    }

    .btn-primary:hover {
      background: #2575fc;
      border-color: #2575fc;
    }

    .text-primary {
      color: #6a11cb !important;
    }
  </style>

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
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0 0V6.85398C0 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0Z"
                        fill="#696CFF"/>
                  <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                        fill="#696CFF"/>
                </svg>
              </span>
              <span class="app-brand-text demo fw-bold ms-2">UniBoard</span>
            </a>
          </div>

          <h4 class="mb-1 text-center">Join UniBoard </h4>
          <p class="mb-6 text-center">Create your campus account</p>

          <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <!-- Avatar -->
            <div class="text-center mb-5">
              <img src="{{ asset('assets/img/avatars/1.png') }}"
                   class="rounded-circle w-px-100" id="uploadedAvatar">
              <div class="mt-3">
                <label class="btn btn-outline-primary btn-sm">
                  Upload Photo
                  <input type="file" name="profile_photo" class="d-none"
                         accept="image/*" onchange="loadFile(event)">
                </label>
              </div>
            </div>

            <!-- Name & ID -->
            <div class="row">
              <div class="col-md-6 mb-4">
                <input type="text" name="name" class="form-control"
                       placeholder="Full Name" required>
              </div>
              <div class="col-md-6 mb-4">
                <input type="text" name="id_number" class="form-control"
                       placeholder="ID Number" required>
              </div>
            </div>

            <div class="mb-4">
              <input type="email" name="email" class="form-control"
                     placeholder="Email Address" required>
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <select name="department" class="form-select" required>
                  <option value="">Department</option>
                  <option value="cse">Computer Science</option>
                  <option value="eee">Electrical Engineering</option>
                  <option value="civil">Civil Engineering</option>
                  <option value="business">Business</option>
                  <option value="law">Law</option>
                </select>
              </div>
              <div class="col-md-6 mb-4">
                <select name="gender" class="form-select" required>
                  <option value="">Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <select name="year" class="form-select" required>
                  <option value="">Year / Role</option>
                  <option value="1st">1st Year</option>
                  <option value="2nd">2nd Year</option>
                  <option value="3rd">3rd Year</option>
                  <option value="4th">4th Year</option>
                  <option value="staff">Staff</option>
                </select>
              </div>
              <div class="col-md-6 mb-4">
                <input type="text" name="phone" class="form-control"
                       placeholder="Phone (optional)">
              </div>
            </div>

            <div class="mb-4">
              <input type="password" name="password" class="form-control"
                     placeholder="Password" required>
            </div>

            <div class="mb-5">
              <input type="password" name="password_confirmation"
                     class="form-control" placeholder="Confirm Password" required>
            </div>

            <button type="submit" class="btn btn-primary d-grid w-100">
              Create Account
            </button>
          </form>

          <p class="text-center mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-primary fw-medium">Sign in</a>
          </p>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function loadFile(event) {
    document.getElementById('uploadedAvatar').src =
      URL.createObjectURL(event.target.files[0]);
  }
</script>

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
