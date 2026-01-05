@extends('layouts.app')

@section('content')
<section id="technicians">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Technician Dashboard</h1>
            @auth
                @if(auth()->user()->role === 'technician')
                    <button class="btn btn-info" onclick="editMyProfile('technician')" id="editMyProfileBtn">
                        <i class="fas fa-user-edit me-2"></i>Edit My Profile
                    </button>
                @endif
            @endauth
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><i class="fas fa-users me-2"></i>Total Technicians</h5>
                        <h3 class="card-text" id="technicianStatsTotal">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-success"><i class="fas fa-check-circle me-2"></i>Available</h5>
                        <h3 class="card-text" id="technicianStatsAvailable">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-warning"><i class="fas fa-clock me-2"></i>Busy</h5>
                        <h3 class="card-text" id="technicianStatsBusy">0</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title text-danger"><i class="fas fa-times-circle me-2"></i>Unavailable</h5>
                        <h3 class="card-text" id="technicianStatsUnavailable">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filters card p-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label for="technicianSearchInput" class="form-label">Search</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="technicianSearchInput" placeholder="Search technicians...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="technicianSpecialtyFilter" class="form-label">Specialty</label>
                    <select class="form-select" id="technicianSpecialtyFilter">
                        <option value="">All Specialties</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="technicianStatusFilter" class="form-label">Status</label>
                    <select class="form-select" id="technicianStatusFilter">
                        <option value="">All Statuses</option>
                        <option value="Available">Available</option>
                        <option value="Busy">Busy</option>
                        <option value="Unavailable">Unavailable</option>
                        <option value="On Leave">On Leave</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="technicianSortFilter" class="form-label">Sort</label>
                    <select class="form-select" id="technicianSortFilter">
                        <option value="name">Name A-Z</option>
                        <option value="experience">Experience High-Low</option>
                        <option value="hourly_rate">Rate High-Low</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="technicianViewFilter" class="form-label">View</label>
                    <select class="form-select" id="technicianViewFilter">
                        <option value="grid">Grid View</option>
                        <option value="list">List View</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Technicians Grid -->
        <div id="techniciansGrid" class="row g-4 mt-4"></div>

        <!-- Technicians List -->
        <div id="techniciansList" class="table-responsive d-none mt-4">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Specialty</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Experience</th>
                        <th>Price</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="techniciansTableBody"></tbody>
            </table>
        </div>
    </div>
</section>

@include('modals.technician_modal')
@include('modals.technician_view_modal')
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const response = await fetch('/user');
            if (response.ok) {
                const user = await response.json();
                currentUser = user;
                if (user.role !== 'technician') {
                    const btn = document.getElementById('editMyProfileBtn');
                    if (btn) btn.style.display = 'none';
                }
            }
        } catch (error) {
            console.log('Not authenticated');
        }

        if (typeof loadTechniciansData === 'function') {
            loadTechniciansData();
        }
    });
</script>
@endsection
