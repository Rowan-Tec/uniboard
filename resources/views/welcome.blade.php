<!doctype html>
<html lang="en" class="layout-navbar-fixed layout-menu-fixed" dir="ltr" data-assets-path="{{ asset('assets/') }}" data-bs-theme="light">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>Welcome to UniBoard – Your Campus Hub</title>

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
    }
    .navbar {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
      z-index: 1000;
    }
    [data-bs-theme="dark"] .navbar {
      background: rgba(15, 23, 42, 0.95);
    }
    .hero-section {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      color: white;
      overflow: hidden;
    }
    .video-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 1;
    }
    .hero-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to bottom, rgba(0,0,0,0.45), rgba(0,0,0,0.75));
      z-index: 2;
    }
    .hero-content {
      position: relative;
      z-index: 3;
      max-width: 900px;
      text-align: center;
      margin: 0 auto;
    }
    .scroll-down {
      position: absolute;
      bottom: 40px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 3rem;
      color: white;
      animation: bounce 2s infinite;
      cursor: pointer;
      z-index: 3;
    }
    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% { transform: translate(-50%, 0); }
      40% { transform: translate(-50%, -20px); }
      60% { transform: translate(-50%, -10px); }
    }
    .feature-icon {
      width: 80px;
      height: 80px;
      background: rgba(102, 126, 234, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: #667eea;
      margin: 0 auto 1.5rem;
    }
    [data-bs-theme="dark"] .feature-icon {
      background: rgba(255, 255, 255, 0.1);
      color: white;
    }
    .btn-hero {
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      font-weight: 600;
      padding: 0.9rem 2.5rem;
      border-radius: 50px;
      transition: all 0.3s ease;
    }
    .btn-hero:hover {
      background: linear-gradient(135deg, #5a67d8, #6b46c1);
      transform: translateY(-3px);
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container-xxl">
      <a class="navbar-brand d-flex align-items-center" href="/">
        <div class="animated-logo me-3">
          <i class="ti ti-school ti-4x"></i>
        </div>
        <span class="fw-bold fs-4">UniBoard</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
          <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
        </ul>

        <div class="d-flex align-items-center gap-3">
          <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">
            Login
          </a>
          <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4">
            Register
          </a>

          <!-- Dark Mode Toggle -->
          <button id="themeToggle" class="btn btn-link text-dark p-0" style="font-size: 1.5rem;">
            <i class="ti ti-moon-stars" id="moonIcon"></i>
            <i class="ti ti-sun d-none" id="sunIcon"></i>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <video class="video-bg" autoplay muted loop playsinline>
      <source src="https://assets.mixkit.co/videos/preview/mixkit-students-in-a-lecture-hall-4520-large.mp4" type="video/mp4">
    </video>
    <div class="hero-overlay"></div>

    <div class="hero-content container-xxl text-center">
      <div class="animated-logo animate__animated animate__zoomIn">
        <i class="ti ti-school ti-6x"></i>
      </div>

      <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInDown">
        Welcome to <span class="text-warning">UniBoard</span>
      </h1>
      <p class="lead mb-5 opacity-90 animate__animated animate__fadeInUp animate__delay-1s">
        Your all-in-one campus hub — stay connected, informed, and never lose track.
      </p>

      <div class="d-flex gap-4 justify-content-center flex-wrap animate__animated animate__fadeInUp animate__delay-2s">
        <a href="{{ route('login') }}" class="btn btn-hero btn-lg px-5">
          Login
        </a>
        <a href="{{ route('register') }}" class="btn btn-outline-hero btn-lg px-5">
          Register
        </a>
      </div>

      <!-- Scroll Down Arrow -->
      <div class="scroll-down animate__animated animate__bounce animate__infinite" onclick="document.querySelector('#features').scrollIntoView({behavior: 'smooth'})">
        <i class="ti ti-chevron-down"></i>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section id="features" class="py-7 bg-white">
    <div class="container-xxl">
      <div class="text-center mb-6">
        <h2 class="display-5 fw-bold mb-3">Your Campus, Connected</h2>
        <p class="lead text-muted mx-auto" style="max-width: 800px;">
          From real-time notices to private chats, lost & found matching, and profile management — everything in one place.
        </p>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="feature-card card h-100 text-center p-5">
            <div class="feature-icon bg-primary">
              <i class="ti ti-bell-ringing ti-3x"></i>
            </div>
            <h4 class="fw-bold mb-3 mt-4">Instant Notices</h4>
            <p class="text-muted">
              Official announcements delivered live. Staff post, admins approve — everyone stays informed.
            </p>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="feature-card card h-100 text-center p-5">
            <div class="feature-icon bg-success">
              <i class="ti ti-search ti-3x"></i>
            </div>
            <h4 class="fw-bold mb-3 mt-4">Lost & Found</h4>
            <p class="text-muted">
              Report lost items or found objects. Smart matching + photos help reunite belongings fast.
            </p>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="feature-card card h-100 text-center p-5">
            <div class="feature-icon bg-info">
              <i class="ti ti-messages ti-3x"></i>
            </div>
            <h4 class="fw-bold mb-3 mt-4">Private Messaging</h4>
            <p class="text-muted">
              Real-time chats with typing indicators, read receipts, and secure conversations.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats -->
  <section id="stats" class="py-7 bg-gradient text-white text-center">
    <div class="container-xxl">
      <div class="row g-5">
        <div class="col-md-4">
          <div class="counter animate__animated animate__fadeInUp">5,000+</div>
          <p class="lead">Active Students</p>
        </div>
        <div class="col-md-4">
          <div class="counter animate__animated animate__fadeInUp">1,200+</div>
          <p class="lead">Notices Posted</p>
        </div>
        <div class="col-md-4">
          <div class="counter animate__animated animate__fadeInUp">3,800+</div>
          <p class="lead">Items Reunited</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section id="testimonials" class="py-7 bg-white">
    <div class="container-xxl">
      <div class="text-center mb-6">
        <h2 class="display-5 fw-bold mb-3">What Students Say</h2>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-5">
              <p class="text-muted mb-4">"UniBoard helped me find my lost wallet in less than 24 hours. Game changer!"</p>
              <div class="d-flex align-items-center">
                
                <div>
                  <h6 class="mb-0">Sarah K.</h6>
                  <small class="text-muted">2nd Year, Computer Science</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-5">
              <p class="text-muted mb-4">"Real-time notices mean I never miss a deadline or event anymore."</p>
              <div class="d-flex align-items-center">
                
                <div>
                  <h6 class="mb-0">Thabo M.</h6>
                  <small class="text-muted">3rd Year, Engineering</small>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-5">
              <p class="text-muted mb-4">"The private chat feature made group projects so much easier."</p>
              <div class="d-flex align-items-center">
                
                <div>
                  <h6 class="mb-0">Lerato N.</h6>
                  <small class="text-muted">1st Year, Business</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-7 bg-gradient text-white text-center">
    <div class="container-xxl">
      <h2 class="display-5 fw-bold mb-4">Join the Connected Campus Today</h2>
      <p class="lead mb-5 opacity-90 mx-auto" style="max-width: 700px;">
        Sign up and make your university life easier, safer, and more connected.
      </p>
      <div class="d-flex gap-4 justify-content-center flex-wrap">
        <a href="{{ route('login') }}" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold">
          Login
        </a>
        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-bold">
          Register
        </a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="py-5 bg-dark text-white text-center">
    <div class="container-xxl">
      <p class="mb-0">
        © {{ date('Y') }} UniBoard • Built for students, by students
      </p>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Dark Mode Toggle -->
  <script>
    const toggle = document.getElementById('themeToggle');
    const moon = document.getElementById('moonIcon');
    const sun = document.getElementById('sunIcon');

    if (localStorage.getItem('theme') === 'dark' || 
        (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.setAttribute('data-bs-theme', 'dark');
      moon.classList.add('d-none');
      sun.classList.remove('d-none');
    }

    toggle.addEventListener('click', () => {
      if (document.documentElement.getAttribute('data-bs-theme') === 'dark') {
        document.documentElement.setAttribute('data-bs-theme', 'light');
        localStorage.setItem('theme', 'light');
        moon.classList.remove('d-none');
        sun.classList.add('d-none');
      } else {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        localStorage.setItem('theme', 'dark');
        moon.classList.add('d-none');
        sun.classList.remove('d-none');
      }
    });
  </script>
</body>
</html>