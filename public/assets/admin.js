// Admin Panel JavaScript for CRUD Operations

// Global variables
let currentEditingId = null;
let currentEditingType = null;

// Initialize admin panel
document.addEventListener('DOMContentLoaded', function() {
    initializeAdminPanel();
    loadAllData();
    setupEventListeners();
});

function initializeAdminPanel() {
    // Update time display
    updateAdminTime();
    setInterval(updateAdminTime, 1000);
    
    // Setup navigation
    setupNavigation();
    
    // Load filter options
    loadFilterOptions();
    
    // Load initial data
    loadPartsTable();
    loadAppliancesTable();
    loadTrainersTable();
    loadTechniciansTable();
    loadUsersTable();
}

function updateAdminTime() {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('en-US', { 
        hour12: true, 
        hour: '2-digit', 
        minute: '2-digit' 
    });
    const dateStr = now.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric' 
    });
    
    document.getElementById('adminTimeVal').textContent = timeStr;
    document.getElementById('adminDate').textContent = dateStr;
}

function setupNavigation() {
    document.querySelectorAll('.nav-link:not(.home-link)').forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href && href.startsWith('#')) {
                e.preventDefault();
                
                // Remove active class from all links
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Show corresponding section
                const targetId = href.substring(1);
                showSection(targetId);
            }
        });
    });
}

function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('[id$="-management"]').forEach(section => {
        section.style.display = 'none';
    });
    
    // Show target section
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.style.display = 'block';
        
        // Load data for the section if needed
        switch(sectionId) {
            case 'parts-management':
                loadPartsTable();
                break;
            case 'appliances-management':
                loadAppliancesTable();
                break;
            case 'trainers-management':
                loadTrainersTable();
                break;
            case 'technicians-management':
                loadTechniciansTable();
                break;
            case 'users-management':
                loadUsersTable();
                break;
        }
    }
}

function setupEventListeners() {
    // Form submissions
    document.getElementById('partForm').addEventListener('submit', handlePartSubmit);
    document.getElementById('applianceForm').addEventListener('submit', handleApplianceSubmit);
    document.getElementById('trainerForm').addEventListener('submit', handleTrainerSubmit);
    document.getElementById('technicianForm').addEventListener('submit', handleTechnicianSubmit);
    document.getElementById('userForm').addEventListener('submit', handleUserSubmit);
    
    // Search filters
    document.getElementById('partsSearch').addEventListener('input', debounce(filterPartsTable, 300));
    document.getElementById('partsApplianceFilter').addEventListener('change', filterPartsTable);
    document.getElementById('partsAvailabilityFilter').addEventListener('change', filterPartsTable);
    
    document.getElementById('appliancesSearch').addEventListener('input', debounce(filterAppliancesTable, 300));
    document.getElementById('appliancesBrandFilter').addEventListener('change', filterAppliancesTable);
    document.getElementById('appliancesStatusFilter').addEventListener('change', filterAppliancesTable);
    
    document.getElementById('trainersSearch').addEventListener('input', debounce(filterTrainersTable, 300));
    document.getElementById('trainersSpecialtyFilter').addEventListener('change', filterTrainersTable);
    document.getElementById('trainersStatusFilter').addEventListener('change', filterTrainersTable);
    
    document.getElementById('techniciansSearch').addEventListener('input', debounce(filterTechniciansTable, 300));
    document.getElementById('techniciansSpecialtyFilter').addEventListener('change', filterTechniciansTable);
    document.getElementById('techniciansStatusFilter').addEventListener('change', filterTechniciansTable);
    
    document.getElementById('usersSearch').addEventListener('input', debounce(filterUsersTable, 300));
    document.getElementById('usersRoleFilter').addEventListener('change', filterUsersTable);
    document.getElementById('usersStatusFilter').addEventListener('change', filterUsersTable);
}

