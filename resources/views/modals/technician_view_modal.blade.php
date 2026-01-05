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
                                    <div class="me-4">
                                        <small class="text-muted d-block">Employment</small>
                                        <strong id="technicianViewEmployment" class="text-dark fs-5"></strong>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Available Stock</small>
                                        <strong id="technicianViewStock" class="text-success fs-5">0</strong>
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
