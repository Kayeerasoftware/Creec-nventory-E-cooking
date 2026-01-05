<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trainer Panel - E-Cooking Inventory</title>
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
                <i class="fas fa-chalkboard-teacher"></i> Trainer Panel
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
                        @if(auth('trainer')->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth('trainer')->user()->profile_picture) }}" alt="Profile" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-danger text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                        @endif
                        <h2 class="mb-2">Welcome, {{ auth('trainer')->user()->name }}</h2>
                        <p class="text-muted mb-2">
                            <span class="badge bg-danger fs-6">Trainer</span>
                        </p>
                        <p class="mb-1"><i class="fas fa-envelope text-muted me-2"></i>{{ auth('trainer')->user()->email }}</p>
                        @if(auth('trainer')->user()->phone)
                        <p class="mb-4"><i class="fas fa-phone text-muted me-2"></i>{{ auth('trainer')->user()->phone }}</p>
                        @endif
                        
                        <div class="d-grid gap-3 col-md-8 mx-auto">
                            <a href="/trainer/home" class="btn btn-danger btn-lg">
                                <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                            </a>
                            <a href="/trainers" class="btn btn-success btn-lg">
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

                <div class="row g-3 mb-4">
                    <!-- Quick Stats -->
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-cogs fa-2x text-primary mb-2"></i>
                                <h3 class="mb-0">{{ $statistics['total_parts'] }}</h3>
                                <small class="text-muted">Total Parts</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-tools fa-2x text-success mb-2"></i>
                                <h3 class="mb-0">{{ $statistics['total_appliances'] }}</h3>
                                <small class="text-muted">Total Appliances</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-user-cog fa-2x text-info mb-2"></i>
                                <h3 class="mb-0">{{ $statistics['total_technicians'] }}</h3>
                                <small class="text-muted">Total Technicians</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Details Card -->
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Professional Profile</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-star text-warning me-2"></i>Specialty:</strong>
                                <p class="mb-0">{{ auth('trainer')->user()->specialty ?? 'Not specified' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-phone text-primary me-2"></i>Phone:</strong>
                                <p class="mb-0">{{ auth('trainer')->user()->phone ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-briefcase text-success me-2"></i>Experience:</strong>
                                <p class="mb-0">{{ auth('trainer')->user()->experience ?? 0 }} years</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-map-marker-alt text-danger me-2"></i>Location:</strong>
                                <p class="mb-0">{{ auth('trainer')->user()->location ?? 'Not specified' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-envelope text-info me-2"></i>Email:</strong>
                                <p class="mb-0">{{ auth('trainer')->user()->email }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong><i class="fas fa-clock text-secondary me-2"></i>Last Seen:</strong>
                                <p class="mb-0">{{ auth('trainer')->user()->last_seen ? \Carbon\Carbon::parse(auth('trainer')->user()->last_seen)->diffForHumans() : 'Never' }}</p>
                            </div>
                            @if(auth('trainer')->user()->qualifications)
                            <div class="col-12 mb-3">
                                <strong><i class="fas fa-certificate text-info me-2"></i>Qualifications:</strong>
                                <p class="mb-0">{{ auth('trainer')->user()->qualifications }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
