<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Cooking Inventory Management System')</title>
    <link rel="icon" type="image/png" href="{{ asset('pictures/creec-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-dark fixed-top" style="background: linear-gradient(90deg, #140168 0%, #5039d6 100%); z-index: 9999; padding: 0.2rem 0.5rem; min-height: 45px;">
        <div class="container-fluid d-flex align-items-center justify-content-between px-2" style="min-height: 45px;">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-outline-light btn-sm d-lg-none p-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand d-flex align-items-center m-0 p-0" href="/">
                    <img src="{{ asset('pictures/creec-logo.png') }}" alt="Logo" id="navLogo" style="height: 30px;">
                    <span id="navTitle" class="ms-2 fw-semibold" style="font-size: 0.85rem;">E-Cooking Inventory</span>
                </a>
            </div>
            
            <div class="d-flex align-items-center gap-2 ms-auto">
                <div id="navTime" class="text-white text-center" style="font-size: 0.7rem; line-height: 1.1;">
                    <div id="time" class="fw-bold"></div>
                    <div id="date" style="font-size: 0.6rem; opacity: 0.9;"></div>
                </div>
                
                @auth
                    <div class="dropdown">
                        <a href="#" class="btn btn-link navbar-text dropdown-toggle d-none d-md-block" style="font-size: 1.1em; color: #ffe66d; border: none; outline: none; background: rgba(255,230,109,0.15); padding: 8px 16px; border-radius: 8px; text-decoration: none; transition: all 0.3s;" data-bs-toggle="dropdown" onmouseover="this.style.background='rgba(255,230,109,0.25)'" onmouseout="this.style.background='rgba(255,230,109,0.15)'">
                            <i class="fas fa-user-circle me-2"></i>{{ auth()->user()->name }}
                        </a>
                        <a href="#" class="btn btn-link navbar-text dropdown-toggle d-md-none" style="font-size: 1.2em; color: #ffe66d; border: none; outline: none; background: rgba(255,230,109,0.15); padding: 8px 12px; border-radius: 8px; text-decoration: none;" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="border-radius: 10px; border: none; min-width: 200px;">
                            <li class="px-3 py-2 border-bottom">
                                <small class="text-muted">Signed in as</small>
                                <div class="fw-bold">{{ auth()->user()->name }}</div>
                                <small class="badge bg-info">Technician</small>
                            </li>
                            <li><a class="dropdown-item" href="/technician/panel"><i class="fas fa-user-circle me-2"></i>Panel</a></li>
                            <li><a class="dropdown-item" href="/technicians"><i class="fas fa-home me-2"></i>Home</a></li>
                            <li><a class="dropdown-item" href="/"><i class="fas fa-globe me-2"></i>Main Site</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();"><i class="fas fa-sign-out-alt me-2 text-danger"></i>Logout</a></li>
                            <form id="logout-form-top" action="/logout" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </div>
                @else
                    <a class="btn btn-outline-light btn-sm" href="/login"><i class="fas fa-sign-in-alt"></i><span id="navLogin" class="ms-1">Login</span></a>
                @endauth
            </div>
        </div>
    </nav>

    @auth
    <!-- Sidebar -->
    <div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="sidebar">
        <div class="offcanvas-header d-lg-none">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="nav flex-column">
                <a class="nav-link" href="/technician/home"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a class="nav-link" href="/trainers"><i class="fas fa-chalkboard-teacher"></i> Trainers</a>
                <a class="nav-link" href="/technicians"><i class="fas fa-user-cog"></i> Technicians</a>
                <hr>
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="nav-link text-danger" style="background:none;border:none;width:100%;text-align:left;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </nav>
        </div>
    </div>
    @endauth

    <!-- Main Content -->
    <main class="main-content" style="padding-top: 45px; min-height: 100vh;">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/navbar.js') }}"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    @yield('scripts')
</body>
</html>
