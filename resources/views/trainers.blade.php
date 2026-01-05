@extends('layouts.app')

@section('content')
<section id="trainers">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h2">Trainer Dashboard</h1>
            @auth
                @if(auth()->user()->role === 'trainer')
                    <button class="btn btn-success" onclick="editMyProfile('trainer')" id="editMyProfileBtn">
                        <i class="fas fa-user-edit me-2"></i>Edit My Profile
                    </button>
                @endif
            @endauth
        </div>

        <!-- Filters Section -->
        <div class="filters card p-4">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label for="trainerSearchInput" class="form-label">Search</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="trainerSearchInput" placeholder="Search trainers...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="trainerSpecialtyFilter" class="form-label">Specialty</label>
                    <select class="form-select" id="trainerSpecialtyFilter">
                        <option value="">All Specialties</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label for="trainerSortFilter" class="form-label">Sort</label>
                    <select class="form-select" id="trainerSortFilter">
                        <option value="name">Name A-Z</option>
                        <option value="experience">Experience High-Low</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
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
                        <th>Price</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="trainersTableBody"></tbody>
            </table>
        </div>
    </div>
</section>

@include('modals.trainer_modal')
@include('modals.trainer_details_modal')
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const response = await fetch('/user');
            if (response.ok) {
                const user = await response.json();
                currentUser = user;
                if (user.role !== 'trainer') {
                    const btn = document.getElementById('editMyProfileBtn');
                    if (btn) btn.style.display = 'none';
                }
            }
        } catch (error) {
            console.log('Not authenticated');
        }
        
        if (typeof loadTrainersData === 'function') {
            loadTrainersData();
        }
    });
</script>
@endsection