async function loadFilterOptions() {
    try {
        // Load appliances for parts filter
        const appliancesResponse = await fetch('/api/appliances');
        const appliances = await appliancesResponse.json();
        
        const partsApplianceFilter = document.getElementById('partsApplianceFilter');
        if (partsApplianceFilter) {
            appliances.forEach(appliance => {
                const option = document.createElement('option');
                option.value = appliance.name;
                option.textContent = appliance.name;
                partsApplianceFilter.appendChild(option);
            });
        }
        
        // Load brands for appliances filter
        const brandsResponse = await fetch('/api/brands');
        if (brandsResponse.ok) {
            const brands = await brandsResponse.json();
            
            const appliancesBrandFilter = document.getElementById('appliancesBrandFilter');
            if (appliancesBrandFilter) {
                brands.forEach(brand => {
                    const option = document.createElement('option');
                    option.value = brand.name;
                    option.textContent = brand.name;
                    appliancesBrandFilter.appendChild(option);
                });
            }
        }
    } catch (error) {
        console.error('Error loading filter options:', error);
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function filterPartsTable() {
    const search = document.getElementById('partsSearch').value.toLowerCase();
    const appliance = document.getElementById('partsApplianceFilter').value;
    const availability = document.getElementById('partsAvailabilityFilter').value;
    
    const rows = document.querySelectorAll('#partsTableContainer tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const applianceCell = row.cells[2].textContent;
        const availabilityCell = row.cells[3].textContent;
        
        const matchesSearch = !search || text.includes(search);
        const matchesAppliance = !appliance || applianceCell.includes(appliance);
        const matchesAvailability = !availability || 
            (availability === '1' && availabilityCell.includes('Available')) ||
            (availability === '0' && availabilityCell.includes('Not Available'));
        
        row.style.display = matchesSearch && matchesAppliance && matchesAvailability ? '' : 'none';
    });
}

function filterAppliancesTable() {
    const search = document.getElementById('appliancesSearch').value.toLowerCase();
    const brand = document.getElementById('appliancesBrandFilter').value;
    const status = document.getElementById('appliancesStatusFilter').value;
    
    const rows = document.querySelectorAll('#appliancesTableContainer tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const brandCell = row.cells[1].textContent;
        const statusCell = row.cells[3].textContent;
        
        const matchesSearch = !search || text.includes(search);
        const matchesBrand = !brand || brandCell.includes(brand);
        const matchesStatus = !status || statusCell.includes(status);
        
        row.style.display = matchesSearch && matchesBrand && matchesStatus ? '' : 'none';
    });
}

function filterTrainersTable() {
    const search = document.getElementById('trainersSearch').value.toLowerCase();
    const specialty = document.getElementById('trainersSpecialtyFilter').value;
    const status = document.getElementById('trainersStatusFilter').value;
    
    const rows = document.querySelectorAll('#trainersTableContainer tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const specialtyCell = row.cells[2].textContent;
        const statusCell = row.cells[4].textContent;
        
        const matchesSearch = !search || text.includes(search);
        const matchesSpecialty = !specialty || specialtyCell.includes(specialty);
        const matchesStatus = !status || statusCell.includes(status);
        
        row.style.display = matchesSearch && matchesSpecialty && matchesStatus ? '' : 'none';
    });
}

function filterTechniciansTable() {
    const search = document.getElementById('techniciansSearch').value.toLowerCase();
    const specialty = document.getElementById('techniciansSpecialtyFilter').value;
    const status = document.getElementById('techniciansStatusFilter').value;
    
    const rows = document.querySelectorAll('#techniciansTableContainer tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const specialtyCell = row.cells[2].textContent;
        const statusCell = row.cells[4].textContent;
        
        const matchesSearch = !search || text.includes(search);
        const matchesSpecialty = !specialty || specialtyCell.includes(specialty);
        const matchesStatus = !status || statusCell.includes(status);
        
        row.style.display = matchesSearch && matchesSpecialty && matchesStatus ? '' : 'none';
    });
}

function filterUsersTable() {
    const search = document.getElementById('usersSearch').value.toLowerCase();
    const role = document.getElementById('usersRoleFilter').value;
    const status = document.getElementById('usersStatusFilter').value;
    
    const rows = document.querySelectorAll('#usersTableContainer tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const roleCell = row.cells[2].textContent.toLowerCase();
        
        const matchesSearch = !search || text.includes(search);
        const matchesRole = !role || roleCell.includes(role);
        
        row.style.display = matchesSearch && matchesRole ? '' : 'none';
    });
}

