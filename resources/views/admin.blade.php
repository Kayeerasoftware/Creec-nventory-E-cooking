<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - E-Cooking Inventory</title>
    <link rel="icon" type="image/png" href="{{ asset('pictures/creec-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .offcanvas-body {
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
            border-right: 1px solid #e2e8f0;
        }

        .sidebar-header {
            background: var(--primary-gradient);
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
        }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #64748b;
            margin-bottom: 0.5rem;
            padding: 0.25rem 0.75rem;
            white-space: nowrap;
        }

        .nav-link {
            color: #475569;
            padding: 0.6rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 0.2rem;
            font-weight: 500;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #1e293b;
            transform: translateX(8px) scale(1.02);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            transform: translateX(4px);
        }

        .nav-link.active:hover {
            transform: translateX(4px) scale(1.02);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.5);
        }

        .nav-link i {
            width: 16px;
            text-align: center;
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }

        .sidebar-footer .nav-link {
            border: 1px solid #e2e8f0;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
        }

        .sidebar-footer .nav-link.text-success:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-color: #10b981;
        }

        .sidebar-footer .nav-link.text-danger:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-color: #ef4444;
        }

        .admin-badge {
            background: var(--success-gradient);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(79, 172, 254, 0.3);
        }

        @media (max-width: 991.98px) {
            .nav-link:hover {
                transform: none;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-dark fixed-top" style="background: linear-gradient(90deg, #140168 0%, #5039d6 100%); z-index: 9999; padding: 0.2rem 0.3rem; min-height: 45px;">
        <div class="container-fluid d-flex align-items-center justify-content-between" style="gap: 0.2rem; flex-wrap: nowrap;">
            <div class="d-flex align-items-center" style="gap: 0.2rem; flex-shrink: 0;">
                <button class="btn btn-outline-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" style="padding: 0.2rem 0.4rem; font-size: 0.9rem;">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="https://creec.or.ug" target="_blank" style="padding: 0;"><img src="{{ asset('pictures/creec-logo.png') }}" alt="Logo" style="height: 30px;"></a>
                <button class="btn btn-link text-warning d-none d-md-block" type="button" data-bs-toggle="modal" data-bs-target="#chatModal" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;"><i class="fas fa-headset me-1"></i>Support</button>
                <button class="btn btn-link text-warning d-md-none" type="button" data-bs-toggle="modal" data-bs-target="#chatModal" style="padding: 0.2rem 0.4rem; font-size: 0.9rem;"><i class="fas fa-headset"></i></button>
            </div>
            <div class="d-none d-md-block" style="flex-grow: 1; text-align: center;">
                <span class="text-white" style="font-size: 0.9rem;"><i class="fas fa-tools"></i> Admin Dashboard</span>
            </div>
            <div class="d-flex align-items-center" style="gap: 0.2rem; flex-shrink: 0; flex-wrap: nowrap;">
                <div class="text-white text-center" style="font-size: 0.65em; padding: 2px 4px; background: rgba(255,255,255,0.1); border-radius: 3px;">
                    <span id="adminTimeVal"></span> <span id="adminDate"></span>
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
                            <li><a class="dropdown-item" href="/admin/profile"><i class="fas fa-user-edit me-2"></i>My Profile</a></li>
                            @if(auth()->user()->role === 'admin')
                                <li><a class="dropdown-item" href="/admin"><i class="fas fa-cog me-2"></i>Admin Dashboard</a></li>
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
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel" data-bs-scroll="true" data-bs-backdrop="false">
        <div class="offcanvas-header d-lg-none border-bottom">
            <h5 class="offcanvas-title fw-bold" id="sidebarLabel">
                <i class="fas fa-shield-alt text-primary me-2"></i>Admin
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-lg-block d-flex flex-column" style="padding: 1rem; padding-top: 50px;">
            <!-- Header -->
            <div class="text-center mb-3">
                <div class="sidebar-header p-3 mb-2">
                    <div class="bg-white rounded-circle p-2 d-inline-flex mb-2">
                        <img src="{{ asset('pictures/CREEC-logo.png') }}" alt="Admin" style="width: 24px; height: 24px; object-fit: contain;">
                    </div>
                    <h6 class="text-white fw-bold mb-1">Admin Panel</h6>
                    <div class="admin-badge">Control</div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <div style="flex: 1;">
                <!-- Inventory Section -->
                <div class="mb-3">
                    <div class="nav-section-title">
                        <i class="fas fa-cube me-1"></i>INVENTORY
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#parts-management">
                            <i class="fas fa-cogs"></i>Parts
                        </a>
                        <a class="nav-link" href="#appliances-management">
                            <i class="fas fa-tools"></i>Appliances
                        </a>
                    </nav>
                </div>

                <!-- Staff Section -->
                <div class="mb-3">
                    <div class="nav-section-title">
                        <i class="fas fa-users me-1"></i>STAFF
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="#trainers-management">
                            <i class="fas fa-graduation-cap"></i>Trainers
                        </a>
                        <a class="nav-link" href="#technicians-management">
                            <i class="fas fa-wrench"></i>Technicians
                        </a>
                    </nav>
                </div>

                <!-- System Section -->
                <div class="mb-3">
                    <div class="nav-section-title">
                        <i class="fas fa-server me-1"></i>SYSTEM
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="#users-management">
                            <i class="fas fa-user-shield"></i>Users
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="sidebar-footer mt-auto">
                <!-- User Profile Box -->
                <div class="px-2 mb-2">
                    <div class="card" style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 8px; margin: 0;">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px; min-width: 40px; overflow: hidden;">
                                @auth
                                    @if(auth()->user()->image)
                                        <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user text-white" style="font-size: 0.8rem;"></i>
                                    @endif
                                @endauth
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="fw-bold text-dark" style="font-size: 0.85rem; line-height: 1.2;">@auth{{ auth()->user()->name }}@endauth</div>
                                <div class="text-muted" style="font-size: 0.7rem; line-height: 1.2;">Admin</div>
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

                <div class="border-top pt-2 mb-2"></div>
                <nav class="nav flex-column gap-1">
                    <a class="nav-link text-success" href="/admin/panel">
                        <i class="fas fa-user-circle"></i>Panel
                    </a>
                    <a class="text-success home-link" href="/admin/home" style="color: #198754; padding: 0.6rem 0.75rem; border-radius: 0.5rem; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-bottom: 0.2rem; font-weight: 500; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; text-decoration: none; border: 1px solid #e2e8f0; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px);">
                        <i class="fas fa-home"></i>Home
                    </a>
                    <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                        <i class="fas fa-sign-out-alt"></i>Logout
                    </a>
                </nav>
                <form id="logout-form-sidebar" action="/logout" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content" style="padding-top: 60px; padding-bottom: 100px; position: relative; z-index: 1;">
        <div class="container-fluid" style="padding-top: 0; margin-top: 0;">
            <div class="row g-4">
                <!-- Parts Management -->
                <div class="col-12" id="parts-management">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Parts Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Manage Spare Parts Inventory</h6>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#partModal" onclick="resetPartForm(); document.getElementById('partModalLabel').textContent='Add New Part';">
                                    <i class="fas fa-plus me-2"></i>Add New Part
                                </button>
                            </div>
                            <!-- Search and Filter Bar -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="partsSearch" placeholder="Search parts...">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="partsApplianceFilter">
                                        <option value="">All Appliances</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="partsAvailabilityFilter">
                                        <option value="">All Status</option>
                                        <option value="1">Available</option>
                                        <option value="0">Not Available</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-secondary w-100" onclick="clearPartsFilters()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                            <div id="partsTableContainer">
                                <!-- Parts table will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appliances Management -->
                <div class="col-12" id="appliances-management">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Appliances Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Manage Appliances Catalog</h6>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#applianceModal" onclick="resetApplianceForm(); document.getElementById('applianceModalLabel').textContent='Add New Appliance';">
                                    <i class="fas fa-plus me-2"></i>Add New Appliance
                                </button>
                            </div>
                            <!-- Search and Filter Bar -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="appliancesSearch" placeholder="Search appliances...">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="appliancesBrandFilter">
                                        <option value="">All Brands</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="appliancesStatusFilter">
                                        <option value="">All Status</option>
                                        <option value="Available">Available</option>
                                        <option value="In Use">In Use</option>
                                        <option value="Maintenance">Maintenance</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-secondary w-100" onclick="clearAppliancesFilters()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                            <div id="appliancesTableContainer">
                                <!-- Appliances table will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trainers Management -->
                <div class="col-12" id="trainers-management">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Trainers Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Manage Training Staff</h6>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#trainerModal" onclick="resetTrainerForm(); document.getElementById('trainerModalLabel').innerHTML='<i class=\"fas fa-user-plus me-2\"></i>Add New Trainer';">
                                    <i class="fas fa-plus me-2"></i>Add New Trainer
                                </button>
                            </div>
                            <!-- Search and Filter Bar -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="trainersSearch" placeholder="Search trainers...">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="trainersSpecialtyFilter">
                                        <option value="">All Specialties</option>
                                        <option value="E-Cooking Training">E-Cooking Training</option>
                                        <option value="Safety Training">Safety Training</option>
                                        <option value="Technical Training">Technical Training</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="trainersStatusFilter">
                                        <option value="">All Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                        <option value="On Leave">On Leave</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-secondary w-100" onclick="clearTrainersFilters()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                            <div id="trainersTableContainer">
                                <!-- Trainers table will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Technicians Management -->
                <div class="col-12" id="technicians-management">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i>Technicians Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Manage Qualified Technicians</h6>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#technicianModal" onclick="resetTechnicianForm(); document.getElementById('technicianModalLabel').innerHTML='<i class=\"fas fa-user-plus me-2\"></i>Add/Edit Technician';">
                                    <i class="fas fa-plus me-2"></i>Add New Technician
                                </button>
                            </div>
                            <!-- Search and Filter Bar -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="techniciansSearch" placeholder="Search technicians...">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="techniciansSpecialtyFilter">
                                        <option value="">All Specialties</option>
                                        <option value="E-cooking technician">E-cooking technician</option>
                                        <option value="Refrigeration Systems">Refrigeration Systems</option>
                                        <option value="Electronics Repair">Electronics Repair</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="techniciansStatusFilter">
                                        <option value="">All Status</option>
                                        <option value="Available">Available</option>
                                        <option value="Busy">Busy</option>
                                        <option value="Unavailable">Unavailable</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-secondary w-100" onclick="clearTechniciansFilters()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                            <div id="techniciansTableContainer">
                                <!-- Technicians table will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Management -->
                <div class="col-12" id="users-management">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Users Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6>Manage System Users</h6>
                                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#userModal" onclick="resetUserForm(); document.getElementById('userModalLabel').innerHTML='<i class=\"fas fa-user-plus me-2\"></i>Add New User';">
                                    <i class="fas fa-plus me-2"></i>Add New User
                                </button>
                            </div>
                            <!-- Search and Filter Bar -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="usersSearch" placeholder="Search users...">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="usersRoleFilter">
                                        <option value="">All Roles</option>
                                        <option value="admin">Administrator</option>
                                        <option value="trainer">Trainer</option>
                                        <option value="technician">Technician</option>
                                        <option value="user">User</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" id="usersStatusFilter">
                                        <option value="">All Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-outline-secondary w-100" onclick="clearUsersFilters()">
                                        <i class="fas fa-times"></i> Clear
                                    </button>
                                </div>
                            </div>
                            <div id="usersTableContainer">
                                <!-- Users table will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include all the modals from welcome.blade.php -->
    @include('modals.part_modal')
    @include('modals.appliance_modal')
    @include('modals.trainer_modal')
    @include('modals.technician_modal')
    @include('modals.user_modal')

    @include('chat_modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/admin-layout.js') }}"></script>
    <script src="{{ asset('assets/navbar.js') }}"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    <script src="{{ asset('assets/admin.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('assets/part-form.js') }}"></script>
    <script>
        // Time display for admin
        function updateAdminTime() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            const dateStr = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            document.getElementById('adminTimeVal').textContent = timeStr;
            document.getElementById('adminDate').textContent = dateStr;
        }
        updateAdminTime();
        setInterval(updateAdminTime, 1000);
    </script>
</body>
</html>
