<!-- Appliance Modal -->
<div class="modal fade" id="applianceModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 0.5rem 1rem;">
                <h6 class="modal-title mb-0" id="applianceModalLabel"><i class="fas fa-tools me-2"></i>Add New Appliance</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="padding: 0.25rem;"></button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto; padding: 0.75rem;">
                <form id="applianceForm" enctype="multipart/form-data">
                    <input type="hidden" id="applianceId" name="id">
                    
                    <!-- Basic Information -->
                    <div class="card mb-2">
                        <div class="card-header bg-primary text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-info-circle me-1"></i>Basic Information</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="applianceName" class="form-label mb-1 small">Appliance Name *</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceName" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceBrand" class="form-label mb-1 small">Brand</label>
                                    <select class="form-select form-select-sm" id="applianceBrand" name="brand_id">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="applianceModel" class="form-label mb-1 small">Model Number</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceModel" name="model">
                                </div>
                                <div class="col-md-4">
                                    <label for="applianceSKU" class="form-label mb-1 small">SKU/Product Code</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceSKU" name="sku">
                                </div>
                                <div class="col-md-4">
                                    <label for="applianceStatus" class="form-label mb-1 small">Status</label>
                                    <select class="form-select form-select-sm" id="applianceStatus" name="status">
                                        <option value="Available">Available</option>
                                        <option value="In Use">In Use</option>
                                        <option value="Maintenance">Maintenance</option>
                                        <option value="Discontinued">Discontinued</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="applianceDescription" class="form-label mb-1 small">Description</label>
                                    <textarea class="form-control form-control-sm" id="applianceDescription" name="description" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Specifications -->
                    <div class="card mb-2">
                        <div class="card-header bg-success text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-cog me-1"></i>Technical Specifications</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label for="appliancePower" class="form-label mb-1 small">Power Rating</label>
                                    <input type="text" class="form-control form-control-sm" id="appliancePower" name="power" placeholder="e.g., 1500W">
                                </div>
                                <div class="col-md-3">
                                    <label for="applianceVoltage" class="form-label mb-1 small">Voltage</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceVoltage" name="voltage" placeholder="e.g., 220V">
                                </div>
                                <div class="col-md-3">
                                    <label for="applianceFrequency" class="form-label mb-1 small">Frequency</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceFrequency" name="frequency" placeholder="e.g., 50Hz">
                                </div>
                                <div class="col-md-3">
                                    <label for="applianceCapacity" class="form-label mb-1 small">Capacity</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceCapacity" name="capacity" placeholder="e.g., 5L">
                                </div>
                                <div class="col-md-4">
                                    <label for="applianceWeight" class="form-label mb-1 small">Weight (kg)</label>
                                    <input type="number" class="form-control form-control-sm" id="applianceWeight" name="weight" step="0.1" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="applianceDimensions" class="form-label mb-1 small">Dimensions (L×W×H cm)</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceDimensions" name="dimensions">
                                </div>
                                <div class="col-md-4">
                                    <label for="applianceColor" class="form-label mb-1 small">Color/Finish</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceColor" name="color">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Availability -->
                    <div class="card mb-2">
                        <div class="card-header bg-warning text-dark py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-money-bill me-1"></i>Pricing & Availability</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label for="appliancePrice" class="form-label mb-1 small">Retail Price (UGX)</label>
                                    <input type="number" class="form-control form-control-sm" id="appliancePrice" name="price" step="0.01" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="applianceCostPrice" class="form-label mb-1 small">Cost Price (UGX)</label>
                                    <input type="number" class="form-control form-control-sm" id="applianceCostPrice" name="cost_price" step="0.01" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="applianceQuantity" class="form-label mb-1 small">Quantity in Stock</label>
                                    <input type="number" class="form-control form-control-sm" id="applianceQuantity" name="quantity" min="0" value="0">
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceWarranty" class="form-label mb-1 small">Warranty Period</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceWarranty" name="warranty" placeholder="e.g., 2 years">
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceLocation" class="form-label mb-1 small">Storage Location</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceLocation" name="location">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features & Certifications -->
                    <div class="card mb-2">
                        <div class="card-header bg-info text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-star me-1"></i>Features & Certifications</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="applianceFeatures" class="form-label mb-1 small">Key Features</label>
                                    <textarea class="form-control form-control-sm" id="applianceFeatures" name="features" rows="2" placeholder="List key features..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceCertifications" class="form-label mb-1 small">Certifications</label>
                                    <textarea class="form-control form-control-sm" id="applianceCertifications" name="certifications" rows="2" placeholder="e.g., CE, FCC..."></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceEnergyRating" class="form-label mb-1 small">Energy Rating</label>
                                    <select class="form-select form-select-sm" id="applianceEnergyRating" name="energy_rating">
                                        <option value="">Select Rating</option>
                                        <option value="A+++">A+++</option>
                                        <option value="A++">A++</option>
                                        <option value="A+">A+</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceCountryOrigin" class="form-label mb-1 small">Country of Origin</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceCountryOrigin" name="country_origin">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier & Maintenance -->
                    <div class="card mb-2">
                        <div class="card-header bg-secondary text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-tools me-1"></i>Supplier & Maintenance</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="applianceSupplier" class="form-label mb-1 small">Supplier Name</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceSupplier" name="supplier_name">
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceSupplierContact" class="form-label mb-1 small">Supplier Contact</label>
                                    <input type="text" class="form-control form-control-sm" id="applianceSupplierContact" name="supplier_contact">
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceLastMaintenance" class="form-label mb-1 small">Last Maintenance Date</label>
                                    <input type="date" class="form-control form-control-sm" id="applianceLastMaintenance" name="last_maintenance">
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceNextMaintenance" class="form-label mb-1 small">Next Maintenance Due</label>
                                    <input type="date" class="form-control form-control-sm" id="applianceNextMaintenance" name="next_maintenance">
                                </div>
                                <div class="col-12">
                                    <label for="applianceMaintenanceNotes" class="form-label mb-1 small">Maintenance Notes</label>
                                    <textarea class="form-control form-control-sm" id="applianceMaintenanceNotes" name="maintenance_notes" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images & Documentation -->
                    <div class="card mb-2">
                        <div class="card-header bg-dark text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-image me-1"></i>Images & Documentation</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="applianceImage" class="form-label mb-1 small">Product Image</label>
                                    <input type="file" class="form-control form-control-sm" id="applianceImage" name="image" accept="image/*">
                                    <div id="currentApplianceImage" class="mt-2"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="applianceManual" class="form-label mb-1 small">User Manual (PDF)</label>
                                    <input type="file" class="form-control form-control-sm" id="applianceManual" name="manual" accept=".pdf">
                                </div>
                                <div class="col-12">
                                    <label for="applianceNotes" class="form-label mb-1 small">Additional Notes</label>
                                    <textarea class="form-control form-control-sm" id="applianceNotes" name="notes" rows="2"></textarea>
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
                <button type="button" class="btn btn-sm btn-outline-success" onclick="resetApplianceForm()">
                    <i class="fas fa-undo me-1"></i>Reset
                </button>
                <button type="submit" form="applianceForm" class="btn btn-sm btn-success">
                    <i class="fas fa-save me-1"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>