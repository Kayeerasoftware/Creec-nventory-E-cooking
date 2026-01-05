<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trainer Dashboard - E-Cooking Inventory</title>
    <link rel="icon" type="image/png" href="{{ asset('pictures/creec-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .offcanvas-body {
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
            border-right: 1px solid #e2e8f0;
        }
        .sidebar-header {
            background: var(--secondary-gradient);
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(240, 147, 251, 0.3);
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
            background: var(--secondary-gradient);
            color: white;
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
            transform: translateX(4px);
        }
        .nav-link.active:hover {
            transform: translateX(4px) scale(1.02);
            box-shadow: 0 12px 35px rgba(240, 147, 251, 0.5);
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
        .trainer-badge {
            background: var(--secondary-gradient);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.7rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(240, 147, 251, 0.3);
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
                <span class="text-white" style="font-size: 0.9rem;"><i class="fas fa-chalkboard-teacher"></i> Trainer Dashboard</span>
            </div>
            <div class="d-flex align-items-center" style="gap: 0.2rem; flex-shrink: 0; flex-wrap: nowrap;">
                <div class="text-white text-center" style="font-size: 0.65em; padding: 2px 4px; background: rgba(255,255,255,0.1); border-radius: 3px;">
                    <span id="trainerTimeVal"></span> <span id="trainerDate"></span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" style="padding: 0.2rem 0.4rem; font-size: 0.8rem; display: flex; align-items-center; gap: 0.25rem;">
                        @if(auth('trainer')->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth('trainer')->user()->profile_picture) }}" alt="Profile" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                        <span class="d-none d-md-inline">Welcome back {{ auth('trainer')->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                        <li class="px-3 py-2 border-bottom">
                            <small class="text-muted">Signed in as</small>
                            <div class="fw-bold">{{ auth('trainer')->user()->name }}</div>
                            <small class="badge bg-danger">Trainer</small>
                        </li>
                        <li><a class="dropdown-item" href="/trainer/panel"><i class="fas fa-user-circle me-2"></i>Panel</a></li>
                        <li><a class="dropdown-item" href="/profile"><i class="fas fa-user-edit me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="/trainers"><i class="fas fa-home me-2"></i>Home</a></li>
                        <li><a class="dropdown-item" href="/"><i class="fas fa-globe me-2"></i>Main Site</a></li>
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt me-2 text-danger"></i>Logout</a></li>
                        <form id="logout-form" action="/logout" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel" data-bs-scroll="true" data-bs-backdrop="false">
        <div class="offcanvas-header d-lg-none border-bottom">
            <h5 class="offcanvas-title fw-bold" id="sidebarLabel">
                <i class="fas fa-chalkboard-teacher text-danger me-2"></i>Trainer
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-lg-block d-flex flex-column" style="padding: 1rem; padding-top: 50px;">
            <div class="text-center mb-3">
                <div class="sidebar-header p-3 mb-2">
                    @if(auth('trainer')->user()->profile_picture)
                        <img src="{{ asset('storage/' . auth('trainer')->user()->profile_picture) }}" alt="Profile" class="rounded-circle mb-2" style="width: 60px; height: 60px; object-fit: cover; border: 3px solid white;">
                    @else
                        <div class="bg-white rounded-circle p-2 d-inline-flex mb-2">
                            <i class="fas fa-user fa-2x text-danger"></i>
                        </div>
                    @endif
                    <h6 class="text-white fw-bold mb-1">{{ auth('trainer')->user()->name }}</h6>
                    <div class="trainer-badge">Trainer Dashboard</div>
                </div>
            </div>

            <div style="flex: 1;">
                <div class="mb-3">
                    <div class="nav-section-title">
                        <i class="fas fa-chart-line me-1"></i>ANALYTICS
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="#overview">
                            <i class="fas fa-tachometer-alt"></i>Overview
                        </a>
                        <a class="nav-link" href="#inventory-stats">
                            <i class="fas fa-chart-bar"></i>Inventory Stats
                        </a>
                    </nav>
                </div>
                <div class="mb-3">
                    <div class="nav-section-title">
                        <i class="fas fa-cube me-1"></i>CATALOG
                    </div>
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#parts-view">
                            <i class="fas fa-cogs"></i>Parts Inventory
                        </a>
                        <a class="nav-link" href="#appliances-view">
                            <i class="fas fa-tools"></i>Appliances Catalog
                        </a>
                        <a class="nav-link" href="#brands-view">
                            <i class="fas fa-tag"></i>Brands Overview
                        </a>
                    </nav>
                </div>
            </div>

            <div class="sidebar-footer mt-auto">
                <div class="border-top pt-2 mb-2"></div>
                <nav class="nav flex-column gap-1">
                    <a class="nav-link text-success" href="/trainer/panel">
                        <i class="fas fa-user"></i>Panel
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
    <main class="main-content" style="padding-top: 600px; padding-bottom: 100px; position: relative; z-index: 1;">
        <div class="container-fluid" style="padding-top: 0; margin-top: 0;">
            <!-- Statistics Cards -->
            <div class="row g-3 mb-4" id="overview">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-center text-white">
                            <i class="fas fa-cogs fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $statistics['total_parts'] }}</h3>
                            <small>Total Parts in Inventory</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                        <div class="card-body text-center text-white">
                            <i class="fas fa-tools fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $overviewStats['total_appliances'] }}</h3>
                            <small>Total Appliances</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <div class="card-body text-center text-white">
                            <i class="fas fa-check-circle fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $statistics['available_parts'] }}</h3>
                            <small>Available Parts in Stock</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                        <div class="card-body text-center text-white">
                            <i class="fas fa-percentage fa-2x mb-2"></i>
                            <h3 class="mb-0">{{ $overviewStats['stock_percentage'] }}%</h3>
                            <small>Stock Availability Rate</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory Statistics -->
            <div class="row g-4 mb-4" id="inventory-stats">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Parts by Appliance Type</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-circle text-primary me-2"></i>Electric Pressure Cooker</span>
                                <strong>{{ $statistics['epc_parts'] }}</strong>
                            </div>
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-circle text-success me-2"></i>Air Fryer</span>
                                <strong>{{ $statistics['air_fryer_parts'] }}</strong>
                            </div>
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-circle text-warning me-2"></i>Induction Cooker</span>
                                <strong>{{ $statistics['induction_parts'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-box-open me-2"></i>Availability Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle text-success me-2"></i>Available EPC Parts</span>
                                <strong>{{ $statistics['available_epc_parts'] }}</strong>
                            </div>
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle text-success me-2"></i>Available Air Fryer Parts</span>
                                <strong>{{ $statistics['available_air_fryer_parts'] }}</strong>
                            </div>
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-check-circle text-success me-2"></i>Available Induction Parts</span>
                                <strong>{{ $statistics['available_induction_parts'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Parts View -->
                <div class="col-12" id="parts-view">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Parts Inventory Management</h5>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-light" onclick="togglePartsView('list')" id="partsListBtn">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-light" onclick="togglePartsView('grid')" id="partsGridBtn">
                                        <i class="fas fa-th"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Loading parts data...</p>
                                </div>
                            </div>
                            <div id="partsGridContainer" style="display: none;">
                                <!-- Parts grid will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appliances View -->
                <div class="col-12" id="appliances-view">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Appliances Catalog</h5>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-light" onclick="toggleAppliancesView('list')" id="appliancesListBtn">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-light" onclick="toggleAppliancesView('grid')" id="appliancesGridBtn">
                                        <i class="fas fa-th"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
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
                                <div class="text-center py-4">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Loading appliances data...</p>
                                </div>
                            </div>
                            <div id="appliancesGridContainer" style="display: none;">
                                <!-- Appliances grid will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Brands Overview -->
                <div class="col-12" id="brands-view">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                            <h5 class="mb-0"><i class="fas fa-tag me-2"></i>Brands Overview</h5>
                        </div>
                        <div class="card-body">
                            <div id="brandsContainer">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-warning" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mt-2">Loading brands data...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('chat_modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize data BEFORE other scripts load
        window.partsData = @json($parts ?? []);
        window.appliancesData = @json($appliances ?? []);
        window.brandsData = @json($brands ?? []);
        console.log('Data initialized:', { parts: window.partsData.length, appliances: window.appliancesData.length, brands: window.brandsData.length });
    </script>
    <script src="{{ asset('assets/admin-layout.js') }}"></script>
    <script src="{{ asset('assets/navbar.js') }}"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
    <script>
        // Render data immediately after DOM loads
        (function() {
            function init() {
                if (window.partsData) renderPartsList(window.partsData);
                if (window.appliancesData) renderAppliancesList(window.appliancesData);
                if (window.brandsData) renderBrands(window.brandsData);
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();

        function renderPartsList(parts) {
            const container = document.getElementById('partsTableContainer');
            if (!container) return;
            if (!parts || parts.length === 0) {
                container.innerHTML = '<p class="text-muted">No parts available</p>';
                return;
            }
            let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Part Number</th><th>Name</th><th>Type</th><th>Brands</th><th>Price</th><th>Status</th><th>Action</th></tr></thead><tbody>';
            parts.forEach(part => {
                const partNum = part.part_number || 'N/A';
                const appType = part.appliance ? part.appliance.name : 'N/A';
                const badge = part.appliance ? part.appliance.color : 'bg-secondary';
                const brandsList = part.brands && part.brands.length > 0 ? part.brands.map(b => b.name).join(', ') : 'N/A';
                const price = part.price ? `UGX ${parseFloat(part.price).toLocaleString()}` : 'N/A';
                html += `<tr><td><strong>${partNum}</strong></td><td>${part.name}</td><td><span class="badge ${badge}">${appType}</span></td><td>${brandsList}</td><td><strong>${price}</strong></td><td><span class="badge ${part.availability ? 'bg-success' : 'bg-secondary'}">${part.availability ? 'Available' : 'Not Available'}</span></td><td><button class="btn btn-sm btn-primary" onclick="viewPartDetails(${part.id})"><i class="fas fa-eye"></i> View</button></td></tr>`;
            });
            html += '</tbody></table></div>';
            container.innerHTML = html;
        }

        function renderPartsGrid(parts) {
            const container = document.getElementById('partsGridContainer');
            if (parts.length === 0) {
                container.innerHTML = '<p class="text-muted">No parts available</p>';
                return;
            }

            let html = '<div class="row g-3">';
            parts.forEach(part => {
                const price = part.price ? `UGX ${parseFloat(part.price).toLocaleString()}` : 'N/A';
                const imgSrc = part.image || 'https://via.placeholder.com/150?text=No+Image';
                html += `<div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <img src="${imgSrc}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="${part.name}">
                        <div class="card-body">
                            <h6 class="card-title">${part.name}</h6>
                            <p class="text-muted small mb-2">${part.partNumber}</p>
                            <span class="badge ${part.badgeClass || 'bg-secondary'} mb-2">${part.applianceType}</span>
                            <p class="mb-1"><strong>Price:</strong> ${price}</p>
                            <p class="mb-2"><span class="badge ${part.availability ? 'bg-success' : 'bg-secondary'}">${part.availability ? 'Available' : 'Not Available'}</span></p>
                            <button class="btn btn-sm btn-primary w-100" onclick='viewPartDetails(${JSON.stringify(part).replace(/'/g, "&apos;")})'><i class="fas fa-eye"></i> View Details</button>
                        </div>
                    </div>
                </div>`;
            });
            html += '</div>';
            container.innerHTML = html;
        }

        function togglePartsView(view) {
            const listBtn = document.getElementById('partsListBtn');
            const gridBtn = document.getElementById('partsGridBtn');
            const listContainer = document.getElementById('partsTableContainer');
            const gridContainer = document.getElementById('partsGridContainer');

            if (view === 'list') {
                listBtn.classList.remove('btn-outline-light');
                listBtn.classList.add('btn-light');
                gridBtn.classList.remove('btn-light');
                gridBtn.classList.add('btn-outline-light');
                listContainer.style.display = 'block';
                gridContainer.style.display = 'none';
            } else {
                gridBtn.classList.remove('btn-outline-light');
                gridBtn.classList.add('btn-light');
                listBtn.classList.remove('btn-light');
                listBtn.classList.add('btn-outline-light');
                listContainer.style.display = 'none';
                gridContainer.style.display = 'block';
                renderPartsGrid(partsData);
            }
        }

        function viewPartDetails(partId) {
            const part = partsData.find(p => p.id === partId);
            if (!part) return;
            const partNum = part.part_number || 'N/A';
            const appType = part.appliance ? part.appliance.name : 'N/A';
            const badge = part.appliance ? part.appliance.color : 'bg-secondary';
            const brandsList = part.brands && part.brands.length > 0 ? part.brands.map(b => b.name).join(', ') : 'N/A';
            const imgSrc = part.image_path ? '/storage/' + part.image_path : 'https://via.placeholder.com/300?text=No+Image';
            const modal = `<div class="modal fade" id="partModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <h5 class="modal-title"><i class="fas fa-cogs me-2"></i>Part Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <img src="${imgSrc}" class="img-fluid rounded" style="max-height: 250px; object-fit: cover;" alt="${part.name}">
                                </div>
                                <div class="col-md-8">
                                    <h5>${part.name}</h5>
                                    <p class="text-muted">${partNum}</p>
                                    <hr>
                                    <div class="row mb-2">
                                        <div class="col-6"><strong>Appliance Type:</strong></div>
                                        <div class="col-6"><span class="badge ${badge}">${appType}</span></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><strong>Price:</strong></div>
                                        <div class="col-6">${part.price ? '$' + parseFloat(part.price).toFixed(2) : 'N/A'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><strong>Brands:</strong></div>
                                        <div class="col-6">${brandsList}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><strong>Status:</strong></div>
                                        <div class="col-6"><span class="badge ${part.availability ? 'bg-success' : 'bg-secondary'}">${part.availability ? 'Available' : 'Not Available'}</span></div>
                                    </div>
                                    ${part.location ? `<div class="row mb-2"><div class="col-6"><strong>Location:</strong></div><div class="col-6">${part.location}</div></div>` : ''}
                                </div>
                            </div>
                            ${part.description ? `<hr><div><strong>Description:</strong><p>${part.description}</p></div>` : ''}
                            ${part.comments ? `<hr><div><strong>Comments:</strong><p>${part.comments}</p></div>` : ''}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>`;
            document.body.insertAdjacentHTML('beforeend', modal);
            const modalEl = new bootstrap.Modal(document.getElementById('partModal'));
            modalEl.show();
            document.getElementById('partModal').addEventListener('hidden.bs.modal', function () {
                this.remove();
            });
        }

        function renderAppliancesList(appliances) {
            const container = document.getElementById('appliancesTableContainer');
            if (!container) return;
            if (!appliances || appliances.length === 0) {
                container.innerHTML = '<p class="text-muted">No appliances available</p>';
                return;
            }
            let html = '<div class="table-responsive"><table class="table table-hover"><thead><tr><th>Name</th><th>Brand</th><th>Model</th><th>Power</th><th>Price</th><th>Status</th><th>Action</th></tr></thead><tbody>';
            appliances.forEach(app => {
                const statusClass = app.status === 'Available' ? 'success' : (app.status === 'In Use' ? 'info' : 'danger');
                const price = app.price ? `UGX ${parseFloat(app.price).toLocaleString()}` : 'N/A';
                const brandName = app.brand ? app.brand.name : 'N/A';
                html += `<tr><td><strong>${app.name}</strong></td><td>${brandName}</td><td>${app.model || 'N/A'}</td><td>${app.power || 'N/A'}</td><td><strong>${price}</strong></td><td><span class="badge bg-${statusClass}">${app.status}</span></td><td><button class="btn btn-sm btn-success" onclick='viewApplianceDetails(${JSON.stringify(app)})'><i class="fas fa-eye"></i> View</button></td></tr>`;
            });
            html += '</tbody></table></div>';
            container.innerHTML = html;
        }

        function renderBrands(brands) {
            const container = document.getElementById('brandsContainer');
            if (!container) return;
            if (!brands || brands.length === 0) {
                container.innerHTML = '<p class="text-muted">No brands available</p>';
                return;
            }
            let html = '<div class="row g-3">';
            brands.forEach(brand => {
                html += `<div class="col-md-3"><div class="card shadow-sm text-center"><div class="card-body"><i class="fas fa-tag fa-2x text-warning mb-2"></i><h6>${brand.name}</h6></div></div></div>`;
            });
            html += '</div>';
            container.innerHTML = html;
        }

        function renderAppliancesGrid(appliances) {
            const container = document.getElementById('appliancesGridContainer');
            if (appliances.length === 0) {
                container.innerHTML = '<p class="text-muted">No appliances available</p>';
                return;
            }

            let html = '<div class="row g-3">';
            appliances.forEach(app => {
                const statusClass = app.status === 'Available' ? 'success' : (app.status === 'In Use' ? 'info' : 'danger');
                const price = app.price ? `UGX ${parseFloat(app.price).toLocaleString()}` : 'N/A';
                const imgSrc = 'https://via.placeholder.com/150?text=' + encodeURIComponent(app.name);
                html += `<div class="col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <img src="${imgSrc}" class="card-img-top" style="height: 150px; object-fit: cover;" alt="${app.name}">
                        <div class="card-body">
                            <h6 class="card-title">${app.name}</h6>
                            <p class="text-muted small mb-2">${app.brand || 'N/A'}</p>
                            <p class="mb-1"><strong>Model:</strong> ${app.model || 'N/A'}</p>
                            <p class="mb-1"><strong>Price:</strong> ${price}</p>
                            <p class="mb-2"><span class="badge bg-${statusClass}">${app.status}</span></p>
                            <button class="btn btn-sm btn-success w-100" onclick='viewApplianceDetails(${JSON.stringify(app).replace(/'/g, "&apos;")})'><i class="fas fa-eye"></i> View Details</button>
                        </div>
                    </div>
                </div>`;
            });
            html += '</div>';
            container.innerHTML = html;
        }

        function toggleAppliancesView(view) {
            const listBtn = document.getElementById('appliancesListBtn');
            const gridBtn = document.getElementById('appliancesGridBtn');
            const listContainer = document.getElementById('appliancesTableContainer');
            const gridContainer = document.getElementById('appliancesGridContainer');

            if (view === 'list') {
                listBtn.classList.remove('btn-outline-light');
                listBtn.classList.add('btn-light');
                gridBtn.classList.remove('btn-light');
                gridBtn.classList.add('btn-outline-light');
                listContainer.style.display = 'block';
                gridContainer.style.display = 'none';
            } else {
                gridBtn.classList.remove('btn-outline-light');
                gridBtn.classList.add('btn-light');
                listBtn.classList.remove('btn-light');
                listBtn.classList.add('btn-outline-light');
                listContainer.style.display = 'none';
                gridContainer.style.display = 'block';
                renderAppliancesGrid(appliancesData);
            }
        }

        function viewApplianceDetails(app) {
            const imgSrc = 'https://via.placeholder.com/300?text=' + encodeURIComponent(app.name);
            const statusClass = app.status === 'Available' ? 'success' : (app.status === 'In Use' ? 'info' : 'danger');
            const modal = `<div class="modal fade" id="applianceModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                            <h5 class="modal-title"><i class="fas fa-tools me-2"></i>Appliance Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <img src="${imgSrc}" class="img-fluid rounded" style="max-height: 250px; object-fit: cover;" alt="${app.name}">
                                </div>
                                <div class="col-md-8">
                                    <h5>${app.name}</h5>
                                    <p class="text-muted">${app.brand || 'N/A'}</p>
                                    <hr>
                                    <div class="row mb-2">
                                        <div class="col-6"><strong>Model:</strong></div>
                                        <div class="col-6">${app.model || 'N/A'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><strong>SKU:</strong></div>
                                        <div class="col-6">${app.sku || 'N/A'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><strong>Power:</strong></div>
                                        <div class="col-6">${app.power || 'N/A'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><strong>Price:</strong></div>
                                        <div class="col-6">${app.price ? '$' + parseFloat(app.price).toFixed(2) : 'N/A'}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6"><strong>Status:</strong></div>
                                        <div class="col-6"><span class="badge bg-${statusClass}">${app.status}</span></div>
                                    </div>
                                </div>
                            </div>
                            ${app.description ? `<hr><div><strong>Description:</strong><p>${app.description}</p></div>` : ''}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>`;
            document.body.insertAdjacentHTML('beforeend', modal);
            const modalEl = new bootstrap.Modal(document.getElementById('applianceModal'));
            modalEl.show();
            document.getElementById('applianceModal').addEventListener('hidden.bs.modal', function () {
                this.remove();
            });
        }



        // Filter functions
        function clearPartsFilters() {
            document.getElementById('partsSearch').value = '';
            document.getElementById('partsApplianceFilter').value = '';
            document.getElementById('partsAvailabilityFilter').value = '';
            filterParts();
        }

        function clearAppliancesFilters() {
            document.getElementById('appliancesSearch').value = '';
            document.getElementById('appliancesBrandFilter').value = '';
            document.getElementById('appliancesStatusFilter').value = '';
            filterAppliances();
        }

        function filterParts() {
            const search = document.getElementById('partsSearch').value.toLowerCase();
            const rows = document.querySelectorAll('#partsTableContainer tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        }

        function filterAppliances() {
            const search = document.getElementById('appliancesSearch').value.toLowerCase();
            const rows = document.querySelectorAll('#appliancesTableContainer tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        }

        document.getElementById('partsSearch').addEventListener('input', filterParts);
        document.getElementById('appliancesSearch').addEventListener('input', filterAppliances);

        // Time display
        function updateTrainerTime() {
            const now = new Date();
            document.getElementById('trainerTimeVal').textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('trainerDate').textContent = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }
        updateTrainerTime();
        setInterval(updateTrainerTime, 1000);
    </script>
</body>
</html>
