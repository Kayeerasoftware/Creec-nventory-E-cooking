<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel - E-Cooking Inventory</title>
    <link rel="icon" type="image/png" href="{{ asset('pictures/creec-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark" style="background: linear-gradient(90deg, #140168 0%, #5039d6 100%);">
        <div class="container-fluid">
            <a href="https://creec.or.ug" target="_blank">
                <img src="{{ asset('pictures/creec-logo.png') }}" alt="CREEC" height="35">
            </a>
            <span class="navbar-text text-white">
                <i class="fas fa-user-shield"></i> Admin Panel
            </span>
            <form action="/logout" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Welcome Card -->
                <div class="card shadow mb-4">
                    <div class="card-body text-center p-5">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        @endif
                        <h2 class="mb-2">Welcome, {{ auth()->user()->name }}</h2>
                        <p class="text-muted mb-4">
                            <span class="badge bg-danger fs-6">Administrator</span>
                        </p>
                        <p class="mb-4">{{ auth()->user()->email }}</p>
                        
                        <div class="d-grid gap-3 col-md-8 mx-auto">
                            <a href="/admin/dashboard" class="btn btn-primary btn-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                            </a>
                            <a href="/admin" class="btn btn-success btn-lg">
                                <i class="fas fa-home me-2"></i>Go to Home
                            </a>
                            <a href="/" class="btn btn-outline-primary">
                                <i class="fas fa-globe me-2"></i>Main Site
                            </a>
                            <a href="/profile" class="btn btn-outline-secondary">
                                <i class="fas fa-user-edit me-2"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-cogs fa-2x text-primary mb-2"></i>
                                <h3 class="mb-0">{{ $statistics['total_parts'] }}</h3>
                                <small class="text-muted">Parts</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-tools fa-2x text-success mb-2"></i>
                                <h3 class="mb-0">{{ $statistics['total_appliances'] }}</h3>
                                <small class="text-muted">Appliances</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-chalkboard-teacher fa-2x text-danger mb-2"></i>
                                <h3 class="mb-0">{{ $statistics['total_trainers'] }}</h3>
                                <small class="text-muted">Trainers</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm text-center">
                            <div class="card-body">
                                <i class="fas fa-user-cog fa-2x text-info mb-2"></i>
                                <h3 class="mb-0">{{ $statistics['total_technicians'] }}</h3>
                                <small class="text-muted">Technicians</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
