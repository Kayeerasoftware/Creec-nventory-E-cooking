<!-- Trainer Details Modal -->
<div class="modal fade" id="trainerDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex align-items-center">
                    <h5 class="modal-title mb-0"><i class="fas fa-user-graduate me-2"></i>Trainer Profile</h5>
                </div>
                <div class="d-flex align-items-center">
                    <span id="trainerDetailsStatus" class="badge bg-success me-2"></span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">
                <div class="row g-2">
                    <div class="col-md-3 text-center">
                        <div id="trainerDetailsImage" class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-bold" style="width: 70px; height: 70px; font-size: 1.8rem;"></div>
                        <div class="mt-2"><small class="text-warning">⭐ <span id="trainerDetailsRating"></span></small></div>
                    </div>
                    <div class="col-md-9">
                        <h5 id="trainerDetailsName" class="mb-1"></h5>
                        <p class="text-muted small mb-2"><i class="fas fa-chalkboard me-1"></i><span id="trainerDetailsSpecialty"></span></p>
                        <div class="row g-1 small">
                            <div class="col-4"><strong>Exp:</strong> <span id="trainerDetailsExperience"></span></div>
                            <div class="col-4"><strong>Rate:</strong> <span id="trainerDetailsRate"></span></div>
                            <div class="col-4"><strong>License:</strong> <span id="trainerDetailsLicense"></span></div>
                            <div class="col-6"><strong>Location:</strong> <span id="trainerDetailsLocation"></span></div>
                            <div class="col-6"><strong>Stock:</strong> <span id="trainerDetailsStock" class="text-success">0</span></div>
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
                            <div class="col-3"><div class="p-1 bg-light rounded"><div class="h6 text-primary mb-0" id="trainerDetailsTrainingsCompleted"></div><small class="text-muted" style="font-size: 0.7rem;">Trainings</small></div></div>
                            <div class="col-3"><div class="p-1 bg-light rounded"><div class="h6 text-warning mb-0" id="trainerDetailsStudents"></div><small class="text-muted" style="font-size: 0.7rem;">Students</small></div></div>
                            <div class="col-3"><div class="p-1 bg-light rounded"><div class="h6 text-info mb-0" id="trainerDetailsTrainings"></div><small class="text-muted" style="font-size: 0.7rem;">Sessions</small></div></div>
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
