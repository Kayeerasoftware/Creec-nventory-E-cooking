<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white" style="padding: 0.5rem 1rem;">
                <h6 class="modal-title mb-0" id="userModalLabel"><i class="fas fa-user-plus me-2"></i>Add New User</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="padding: 0.25rem;"></button>
            </div>
            <div class="modal-body" style="padding: 0.75rem;">
                <form id="userForm" enctype="multipart/form-data">
                    <input type="hidden" id="userId" name="id">
                    
                    <!-- Basic Information -->
                    <div class="card mb-2">
                        <div class="card-header bg-primary text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-user me-1"></i>Basic Information</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="userName" class="form-label mb-1 small">Full Name *</label>
                                    <input type="text" class="form-control form-control-sm" id="userName" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="userEmail" class="form-label mb-1 small">Email Address *</label>
                                    <input type="email" class="form-control form-control-sm" id="userEmail" name="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="userRole" class="form-label mb-1 small">Role *</label>
                                    <select class="form-select form-select-sm" id="userRole" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admin">Administrator</option>
                                        <option value="manager">Manager</option>
                                        <option value="trainer">Trainer</option>
                                        <option value="technician">Technician</option>
                                        <option value="user">Regular User</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="userPassword" class="form-label mb-1 small">Password *</label>
                                    <input type="password" class="form-control form-control-sm" id="userPassword" name="password" minlength="6" required>
                                    <small class="text-muted" style="font-size: 0.7rem;">Min 6 characters (leave blank when editing)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Linked Profiles -->
                    <div class="card mb-2">
                        <div class="card-header bg-success text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-link me-1"></i>Linked Profiles (Optional)</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="userTrainerId" class="form-label mb-1 small">Link to Trainer Profile</label>
                                    <select class="form-select form-select-sm" id="userTrainerId" name="trainer_id">
                                        <option value="">No Trainer Profile</option>
                                        <!-- Trainers will be loaded dynamically -->
                                    </select>
                                    <small class="text-muted" style="font-size: 0.7rem;">Only for users with trainer role</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="userTechnicianId" class="form-label mb-1 small">Link to Technician Profile</label>
                                    <select class="form-select form-select-sm" id="userTechnicianId" name="technician_id">
                                        <option value="">No Technician Profile</option>
                                        <!-- Technicians will be loaded dynamically -->
                                    </select>
                                    <small class="text-muted" style="font-size: 0.7rem;">Only for users with technician role</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-sm btn-outline-dark" onclick="resetUserForm()">
                    <i class="fas fa-undo me-1"></i>Reset
                </button>
                <button type="submit" form="userForm" class="btn btn-sm btn-dark">
                    <i class="fas fa-save me-1"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>