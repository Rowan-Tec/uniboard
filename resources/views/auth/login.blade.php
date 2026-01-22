<!doctype html>
<html lang="en" class="layout-wide customizer-hide" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login • UniBoard</title>

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
      background-size: cover;
      background-position: center;
      min-height: 100vh;
      color: white;
    }

    .authentication-inner {
      max-width: 420px;
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

            <h4 class="mb-1 text-center">Welcome back!</h4>
            <p class="mb-6 text-center">Please sign in to your account</p>

            <form method="POST" action="{{ route('login') }}">
              @csrf

              <div class="mb-4">
                <label class="form-label">Email or ID Number</label>
                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required autofocus />
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-4 form-password-toggle">
                <label class="form-label">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required />
                  <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                </div>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember-me" />
                  <label class="form-check-label" for="remember-me">Remember Me</label>
                </div>
              </div>

              <button type="submit" class="btn btn-primary d-grid w-100">Login</button>
            </form>

            <p class="text-center mt-6">
              <span>New here?</span>
              <a href="{{ route('register') }}"><span class="text-primary fw-medium">Create an account</span></a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>