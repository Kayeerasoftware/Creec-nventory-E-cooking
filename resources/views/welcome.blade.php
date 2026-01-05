<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>E-Cooking Inventory Management System</title>
    <link rel="icon" type="image/png" href="{{ asset('pictures/creec-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
</head>
<body id="dashboard">
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-dark fixed-top" style="background: linear-gradient(90deg, #140168 0%, #5039d6 100%); z-index: 9999; padding: 0.2rem 0.3rem; min-height: 45px;">
        <div class="container-fluid d-flex justify-content-between align-items-center" style="gap: 0.2rem; flex-wrap: nowrap;">
            <div class="d-flex align-items-center" style="gap: 0.2rem; flex-shrink: 0;">
                <button class="btn btn-outline-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" style="padding: 0.2rem 0.4rem; font-size: 0.9rem;">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="btn btn-link" href="https://creec.or.ug" target="_blank" style="padding: 0; border: none;"><img src="{{ asset('pictures/creec-logo.png') }}" alt="CREEC Logo" height="30" id="welcomeLogo"></a>
                <button class="btn btn-link d-none d-md-block" style="font-size: 1em; color: #ff8c00; padding: 0.2rem 0.5rem;" type="button" data-bs-toggle="modal" data-bs-target="#chatModal"><i class="fas fa-headset me-1"></i>Support</button>
                <button class="btn btn-link d-md-none" style="font-size: 1em; color: #ff8c00; padding: 0.2rem 0.4rem;" type="button" data-bs-toggle="modal" data-bs-target="#chatModal"><i class="fas fa-headset"></i></button>
            </div>
            <div class="d-none d-md-block" style="flex-grow: 1; text-align: center;">
                <span style="font-size: 1em; color: white;"><i class="fas fa-tools"></i> E-Cooking Inventory</span>
            </div>
            <div class="navbar-nav d-flex align-items-center flex-row" style="gap: 0.2rem; flex-shrink: 0; flex-wrap: nowrap;">
                <div style="background: rgba(255,255,255,0.1); padding: 2px 4px; border-radius: 3px;">
                    <span class="text-white" style="font-size: 0.65em; white-space: nowrap;">
                        <span id="currentDate"></span> <span id="currentTime"></span>
                    </span>
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
                                <small class="badge bg-{{ auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'trainer' ? 'warning' : 'info') }}">{{ ucfirst(auth()->user()->role) }}</small>
                            </li>
                            @if(auth()->user()->role === 'admin')
                                <li><a class="dropdown-item" href="/admin"><i class="fas fa-cog me-2"></i>Admin Dashboard</a></li>
                                <li><a class="dropdown-item" href="/profile"><i class="fas fa-user me-2"></i>My Profile</a></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-top').submit();">
                                    <i class="fas fa-sign-out-alt me-2 text-danger"></i>Logout
                                </a>
                                <form id="logout-form-top" action="/logout" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="/login" class="btn btn-link navbar-text d-none d-md-block" style="font-size: 1.1em; color: #ffe66d; border: none; outline: none; background: rgba(255,230,109,0.15); padding: 8px 16px; border-radius: 8px; text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,230,109,0.25)'" onmouseout="this.style.background='rgba(255,230,109,0.15)'"><img src="/pictures/Power Cord.png" alt="Icon" style="width: 35px; height: 35px; margin-right: 8px;">Manage Your Workshop</a>
                    <a href="/login" class="btn btn-link navbar-text d-md-none" style="font-size: 1.2em; color: #ffe66d; border: none; outline: none; background: rgba(255,230,109,0.15); padding: 8px 12px; border-radius: 8px; text-decoration: none;"><img src="/pictures/Power Cord.png" alt="Icon" style="width: 35px; height: 35px;"></a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="offcanvas offcanvas-start bg-light" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
        <div class="offcanvas-header d-lg-none">
            <h5 class="offcanvas-title" id="sidebarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-lg-block d-flex flex-column" style="padding-bottom: 0; position: relative;">
            <!-- Sidebar Header -->
            <div class="sidebar-header mb-2 px-2">
                <div class="text-center">
                    <h5 class="mb-0 fw-bold" style="color: #140168; font-size: 0.9rem;">R&M</h5>
                    <div class="d-flex align-items-center justify-content-center mt-1">
                        <i class="fas fa-chalkboard-teacher me-1" style="color: #140168; font-size: 0.75rem;"></i>
                        <span class="fw-bold" style="color: #140168; font-size: 0.5rem;">Inventory System</span>
                    </div>
                </div>
                <hr class="my-2" style="border: 1px solid #140168; opacity: 1;">
            </div>
            <div class="px-2 mb-2">
                @auth
                    <span class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-1 text-success" style="font-size: 0.7rem;"></i>
                        <span class="fw-bold text-success" style="font-size: 0.7rem;">Authenticated</span>
                    </span>
                @else
                    <span class="d-flex align-items-center">
                        <i class="fas fa-user-friends me-1 text-info" style="font-size: 0.7rem;"></i>
                        <span class="fw-bold text-info" style="font-size: 0.7rem;">Guest access</span>
                    </span>
                @endauth
            </div>
            <div class="px-2 mb-2">
                @auth
                    <span class="d-flex align-items-center">
                        <i class="fas fa-shield-alt me-1 text-success" style="font-size: 0.7rem;"></i>
                        <span class="fw-bold text-success" style="font-size: 0.7rem;">{{ ucfirst(auth()->user()->role) }} Access</span>
                    </span>
                @else
                    <span class="d-flex align-items-center">
                        <i class="fas fa-user-lock me-1 text-danger" style="font-size: 0.7rem;"></i>
                        <span class="fw-bold text-danger" style="font-size: 0.6rem;">No login needed</span>
                    </span>
                @endauth
            </div>
            <div style="flex: 1;">
            <nav class="nav flex-column">
                <a class="nav-link active" href="#dashboard-section">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a class="nav-link" href="#inventory">
                    <i class="fas fa-boxes"></i> Inventory
                </a>
                <a class="nav-link" href="#appliances">
                    <i class="fas fa-tools"></i> Appliances
                </a>
                <a class="nav-link" href="#trainers">
                    <i class="fas fa-chalkboard-teacher"></i> Trainers
                </a>
                <a class="nav-link" href="#qualified-technicians">
                    <i class="fas fa-user-graduate"></i> Technicians
                </a>
                <a class="nav-link" href="#reports">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
                <a class="nav-link" href="#settings">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <hr class="my-3" style="border: 0.2px solid #ccc; opacity: 0.5;">

            <div class="px-2 mb-2">
                <span class="d-flex align-items-center">
                    <i class="fas fa-user-friends me-1 text-info" style="font-size: 0.7rem;"></i>
                    <span class="fw-bold text-info" style="font-size: 0.7rem;">User access</span>
                </span>
            </div>
            <div class="px-2 mb-2">
                <span class="d-flex align-items-center">
                    <i class="fas fa-user-lock me-1 text-danger" style="font-size: 0.7rem;"></i>
                    <span class="fw-bold text-danger" style="font-size: 0.7rem;">must login</span>
                </span>
            </div>

                @auth
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="/logout" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a class="nav-link" href="/login">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                @endauth

                <a class="nav-link" href="/admin/panel">
                    <i class="fas fa-user-circle"></i> Panel
                </a>
                <a class="nav-link" href="/admin/home">
                    <i class="fas fa-chart-line"></i> Admin Acess
                </a>
            </nav>
            </div>

            <!-- User Profile Box -->
            <div class="px-2 mb-2">
                <div class="card" style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 8px; margin: 0;">
                    <div class="d-flex align-items-center">
                        <div class="@auth bg-{{ auth()->user()->role === 'admin' ? 'danger' : (auth()->user()->role === 'trainer' ? 'warning' : 'info') }} @else bg-secondary @endauth rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 30px; min-width: 30px;">
                            <i class="fas fa-user text-white" style="font-size: 0.8rem;"></i>
                        </div>
                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="fw-bold text-dark" style="font-size: 0.85rem; line-height: 1.2;">@auth{{ auth()->user()->name }}@else Guest User @endauth</div>
                            <div class="text-muted" style="font-size: 0.7rem; line-height: 1.2;">@auth{{ ucfirst(auth()->user()->role) }}@else No Role @endauth</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Chat Button -->
            <div class="px-2 pb-4 mb-3">
                <button class="btn btn-success w-100 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#chatModal" style="border: none; border-radius: 8px; padding: 12px 20px; font-size: 1rem; font-weight: 500; box-shadow: 0 2px 4px rgba(40,167,69,0.3); transition: all 0.2s ease; white-space: nowrap;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(40,167,69,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(40,167,69,0.3)'">
                    <i class="fas fa-comments me-2" style="font-size: 1.1rem;"></i>
                    <span>Support Chat</span>
                    <span class="position-absolute top-0 end-0 translate-middle p-1 bg-success border border-light rounded-circle" style="width: 8px; height: 8px;"></span>
                </button>
            </div>

        </div>
    </div>



    <!-- Main Content -->
    <main class="main-content" style="padding-bottom: 100px; position: relative; z-index: 1;">
        <div class="container-fluid" style="padding-top: 0; margin-top: 0;">
            <!-- Dashboard Section -->
            <section id="dashboard-section" class="mb-5">
                <!-- System Header -->
                <div class="text-center mb-0">
                    <p class="lead mb-0 fw-bold">
                        Comprehensive dashboard for managing e-cooking appliance spare parts, tracking inventory levels, and monitoring stock availability across all brands and appliance types.
                    </p>
                    <hr style="margin-top: 0 !important; margin-bottom: 0px !important;">
                </div>

                <!-- Charts Section -->
                <div class="charts row g-4 mt-4">
                <div class="statistics">
                    <div class="row g-4 equal-height-row">
                        <!-- Total Parts Group -->
                        <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                            <div class="stat-group flex-fill">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="stat-card card p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="fas fa-cogs fa-3x text-primary"></i>
                                                </div>
                                                <div class="text-center flex-grow-1">
                                                    <div class="stat-number h2 text-primary mb-1" id="totalParts">{{ $statistics['total_parts'] }}</div>
                                                    <div class="stat-label h5">Total Parts</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-card card text-center p-2 h-100">
                                            <div class="stat-number h4 text-info">{{ $statistics['epc_parts'] }}</div>
                                            <div class="stat-label small">EPC Parts</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-card card text-center p-2 h-100">
                                            <div class="stat-number h4 text-warning">{{ $statistics['air_fryer_parts'] }}</div>
                                            <div class="stat-label small">Air Fryer Parts</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-card card text-center p-2 h-100">
                                            <div class="stat-number h4 text-danger">{{ $statistics['induction_parts'] }}</div>
                                            <div class="stat-label small">Induction Parts</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Available Parts Group -->
                        <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                            <div class="stat-group flex-fill">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="stat-card card p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="fas fa-check-circle fa-3x text-success"></i>
                                                </div>
                                                <div class="text-center flex-grow-1">
                                                    <div class="stat-number h2 text-success mb-1" id="availableParts">{{ $statistics['available_parts'] }}</div>
                                                    <div class="stat-label h5">Available Parts</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-card card text-center p-2 h-100">
                                            <div class="stat-number h4 text-info">{{ $statistics['available_epc_parts'] }}</div>
                                            <div class="stat-label small">Available EPC Parts</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-card card text-center p-2 h-100">
                                            <div class="stat-number h4 text-warning">{{ $statistics['available_air_fryer_parts'] }}</div>
                                            <div class="stat-label small">Available Air Fryer Parts</div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="stat-card card text-center p-2 h-100">
                                            <div class="stat-number h4 text-danger">{{ $statistics['available_induction_parts'] }}</div>
                                            <div class="stat-label small">Available Induction Parts</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Overview Group -->
                        <div class="col-lg-4 col-md-6 col-sm-12 d-flex">
                            <div class="stat-group flex-fill">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="stat-card card p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="fas fa-chart-pie fa-3x text-secondary"></i>
                                                </div>
                                                <div class="text-center flex-grow-1">
                                                    <div class="stat-number h2 text-secondary mb-1">{{ $overviewStats['stock_percentage'] }}%</div>
                                                    <div class="stat-label h5">Stock Level</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-card card text-center p-2 h-100">
                                            <div class="stat-number h4 text-info">{{ $overviewStats['total_brands'] }}</div>
                                            <div class="stat-label small">Brands</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-card card text-center p-2 h-100">
                                            <div class="stat-number h4 text-warning">{{ $overviewStats['total_appliances'] }}</div>
                                            <div class="stat-label small">Appliances</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-pie"></i> Parts by Appliance Type</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="applianceChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-bar"></i> Parts by Brand</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="brandChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-doughnut"></i> Availability Status</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="availabilityChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-line"></i> Availability by Appliance Type</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="distributionChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0"><i class="fas fa-chart-bar"></i> Availability by Brand</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="brandAvailabilityChart" width="400" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Inventory Section -->
            <section id="inventory">
                <h2 class="mb-4"><i class="fas fa-boxes"></i> Inventory</h2>

                <!-- Filters Section -->
                <div class="filters card p-4">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <label for="searchInput" class="form-label">Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search parts, brands, or appliances...">
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                            <label for="applianceFilter" class="form-label">Appliance</label>
                            <select class="form-select" id="applianceFilter">
                                <option value="">All Appliances</option>
                                @foreach($appliances as $appliance)
                                    <option value="{{ $appliance->name }}">{{ $appliance->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                            <label for="brandFilter" class="form-label">Brand</label>
                            <select class="form-select" id="brandFilter">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                            <label for="availabilityFilter" class="form-label">Availability</label>
                            <select class="form-select" id="availabilityFilter">
                                <option value="">All Availability</option>
                                <option value="true">Available</option>
                                <option value="false">Not Available</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                            <label for="viewFilter" class="form-label">View</label>
                            <select class="form-select" id="viewFilter">
                                <option value="grid">Grid View</option>
                                <option value="list">List View</option>
                            </select>
                        </div>
                    </div>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <div class="mt-3">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#partModal" onclick="resetPartForm(); document.getElementById('partModalLabel').textContent='Add New Part';">
                                    <i class="fas fa-plus me-2"></i>Add New Part
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Inventory Grid -->
                <div id="inventoryGrid" class="inventory-grid row g-4"></div>

                <!-- Inventory List -->
                <div id="inventoryList" class="inventory-list d-none">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Part Number</th>
                                    <th>Part Name</th>
                                    <th>Appliance Type</th>
                                    <th>Brands</th>
                                    <th>Availability</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="listTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Appliances Section -->
            <section id="appliances" style="display: none;">
                <div class="container-fluid py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h2"><i class="fas fa-tools me-2" style="color: #667eea;"></i>Appliances Overview</h1>
                    </div>

                    <!-- Appliances Statistics -->
                    <div class="row g-4 mb-4">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card p-4 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white rounded p-2 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; opacity: 0.9;">
                                        <i class="fas fa-layer-group fa-lg" style="color: #667eea;"></i>
                                    </div>
                                    <div>
                                        <p class="text-white-50 small mb-0">Total</p>
                                        <p class="h4 mb-0 fw-bold">{{ $appliances->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card p-4 h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4);">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white rounded p-2 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; opacity: 0.9;">
                                        <i class="fas fa-check-circle fa-lg" style="color: #11998e;"></i>
                                    </div>
                                    <div>
                                        <p class="text-white-50 small mb-0">Available</p>
                                        <p class="h4 mb-0 fw-bold">{{ $appliances->where('status', 'Available')->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card p-4 h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4);">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white rounded p-2 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; opacity: 0.9;">
                                        <i class="fas fa-play-circle fa-lg" style="color: #4facfe;"></i>
                                    </div>
                                    <div>
                                        <p class="text-white-50 small mb-0">In Use</p>
                                        <p class="h4 mb-0 fw-bold">{{ $appliances->where('status', 'In Use')->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card p-4 h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none; box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white rounded p-2 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; opacity: 0.9;">
                                        <i class="fas fa-wrench fa-lg" style="color: #f5576c;"></i>
                                    </div>
                                    <div>
                                        <p class="text-white-50 small mb-0">Maintenance</p>
                                        <p class="h4 mb-0 fw-bold">{{ $appliances->where('status', 'Maintenance')->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters Section -->
                    <div class="filters card p-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="applianceSearchInput" class="form-label">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="applianceSearchInput" placeholder="Search appliances by name, model, or brand...">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                <label for="applianceStatusFilter" class="form-label">Status</label>
                                <select class="form-select" id="applianceStatusFilter">
                                    <option value="">All Status</option>
                                    <option value="Available">Available</option>
                                    <option value="In Use">In Use</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                <label for="applianceBrandFilter" class="form-label">Brand</label>
                                <select class="form-select" id="applianceBrandFilter">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                <label for="applianceSortFilter" class="form-label">Sort</label>
                                <select class="form-select" id="applianceSortFilter">
                                    <option value="name">Name A-Z</option>
                                    <option value="name_desc">Name Z-A</option>
                                    <option value="newest">Newest First</option>
                                    <option value="oldest">Oldest First</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                <label for="applianceViewFilter" class="form-label">View</label>
                                <select class="form-select" id="applianceViewFilter">
                                    <option value="grid">Grid View</option>
                                    <option value="list">List View</option>
                                </select>
                            </div>
                        </div>
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <div class="mt-3">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applianceModal" onclick="resetApplianceForm(); document.getElementById('applianceModalLabel').textContent='Add New Appliance';">
                                        <i class="fas fa-plus me-2"></i>Add New Appliance
                                    </button>
                                </div>
                            @endif
                        @endauth
                    </div>

                    <!-- Appliances Grid -->
                    <div id="appliancesGrid" class="row g-4 mt-4">
                        <!-- Appliances will be loaded dynamically from database -->
                    </div>

                    <!-- Appliances List -->
                    <div id="appliancesList" class="table-responsive d-none mt-4">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Appliance</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Power</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="appliancesTableBody">
                                <!-- Appliances will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Trainers Section -->
            <section id="trainers" style="display: none;">
                <div class="container-fluid py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h2">Trainers Overview</h1>
                    </div>

                    <!-- Filters Section -->
                    <div class="filters card p-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <label for="trainerSearchInput" class="form-label">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="trainerSearchInput" placeholder="Search trainers...">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                <label for="trainerSpecialtyFilter" class="form-label">Specialty</label>
                                <select class="form-select" id="trainerSpecialtyFilter">
                                    <option value="">All Specialties</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                <label for="trainerSortFilter" class="form-label">Sort</label>
                                <select class="form-select" id="trainerSortFilter">
                                    <option value="name">Name A-Z</option>
                                    <option value="experience">Experience High-Low</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                <label for="trainerViewFilter" class="form-label">View</label>
                                <select class="form-select" id="trainerViewFilter">
                                    <option value="grid">Grid View</option>
                                    <option value="list">List View</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Trainers Grid -->
                    <div id="trainersGrid" class="row g-4 mt-4"></div>

                    <!-- Trainers List -->
                    <div id="trainersList" class="table-responsive d-none mt-4">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Specialty</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Experience</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="trainersTableBody"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Qualified Technicians Section -->
            <section id="qualified-technicians" style="display: none;">
                <div class="container-fluid py-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h2">Qualified Technicians Overview</h1>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4) !important;">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-25 rounded p-3 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user-cog fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-white-50 small mb-0">Total</p>
                                        <p class="h4 mb-0 fw-bold" id="technicianStatsTotal">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; box-shadow: 0 4px 15px rgba(17, 153, 142, 0.4) !important;">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-25 rounded p-3 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-check-circle fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-white-50 small mb-0">Available</p>
                                        <p class="h4 mb-0 fw-bold" id="technicianStatsAvailable">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; box-shadow: 0 4px 15px rgba(79, 172, 254, 0.4) !important;">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-25 rounded p-3 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-clock fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-white-50 small mb-0">Busy</p>
                                        <p class="h4 mb-0 fw-bold" id="technicianStatsBusy">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="card p-4 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4) !important;">
                                <div class="d-flex align-items-center">
                                    <div class="bg-white bg-opacity-25 rounded p-3 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-times-circle fa-lg text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-white-50 small mb-0">Unavailable</p>
                                        <p class="h4 mb-0 fw-bold" id="technicianStatsUnavailable">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">All Qualified Technicians</h3>
                        </div>

                        <!-- Filters Section -->
                        <div class="filters card p-4 border-top-0 rounded-0">
                            <div class="row g-3 align-items-end">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <label for="technicianSearchInput" class="form-label">Search</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" id="technicianSearchInput" placeholder="Search technicians by name, specialty, or phone...">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                    <label for="technicianSpecialtyFilter" class="form-label">Specialty</label>
                                    <select class="form-select" id="technicianSpecialtyFilter">
                                        <option value="">All Specialties</option>
                                        <option value="Refrigeration Systems">Refrigeration Systems</option>
                                        <option value="Electronics Repair">Electronics Repair</option>
                                        <option value="Washing Machine Repair">Washing Machine Repair</option>
                                        <option value="Kitchen Appliances">Kitchen Appliances</option>
                                        <option value="Audio-Visual Equipment">Audio-Visual Equipment</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                    <label for="technicianStatusFilter" class="form-label">Status</label>
                                    <select class="form-select" id="technicianStatusFilter">
                                        <option value="">All Status</option>
                                        <option value="Available">Available</option>
                                        <option value="Busy">Busy</option>
                                        <option value="Unavailable">Unavailable</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                    <label for="technicianSortFilter" class="form-label">Sort</label>
                                    <select class="form-select" id="technicianSortFilter">
                                        <option value="name">Name A-Z</option>
                                        <option value="experience">Experience High-Low</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                                    <label for="technicianViewFilter" class="form-label">View</label>
                                    <select class="form-select" id="technicianViewFilter">
                                        <option value="grid">Grid View</option>
                                        <option value="list">List View</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- Technicians Grid -->
                            <div id="techniciansGrid" class="row g-4">
                                <!-- Technicians will be loaded dynamically from database -->
                            </div>

                            <!-- Technicians List (Table View) -->
                            <div id="techniciansList" class="table-responsive d-none mt-4">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Name</th>
                                            <th>Specialty</th>
                                            <th>Phone</th>
                                            <th>License</th>
                                            <th>Location</th>
                                            <th>Experience</th>
                                            <th>Rate</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="techniciansTableBody">
                                        <!-- Technicians will be loaded dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Reports Section - Clean Print-Optimized Structure -->
            <section id="reports" style="display: none;">
                <div class="container-fluid py-4">
                    <!-- Screen View Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
                        <h1 class="h2"><i class="fas fa-chart-bar me-2"></i>System Reports & Analytics</h1>
                        <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print me-2"></i>Print Report</button>
                    </div>

                    <!-- Print Header -->
                    <div class="d-none d-print-block report-header">
                        <h1 class="report-title">E-COOKING INVENTORY MANAGEMENT SYSTEM</h1>
                        <h2 class="report-subtitle">COMPREHENSIVE SYSTEM REPORT</h2>
                        <p class="report-date">Generated: <span id="reportDate"></span></p>
                    </div>

                    <!-- Executive Summary -->
                    <h2 class="section-title">EXECUTIVE SUMMARY</h2>
                    <div class="row g-3 mb-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <div class="card-body d-flex align-items-center text-white">
                                    <div class="bg-white bg-opacity-25 rounded p-3 me-3"><i class="fas fa-cogs fa-2x"></i></div>
                                    <div><div class="h3 mb-0" id="reportTotalParts">0</div><small>Total Parts</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                <div class="card-body d-flex align-items-center text-white">
                                    <div class="bg-white bg-opacity-25 rounded p-3 me-3"><i class="fas fa-check-circle fa-2x"></i></div>
                                    <div><div class="h3 mb-0" id="reportAvailableParts">0</div><small>Available Parts</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <div class="card-body d-flex align-items-center text-white">
                                    <div class="bg-white bg-opacity-25 rounded p-3 me-3"><i class="fas fa-times-circle fa-2x"></i></div>
                                    <div><div class="h3 mb-0" id="reportUnavailableParts">0</div><small>Unavailable Parts</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <div class="card-body d-flex align-items-center text-white">
                                    <div class="bg-white bg-opacity-25 rounded p-3 me-3"><i class="fas fa-chart-pie fa-2x"></i></div>
                                    <div><div class="h3 mb-0" id="reportAvailabilityRate">0%</div><small>Availability Rate</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%);">
                                <div class="card-body d-flex align-items-center text-white">
                                    <div class="bg-white bg-opacity-25 rounded p-3 me-3"><i class="fas fa-tools fa-2x"></i></div>
                                    <div><div class="h3 mb-0" id="reportTotalAppliances">0</div><small>Total Appliances</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                                <div class="card-body d-flex align-items-center text-dark">
                                    <div class="bg-white bg-opacity-50 rounded p-3 me-3"><i class="fas fa-chalkboard-teacher fa-2x"></i></div>
                                    <div><div class="h3 mb-0" id="reportTotalTrainers">0</div><small>Total Trainers</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);">
                                <div class="card-body d-flex align-items-center text-white">
                                    <div class="bg-white bg-opacity-25 rounded p-3 me-3"><i class="fas fa-user-cog fa-2x"></i></div>
                                    <div><div class="h3 mb-0" id="reportTotalTechnicians">0</div><small>Total Technicians</small></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                <div class="card-body d-flex align-items-center text-white">
                                    <div class="bg-white bg-opacity-25 rounded p-3 me-3"><i class="fas fa-industry fa-2x"></i></div>
                                    <div><div class="h3 mb-0" id="reportTotalBrands">0</div><small>Total Brands</small></div>
                                </div>
                            </div>
                        </div>
                    </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-4 print-page-1">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Parts Distribution</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="reportPartsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100">
                        <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Availability</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="reportAvailabilityChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appliances & Brands Row -->
            <div class="row g-4 mb-4 print-page-1">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Appliances Status</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="reportAppliancesChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #ffa726 0%, #fb8c00 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-industry me-2"></i>Top Brands</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="reportBrandsChart" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Human Resources Row -->
            <div class="row g-4 mb-4 print-page-2">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Trainers Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center g-3">
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="text-success mb-1" id="reportTrainersActive">0</h3>
                                        <small class="text-muted">Active</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="text-warning mb-1" id="reportTrainersInactive">0</h3>
                                        <small class="text-muted">Inactive</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="text-info mb-1" id="reportTrainersLeave">0</h3>
                                        <small class="text-muted">On Leave</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" id="reportTrainersSpecialties"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i>Technicians Overview</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center g-3">
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="text-success mb-1" id="reportTechniciansAvailable">0</h3>
                                        <small class="text-muted">Available</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="text-warning mb-1" id="reportTechniciansBusy">0</h3>
                                        <small class="text-muted">Busy</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-3 bg-light rounded">
                                        <h3 class="text-danger mb-1" id="reportTechniciansUnavailable">0</h3>
                                        <small class="text-muted">Unavailable</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" id="reportTechniciansSpecialties"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Summary Card -->
            <div class="card print-page-3">
                <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>Detailed Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#partsTab">Parts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#appliancesTab">Appliances</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#trainersTab">Trainers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#techniciansTab">Technicians</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-3">
                        <div id="partsTab" class="tab-pane fade show active">
                            <h6 class="mb-3"><i class="fas fa-cogs text-primary me-2"></i>SPARE PARTS INVENTORY LISTING</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Part Number</th>
                                            <th>Name</th>
                                            <th>Appliance</th>
                                            <th>Brands</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportPartsTable"></tbody>
                                </table>
                            </div>
                        </div>
                        <div id="appliancesTab" class="tab-pane fade">
                            <h6 class="mb-3"><i class="fas fa-tools text-warning me-2"></i>APPLIANCES CATALOG & STATUS</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Name</th>
                                            <th>Brand</th>
                                            <th>Model</th>
                                            <th>Power</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportAppliancesTable"></tbody>
                                </table>
                            </div>
                        </div>
                        <div id="trainersTab" class="tab-pane fade">
                            <h6 class="mb-3"><i class="fas fa-chalkboard-teacher text-info me-2"></i>TRAINERS DIRECTORY & QUALIFICATIONS</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Name</th>
                                            <th>Specialty</th>
                                            <th>Experience</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportTrainersTable"></tbody>
                                </table>
                            </div>
                        </div>
                        <div id="techniciansTab" class="tab-pane fade">
                            <h6 class="mb-3"><i class="fas fa-user-cog text-success me-2"></i>QUALIFIED TECHNICIANS ROSTER</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Name</th>
                                            <th>Specialty</th>
                                            <th>Experience</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="reportTechniciansTable"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations Section -->
            <div class="card mt-4 print-page-4">
                <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>RECOMMENDATIONS</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="fas fa-exclamation"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Urgent Parts Restocking</h6>
                                    <p class="mb-0 text-muted">With only <strong id="recAvailabilityRate">0%</strong> parts availability, immediate procurement is required</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="fas fa-clipboard-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Inventory Review</h6>
                                    <p class="mb-0 text-muted">Conduct comprehensive audit of all <strong id="recUnavailableParts">0</strong> unavailable parts</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Supplier Engagement</h6>
                                    <p class="mb-0 text-muted">Contact <strong id="recBrand1">N/A</strong> and <strong id="recBrand2">N/A</strong> for bulk parts ordering</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="fas fa-users-cog"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Technician Allocation</h6>
                                    <p class="mb-0 text-muted"><strong id="recTechAvailability">0%</strong> technician availability is good, but cross-training recommended</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="fas fa-wrench"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Maintenance Schedule</h6>
                                    <p class="mb-0 text-muted">Regular maintenance for appliances to prevent downtime</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Training Program</h6>
                                    <p class="mb-0 text-muted">Utilize <strong id="recTotalTrainers">0</strong> trainers to upskill technicians on new equipment</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex align-items-start">
                                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; min-width: 40px;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Documentation Update</h6>
                                    <p class="mb-0 text-muted">Ensure all parts, appliances, and personnel records are current</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Footer -->
            <div class="card mt-4 border-0 d-print-block">
                <div class="card-body text-center py-4">
                    <hr class="mb-3">
                    <p class="text-muted mb-2"><strong>E-Cooking Inventory Management System</strong></p>
                    <p class="text-muted small mb-2">Centre for Research in Energy and Energy Conservation (CREEC)</p>
                    <p class="text-muted small mb-0">Report Generated: <span id="reportFooterDate"></span></p>
                    <p class="text-muted small">© 2026 CREEC. All Rights Reserved. | Confidential Document</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Settings Section -->
    <section id="settings" style="display: none;">
        <div class="container-fluid py-4">
            <h1 class="h2 mb-4"><i class="fas fa-cog me-2"></i>System Settings</h1>

            <div class="row g-4">
                <!-- General Settings -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>General Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">System Name</label>
                                <input type="text" class="form-control" value="E-Cooking Inventory Management System" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Organization</label>
                                <input type="text" class="form-control" value="CREEC" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Language</label>
                                <select class="form-select">
                                    <option selected>English</option>
                                    <option>Luganda</option>
                                    <option>Swahili</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Time Zone</label>
                                <select class="form-select">
                                    <option selected>East Africa Time (EAT)</option>
                                    <option>UTC</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Display Settings -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-desktop me-2"></i>Display Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Theme</label>
                                <select class="form-select" id="themeSelect">
                                    <option value="light" selected>Light</option>
                                    <option value="dark">Dark</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Items Per Page</label>
                                <select class="form-select">
                                    <option>10</option>
                                    <option selected>20</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="showImages" checked>
                                <label class="form-check-label" for="showImages">Show Part Images</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="compactView">
                                <label class="form-check-label" for="compactView">Compact View</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Notifications</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="lowStockAlert" checked>
                                <label class="form-check-label" for="lowStockAlert">Low Stock Alerts</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                                <label class="form-check-label" for="emailNotif">Email Notifications</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="smsNotif">
                                <label class="form-check-label" for="smsNotif">SMS Notifications</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="soundNotif" checked>
                                <label class="form-check-label" for="soundNotif">Sound Notifications</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Management -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-database me-2"></i>Data Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" onclick="exportData('csv')"><i class="fas fa-file-csv me-2"></i>Export to CSV</button>
                                <button class="btn btn-outline-success" onclick="exportData('excel')"><i class="fas fa-file-excel me-2"></i>Export to Excel</button>
                                <button class="btn btn-outline-info" onclick="exportData('pdf')"><i class="fas fa-file-pdf me-2"></i>Export to PDF</button>
                                <button class="btn btn-outline-warning" onclick="backupData()"><i class="fas fa-download me-2"></i>Backup Database</button>
                                <button class="btn btn-outline-danger" onclick="clearCache()"><i class="fas fa-trash me-2"></i>Clear Cache</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Information -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="text-muted mb-1">Version</p>
                                    <p class="fw-bold">1.0.0</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-muted mb-1">Last Updated</p>
                                    <p class="fw-bold" id="lastUpdated"></p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-muted mb-1">Database Size</p>
                                    <p class="fw-bold" id="dbSize">Calculating...</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="text-muted mb-1">Total Records</p>
                                    <p class="fw-bold" id="totalRecords">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="partModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 1rem 1.5rem;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-cogs fa-lg me-2"></i>
                        <h5 class="modal-title mb-0" id="modalPartName"></h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-3">
                        <!-- Image -->
                        <div class="col-md-4 text-center">
                            <div id="imagePlaceholder" class="border rounded p-3 bg-light" style="height: 150px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                <i class="fas fa-cogs fa-2x text-muted"></i>
                                <small class="text-muted mt-2">No image available</small>
                            </div>
                            <img id="modalPartImage" src="" alt="Part Image" class="img-fluid rounded d-none" style="max-height: 150px; object-fit: contain;">
                        </div>
                        <!-- Basic Info -->
                        <div class="col-md-8">
                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted"><i class="fas fa-barcode me-1"></i>Part ID</small>
                                    <div class="fw-bold" id="modalPartNumber"></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted"><i class="fas fa-tools me-1"></i>For Appliance</small>
                                    <div class="fw-bold" id="modalApplianceType"></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i>Part Location</small>
                                    <div class="fw-bold" id="modalLocation"></div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted"><i class="fas fa-check-circle me-1"></i>Stock Status</small>
                                    <div id="modalAvailability"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Brands -->
                    <div class="mt-3 p-2 bg-light rounded">
                        <small class="text-muted d-block mb-1"><i class="fas fa-industry me-1"></i>Works with Brands</small>
                        <div class="fw-bold" id="modalBrands"></div>
                    </div>

                    <!-- Description -->
                    <div class="mt-3">
                        <small class="text-muted d-block mb-1"><i class="fas fa-info-circle me-1"></i>What This Part Does</small>
                        <div id="modalDescription" class="border rounded p-2 bg-light" style="max-height: 100px; overflow-y: auto; font-size: 0.9rem;"></div>
                    </div>

                    <!-- Compatible Appliances & Comments -->
                    <div class="row g-2 mt-2">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1"><i class="fas fa-cogs me-1"></i>Compatible Models</small>
                            <div id="modalAppliances" class="small border rounded p-2 bg-light" style="min-height: 50px;"></div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1"><i class="fas fa-sticky-note me-1"></i>Special Notes</small>
                            <div id="modalComments" class="small border rounded p-2 bg-light fw-bold" style="min-height: 50px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 p-2">
                    <small class="text-muted me-auto"><i class="fas fa-info-circle me-1"></i>Part Information</small>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Trainer Modal -->
    <div class="modal fade" id="trainerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title" id="trainerModalLabel"><i class="fas fa-chalkboard-teacher me-2"></i>Add New Trainer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <form id="trainerForm">
                        <input type="hidden" id="trainerId">
                        <div class="row g-2">
                            <div class="col-md-4"><label class="form-label small">First Name *</label><input type="text" class="form-control form-control-sm" id="trainerFirstName" required></div>
                            <div class="col-md-4"><label class="form-label small">Middle Name</label><input type="text" class="form-control form-control-sm" id="trainerMiddleName"></div>
                            <div class="col-md-4"><label class="form-label small">Last Name *</label><input type="text" class="form-control form-control-sm" id="trainerLastName" required></div>
                            <div class="col-md-3"><label class="form-label small">Gender *</label><select class="form-select form-select-sm" id="trainerGender" required><option value="">Select</option><option value="Male">Male</option><option value="Female">Female</option></select></div>
                            <div class="col-md-3"><label class="form-label small">Date of Birth</label><input type="date" class="form-control form-control-sm" id="trainerDOB"></div>
                            <div class="col-md-3"><label class="form-label small">Nationality</label><input type="text" class="form-control form-control-sm" id="trainerNationality" value="Ugandan"></div>
                            <div class="col-md-3"><label class="form-label small">ID Number</label><input type="text" class="form-control form-control-sm" id="trainerIDNumber"></div>
                            <div class="col-12"><hr class="my-2"></div>
                            <div class="col-md-4"><label class="form-label small">Email *</label><input type="email" class="form-control form-control-sm" id="trainerEmail" required></div>
                            <div class="col-md-4"><label class="form-label small">Phone *</label><input type="tel" class="form-control form-control-sm" id="trainerPhone" required></div>
                            <div class="col-md-4"><label class="form-label small">WhatsApp</label><input type="tel" class="form-control form-control-sm" id="trainerWhatsapp"></div>
                            <div class="col-md-6"><label class="form-label small">Emergency Contact</label><input type="text" class="form-control form-control-sm" id="trainerEmergencyContact"></div>
                            <div class="col-md-6"><label class="form-label small">Emergency Phone</label><input type="tel" class="form-control form-control-sm" id="trainerEmergencyPhone"></div>
                            <div class="col-12"><hr class="my-2"></div>
                            <div class="col-md-3"><label class="form-label small">Country *</label><select class="form-select form-select-sm" id="trainerCountry" required><option value="Uganda" selected>Uganda</option><option value="Kenya">Kenya</option><option value="Tanzania">Tanzania</option></select></div>
                            <div class="col-md-3"><label class="form-label small">Region *</label><select class="form-select form-select-sm" id="trainerRegion" required><option value="">Select</option><option value="Central">Central</option><option value="Eastern">Eastern</option><option value="Northern">Northern</option><option value="Western">Western</option></select></div>
                            <div class="col-md-3"><label class="form-label small">District *</label><input type="text" class="form-control form-control-sm" id="trainerDistrict" required></div>
                            <div class="col-md-3"><label class="form-label small">Sub-County</label><input type="text" class="form-control form-control-sm" id="trainerSubCounty"></div>
                            <div class="col-md-6"><label class="form-label small">Village/Street</label><input type="text" class="form-control form-control-sm" id="trainerVillage"></div>
                            <div class="col-md-6"><label class="form-label small">Postal Code</label><input type="text" class="form-control form-control-sm" id="trainerPostalCode"></div>
                            <div class="col-12"><hr class="my-2"></div>
                            <div class="col-md-4"><label class="form-label small">Specialty *</label><select class="form-select form-select-sm" id="trainerSpecialty" required><option value="">Select</option><option value="EPC Training">EPC Training</option><option value="Air Fryer Training">Air Fryer Training</option><option value="Induction Cooker Training">Induction Cooker Training</option><option value="General Appliance Training">General Appliance Training</option></select></div>
                            <div class="col-md-4"><label class="form-label small">Experience (Years) *</label><input type="number" class="form-control form-control-sm" id="trainerExperience" min="0" required></div>
                            <div class="col-md-4"><label class="form-label small">License Number</label><input type="text" class="form-control form-control-sm" id="trainerLicenseNumber"></div>
                            <div class="col-md-4"><label class="form-label small">Hourly Rate (UGX)</label><input type="number" class="form-control form-control-sm" id="trainerHourlyRate" min="0"></div>
                            <div class="col-md-4"><label class="form-label small">Daily Rate (UGX)</label><input type="number" class="form-control form-control-sm" id="trainerDailyRate" min="0"></div>
                            <div class="col-md-4"><label class="form-label small">Status *</label><select class="form-select form-select-sm" id="trainerStatus" required><option value="Active">Active</option><option value="Inactive">Inactive</option><option value="On Leave">On Leave</option></select></div>
                            <div class="col-12"><hr class="my-2"></div>
                            <div class="col-md-6"><label class="form-label small">Skills</label><textarea class="form-control form-control-sm" id="trainerSkills" rows="2"></textarea></div>
                            <div class="col-md-6"><label class="form-label small">Qualifications</label><textarea class="form-control form-control-sm" id="trainerQualifications" rows="2"></textarea></div>
                            <div class="col-md-6"><label class="form-label small">Certifications</label><textarea class="form-control form-control-sm" id="trainerCertifications" rows="2"></textarea></div>
                            <div class="col-md-6"><label class="form-label small">Languages</label><textarea class="form-control form-control-sm" id="trainerLanguages" rows="2"></textarea></div>
                            <div class="col-12"><label class="form-label small">Notes</label><textarea class="form-control form-control-sm" id="trainerNotes" rows="2"></textarea></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('trainerForm').reset();"><i class="fas fa-undo me-1"></i>Reset</button>
                    <button type="submit" form="trainerForm" class="btn btn-sm btn-primary"><i class="fas fa-save me-1"></i>Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Trainer Details Modal -->
    <div class="modal fade" id="trainerDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="d-flex align-items-center">
                        <h5 class="modal-title mb-0"><i class="fas fa-user-graduate me-2"></i>Trainer Profile</h5>
                    </div>
                    <div class="d-flex align-items-center">
                        <span id="trainerDetailsStatus" class="badge bg-success me-2">Active</span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                </div>
                <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row g-2">
                        <div class="col-md-3 text-center">
                            <div id="trainerDetailsImage" class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold" style="width: 70px; height: 70px; font-size: 1.8rem;"></div>
                            <div class="mt-2"><small class="text-warning">⭐ <span id="trainerDetailsRating">5.0</span></small></div>
                        </div>
                        <div class="col-md-9">
                            <h5 id="trainerDetailsName" class="mb-1"></h5>
                            <p class="text-muted small mb-2"><i class="fas fa-chalkboard me-1"></i><span id="trainerDetailsSpecialty"></span></p>
                            <div class="row g-1 small">
                                <div class="col-4"><strong>Exp:</strong> <span id="trainerDetailsExperience"></span></div>
                                <div class="col-4"><strong>Rate:</strong> <span id="trainerDetailsRate"></span></div>
                                <div class="col-4"><strong>License:</strong> <span id="trainerDetailsLicense"></span></div>
                                <div class="col-12"><strong>Location:</strong> <span id="trainerDetailsLocation"></span></div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="card border" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                                <div class="card-body p-2">
                                    <h6 class="mb-2 small"><i class="fas fa-address-book me-1"></i>Contact</h6>
                                    <div class="small mb-1"><i class="fas fa-envelope me-1"></i><span id="trainerDetailsEmail"></span></div>
                                    <div class="small mb-1"><i class="fas fa-phone me-1"></i><span id="trainerDetailsPhone"></span></div>
                                    <div class="small mb-2"><i class="fab fa-whatsapp me-1"></i><span id="trainerDetailsWhatsapp"></span></div>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-light btn-sm py-0 px-2" onclick="sendEmailToTrainer()"><i class="fas fa-envelope"></i></button>
                                        <button class="btn btn-light btn-sm py-0 px-2" onclick="callTrainer()"><i class="fas fa-phone"></i></button>
                                        <button class="btn btn-light btn-sm py-0 px-2" onclick="whatsappTrainer()"><i class="fab fa-whatsapp"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                                <div class="card-body p-2">
                                    <h6 class="mb-2 small"><i class="fas fa-map-marker-alt me-1"></i>Address</h6>
                                    <div class="small mb-1"><strong>Country:</strong> <span id="trainerDetailsCountry"></span></div>
                                    <div class="small mb-1"><strong>Region:</strong> <span id="trainerDetailsRegionDistrict"></span></div>
                                    <div class="small"><strong>Address:</strong> <span id="trainerDetailsFullAddress"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body p-2">
                                    <h6 class="mb-2 small"><i class="fas fa-tools text-primary me-1"></i>Skills & Languages</h6>
                                    <div id="trainerDetailsSkills" class="small mb-2"></div>
                                    <div id="trainerDetailsLanguages" class="small"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body p-2">
                                    <h6 class="mb-2 small"><i class="fas fa-certificate text-warning me-1"></i>Certifications & Qualifications</h6>
                                    <div id="trainerDetailsCertifications" class="small mb-2"></div>
                                    <div id="trainerDetailsQualifications" class="small"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-2">
                        <div class="card-body p-2">
                            <h6 class="mb-2 small"><i class="fas fa-chart-line text-info me-1"></i>Statistics</h6>
                            <div class="row text-center g-1">
                                <div class="col-3"><div class="p-1 bg-light rounded"><div class="h6 text-primary mb-0" id="trainerDetailsTrainingsCompleted">0</div><small class="text-muted" style="font-size: 0.7rem;">Trainings</small></div></div>
                                <div class="col-3"><div class="p-1 bg-light rounded"><div class="h6 text-warning mb-0" id="trainerDetailsStudents">0</div><small class="text-muted" style="font-size: 0.7rem;">Students</small></div></div>
                                <div class="col-3"><div class="p-1 bg-light rounded"><div class="h6 text-info mb-0" id="trainerDetailsTrainings">0</div><small class="text-muted" style="font-size: 0.7rem;">Sessions</small></div></div>
                                <div class="col-3"><div class="p-1 bg-light rounded"><div class="h6 text-success mb-0"><span id="trainerDetailsId"></span></div><small class="text-muted" style="font-size: 0.7rem;">ID</small></div></div>
                            </div>
                            <div class="mt-2 pt-2 border-top small">
                                <div class="d-flex justify-content-between"><span class="text-muted">Joined:</span><strong id="trainerDetailsCreated"></strong></div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-2">
                        <div class="card-body p-2">
                            <h6 class="mb-1 small"><i class="fas fa-sticky-note text-secondary me-1"></i>Notes</h6>
                            <div id="trainerDetailsNotes" class="text-muted small"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <small class="text-muted me-auto"><i class="fas fa-shield-alt me-1"></i>Verified</small>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Technician Modal -->
    <div class="modal fade" id="technicianModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title" id="technicianModalLabel"><i class="fas fa-user-cog me-2"></i>Add/Edit Technician</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <form id="technicianForm">
                        <input type="hidden" id="technicianId">
                        <!-- Personal Information Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label for="technicianTitle" class="form-label">Title *</label>
                                        <select class="form-select" id="technicianTitle" name="title" required>
                                            <option value="">Select</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Ms.">Ms.</option>
                                            <option value="Dr.">Dr.</option>
                                            <option value="Eng.">Eng.</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianFirstName" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="technicianFirstName" name="first_name" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianMiddleName" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="technicianMiddleName" name="middle_name">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianLastName" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="technicianLastName" name="last_name" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianGender" class="form-label">Gender *</label>
                                        <select class="form-select" id="technicianGender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianDOB" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="technicianDOB" name="date_of_birth">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianNationality" class="form-label">Nationality</label>
                                        <input type="text" class="form-control" id="technicianNationality" name="nationality" value="Ugandan">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianIDNumber" class="form-label">ID Number</label>
                                        <input type="text" class="form-control" id="technicianIDNumber" name="id_number">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-address-book me-2"></i>Contact Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="technicianEmail" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="technicianEmail" name="email" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianPhone1" class="form-label">Phone Number 1 *</label>
                                        <input type="tel" class="form-control" id="technicianPhone1" name="phone_1" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianPhone2" class="form-label">Phone Number 2</label>
                                        <input type="tel" class="form-control" id="technicianPhone2" name="phone_2">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianWhatsapp" class="form-label">WhatsApp Number</label>
                                        <input type="tel" class="form-control" id="technicianWhatsapp" name="whatsapp">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianEmergencyContact" class="form-label">Emergency Contact</label>
                                        <input type="text" class="form-control" id="technicianEmergencyContact" name="emergency_contact">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianEmergencyPhone" class="form-label">Emergency Phone</label>
                                        <input type="tel" class="form-control" id="technicianEmergencyPhone" name="emergency_phone">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Address & Location Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Address & Location</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="technicianCountry" class="form-label">Country *</label>
                                        <select class="form-select" id="technicianCountry" name="country" required>
                                            <option value="">Select Country</option>
                                            <option value="Uganda" selected>Uganda</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="South Sudan">South Sudan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianRegion" class="form-label">Region/Province *</label>
                                        <select class="form-select" id="technicianRegion" name="region" required>
                                            <option value="">Select Region</option>
                                            <option value="Central">Central</option>
                                            <option value="Eastern">Eastern</option>
                                            <option value="Northern">Northern</option>
                                            <option value="Western">Western</option>
                                            <option value="Kampala">Kampala</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianDistrict" class="form-label">District *</label>
                                        <input type="text" class="form-control" id="technicianDistrict" name="district" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianSubCounty" class="form-label">Sub-County</label>
                                        <input type="text" class="form-control" id="technicianSubCounty" name="sub_county">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianParish" class="form-label">Parish</label>
                                        <input type="text" class="form-control" id="technicianParish" name="parish">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianVillage" class="form-label">Village/Street</label>
                                        <input type="text" class="form-control" id="technicianVillage" name="village">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianPostalCode" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" id="technicianPostalCode" name="postal_code">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-tools me-2"></i>Professional Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="technicianSpecialty" class="form-label">Specialty *</label>
                                        <select class="form-select" id="technicianSpecialty" name="specialty" required>
                                            <option value="">Select Specialty</option>
                                            <option value="Refrigeration Systems">Refrigeration Systems</option>
                                            <option value="Electronics Repair">Electronics Repair</option>
                                            <option value="Washing Machine Repair">Washing Machine Repair</option>
                                            <option value="Kitchen Appliances">Kitchen Appliances</option>
                                            <option value="Audio-Visual Equipment">Audio-Visual Equipment</option>
                                            <option value="Air Conditioning">Air Conditioning</option>
                                            <option value="Microwave & Oven Repair">Microwave & Oven Repair</option>
                                            <option value="Dishwasher Repair">Dishwasher Repair</option>
                                            <option value="Small Appliances">Small Appliances</option>
                                            <option value="Industrial Equipment">Industrial Equipment</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianSubSpecialty" class="form-label">Sub-Specialty</label>
                                        <input type="text" class="form-control" id="technicianSubSpecialty" name="sub_specialty" placeholder="e.g., Compressor Repair, PCB Diagnostics">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianLicenseNumber" class="form-label">License Number *</label>
                                        <input type="text" class="form-control" id="technicianLicenseNumber" name="license_number" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianLicenseExpiry" class="form-label">License Expiry Date</label>
                                        <input type="date" class="form-control" id="technicianLicenseExpiry" name="license_expiry">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianExperience" class="form-label">Experience (Years) *</label>
                                        <input type="number" class="form-control" id="technicianExperience" name="experience" min="0" max="50" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianHourlyRate" class="form-label">Hourly Rate (UGX)</label>
                                        <input type="number" class="form-control" id="technicianHourlyRate" name="hourly_rate" min="0">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="technicianDailyRate" class="form-label">Daily Rate (UGX)</label>
                                        <input type="number" class="form-control" id="technicianDailyRate" name="daily_rate" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianStatus" class="form-label">Status *</label>
                                        <select class="form-select" id="technicianStatus" name="status" required>
                                            <option value="Available">Available</option>
                                            <option value="Busy">Busy</option>
                                            <option value="Unavailable">Unavailable</option>
                                            <option value="On Leave">On Leave</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianEmploymentType" class="form-label">Employment Type *</label>
                                        <select class="form-select" id="technicianEmploymentType" name="employment_type" required>
                                            <option value="">Select Type</option>
                                            <option value="Full-Time">Full-Time</option>
                                            <option value="Part-Time">Part-Time</option>
                                            <option value="Contract">Contract</option>
                                            <option value="Freelance">Freelance</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianStartDate" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="technicianStartDate" name="start_date">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Skills & Certifications Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-certificate me-2"></i>Skills & Certifications</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="technicianSkills" class="form-label">Skills (comma separated)</label>
                                        <textarea class="form-control" id="technicianSkills" name="skills" rows="3" placeholder="e.g., AC Repair, Refrigerator Maintenance, Cold Room Installation"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="technicianCertifications" class="form-label">Certifications (comma separated)</label>
                                        <textarea class="form-control" id="technicianCertifications" name="certifications" rows="3" placeholder="e.g., Certified HVAC Technician, Refrigeration License Class A"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="technicianTraining" class="form-label">Training Completed</label>
                                        <textarea class="form-control" id="technicianTraining" name="training" rows="3" placeholder="e.g., Advanced Electronics, Safety Compliance"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="technicianLanguages" class="form-label">Languages Spoken</label>
                                        <textarea class="form-control" id="technicianLanguages" name="languages" rows="3" placeholder="e.g., English, Luganda, Swahili"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Equipment & Tools Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0"><i class="fas fa-wrench me-2"></i>Equipment & Tools</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="technicianOwnTools" class="form-label">Own Tools?</label>
                                        <select class="form-select" id="technicianOwnTools" name="own_tools">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                            <option value="Partial">Partial</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianVehicle" class="form-label">Has Vehicle?</label>
                                        <select class="form-select" id="technicianVehicle" name="has_vehicle">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianVehicleType" class="form-label">Vehicle Type</label>
                                        <select class="form-select" id="technicianVehicleType" name="vehicle_type">
                                            <option value="">Select Type</option>
                                            <option value="Motorcycle">Motorcycle</option>
                                            <option value="Car">Car</option>
                                            <option value="Van">Van</option>
                                            <option value="Truck">Truck</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="technicianEquipmentList" class="form-label">Equipment List</label>
                                        <textarea class="form-control" id="technicianEquipmentList" name="equipment_list" rows="2" placeholder="List major equipment/tools owned"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="technicianServiceAreas" class="form-label">Service Areas</label>
                                        <textarea class="form-control" id="technicianServiceAreas" name="service_areas" rows="2" placeholder="Areas where technician can provide service"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work History & References Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-dark text-white">
                                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Work History & References</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="technicianPreviousEmployer" class="form-label">Previous Employer</label>
                                        <input type="text" class="form-control" id="technicianPreviousEmployer" name="previous_employer">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianPreviousPosition" class="form-label">Previous Position</label>
                                        <input type="text" class="form-control" id="technicianPreviousPosition" name="previous_position">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="technicianYearsAtPrevious" class="form-label">Years at Previous</label>
                                        <input type="number" class="form-control" id="technicianYearsAtPrevious" name="years_at_previous" min="0" step="0.5">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="technicianReferenceName" class="form-label">Reference Name</label>
                                        <input type="text" class="form-control" id="technicianReferenceName" name="reference_name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="technicianReferencePhone" class="form-label">Reference Phone</label>
                                        <input type="tel" class="form-control" id="technicianReferencePhone" name="reference_phone">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-light text-dark">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Additional Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="technicianNotes" class="form-label">Notes</label>
                                        <textarea class="form-control" id="technicianNotes" name="notes" rows="3" placeholder="Additional notes about the technician..."></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="technicianMedicalConditions" class="form-label">Medical Conditions (if any)</label>
                                        <textarea class="form-control" id="technicianMedicalConditions" name="medical_conditions" rows="3" placeholder="Any relevant medical conditions..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Agreement Section -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="technicianTerms" name="terms" required>
                                    <label class="form-check-label" for="technicianTerms">
                                        I confirm that all information provided is accurate and complete. I agree to the terms and conditions of service.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                    <button type="button" class="btn btn-outline-primary" onclick="technicianForm.reset(); document.getElementById('technicianId').value = '';"><i class="fas fa-undo me-1"></i>Reset</button>
                    <button type="submit" form="technicianForm" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Technician</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Part Modal -->
    <div class="modal fade" id="partModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title" id="partModalLabel"><i class="fas fa-cogs me-2"></i>Add/Edit Part</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <form id="partForm">
                        <input type="hidden" id="partId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="partNumber" class="form-label">Part Number *</label>
                                <input type="text" class="form-control" id="partNumber" name="part_number" required>
                            </div>
                            <div class="col-md-6">
                                <label for="partName" class="form-label">Part Name *</label>
                                <input type="text" class="form-control" id="partName" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="partAppliance" class="form-label">Appliance Type *</label>
                                <select class="form-select" id="partAppliance" name="appliance_id" required>
                                    <option value="">Select Appliance</option>
                                    @foreach($appliances as $appliance)
                                        <option value="{{ $appliance->id }}">{{ $appliance->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="partLocation" class="form-label">Location</label>
                                <input type="text" class="form-control" id="partLocation" name="location">
                            </div>
                            <div class="col-md-6">
                                <label for="partPrice" class="form-label">Price (UGX)</label>
                                <input type="number" class="form-control" id="partPrice" name="price" min="0" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label for="partAvailability" class="form-label">Availability</label>
                                <select class="form-select" id="partAvailability" name="availability">
                                    <option value="1">Available</option>
                                    <option value="0">Not Available</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="partDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="partDescription" name="description" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label for="partComments" class="form-label">Comments</label>
                                <textarea class="form-control" id="partComments" name="comments" rows="2"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Compatible Brands</label>
                                <div id="partBrandsContainer">
                                    @foreach($brands as $brand)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="brands[]" value="{{ $brand->id }}" id="brand{{ $brand->id }}">
                                            <label class="form-check-label" for="brand{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Specific Appliances</label>
                                <div id="partSpecificAppliancesContainer">
                                    <!-- Specific appliances will be loaded dynamically -->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                    <button type="button" class="btn btn-outline-primary" onclick="resetPartForm();"><i class="fas fa-undo me-1"></i>Reset</button>
                    <button type="submit" form="partForm" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Part</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Appliance Modal -->
    <div class="modal fade" id="applianceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="modal-title" id="applianceModalLabel"><i class="fas fa-tools me-2"></i>Add/Edit Appliance</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <form id="applianceForm">
                        <input type="hidden" id="applianceId">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="applianceName" class="form-label">Appliance Name *</label>
                                <input type="text" class="form-control" id="applianceName" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="applianceBrand" class="form-label">Brand</label>
                                <select class="form-select" id="applianceBrand" name="brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="applianceModel" class="form-label">Model</label>
                                <input type="text" class="form-control" id="applianceModel" name="model">
                            </div>
                            <div class="col-md-4">
                                <label for="appliancePower" class="form-label">Power</label>
                                <input type="text" class="form-control" id="appliancePower" name="power">
                            </div>
                            <div class="col-md-4">
                                <label for="applianceSku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="applianceSku" name="sku">
                            </div>
                            <div class="col-md-6">
                                <label for="applianceStatus" class="form-label">Status *</label>
                                <select class="form-select" id="applianceStatus" name="status" required>
                                    <option value="Available">Available</option>
                                    <option value="In Use">In Use</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="appliancePrice" class="form-label">Price (UGX)</label>
                                <input type="number" class="form-control" id="appliancePrice" name="price" min="0" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label for="applianceColor" class="form-label">Color</label>
                                <input type="text" class="form-control" id="applianceColor" name="color" placeholder="e.g., bg-primary">
                            </div>
                            <div class="col-md-6">
                                <label for="applianceIcon" class="form-label">Icon</label>
                                <input type="text" class="form-control" id="applianceIcon" name="icon" placeholder="e.g., fas fa-tools">
                            </div>
                            <div class="col-12">
                                <label for="applianceDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="applianceDescription" name="description" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Cancel</button>
                    <button type="button" class="btn btn-outline-primary" onclick="resetApplianceForm();"><i class="fas fa-undo me-1"></i>Reset</button>
                    <button type="submit" form="applianceForm" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Appliance</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Technician Details Modal -->
    <div class="modal fade" id="technicianViewModal" tabindex="-1" aria-labelledby="technicianViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <!-- Header with gradient -->
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-cog fa-2x me-3"></i>
                        <div>
                            <h4 class="modal-title mb-0" id="technicianViewModalLabel">
                                <i class="fas fa-id-card me-2"></i>Technician Profile
                            </h4>
                            <small class="text-white-50">Complete Professional Information</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span id="technicianViewStatus" class="badge bg-success me-3 fs-6 px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i>Available
                        </span>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body p-0">
                    <!-- Profile Header Section -->
                    <div class="bg-light border-bottom">
                        <div class="row g-0">
                            <!-- Photo Section -->
                            <div class="col-md-3 p-4 d-flex flex-column align-items-center justify-content-center" style="min-height: 200px;">
                                <div id="technicianViewPhoto" class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-lg" style="width: 140px; height: 140px; font-size: 3rem;">
                                    <!-- Initials will be inserted here -->
                                </div>
                                <div class="mt-3 text-center">
                                    <span id="technicianViewVerified" class="badge bg-success fs-6 px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                </div>
                                <div class="mt-2 text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <span class="ms-1">5.0 Rating</span>
                                    </small>
                                </div>
                            </div>
                            <!-- Name & Basic Info -->
                            <div class="col-md-5 p-4 d-flex flex-column justify-content-center" style="min-height: 200px;">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <h3 id="technicianViewName" class="mb-0 text-dark"></h3>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-tools text-success me-2"></i>
                                    <h5 id="technicianViewSpecialty" class="mb-0 text-muted fw-normal"></h5>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-certificate text-warning me-2"></i>
                                    <span id="technicianViewLicense" class="badge bg-primary"></span>
                                </div>
                                <div class="d-flex align-items-center mt-3">
                                    <div class="me-4">
                                        <small class="text-muted d-block">Experience</small>
                                        <strong id="technicianViewExperience" class="text-dark fs-5"></strong>
                                    </div>
                                    <div class="me-4">
                                        <small class="text-muted d-block">Rate</small>
                                        <strong id="technicianViewRate" class="text-dark fs-5"></strong>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Employment</small>
                                        <strong id="technicianViewEmployment" class="text-dark fs-5"></strong>
                                    </div>
                                </div>
                            </div>
                            <!-- Location Card -->
                            <div class="col-md-4 p-4" style="min-height: 200px;">
                                <a id="technicianViewMapCard" href="#" target="_blank" class="text-decoration-none h-100 d-block">
                                    <div class="card border-0 h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                        <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                                            <i class="fas fa-map-marked-alt fa-2x mb-2"></i>
                                            <small class="text-white-50">Location</small>
                                            <strong id="technicianViewLocation" class="fs-5 text-center"></strong>
                                            <small class="text-white-50 mt-1"><i class="fas fa-external-link-alt me-1"></i>View on Map</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="p-4">
                        <div class="row g-4">
                            <!-- Contact Information -->
                            <div class="col-lg-6">
                                <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-address-book fa-2x me-3"></i>
                                            <h5 class="card-title mb-0">Contact Information</h5>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-envelope me-3"></i>
                                                <div>
                                                    <small class="text-white-50">Email Address</small>
                                                    <div id="technicianViewEmail" class="fw-bold"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-phone me-3"></i>
                                                <div>
                                                    <small class="text-white-50">Phone Number 1</small>
                                                    <div id="technicianViewPhone1" class="fw-bold"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fab fa-whatsapp me-3"></i>
                                                <div>
                                                    <small class="text-white-50">WhatsApp</small>
                                                    <div id="technicianViewWhatsapp" class="fw-bold"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2 mt-4">
                                            <button class="btn btn-light btn-sm" onclick="sendEmailToTechnician()">
                                                <i class="fas fa-envelope me-1"></i>Send Email
                                            </button>
                                            <button class="btn btn-light btn-sm" onclick="callTechnician()">
                                                <i class="fas fa-phone me-1"></i>Call Now
                                            </button>
                                            <button class="btn btn-light btn-sm" onclick="whatsappTechnician()">
                                                <i class="fab fa-whatsapp me-1"></i>WhatsApp
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Details -->
                            <div class="col-lg-6">
                                <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-map-marker-alt fa-2x me-3"></i>
                                            <h5 class="card-title mb-0">Address Details</h5>
                                        </div>
                                        <div class="mb-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <i class="fas fa-globe me-3 mt-1"></i>
                                                <div>
                                                    <small class="text-white-50">Country</small>
                                                    <div id="technicianViewCountry" class="fw-bold"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <i class="fas fa-map me-3 mt-1"></i>
                                                <div>
                                                    <small class="text-white-50">Region & District</small>
                                                    <div id="technicianViewRegionDistrict" class="fw-bold"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="d-flex align-items-start mb-2">
                                                <i class="fas fa-location-arrow me-3 mt-1"></i>
                                                <div>
                                                    <small class="text-white-50">Full Address</small>
                                                    <div id="technicianViewFullAddress" class="fw-bold"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Skills & Certifications -->
                        <div class="row g-4 mt-2">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-tools text-primary fa-lg me-3"></i>
                                            <h6 class="card-title mb-0">Skills</h6>
                                        </div>
                                        <div id="technicianViewSkills" class="mb-3"></div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-language text-info fa-lg me-3"></i>
                                            <h6 class="card-title mb-0">Languages</h6>
                                        </div>
                                        <div id="technicianViewLanguages"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-certificate text-warning fa-lg me-3"></i>
                                            <h6 class="card-title mb-0">Certifications</h6>
                                        </div>
                                        <div id="technicianViewCertifications" class="mb-3"></div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-graduation-cap text-success fa-lg me-3"></i>
                                            <h6 class="card-title mb-0">Training</h6>
                                        </div>
                                        <div id="technicianViewTraining"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Equipment & Service Info -->
                        <div class="row g-4 mt-2">
                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-wrench text-secondary fa-lg me-3"></i>
                                            <h6 class="card-title mb-0">Equipment & Tools</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-tools me-2 text-primary"></i>
                                                    <span>Own Tools:</span>
                                                    <strong id="technicianViewOwnTools" class="ms-2"></strong>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-car me-2 text-primary"></i>
                                                    <span>Vehicle:</span>
                                                    <strong id="technicianViewVehicle" class="ms-2"></strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted d-block mb-2">Equipment List</small>
                                            <div id="technicianViewEquipmentList" class="text-dark"></div>
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted d-block mb-2">Service Areas</small>
                                            <div id="technicianViewServiceAreas" class="text-dark"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-chart-line text-info fa-lg me-3"></i>
                                            <h6 class="card-title mb-0">Work Statistics</h6>
                                        </div>
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="p-3 bg-light rounded">
                                                    <div class="h4 text-primary mb-1" id="technicianViewJobsCompleted">0</div>
                                                    <small class="text-muted">Jobs Completed</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-3 bg-light rounded">
                                                    <div class="h4 text-success mb-1" id="technicianViewRating">5.0</div>
                                                    <small class="text-muted">Rating</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="p-3 bg-light rounded">
                                                    <div class="h4 text-warning mb-1" id="technicianViewResponseTime">2hrs</div>
                                                    <small class="text-muted">Avg. Response</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3 pt-3 border-top">
                                            <div class="d-flex justify-content-between mb-2">
                                                <small class="text-muted"><i class="fas fa-calendar me-1"></i>Joined:</small>
                                                <strong id="technicianViewJoined"></strong>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <small class="text-muted"><i class="fas fa-clock me-1"></i>Last Active:</small>
                                                <strong id="technicianViewLastActive"></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="row g-4 mt-2">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-sticky-note text-secondary fa-lg me-3"></i>
                                            <h6 class="card-title mb-0">Notes</h6>
                                        </div>
                                        <div id="technicianViewNotes" class="text-muted"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-0">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <small class="text-muted"><i class="fas fa-shield-alt me-1"></i>Profile verified and up to date</small>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- View Appliance Details Modal -->
    <div class="modal fade" id="applianceViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem;">
                    <h5 class="modal-title"><i class="fas fa-tools me-2"></i>Appliance Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light text-center" style="height: 200px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                <div id="applianceViewIcon" class="bg-primary rounded d-inline-flex align-items-center justify-content-center text-white" style="width: 80px; height: 80px; font-size: 2rem;">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <small id="applianceViewImageName" class="text-muted mt-2"></small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 id="applianceViewName" class="mb-2"></h4>
                            <p class="text-muted mb-2"><i class="fas fa-tag me-1"></i><span id="applianceViewBrand"></span></p>
                            <span id="applianceViewPrice" class="badge bg-primary mb-3"><span class="price-text">UGX 0</span></span>
                            <div class="row g-2 small">
                                <div class="col-6"><strong>Model:</strong> <span id="applianceViewModel"></span></div>
                                <div class="col-6"><strong>Power:</strong> <span id="applianceViewPower"></span></div>
                                <div class="col-6"><strong>SKU:</strong> <span id="applianceViewSku"></span></div>
                                <div class="col-6"><strong>Status:</strong> <span id="applianceViewStatus" class="badge bg-success"><i class="fas fa-check-circle me-1"></i><span class="status-text">Available</span></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body p-3">
                            <h6 class="mb-2"><i class="fas fa-align-left text-info me-2"></i>Description</h6>
                            <p id="applianceViewDescription" class="text-muted mb-0 small"></p>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-6">
                            <div class="card text-center">
                                <div class="card-body p-3">
                                    <div class="h4 text-primary mb-0" id="applianceViewPartsCount">0</div>
                                    <small class="text-muted">Available number in stock</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="small">
                                        <div><strong>ID:</strong> <span id="applianceViewId"></span></div>
                                        <div><strong>Created:</strong> <span id="applianceViewCreated"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>Close</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                    <h5 class="modal-title"><i class="fas fa-user-lock me-2"></i>User Login</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="loginForm">
                        <div class="text-center mb-4">
                            <img src="{{ asset('pictures/creec-logo.png') }}" alt="CREEC" height="60">
                        </div>
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                            <input type="email" class="form-control" id="loginEmail" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                            <input type="password" class="form-control" id="loginPassword" placeholder="Enter your password" required>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-sign-in-alt me-2"></i>Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="#" class="text-muted small">Forgot password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <div class="d-flex justify-content-center align-items-start gap-2 gap-md-3" style="flex-wrap: nowrap; overflow-x: auto;">
                <a href="https://creec.or.ug" target="_blank" class="flex-shrink-0"><img src="{{ asset('pictures/creec-logo.png') }}" alt="CREEC" height="40"></a>
                <span class="d-none d-lg-inline flex-shrink-0">&copy; <span id="currentDate"></span> CREEC | E-Cooking Inventory Management System</span>
                <span class="d-lg-none flex-shrink-0 small">&copy; <span id="currentDate"></span></span>
                <a href="https://creec.or.ug" target="_blank" class="text-white flex-shrink-0"><i class="fas fa-globe fa-lg"></i></a>
                <a href="https://facebook.com/creec" target="_blank" class="text-white flex-shrink-0"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="https://twitter.com/creec" target="_blank" class="text-white flex-shrink-0"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="https://instagram.com/creec" target="_blank" class="text-white flex-shrink-0"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="https://linkedin.com/company/creec" target="_blank" class="text-white flex-shrink-0"><i class="fab fa-linkedin fa-lg"></i></a>
                <a href="https://youtube.com/creec" target="_blank" class="text-white flex-shrink-0"><i class="fab fa-youtube fa-lg"></i></a>
                <a href="https://wa.me/256700000000" target="_blank" class="text-white flex-shrink-0"><i class="fab fa-whatsapp fa-lg"></i></a>
                <a href="mailto:info@creec.or.ug" class="text-white flex-shrink-0"><i class="fas fa-envelope fa-lg"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const chartData = @json($chartData);
    </script>
    <script src="{{ asset('assets/navbar.js') }}"></script>
    <script src="{{ asset('assets/script.js') }}"></script>

    <!-- Admin Forms JavaScript -->
    <script>
        // Part Form Functions
        function resetPartForm() {
            document.getElementById('partForm').reset();
            document.getElementById('partId').value = '';
            document.getElementById('partModalLabel').textContent = 'Add New Part';
            // Uncheck all brand checkboxes
            document.querySelectorAll('input[name="brands[]"]').forEach(cb => cb.checked = false);
        }

        document.getElementById('partForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const partId = document.getElementById('partId').value;

            // Handle brands array
            const selectedBrands = [];
            document.querySelectorAll('input[name="brands[]"]:checked').forEach(cb => {
                selectedBrands.push(cb.value);
            });
            formData.set('brands', JSON.stringify(selectedBrands));

            // Handle specific appliances array (if implemented)
            const selectedSpecificAppliances = [];
            // Add logic for specific appliances if needed
            formData.set('specific_appliances', JSON.stringify(selectedSpecificAppliances));

            const url = partId ? `/api/parts/${partId}` : '/api/parts';
            const method = partId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.message || data.id) {
                    alert('Part saved successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('partModal')).hide();
                    location.reload(); // Refresh to show updated data
                } else {
                    alert('Error saving part');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving part');
            });
        });

        // Appliance Form Functions
        function resetApplianceForm() {
            document.getElementById('applianceForm').reset();
            document.getElementById('applianceId').value = '';
            document.getElementById('applianceModalLabel').textContent = 'Add New Appliance';
        }

        document.getElementById('applianceForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const applianceId = document.getElementById('applianceId').value;

            const url = applianceId ? `/api/appliances/${applianceId}` : '/api/appliances';
            const method = applianceId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(Object.fromEntries(formData))
            })
            .then(response => response.json())
            .then(data => {
                if (data.message || data.id) {
                    alert('Appliance saved successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('applianceModal')).hide();
                    location.reload(); // Refresh to show updated data
                } else {
                    alert('Error saving appliance');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error saving appliance');
            });
        });
    </script>

    @include('chat_modal')

</body>
</html>