// Parts Management
async function loadPartsTable() {
    const container = document.getElementById('partsTableContainer');
    if (!container) {
        console.error('ERROR: partsTableContainer element not found in DOM');
        return;
    }
    
    container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="mt-3 text-muted">Loading parts data...</p></div>';
    console.log('Fetching parts from /api/parts...');
    
    try {
        const response = await fetch('/api/parts');
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const parts = await response.json();
        console.log('Parts received:', parts);
        console.log('Parts count:', parts.length);
        
        if (!Array.isArray(parts)) {
            throw new Error('Response is not an array');
        }
        
        if (parts.length === 0) {
            container.innerHTML = '<div class="alert alert-info">No parts found in inventory.</div>';
            return;
        }
        
        const tableHtml = parts.map(part => `
            <tr>
                <td><strong>${part.partNumber || part.part_number || 'N/A'}</strong></td>
                <td>${part.name || 'N/A'}</td>
                <td><span class="badge bg-primary">${part.applianceType || 'N/A'}</span></td>
                <td><span class="badge ${part.availability ? 'bg-success' : 'bg-secondary'}">${part.availability ? 'Available' : 'Not Available'}</span></td>
                <td>${part.price ? 'UGX ' + parseFloat(part.price).toLocaleString() : 'N/A'}</td>
                <td>
                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editPart(${part.id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deletePart(${part.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
        
        container.innerHTML = `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Part Number</th>
                            <th>Name</th>
                            <th>Appliance</th>
                            <th>Availability</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>${tableHtml}</tbody>
                </table>
            </div>
        `;
        console.log('Table loaded successfully');
    } catch (error) {
        console.error('ERROR loading parts:', error);
        container.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle me-2"></i>Error: ${error.message}</div>`;
        showNotification('Error loading parts data', 'error');
    }
}

async function handlePartSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const partId = formData.get('id');
    const isEdit = !!partId;
    
    try {
        const response = await fetch(`/api/parts${isEdit ? `/${partId}` : ''}`, {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        if (response.ok) {
            showNotification(`Part ${isEdit ? 'updated' : 'created'} successfully!`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('partModal')).hide();
            loadPartsTable();
            resetPartForm();
        } else {
            const error = await response.json();
            let errorMessage = 'Unknown error';
            if (error.errors) {
                errorMessage = Object.values(error.errors).flat().join(', ');
            } else if (error.message) {
                errorMessage = error.message;
            }
            showNotification('Error saving part: ' + errorMessage, 'error');
        }
    } catch (error) {
        console.error('Error saving part:', error);
        showNotification('Error saving part: Network or server error', 'error');
    }
}

async function editPart(id) {
    try {
        const response = await fetch(`/api/parts/${id}`);
        const part = await response.json();
        
        // Populate form fields
        document.getElementById('partId').value = part.id;
        document.getElementById('partNumber').value = part.part_number || part.partNumber;
        document.getElementById('partName').value = part.name;
        document.getElementById('partAppliance').value = part.appliance_id;
        document.getElementById('partLocation').value = part.location || '';
        document.getElementById('partDescription').value = part.description || '';
        document.getElementById('partAvailability').value = part.availability ? '1' : '0';
        document.getElementById('partPrice').value = part.price || '';
        
        // Update modal title
        document.getElementById('partModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Part';
        
        // Show modal
        new bootstrap.Modal(document.getElementById('partModal')).show();
    } catch (error) {
        console.error('Error loading part:', error);
        showNotification('Error loading part data', 'error');
    }
}

async function deletePart(id) {
    if (!confirm('Are you sure you want to delete this part?')) return;
    
    try {
        const response = await fetch(`/api/parts/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            showNotification('Part deleted successfully!', 'success');
            loadPartsTable();
        } else {
            showNotification('Error deleting part', 'error');
        }
    } catch (error) {
        console.error('Error deleting part:', error);
        showNotification('Error deleting part', 'error');
    }
}

function resetPartForm() {
    document.getElementById('partForm').reset();
    document.getElementById('partId').value = '';
    document.getElementById('currentImage').innerHTML = '';
}

// Appliances Management
async function loadAppliancesTable() {
    try {
        const response = await fetch('/api/appliances');
        const appliances = await response.json();
        
        const tableHtml = `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Status</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${appliances.map(appliance => `
                            <tr>
                                <td><strong>${appliance.name}</strong></td>
                                <td>${appliance.brand || 'N/A'}</td>
                                <td>${appliance.model || 'N/A'}</td>
                                <td><span class="badge ${getStatusBadgeClass(appliance.status)}">${appliance.status}</span></td>
                                <td>${appliance.price ? 'UGX ' + parseFloat(appliance.price).toLocaleString() : 'N/A'}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-success me-1" onclick="editAppliance(${appliance.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteAppliance(${appliance.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        
        document.getElementById('appliancesTableContainer').innerHTML = tableHtml;
    } catch (error) {
        console.error('Error loading appliances:', error);
        showNotification('Error loading appliances data', 'error');
    }
}

function getStatusBadgeClass(status) {
    switch(status) {
        case 'Available': return 'bg-success';
        case 'In Use': return 'bg-info';
        case 'Maintenance': return 'bg-warning';
        case 'Discontinued': return 'bg-secondary';
        default: return 'bg-secondary';
    }
}

async function handleApplianceSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const applianceId = formData.get('id');
    const isEdit = !!applianceId;
    
    if (isEdit) {
        formData.append('_method', 'PUT');
    }
    
    try {
        const response = await fetch(`/api/appliances${isEdit ? `/${applianceId}` : ''}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok && result.success !== false) {
            showNotification(`Appliance ${isEdit ? 'updated' : 'created'} successfully!`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('applianceModal')).hide();
            loadAppliancesTable();
            resetApplianceForm();
        } else {
            let errorMessage = 'Unknown error';
            if (result.errors) {
                errorMessage = Object.values(result.errors).flat().join(', ');
            } else if (result.message) {
                errorMessage = result.message;
            }
            showNotification('Error: ' + errorMessage, 'error');
        }
    } catch (error) {
        console.error('Error saving appliance:', error);
        showNotification('Network error. Please try again.', 'error');
    }
}

async function editAppliance(id) {
    try {
        const response = await fetch(`/api/appliances/${id}`);
        const appliance = await response.json();
        
        document.getElementById('applianceId').value = appliance.id;
        document.getElementById('applianceName').value = appliance.name || '';
        document.getElementById('applianceBrand').value = appliance.brand_id || '';
        document.getElementById('applianceModel').value = appliance.model || '';
        document.getElementById('applianceSKU').value = appliance.sku || '';
        document.getElementById('applianceStatus').value = appliance.status || 'Available';
        document.getElementById('applianceDescription').value = appliance.description || '';
        document.getElementById('appliancePower').value = appliance.power || '';
        document.getElementById('applianceVoltage').value = appliance.voltage || '';
        document.getElementById('applianceFrequency').value = appliance.frequency || '';
        document.getElementById('applianceCapacity').value = appliance.capacity || '';
        document.getElementById('applianceWeight').value = appliance.weight || '';
        document.getElementById('applianceDimensions').value = appliance.dimensions || '';
        document.getElementById('applianceColor').value = appliance.color || '';
        document.getElementById('appliancePrice').value = appliance.price || '';
        document.getElementById('applianceCostPrice').value = appliance.cost_price || '';
        document.getElementById('applianceQuantity').value = appliance.quantity || 0;
        document.getElementById('applianceWarranty').value = appliance.warranty || '';
        document.getElementById('applianceLocation').value = appliance.location || '';
        document.getElementById('applianceFeatures').value = appliance.features || '';
        document.getElementById('applianceCertifications').value = appliance.certifications || '';
        document.getElementById('applianceEnergyRating').value = appliance.energy_rating || '';
        document.getElementById('applianceCountryOrigin').value = appliance.country_origin || '';
        document.getElementById('applianceSupplier').value = appliance.supplier_name || '';
        document.getElementById('applianceSupplierContact').value = appliance.supplier_contact || '';
        document.getElementById('applianceLastMaintenance').value = appliance.last_maintenance || '';
        document.getElementById('applianceNextMaintenance').value = appliance.next_maintenance || '';
        document.getElementById('applianceMaintenanceNotes').value = appliance.maintenance_notes || '';
        document.getElementById('applianceNotes').value = appliance.notes || '';
        
        const imagePreview = document.getElementById('currentApplianceImage');
        if (imagePreview) {
            if (appliance.image) {
                imagePreview.innerHTML = `<img src="/storage/${appliance.image}" class="img-thumbnail" style="max-width: 150px;" alt="Current image">`;
            } else {
                imagePreview.innerHTML = '';
            }
        }
        
        document.getElementById('applianceModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Appliance';
        new bootstrap.Modal(document.getElementById('applianceModal')).show();
    } catch (error) {
        console.error('Error loading appliance:', error);
        showNotification('Failed to load appliance data', 'error');
    }
}

async function deleteAppliance(id) {
    if (!confirm('Are you sure you want to delete this appliance?')) return;
    
    try {
        const response = await fetch(`/api/appliances/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            showNotification('Appliance deleted successfully!', 'success');
            loadAppliancesTable();
        } else {
            showNotification('Error deleting appliance', 'error');
        }
    } catch (error) {
        console.error('Error deleting appliance:', error);
        showNotification('Error deleting appliance', 'error');
    }
}

async function editTrainer(id) {
    try {
        const response = await fetch(`/api/trainers/${id}`);
        const trainer = await response.json();
        
        document.getElementById('trainerId').value = trainer.id;
        document.getElementById('trainerFirstName').value = trainer.first_name || trainer.name?.split(' ')[0] || '';
        document.getElementById('trainerMiddleName').value = trainer.middle_name || '';
        document.getElementById('trainerLastName').value = trainer.last_name || trainer.name?.split(' ').slice(1).join(' ') || '';
        document.getElementById('trainerGender').value = trainer.gender || '';
        document.getElementById('trainerDOB').value = trainer.date_of_birth || '';
        document.getElementById('trainerNationality').value = trainer.nationality || '';
        document.getElementById('trainerIDNumber').value = trainer.id_number || '';
        document.getElementById('trainerEmail').value = trainer.email;
        document.getElementById('trainerPhone').value = trainer.phone;
        document.getElementById('trainerWhatsapp').value = trainer.whatsapp || '';
        document.getElementById('trainerEmergencyContact').value = trainer.emergency_contact || '';
        document.getElementById('trainerEmergencyPhone').value = trainer.emergency_phone || '';
        document.getElementById('trainerCountry').value = trainer.country || '';
        document.getElementById('trainerRegion').value = trainer.region || '';
        document.getElementById('trainerDistrict').value = trainer.district || '';
        document.getElementById('trainerSubCounty').value = trainer.sub_county || '';
        document.getElementById('trainerVillage').value = trainer.village || '';
        document.getElementById('trainerPostalCode').value = trainer.postal_code || '';
        document.getElementById('trainerSpecialty').value = trainer.specialty;
        document.getElementById('trainerExperience').value = trainer.experience;
        document.getElementById('trainerLicenseNumber').value = trainer.license_number || '';
        document.getElementById('trainerHourlyRate').value = trainer.hourly_rate || '';
        document.getElementById('trainerDailyRate').value = trainer.daily_rate || '';
        document.getElementById('trainerStatus').value = trainer.status || 'Active';
        document.getElementById('trainerSkills').value = trainer.skills || '';
        document.getElementById('trainerQualifications').value = trainer.qualifications || '';
        document.getElementById('trainerCertifications').value = trainer.certifications || '';
        document.getElementById('trainerLanguages').value = trainer.languages || '';
        document.getElementById('trainerSessionsCount').value = trainer.sessions_count || '';
        document.getElementById('trainerStudentsCount').value = trainer.students_count || '';
        document.getElementById('trainerRating').value = trainer.rating || '';
        document.getElementById('trainerAvailability').value = trainer.weekly_hours || '';
        document.getElementById('trainerNotes').value = trainer.notes || '';
        
        if (trainer.profile_picture || trainer.image) {
            document.getElementById('currentTrainerImage').innerHTML = `<img src="/storage/${trainer.profile_picture || trainer.image}" class="img-thumbnail mt-2" style="max-width: 150px;">`;
        }
        
        document.getElementById('trainerModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Trainer';
        new bootstrap.Modal(document.getElementById('trainerModal')).show();
    } catch (error) {
        console.error('Error loading trainer:', error);
        showNotification('Error loading trainer data', 'error');
    }
}

async function deleteTrainer(id) {
    if (!confirm('Are you sure you want to delete this trainer?')) return;
    
    try {
        const response = await fetch(`/api/trainers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            showNotification('Trainer deleted successfully!', 'success');
            loadTrainersTable();
        } else {
            showNotification('Error deleting trainer', 'error');
        }
    } catch (error) {
        console.error('Error deleting trainer:', error);
        showNotification('Error deleting trainer', 'error');
    }
}

async function editTechnician(id) {
    try {
        const response = await fetch(`/api/technicians/${id}`);
        const tech = await response.json();
        
        document.getElementById('technicianId').value = tech.id;
        document.getElementById('technicianTitle').value = tech.title || '';
        document.getElementById('technicianFirstName').value = tech.first_name || tech.name?.split(' ')[0] || '';
        document.getElementById('technicianMiddleName').value = tech.middle_name || '';
        document.getElementById('technicianLastName').value = tech.last_name || tech.name?.split(' ').slice(1).join(' ') || '';
        document.getElementById('technicianGender').value = tech.gender || '';
        document.getElementById('technicianDOB').value = tech.date_of_birth || '';
        document.getElementById('technicianNationality').value = tech.nationality || '';
        document.getElementById('technicianIDNumber').value = tech.id_number || '';
        document.getElementById('technicianEmail').value = tech.email;
        document.getElementById('technicianPhone1').value = tech.phone_1 || tech.phone;
        document.getElementById('technicianPhone2').value = tech.phone_2 || '';
        document.getElementById('technicianWhatsapp').value = tech.whatsapp || '';
        document.getElementById('technicianEmergencyContact').value = tech.emergency_contact || '';
        document.getElementById('technicianEmergencyPhone').value = tech.emergency_phone || '';
        document.getElementById('technicianCountry').value = tech.country || '';
        document.getElementById('technicianRegion').value = tech.region || '';
        document.getElementById('technicianDistrict').value = tech.district || '';
        document.getElementById('technicianSubCounty').value = tech.sub_county || '';
        document.getElementById('technicianParish').value = tech.parish || '';
        document.getElementById('technicianVillage').value = tech.village || '';
        document.getElementById('technicianPostalCode').value = tech.postal_code || '';
        document.getElementById('technicianSpecialty').value = tech.specialty;
        document.getElementById('technicianSubSpecialty').value = tech.sub_specialty || '';
        document.getElementById('technicianLicenseNumber').value = tech.license_number || tech.license || '';
        document.getElementById('technicianLicenseExpiry').value = tech.license_expiry || '';
        document.getElementById('technicianExperience').value = tech.experience;
        document.getElementById('technicianHourlyRate').value = tech.hourly_rate || '';
        document.getElementById('technicianDailyRate').value = tech.daily_rate || '';
        document.getElementById('technicianStatus').value = tech.status;
        document.getElementById('technicianEmploymentType').value = tech.employment_type || '';
        document.getElementById('technicianStartDate').value = tech.start_date || '';
        document.getElementById('technicianSkills').value = tech.skills || '';
        document.getElementById('technicianCertifications').value = tech.certifications || '';
        document.getElementById('technicianTraining').value = tech.training || '';
        document.getElementById('technicianLanguages').value = tech.languages || '';
        document.getElementById('technicianOwnTools').value = tech.own_tools || '';
        document.getElementById('technicianVehicle').value = tech.has_vehicle || '';
        document.getElementById('technicianVehicleType').value = tech.vehicle_type || '';
        document.getElementById('technicianEquipmentList').value = tech.equipment_list || '';
        document.getElementById('technicianServiceAreas').value = tech.service_areas || '';
        document.getElementById('technicianNotes').value = tech.notes || '';
        
        if (tech.profile_picture || tech.image) {
            document.getElementById('currentTechnicianImage').innerHTML = `<img src="/storage/${tech.profile_picture || tech.image}" class="img-thumbnail mt-2" style="max-width: 150px;">`;
        }
        
        document.getElementById('technicianModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Technician';
        new bootstrap.Modal(document.getElementById('technicianModal')).show();
    } catch (error) {
        console.error('Error loading technician:', error);
        showNotification('Error loading technician data', 'error');
    }
}

async function deleteTechnician(id) {
    if (!confirm('Are you sure you want to delete this technician?')) return;
    
    try {
        const response = await fetch(`/api/technicians/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            showNotification('Technician deleted successfully!', 'success');
            loadTechniciansTable();
        } else {
            showNotification('Error deleting technician', 'error');
        }
    } catch (error) {
        console.error('Error deleting technician:', error);
        showNotification('Error deleting technician', 'error');
    }
}

async function editUser(id) {
    try {
        const response = await fetch(`/api/users/${id}`);
        const user = await response.json();
        
        document.getElementById('userId').value = user.id;
        document.getElementById('userName').value = user.name;
        document.getElementById('userEmail').value = user.email;
        document.getElementById('userRole').value = user.role;
        document.getElementById('userTrainerId').value = user.trainer_id || '';
        document.getElementById('userTechnicianId').value = user.technician_id || '';
        
        // Clear password field for edit and make it optional
        document.getElementById('userPassword').value = '';
        document.getElementById('userPassword').removeAttribute('required');
        
        document.getElementById('userModalLabel').innerHTML = '<i class="fas fa-edit me-2"></i>Edit User';
        new bootstrap.Modal(document.getElementById('userModal')).show();
    } catch (error) {
        console.error('Error loading user:', error);
        showNotification('Error loading user data', 'error');
    }
}

async function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) return;
    
    try {
        const response = await fetch(`/api/users/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            showNotification('User deleted successfully!', 'success');
            loadUsersTable();
        } else {
            showNotification('Error deleting user', 'error');
        }
    } catch (error) {
        console.error('Error deleting user:', error);
        showNotification('Error deleting user', 'error');
    }
}

// Trainers Management
async function loadTrainersTable() {
    try {
        const response = await fetch('/api/trainers');
        const trainers = await response.json();
        
        const tableHtml = `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Specialty</th>
                            <th>Experience</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${trainers.map(trainer => `
                            <tr>
                                <td><strong>${trainer.name}</strong></td>
                                <td>${trainer.email}</td>
                                <td><span class="badge bg-info">${trainer.specialty}</span></td>
                                <td>${trainer.experience} years</td>
                                <td><span class="text-success fw-bold">UGX ${parseFloat(trainer.hourly_rate || 0).toLocaleString()}</span></td>
                                <td><span class="badge bg-primary">${trainer.quantity || trainer.available_stock || 0}</span></td>
                                <td><span class="badge ${trainer.status === 'Active' ? 'bg-success' : 'bg-secondary'}">${trainer.status}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger me-1" onclick="editTrainer(${trainer.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteTrainer(${trainer.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        
        document.getElementById('trainersTableContainer').innerHTML = tableHtml;
    } catch (error) {
        console.error('Error loading trainers:', error);
        showNotification('Error loading trainers data', 'error');
    }
}

async function handleTrainerSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const trainerId = formData.get('id');
    const isEdit = !!trainerId;
    
    try {
        const response = await fetch(`/api/trainers${isEdit ? `/${trainerId}` : ''}`, {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        if (response.ok) {
            showNotification(`Trainer ${isEdit ? 'updated' : 'created'} successfully!`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('trainerModal')).hide();
            loadTrainersTable();
            resetTrainerForm();
        } else {
            const error = await response.json();
            showNotification('Error saving trainer: ' + (error.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error saving trainer:', error);
        showNotification('Error saving trainer', 'error');
    }
}

function resetTrainerForm() {
    document.getElementById('trainerForm').reset();
    document.getElementById('trainerId').value = '';
    document.getElementById('currentTrainerImage').innerHTML = '';
}

// Technicians Management
async function loadTechniciansTable() {
    try {
        const response = await fetch('/api/technicians');
        const technicians = await response.json();
        
        const tableHtml = `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Specialty</th>
                            <th>License</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${technicians.map(tech => `
                            <tr>
                                <td><strong>${tech.name}</strong></td>
                                <td>${tech.email}</td>
                                <td><span class="badge bg-primary">${tech.specialty}</span></td>
                                <td>${tech.license || 'N/A'}</td>
                                <td><span class="text-success fw-bold">UGX ${parseFloat(tech.hourly_rate || tech.rate || 0).toLocaleString()}</span></td>
                                <td><span class="badge bg-primary">${tech.quantity || tech.available_stock || 0}</span></td>
                                <td><span class="badge ${getStatusBadgeClass(tech.status)}">${tech.status}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info me-1" onclick="editTechnician(${tech.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteTechnician(${tech.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        
        document.getElementById('techniciansTableContainer').innerHTML = tableHtml;
    } catch (error) {
        console.error('Error loading technicians:', error);
        showNotification('Error loading technicians data', 'error');
    }
}

async function handleTechnicianSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const technicianId = formData.get('id');
    const isEdit = !!technicianId;
    
    try {
        const response = await fetch(`/api/technicians${isEdit ? `/${technicianId}` : ''}`, {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        if (response.ok) {
            showNotification(`Technician ${isEdit ? 'updated' : 'created'} successfully!`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('technicianModal')).hide();
            loadTechniciansTable();
            resetTechnicianForm();
        } else {
            const error = await response.json();
            showNotification('Error saving technician: ' + (error.message || 'Unknown error'), 'error');
        }
    } catch (error) {
        console.error('Error saving technician:', error);
        showNotification('Error saving technician', 'error');
    }
}

function resetTechnicianForm() {
    document.getElementById('technicianForm').reset();
    document.getElementById('technicianId').value = '';
    document.getElementById('currentTechnicianImage').innerHTML = '';
}

// Users Management
async function loadUsersTable() {
    try {
        const response = await fetch('/api/users');
        const result = await response.json();
        const users = result.data || result;
        
        const tableHtml = `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${users.map(user => `
                            <tr>
                                <td><strong>${user.name}</strong></td>
                                <td>${user.email}</td>
                                <td><span class="badge ${getRoleBadgeClass(user.role)}">${user.role.toUpperCase()}</span></td>
                                <td>${new Date(user.created_at).toLocaleDateString()}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-dark me-1" onclick="editUser(${user.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        
        document.getElementById('usersTableContainer').innerHTML = tableHtml;
    } catch (error) {
        console.error('Error loading users:', error);
        showNotification('Error loading users data', 'error');
    }
}

function getRoleBadgeClass(role) {
    switch(role) {
        case 'admin': return 'bg-danger';
        case 'manager': return 'bg-warning text-dark';
        case 'trainer': return 'bg-info';
        case 'technician': return 'bg-primary';
        default: return 'bg-secondary';
    }
}

async function handleUserSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const userId = formData.get('id');
    const isEdit = !!userId;
    
    const data = {
        name: formData.get('name') || '',
        email: formData.get('email') || '',
        role: formData.get('role') || ''
    };
    
    if (formData.get('trainer_id')) {
        data.trainer_id = formData.get('trainer_id');
    }
    
    if (formData.get('technician_id')) {
        data.technician_id = formData.get('technician_id');
    }
    
    if (formData.get('password')) {
        data.password = formData.get('password');
    }
    
    try {
        const response = await fetch(`/api/users${isEdit ? `/${userId}` : ''}`, {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            showNotification(`User ${isEdit ? 'updated' : 'created'} successfully!`, 'success');
            bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
            loadUsersTable();
            resetUserForm();
        } else {
            const error = await response.json();
            let errorMessage = 'Unknown error';
            if (error.errors) {
                errorMessage = Object.values(error.errors).flat().join(', ');
            } else if (error.message) {
                errorMessage = error.message;
            }
            showNotification('Error saving user: ' + errorMessage, 'error');
        }
    } catch (error) {
        console.error('Error saving user:', error);
        showNotification('Error saving user: Network or server error', 'error');
    }
}

function resetApplianceForm() {
    document.getElementById('applianceForm').reset();
    document.getElementById('applianceId').value = '';
    const imagePreview = document.getElementById('currentApplianceImage');
    if (imagePreview) {
        imagePreview.innerHTML = '';
    }
}

function resetUserForm() {
    document.getElementById('userForm').reset();
    document.getElementById('userId').value = '';
    document.getElementById('currentUserImage').innerHTML = '';
    // Re-add required attribute for new user
    document.getElementById('userPassword').setAttribute('required', 'required');
}

// Utility functions
function loadAllData() {
    loadPartsTable();
    loadAppliancesTable();
    loadTrainersTable();
    loadTechniciansTable();
    loadUsersTable();
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Export functions for global access
window.editPart = editPart;
window.deletePart = deletePart;
window.resetPartForm = resetPartForm;
window.editAppliance = editAppliance;
window.deleteAppliance = deleteAppliance;
window.resetApplianceForm = resetApplianceForm;
window.editTrainer = editTrainer;
window.deleteTrainer = deleteTrainer;
window.resetTrainerForm = resetTrainerForm;
window.editTechnician = editTechnician;
window.deleteTechnician = deleteTechnician;
window.resetTechnicianForm = resetTechnicianForm;
window.editUser = editUser;
window.deleteUser = deleteUser;
window.resetUserForm = resetUserForm;

// Filter functions
function clearPartsFilters() {
    document.getElementById('partsSearch').value = '';
    document.getElementById('partsApplianceFilter').value = '';
    document.getElementById('partsAvailabilityFilter').value = '';
    loadPartsTable();
}

function clearAppliancesFilters() {
    document.getElementById('appliancesSearch').value = '';
    document.getElementById('appliancesBrandFilter').value = '';
    document.getElementById('appliancesStatusFilter').value = '';
    loadAppliancesTable();
}

function clearTrainersFilters() {
    document.getElementById('trainersSearch').value = '';
    document.getElementById('trainersSpecialtyFilter').value = '';
    document.getElementById('trainersStatusFilter').value = '';
    loadTrainersTable();
}

function clearTechniciansFilters() {
    document.getElementById('techniciansSearch').value = '';
    document.getElementById('techniciansSpecialtyFilter').value = '';
    document.getElementById('techniciansStatusFilter').value = '';
    loadTechniciansTable();
}

function clearUsersFilters() {
    document.getElementById('usersSearch').value = '';
    document.getElementById('usersRoleFilter').value = '';
    document.getElementById('usersStatusFilter').value = '';
    loadUsersTable();
}

window.clearPartsFilters = clearPartsFilters;
window.clearAppliancesFilters = clearAppliancesFilters;
window.clearTrainersFilters = clearTrainersFilters;
window.clearTechniciansFilters = clearTechniciansFilters;
window.clearUsersFilters = clearUsersFilters;