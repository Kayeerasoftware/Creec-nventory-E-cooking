<!-- Part Modal -->
<div class="modal fade" id="partModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem 1rem;">
                <h6 class="modal-title mb-0" id="partModalLabel"><i class="fas fa-cogs me-2"></i>Add New Part</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="padding: 0.25rem;"></button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y: auto; padding: 0.75rem;">
                <form id="partForm" enctype="multipart/form-data">
                    <input type="hidden" id="partId" name="id">
                    
                    <!-- Basic Information -->
                    <div class="card mb-2">
                        <div class="card-header bg-primary text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-info-circle me-1"></i>Basic Information</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label for="partNumber" class="form-label mb-1 small">Part Number *</label>
                                    <input type="text" class="form-control form-control-sm" id="partNumber" name="part_number" required>
                                </div>
                                <div class="col-md-8">
                                    <label for="partName" class="form-label mb-1 small">Part Name *</label>
                                    <input type="text" class="form-control form-control-sm" id="partName" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="partAppliance" class="form-label mb-1 small">Appliance Type *</label>
                                    <select class="form-select form-select-sm" id="partAppliance" name="appliance_id" required>
                                        <option value="">Select Appliance</option>
                                        @foreach($appliances as $appliance)
                                            <option value="{{ $appliance->id }}">{{ $appliance->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="partLocation" class="form-label mb-1 small">Storage Location</label>
                                    <input type="text" class="form-control form-control-sm" id="partLocation" name="location" placeholder="e.g., Shelf A-1">
                                </div>
                                <div class="col-12">
                                    <label for="partDescription" class="form-label mb-1 small">Description</label>
                                    <textarea class="form-control form-control-sm" id="partDescription" name="description" rows="2" placeholder="Description..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Availability & Stock -->
                    <div class="card mb-2">
                        <div class="card-header bg-success text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-warehouse me-1"></i>Availability & Stock</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <label for="partAvailability" class="form-label mb-1 small">Availability Status *</label>
                                    <select class="form-select form-select-sm" id="partAvailability" name="availability" required>
                                        <option value="1">Available</option>
                                        <option value="0">Not Available</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="partQuantity" class="form-label mb-1 small">Quantity in Stock</label>
                                    <input type="number" class="form-control form-control-sm" id="partQuantity" name="quantity" min="0" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label for="partMinStock" class="form-label mb-1 small">Minimum Stock Level</label>
                                    <input type="number" class="form-control form-control-sm" id="partMinStock" name="min_stock" min="0" value="5">
                                </div>
                                <div class="col-md-3">
                                    <label for="partPrice" class="form-label mb-1 small">Price (UGX)</label>
                                    <input type="number" class="form-control form-control-sm" id="partPrice" name="price" min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Brands & Compatibility -->
                    <div class="card mb-2">
                        <div class="card-header bg-warning text-dark py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-tags me-1"></i>Brands & Compatibility</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="partBrands" class="form-label mb-1 small">Compatible Brands</label>
                                    <select class="form-select form-select-sm" id="partBrands" name="brands[]" multiple size="4">
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted" style="font-size: 0.7rem;">Hold Ctrl/Cmd for multiple</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="partAppliances" class="form-label mb-1 small">Compatible Appliance Models</label>
                                    <textarea class="form-control form-control-sm" id="partAppliances" name="compatible_models" rows="4" placeholder="List models..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Details -->
                    <div class="card mb-2">
                        <div class="card-header bg-info text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-cog me-1"></i>Technical Details</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label for="partSKU" class="form-label mb-1 small">SKU/Barcode</label>
                                    <input type="text" class="form-control form-control-sm" id="partSKU" name="sku">
                                </div>
                                <div class="col-md-4">
                                    <label for="partWeight" class="form-label mb-1 small">Weight (kg)</label>
                                    <input type="number" class="form-control form-control-sm" id="partWeight" name="weight" step="0.01" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="partDimensions" class="form-label mb-1 small">Dimensions (L×W×H cm)</label>
                                    <input type="text" class="form-control form-control-sm" id="partDimensions" name="dimensions" placeholder="10×5×3">
                                </div>
                                <div class="col-md-6">
                                    <label for="partMaterial" class="form-label mb-1 small">Material</label>
                                    <input type="text" class="form-control form-control-sm" id="partMaterial" name="material" placeholder="e.g., Steel">
                                </div>
                                <div class="col-md-6">
                                    <label for="partWarranty" class="form-label mb-1 small">Warranty Period</label>
                                    <input type="text" class="form-control form-control-sm" id="partWarranty" name="warranty" placeholder="e.g., 1 year">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supplier Information -->
                    <div class="card mb-2">
                        <div class="card-header bg-secondary text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-truck me-1"></i>Supplier Information</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="partSupplier" class="form-label mb-1 small">Supplier Name</label>
                                    <input type="text" class="form-control form-control-sm" id="partSupplier" name="supplier_name">
                                </div>
                                <div class="col-md-6">
                                    <label for="partSupplierContact" class="form-label mb-1 small">Supplier Contact</label>
                                    <input type="text" class="form-control form-control-sm" id="partSupplierContact" name="supplier_contact">
                                </div>
                                <div class="col-md-6">
                                    <label for="partSupplierPrice" class="form-label mb-1 small">Supplier Price (UGX)</label>
                                    <input type="number" class="form-control form-control-sm" id="partSupplierPrice" name="supplier_price" step="0.01" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label for="partLeadTime" class="form-label mb-1 small">Lead Time (days)</label>
                                    <input type="number" class="form-control form-control-sm" id="partLeadTime" name="lead_time" min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image & Files -->
                    <div class="card mb-2">
                        <div class="card-header bg-dark text-white py-1">
                            <small class="mb-0 fw-bold"><i class="fas fa-image me-1"></i>Image & Documentation</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="partImage" class="form-label mb-1 small">Part Image</label>
                                    <input type="file" class="form-control form-control-sm" id="partImage" name="image" accept="image/*">
                                    <div id="currentImage" class="mt-1"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="partComments" class="form-label mb-1 small">Additional Notes</label>
                                    <textarea class="form-control form-control-sm" id="partComments" name="comments" rows="3" placeholder="Notes..."></textarea>
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
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="resetPartForm()">
                    <i class="fas fa-undo me-1"></i>Reset
                </button>
                <button type="submit" form="partForm" class="btn btn-sm btn-primary">
                    <i class="fas fa-save me-1"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>