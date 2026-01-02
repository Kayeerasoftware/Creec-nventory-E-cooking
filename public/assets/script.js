// Global variables
let inventoryData = [];
let filteredData = [];
let currentView = 'grid';
let trainersData = [];
let filteredTrainersData = [];
let currentTrainerView = 'grid';
let techniciansData = [];
let filteredTechniciansData = [];
let currentTechnicianView = 'grid';
let appliancesData = [];
let currentApplianceView = 'grid';
let chatId = null;
let userName = 'User'; // Default user name
let messageInterval = null;

// DOM Elements
const inventoryGrid = document.getElementById('inventoryGrid');
const listTableBody = document.getElementById('listTableBody');
const searchInput = document.getElementById('searchInput');
const applianceFilter = document.getElementById('applianceFilter');
const brandFilter = document.getElementById('brandFilter');
const availabilityFilter = document.getElementById('availabilityFilter');
const viewFilter = document.getElementById('viewFilter');
const partModal = new bootstrap.Modal(document.getElementById('partModal'));

// Trainer elements
const trainersGrid = document.getElementById('trainersGrid');
const trainersTableBody = document.getElementById('trainersTableBody');
const trainerSearchInput = document.getElementById('trainerSearchInput');
const trainerSpecialtyFilter = document.getElementById('trainerSpecialtyFilter');
const trainerSortFilter = document.getElementById('trainerSortFilter');
const trainerViewFilter = document.getElementById('trainerViewFilter');
const trainerModal = new bootstrap.Modal(document.getElementById('trainerModal'));

// Technician elements
const techniciansGrid = document.getElementById('techniciansGrid');
const techniciansTableBody = document.getElementById('techniciansTableBody');
const technicianSearchInput = document.getElementById('technicianSearchInput');
const technicianSpecialtyFilter = document.getElementById('technicianSpecialtyFilter');
const technicianStatusFilter = document.getElementById('technicianStatusFilter');
const technicianSortFilter = document.getElementById('technicianSortFilter');
const technicianViewFilter = document.getElementById('technicianViewFilter');
const technicianViewModal = new bootstrap.Modal(document.getElementById('technicianViewModal'));

// Appliance elements
const appliancesGrid = document.getElementById('appliancesGrid');
const appliancesTableBody = document.getElementById('appliancesTableBody');
const applianceSearchInput = document.getElementById('applianceSearchInput');
const applianceStatusFilter = document.getElementById('applianceStatusFilter');
const applianceBrandFilter = document.getElementById('applianceBrandFilter');
const applianceSortFilter = document.getElementById('applianceSortFilter');
const applianceViewFilter = document.getElementById('applianceViewFilter');

const chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
const chatButton = document.getElementById('chatButton');
const chatMessages = document.getElementById('chatMessages');
const chatMessageInput = document.getElementById('chatMessageInput');
const sendMessageBtn = document.getElementById('sendMessageBtn');
const attachBtn = document.getElementById('attachBtn');
const fileInput = document.getElementById('fileInput');
const emojiBtn = document.getElementById('emojiBtn');
const emojiPicker = document.getElementById('emojiPicker');
const voiceBtn = document.getElementById('voiceBtn');
const voiceRecorder = document.getElementById('voiceRecorder');
const recordBtn = document.getElementById('recordBtn');
const stopRecordBtn = document.getElementById('stopRecordBtn');
const audioPreview = document.getElementById('audioPreview');
const gifBtn = document.getElementById('gifBtn');
const gifPicker = document.getElementById('gifPicker');
const gifSearch = document.getElementById('gifSearch');
const gifResults = document.getElementById('gifResults');
const locationBtn = document.getElementById('locationBtn');
const contactBtn = document.getElementById('contactBtn');
const chatSearchInput = document.getElementById('searchInput');
const searchBtn = document.getElementById('searchBtn');

// Modal elements
const modalPartImage = document.getElementById('modalPartImage');
const imagePlaceholder = document.getElementById('imagePlaceholder');
const modalPartName = document.getElementById('modalPartName');
const modalPartNumber = document.getElementById('modalPartNumber');
const modalApplianceType = document.getElementById('modalApplianceType');
const modalLocation = document.getElementById('modalLocation');
const modalBrands = document.getElementById('modalBrands');
const modalAppliances = document.getElementById('modalAppliances');
const modalDescription = document.getElementById('modalDescription');
const modalAvailability = document.getElementById('modalAvailability');
const modalComments = document.getElementById('modalComments');

// Cache flags
let dataLoaded = {
    inventory: false,
    appliances: false,
    trainers: false,
    technicians: false,
    reports: false
};

// Initialize
async function init() {
    setupEventListeners();
    updateCurrentDate();
    adjustSidebarWidth();
    createFooter();
    styleNavbar();
    document.getElementById('inventory').style.display = 'none';
    document.getElementById('appliances').style.display = 'none';
    document.getElementById('trainers').style.display = 'none';
    document.getElementById('qualified-technicians').style.display = 'none';
    document.getElementById('reports').style.display = 'none';
    window.scrollTo(0, 0);
    
    // Load only dashboard data in parallel
    Promise.all([
        loadInventoryData(),
        loadStatistics(),
        loadTrainerStatistics()
    ]).then(() => {
        renderCharts();
    });
}

// Load technicians data from API
async function loadTechniciansData() {
    if (dataLoaded.technicians) return;
    try {
        const response = await fetch('/api/technicians');
        techniciansData = await response.json();
        filteredTechniciansData = [...techniciansData];
        dataLoaded.technicians = true;
        updateTechnicianStatistics();
        renderTechnicians();
    } catch (error) {
        console.error('Error loading technicians data:', error);
    }
}

// Update technician statistics cards
function updateTechnicianStatistics() {
    const total = techniciansData.length;
    const available = techniciansData.filter(t => t.status === 'Available').length;
    const busy = techniciansData.filter(t => t.status === 'Busy').length;
    const unavailable = techniciansData.filter(t => t.status === 'Unavailable').length;
    
    document.getElementById('technicianStatsTotal').textContent = total;
    document.getElementById('technicianStatsAvailable').textContent = available;
    document.getElementById('technicianStatsBusy').textContent = busy;
    document.getElementById('technicianStatsUnavailable').textContent = unavailable;
}

// Load data from API
async function loadInventoryData() {
    if (dataLoaded.inventory) return;
    try {
        const response = await fetch('/api/parts');
        inventoryData = await response.json();
        filteredData = [...inventoryData];
        dataLoaded.inventory = true;
        renderInventory();
    } catch (error) {
        console.error('Error loading inventory data:', error);
    }
}

// Load appliances data from API
async function loadAppliancesData() {
    if (dataLoaded.appliances) return;
    try {
        const response = await fetch('/api/appliances');
        appliancesData = await response.json();
        dataLoaded.appliances = true;
        renderAppliances();
    } catch (error) {
        console.error('Error loading appliances data:', error);
    }
}

// Render appliances
function renderAppliances() {
    if (currentApplianceView === 'grid') {
        renderAppliancesGridView();
    } else {
        renderAppliancesListView();
    }
}

// Render appliances grid view
function renderAppliancesGridView() {
    appliancesGrid.innerHTML = '';
    appliancesData.forEach(appliance => {
        const card = createApplianceCard(appliance);
        appliancesGrid.appendChild(card);
    });
}

// Render appliances list view
function renderAppliancesListView() {
    appliancesTableBody.innerHTML = '';
    appliancesData.forEach(appliance => {
        const row = createApplianceListRow(appliance);
        appliancesTableBody.appendChild(row);
    });
}

// Create appliance card
function createApplianceCard(appliance) {
    const card = document.createElement('div');
    card.className = 'col-lg-4 col-md-6 col-sm-12 appliance-card';
    card.dataset.name = appliance.name.toLowerCase();
    card.dataset.status = appliance.status;
    card.dataset.brand = (appliance.brand || '').toLowerCase();

    const statusClass = appliance.status === 'Available' ? 'success' : (appliance.status === 'In Use' ? 'info' : 'danger');
    const statusIcon = appliance.status === 'Available' ? 'check-circle' : (appliance.status === 'In Use' ? 'play-circle' : 'times-circle');
    const statusText = appliance.status === 'Available' ? 'Available' : (appliance.status === 'In Use' ? 'In Use' : 'Not Available');

    card.innerHTML = `
        <div class="card p-4 h-100" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-${appliance.color || 'primary'} rounded p-3 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-${appliance.icon || 'tv'} text-white"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="h5 mb-1">${appliance.name}</h4>
                    <p class="text-muted small mb-0">${appliance.brand || 'No Brand'}</p>
                </div>
                <span class="badge bg-${statusClass}"><i class="fas fa-${statusIcon} me-1"></i>${statusText}</span>
            </div>
            <div class="text-muted small">
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-tag me-2"></i>
                    <span>Model: ${appliance.model || 'N/A'}</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-bolt me-2"></i>
                    <span>Power: ${appliance.power || 'N/A'}</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-barcode me-2"></i>
                    <span>SKU: ${appliance.sku || 'N/A'}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar me-2"></i>
                    <span>${new Date(appliance.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</span>
                </div>
            </div>
            ${appliance.description ? `<div class="mt-3 pt-3 border-top"><small class="text-muted">${appliance.description.substring(0, 100)}...</small></div>` : ''}
        </div>
    `;

    card.addEventListener('click', async () => {
        const partsCount = await fetchAppliancePartsCount(appliance.id);
        viewApplianceDetails(appliance.id, appliance.name, appliance.brand || 'N/A', appliance.model || 'N/A',
            appliance.power || 'N/A', appliance.sku || 'N/A', appliance.status, appliance.description || '',
            appliance.icon || 'tools', appliance.color || 'primary', appliance.price || '', appliance.created_at, partsCount);
    });

    return card;
}

// Create appliance list row
function createApplianceListRow(appliance) {
    const row = document.createElement('tr');
    const statusClass = appliance.status === 'Available' ? 'success' : (appliance.status === 'In Use' ? 'info' : 'danger');
    const statusIcon = appliance.status === 'Available' ? 'check-circle' : (appliance.status === 'In Use' ? 'play-circle' : 'times-circle');
    const statusText = appliance.status === 'Available' ? 'Available' : (appliance.status === 'In Use' ? 'In Use' : 'Not Available');

    row.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <div class="bg-${appliance.color || 'primary'} rounded d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                    <i class="fas fa-${appliance.icon || 'tv'} text-white small"></i>
                </div>
                <strong>${appliance.name}</strong>
            </div>
        </td>
        <td>${appliance.brand || 'N/A'}</td>
        <td>${appliance.model || 'N/A'}</td>
        <td>${appliance.power || 'N/A'}</td>
        <td>${appliance.sku || 'N/A'}</td>
        <td>${appliance.price ? 'UGX ' + parseFloat(appliance.price).toLocaleString() : 'N/A'}</td>
        <td><span class="badge bg-${statusClass}"><i class="fas fa-${statusIcon} me-1"></i>${statusText}</span></td>
        <td>${new Date(appliance.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</td>
        <td>
            <button class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> View</button>
        </td>
    `;

    row.querySelector('button').addEventListener('click', async () => {
        const partsCount = await fetchAppliancePartsCount(appliance.id);
        viewApplianceDetails(appliance.id, appliance.name, appliance.brand || 'N/A', appliance.model || 'N/A',
            appliance.power || 'N/A', appliance.sku || 'N/A', appliance.status, appliance.description || '',
            appliance.icon || 'tools', appliance.color || 'primary', appliance.price || '', appliance.created_at, partsCount);
    });

    return row;
}

// Load trainers data from API
async function loadTrainersData() {
    if (dataLoaded.trainers) return;
    try {
        const response = await fetch('/api/trainers');
        trainersData = await response.json();
        filteredTrainersData = [...trainersData];
        dataLoaded.trainers = true;
        renderTrainers();
        populateTrainerFilters();
    } catch (error) {
        console.error('Error loading trainers data:', error);
    }
}

// Render inventory based on current view
function renderInventory() {
    if (currentView === 'grid') {
        renderGridView();
    } else {
        renderListView();
    }
}

// Render trainers based on current view
function renderTrainers() {
    if (currentTrainerView === 'grid') {
        renderTrainersGridView();
    } else {
        renderTrainersListView();
    }
}

// Render grid view
function renderGridView() {
    inventoryGrid.innerHTML = '';

    filteredData.forEach(item => {
        const card = createPartCard(item);
        inventoryGrid.appendChild(card);
    });
}

// Render list view
function renderListView() {
    listTableBody.innerHTML = '';

    filteredData.forEach(item => {
        const row = createListRow(item);
        listTableBody.appendChild(row);
    });
}

// Render trainers grid view
function renderTrainersGridView() {
    trainersGrid.innerHTML = '';

    filteredTrainersData.forEach(trainer => {
        const card = createTrainerCard(trainer);
        trainersGrid.appendChild(card);
    });
}

// Render trainers list view
function renderTrainersListView() {
    trainersTableBody.innerHTML = '';

    filteredTrainersData.forEach(trainer => {
        const row = createTrainerListRow(trainer);
        trainersTableBody.appendChild(row);
    });
}

// Create a part card for grid view
function createPartCard(item) {
    const card = document.createElement('div');
    card.className = 'col-lg-3 col-md-4 col-sm-6';

    // Use badge class from database
    let badgeClass = item.badgeClass || 'bg-secondary';

    card.innerHTML = `
        <div class="card h-100 shadow-sm">
            <div class="card-image" style="height: 200px; overflow: hidden;">
                ${item.image ?
                    `<img src="${item.image}" class="card-img-top" alt="${item.name}" style="height: 100%; object-fit: cover;">` :
                    `<div class="d-flex flex-column align-items-center justify-content-center h-100 bg-light">
                        <i class="fas fa-cogs fa-3x text-muted"></i>
                        <p class="mt-2 text-muted">${item.applianceType}</p>
                    </div>`
                }
            </div>
            <div class="card-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <small class="text-primary fw-bold">${item.partNumber}</small>
                    <span class="badge ${badgeClass}">${item.applianceType}</span>
                </div>
                <h6 class="card-title">${item.name}</h6>
                <p class="card-text text-muted small flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${item.description}</p>
                <div class="mt-auto">
                    <small class="text-muted">${item.brands.join(', ')}</small>
                    <div class="mt-1">
                        <span class="badge ${item.availability ? 'bg-success' : 'bg-secondary'}">
                            ${item.availability ? 'Available' : 'Not Available'}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    `;

    card.addEventListener('click', () => openModal(item));
    return card;
}

// Create a table row for list view
function createListRow(item) {
    const row = document.createElement('tr');

    row.innerHTML = `
        <td><strong>${item.partNumber}</strong></td>
        <td>${item.name}</td>
        <td><span class="badge ${item.badgeClass}">${item.applianceType}</span></td>
        <td>${item.brands.join(', ')}</td>
        <td>
            <span class="badge ${item.availability ? 'bg-success' : 'bg-secondary'}">
                ${item.availability ? 'Available' : 'Not Available'}
            </span>
        </td>
        <td>
            <button class="btn btn-sm btn-outline-primary view-details-btn">
                <i class="fas fa-eye"></i> View
            </button>
        </td>
    `;

    row.querySelector('.view-details-btn').addEventListener('click', (e) => {
        e.stopPropagation();
        openModal(item);
    });

    return row;
}

// Create a trainer card for grid view
function createTrainerCard(trainer) {
    const card = document.createElement('div');
    card.className = 'col-lg-4 col-md-6 col-sm-12 trainer-card';
    card.style.cursor = 'pointer';

    const initials = trainer.name.split(' ').map(n => n[0]).join('').toUpperCase();

    card.innerHTML = `
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                        <span class="text-white fw-bold fs-5">${initials}</span>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-1">${trainer.name}</h5>
                        <p class="text-muted small mb-0">${trainer.specialty}</p>
                    </div>
                </div>
                <div class="text-muted small mb-3">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-envelope me-2"></i>
                        ${trainer.email}
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-phone me-2"></i>
                        ${trainer.phone}
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-clock me-2"></i>
                        ${trainer.experience} years experience
                    </div>
                </div>
                <div class="mt-auto">
                    <div class="text-muted small">
                        <strong>Qualifications:</strong> ${trainer.qualifications || 'Not specified'}
                    </div>
                </div>
            </div>
        </div>
    `;


    card.addEventListener('click', () => viewTrainerDetails(trainer));

    return card;
}

// Create a trainer table row for list view
function createTrainerListRow(trainer) {
    const row = document.createElement('tr');

    row.innerHTML = `
        <td>${trainer.name}</td>
        <td>${trainer.specialty}</td>
        <td>${trainer.email}</td>
        <td>${trainer.phone}</td>
        <td>${trainer.experience} years</td>
        <td>${trainer.location || 'Not specified'}</td>
        <td>
            <button class="btn btn-sm btn-outline-primary view-trainer-btn" data-id="${trainer.id}">
                <i class="fas fa-eye"></i> View
            </button>
        </td>
    `;

    row.querySelector('.view-trainer-btn').addEventListener('click', () => viewTrainerDetails(trainer));

    return row;
}

// Open modal with part details
function openModal(item) {
    modalPartName.textContent = item.name;
    modalPartNumber.textContent = item.partNumber;
    modalApplianceType.textContent = item.applianceType;
    modalLocation.textContent = item.location;
    modalBrands.textContent = item.brands.join(', ');
    modalAppliances.textContent = item.appliances && item.appliances.length > 0 ? item.appliances.join(' | ') : 'No specific models assigned';
    modalDescription.textContent = item.description;
    modalComments.textContent = item.comments;

    if (item.availability) {
        modalAvailability.innerHTML = '<span class="badge bg-success">Available in Uganda</span>';
    } else {
        modalAvailability.innerHTML = '<span class="badge bg-secondary">Currently Not Available</span>';
    }

    if (item.image) {
        modalPartImage.src = item.image;
        modalPartImage.classList.remove('d-none');
        imagePlaceholder.classList.add('d-none');
    } else {
        modalPartImage.classList.add('d-none');
        imagePlaceholder.classList.remove('d-none');
        imagePlaceholder.innerHTML = `
            <i class="fas fa-cogs fa-3x text-muted"></i>
            <p class="mt-2">${item.applianceType} Part</p>
            <small class="text-muted">No image available</small>
        `;
    }

    partModal.show();
}

// Open trainer modal for create/edit
function openTrainerModal(trainer = null) {
    const modal = document.getElementById('trainerModal');
    const form = document.getElementById('trainerForm');
    const modalTitle = document.getElementById('trainerModalLabel');

    if (trainer) {
        modalTitle.textContent = 'Edit Trainer';
        document.getElementById('trainerId').value = trainer.id;
        document.getElementById('trainerName').value = trainer.name;
        document.getElementById('trainerSpecialty').value = trainer.specialty;
        document.getElementById('trainerEmail').value = trainer.email;
        document.getElementById('trainerPhone').value = trainer.phone;
        document.getElementById('trainerExperience').value = trainer.experience;
        document.getElementById('trainerQualifications').value = trainer.qualifications || '';
    } else {
        modalTitle.textContent = 'Add New Trainer';
        form.reset();
        document.getElementById('trainerId').value = '';
    }

    trainerModal.show();
}

// Save trainer
async function saveTrainer() {
    const form = document.getElementById('trainerForm');
    const formData = new FormData(form);

    const trainerId = document.getElementById('trainerId').value;
    const isEdit = !!trainerId;

    try {
        const response = await fetch(`/api/trainers${isEdit ? `/${trainerId}` : ''}`, {
            method: isEdit ? 'POST' : 'POST',
            headers: {
                'X-HTTP-Method-Override': isEdit ? 'PUT' : 'POST',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: formData
        });

        if (response.ok) {
            trainerModal.hide();
            await loadTrainersData();
        } else {
            const errors = await response.json();
            alert('Error saving trainer: ' + JSON.stringify(errors.errors));
        }
    } catch (error) {
        console.error('Error saving trainer:', error);
        alert('Error saving trainer');
    }
}

// Delete trainer
async function deleteTrainer(id) {
    if (!confirm('Are you sure you want to delete this trainer?')) return;

    try {
        const response = await fetch(`/api/trainers/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        if (response.ok) {
            await loadTrainersData();
        } else {
            alert('Error deleting trainer');
        }
    } catch (error) {
        console.error('Error deleting trainer:', error);
        alert('Error deleting trainer');
    }
}

// View trainer details
function viewTrainerDetails(trainer) {
    const modalEl = document.getElementById('trainerDetailsModal');
    if (!modalEl) {
        console.error('Modal element not found');
        return;
    }
    
    const initials = trainer.name.split(' ').map(n => n[0]).join('').toUpperCase();
    document.getElementById('trainerDetailsImage').textContent = initials;
    document.getElementById('trainerDetailsName').textContent = trainer.name;
    document.getElementById('trainerDetailsSpecialty').textContent = trainer.specialty;
    document.getElementById('trainerDetailsEmail').textContent = trainer.email;
    document.getElementById('trainerDetailsPhone').textContent = trainer.phone;
    document.getElementById('trainerDetailsWhatsapp').textContent = trainer.whatsapp || trainer.phone;
    document.getElementById('trainerDetailsLocation').textContent = trainer.location || 'Not specified';
    document.getElementById('trainerDetailsExperience').textContent = `${trainer.experience} years`;
    document.getElementById('trainerDetailsRate').textContent = trainer.hourly_rate ? `UGX ${trainer.hourly_rate}/hr` : 'Not set';
    document.getElementById('trainerDetailsLicense').textContent = trainer.license_number || 'N/A';
    document.getElementById('trainerDetailsRegionDistrict').textContent = `${trainer.region || ''}, ${trainer.district || ''}`;
    document.getElementById('trainerDetailsFullAddress').textContent = `${trainer.village || ''}, ${trainer.sub_county || ''}, ${trainer.district || ''}`;
    document.getElementById('trainerDetailsCountry').textContent = trainer.country || 'Uganda';
    
    const addressCard = document.querySelector('#trainerDetailsModal .card[style*="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)"]');
    if (addressCard) {
        addressCard.style.cursor = 'pointer';
        addressCard.onclick = () => {
            const fullAddress = document.getElementById('trainerDetailsFullAddress').textContent;
            const location = fullAddress || `${trainer.district || ''}, ${trainer.country || 'Uganda'}`;
            window.open(`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(location)}`, '_blank');
        };
    }
    
    document.getElementById('trainerDetailsSkills').innerHTML = trainer.skills ? trainer.skills.split(',').map(s => `<span class="badge bg-light text-dark me-1 mb-1">${s.trim()}</span>`).join('') : '<span class="text-muted">Not specified</span>';
    document.getElementById('trainerDetailsLanguages').innerHTML = trainer.languages ? trainer.languages.split(',').map(l => `<span class="badge bg-light text-dark me-1 mb-1">${l.trim()}</span>`).join('') : '<span class="text-muted">Not specified</span>';
    document.getElementById('trainerDetailsCertifications').innerHTML = trainer.certifications ? trainer.certifications.split(',').map(c => `<span class="badge bg-success me-1 mb-1">${c.trim()}</span>`).join('') : '<span class="text-muted">Not specified</span>';
    document.getElementById('trainerDetailsQualifications').innerHTML = trainer.qualifications ? `<p class="text-muted mb-0">${trainer.qualifications}</p>` : '<span class="text-muted">Not specified</span>';
    document.getElementById('trainerDetailsNotes').textContent = trainer.notes || 'No additional notes';
    
    document.getElementById('trainerDetailsTrainingsCompleted').textContent = trainer.trainings_count || 0;
    document.getElementById('trainerDetailsStudents').textContent = trainer.students_count || 0;
    document.getElementById('trainerDetailsTrainings').textContent = trainer.sessions_count || 0;
    document.getElementById('trainerDetailsId').textContent = trainer.id || 'N/A';
    
    const status = trainer.status || 'Active';
    const statusBadge = document.getElementById('trainerDetailsStatus');
    statusBadge.className = status === 'Active' ? 'badge bg-success me-2' : 'badge bg-secondary me-2';
    statusBadge.textContent = status;
    
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function sendEmailToTrainer() {
    window.location.href = `mailto:${document.getElementById('trainerDetailsEmail').textContent}`;
}

function callTrainer() {
    window.location.href = `tel:${document.getElementById('trainerDetailsPhone').textContent}`;
}

function whatsappTrainer() {
    const phone = document.getElementById('trainerDetailsWhatsapp').textContent.replace(/[^0-9]/g, '');
    window.open(`https://wa.me/${phone}`, '_blank');
}

// Filter data based on search and filters
async function filterData() {
    const searchTerm = searchInput.value.toLowerCase();
    const applianceValue = applianceFilter.value;
    const brandValue = brandFilter.value;
    const availabilityValue = availabilityFilter.value;

    const params = new URLSearchParams();
    if (searchTerm) params.append('search', searchTerm);
    if (applianceValue) params.append('appliance', applianceValue);
    if (brandValue) params.append('brand', brandValue);
    if (availabilityValue) params.append('availability', availabilityValue);

    try {
        const response = await fetch(`/api/parts?${params}`);
        filteredData = await response.json();
        renderInventory();
    } catch (error) {
        console.error('Error filtering data:', error);
    }
}

// Filter trainers data
function filterTrainersData() {
    const searchTerm = trainerSearchInput.value.toLowerCase();
    const specialtyValue = trainerSpecialtyFilter.value;
    const sortValue = trainerSortFilter.value;

    filteredTrainersData = trainersData.filter(trainer => {
        const matchesSearch = !searchTerm ||
            trainer.name.toLowerCase().includes(searchTerm) ||
            trainer.specialty.toLowerCase().includes(searchTerm) ||
            trainer.email.toLowerCase().includes(searchTerm);

        const matchesSpecialty = !specialtyValue || trainer.specialty === specialtyValue;

        return matchesSearch && matchesSpecialty;
    });

    // Sort data
    if (sortValue === 'name') {
        filteredTrainersData.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortValue === 'experience') {
        filteredTrainersData.sort((a, b) => b.experience - a.experience);
    }

    renderTrainers();
}

// Populate trainer specialty filter
function populateTrainerFilters() {
    const specialties = [...new Set(trainersData.map(trainer => trainer.specialty))];
    trainerSpecialtyFilter.innerHTML = '<option value="">All Specialties</option>';

    specialties.forEach(specialty => {
        const option = document.createElement('option');
        option.value = specialty;
        option.textContent = specialty;
        trainerSpecialtyFilter.appendChild(option);
    });
}

// Render technicians based on current view
function renderTechnicians() {
    if (currentTechnicianView === 'grid') {
        renderTechniciansGridView();
    } else {
        renderTechniciansListView();
    }
}

// Render technicians grid view
function renderTechniciansGridView() {
    techniciansGrid.innerHTML = '';
    filteredTechniciansData.forEach(tech => {
        const card = createTechnicianCard(tech);
        techniciansGrid.appendChild(card);
    });
}

// Render technicians list view
function renderTechniciansListView() {
    techniciansTableBody.innerHTML = '';
    filteredTechniciansData.forEach(tech => {
        const row = createTechnicianListRow(tech);
        techniciansTableBody.appendChild(row);
    });
}

// Create technician card
function createTechnicianCard(tech) {
    const card = document.createElement('div');
    card.className = 'col-lg-4 col-md-6 col-sm-12';
    const initials = tech.name.split(' ').map(n => n[0]).join('').toUpperCase();
    const statusClass = tech.status === 'Available' ? 'success' : (tech.status === 'Busy' ? 'warning' : 'danger');
    const badgeClass = tech.status === 'Busy' ? 'warning' : 'info';
    const skills = Array.isArray(tech.skills) ? tech.skills.join(', ') : (tech.skills || 'N/A');
    const certs = Array.isArray(tech.certifications) ? tech.certifications.join(', ') : (tech.certifications || 'N/A');
    
    card.innerHTML = `
        <div class="card p-4 technician-card" style="cursor: pointer;">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-${badgeClass} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <span class="text-white fw-bold">${initials}</span>
                </div>
                <div class="flex-grow-1">
                    <h4 class="h5 mb-1">${tech.name}</h4>
                    <p class="text-muted small">${tech.specialty}</p>
                </div>
                <span class="badge bg-${statusClass}">${tech.status}</span>
            </div>
            <div class="text-muted small mb-3">
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-envelope me-2"></i>
                    ${tech.email}
                </div>
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-phone me-2"></i>
                    ${tech.phone}
                </div>
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-id-card me-2"></i>
                    License: ${tech.license}
                </div>
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    ${tech.location}
                </div>
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-clock me-2"></i>
                    ${tech.experience} years experience
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-money-bill me-2"></i>
                    UGX ${parseFloat(tech.rate || 0).toLocaleString()}/hour
                </div>
            </div>
            <div class="text-muted small mb-3">
                <strong>Skills:</strong> ${skills}
            </div>
            <div class="text-muted small">
                <strong>Certifications:</strong> ${certs}
            </div>
        </div>
    `;
    
    card.addEventListener('click', () => viewTechnicianDetails(tech));
    return card;
}

// Create technician list row
function createTechnicianListRow(tech) {
    const row = document.createElement('tr');
    const initials = tech.name.split(' ').map(n => n[0]).join('').toUpperCase();
    const statusClass = tech.status === 'Available' ? 'success' : (tech.status === 'Busy' ? 'warning' : 'danger');
    const badgeClass = tech.status === 'Busy' ? 'warning' : 'info';
    
    row.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <div class="bg-${badgeClass} rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                    <span class="text-white fw-bold small">${initials}</span>
                </div>
                <strong>${tech.name}</strong>
            </div>
        </td>
        <td><span class="badge bg-primary">${tech.specialty}</span></td>
        <td>${tech.phone}</td>
        <td>${tech.license}</td>
        <td>${tech.location}</td>
        <td>${tech.experience} years</td>
        <td>UGX ${parseFloat(tech.rate || 0).toLocaleString()}/hour</td>
        <td><span class="badge bg-${statusClass}">${tech.status}</span></td>
        <td>
            <button class="btn btn-sm btn-outline-primary view-technician-btn"><i class="fas fa-eye"></i> View</button>
        </td>
    `;
    
    row.querySelector('.view-technician-btn').addEventListener('click', () => viewTechnicianDetails(tech));
    return row;
}

// Filter technicians data
function filterTechniciansData() {
    const searchTerm = technicianSearchInput ? technicianSearchInput.value.toLowerCase() : '';
    const specialtyValue = technicianSpecialtyFilter ? technicianSpecialtyFilter.value : '';
    const statusValue = technicianStatusFilter ? technicianStatusFilter.value : '';
    const sortValue = technicianSortFilter ? technicianSortFilter.value : 'name';

    filteredTechniciansData = techniciansData.filter(tech => {
        const matchesSearch = !searchTerm ||
            tech.name.toLowerCase().includes(searchTerm) ||
            tech.specialty.toLowerCase().includes(searchTerm) ||
            tech.phone.toLowerCase().includes(searchTerm);

        const matchesSpecialty = !specialtyValue || tech.specialty === specialtyValue;
        const matchesStatus = !statusValue || tech.status === statusValue;

        return matchesSearch && matchesSpecialty && matchesStatus;
    });

    if (sortValue === 'name') {
        filteredTechniciansData.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortValue === 'experience') {
        filteredTechniciansData.sort((a, b) => b.experience - a.experience);
    }

    renderTechnicians();
}

// Filter appliances data
function filterAppliancesData() {
    const searchTerm = applianceSearchInput ? applianceSearchInput.value.toLowerCase() : '';
    const statusValue = applianceStatusFilter ? applianceStatusFilter.value : '';
    const brandValue = applianceBrandFilter ? applianceBrandFilter.value : '';
    const sortValue = applianceSortFilter ? applianceSortFilter.value : 'name';

    // Filter grid cards
    const gridCards = document.querySelectorAll('.appliance-card');
    gridCards.forEach(card => {
        const name = card.dataset.name || '';
        const status = card.dataset.status || '';
        const brand = card.dataset.brand || '';

        const matchesSearch = !searchTerm ||
            name.includes(searchTerm) ||
            brand.includes(searchTerm);

        const matchesStatus = !statusValue || status === statusValue;

        const matchesBrand = !brandValue || brand.includes(brandValue.toLowerCase());

        if (matchesSearch && matchesStatus && matchesBrand) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });

    // Filter table rows
    const tableRows = document.querySelectorAll('#appliancesTableBody tr');
    tableRows.forEach(row => {
        const name = row.cells[0].textContent.toLowerCase();
        const brand = row.cells[1].textContent.toLowerCase();
        const status = row.cells[6].textContent.trim();

        const matchesSearch = !searchTerm ||
            name.includes(searchTerm) ||
            brand.includes(searchTerm);

        const matchesStatus = !statusValue || status === statusValue;

        const matchesBrand = !brandValue || brand.includes(brandValue.toLowerCase());

        if (matchesSearch && matchesStatus && matchesBrand) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });

    // Sort if needed
    if (sortValue === 'name_desc') {
        sortAppliancesByName(true);
    } else if (sortValue === 'newest') {
        sortAppliancesByDate(true);
    } else if (sortValue === 'oldest') {
        sortAppliancesByDate(false);
    } else {
        sortAppliancesByName(false);
    }
}

// Sort appliances by name
function sortAppliancesByName(descending = false) {
    const gridCards = Array.from(document.querySelectorAll('.appliance-card'));
    const container = document.getElementById('appliancesGrid');

    gridCards.sort((a, b) => {
        const nameA = a.querySelector('h4').textContent.toLowerCase();
        const nameB = b.querySelector('h4').textContent.toLowerCase();
        return descending ? nameB.localeCompare(nameA) : nameA.localeCompare(nameB);
    });

    gridCards.forEach(card => container.appendChild(card));
}

// Sort appliances by date
function sortAppliancesByDate(descending = false) {
    const gridCards = Array.from(document.querySelectorAll('.appliance-card'));
    const container = document.getElementById('appliancesGrid');

    gridCards.sort((a, b) => {
        const dateA = a.dataset.created || '';
        const dateB = b.dataset.created || '';
        const timeA = new Date(dateA).getTime();
        const timeB = new Date(dateB).getTime();
        return descending ? timeB - timeA : timeA - timeB;
    });

    gridCards.forEach(card => container.appendChild(card));
}

// Sort table rows by experience
function sortTableRowsByExperience(tableBodySelector) {
    const tableBody = document.querySelector(tableBodySelector);
    const rows = Array.from(tableBody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        const experienceA = parseInt(a.cells[5].textContent) || 0;
        const experienceB = parseInt(b.cells[5].textContent) || 0;
        return experienceB - experienceA; // High to low
    });

    rows.forEach(row => tableBody.appendChild(row));
}

// View technician details
function viewTechnicianDetails(tech) {
    const initials = tech.name.split(' ').map(n => n[0]).join('').toUpperCase();
    const technicianViewPhoto = document.getElementById('technicianViewPhoto');
    technicianViewPhoto.innerHTML = initials;

    document.getElementById('technicianViewName').textContent = tech.name;
    document.getElementById('technicianViewSpecialty').textContent = tech.specialty;
    document.getElementById('technicianViewLicense').textContent = tech.license || 'N/A';
    document.getElementById('technicianViewExperience').textContent = tech.experience + ' years';
    document.getElementById('technicianViewRate').textContent = 'UGX ' + parseFloat(tech.hourly_rate || tech.rate || 0).toLocaleString() + '/hour';
    document.getElementById('technicianViewEmployment').textContent = tech.employment_type || 'Full-Time';

    document.getElementById('technicianViewEmail').textContent = tech.email;
    document.getElementById('technicianViewPhone1').textContent = tech.phone;
    document.getElementById('technicianViewWhatsapp').textContent = tech.whatsapp || tech.phone;

    document.getElementById('technicianViewLocation').textContent = tech.location;
    document.getElementById('technicianViewRegionDistrict').textContent = `${tech.region || ''}, ${tech.district || tech.location}`;
    document.getElementById('technicianViewFullAddress').textContent = `${tech.village || ''}, ${tech.sub_county || ''}, ${tech.district || tech.location}`;
    document.getElementById('technicianViewCountry').textContent = tech.country || 'Uganda';

    const mapCard = document.getElementById('technicianViewMapCard');
    mapCard.href = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(tech.location)}`;

    const statusBadge = document.getElementById('technicianViewStatus');
    if (tech.status === 'Available') {
        statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i>Available';
        statusBadge.className = 'badge bg-success me-3 fs-6 px-3 py-2';
    } else if (tech.status === 'Busy') {
        statusBadge.innerHTML = '<i class="fas fa-clock me-1"></i>Busy';
        statusBadge.className = 'badge bg-warning text-dark me-3 fs-6 px-3 py-2';
    } else {
        statusBadge.innerHTML = '<i class="fas fa-times-circle me-1"></i>Unavailable';
        statusBadge.className = 'badge bg-danger me-3 fs-6 px-3 py-2';
    }

    const skills = Array.isArray(tech.skills) ? tech.skills : (tech.skills ? tech.skills.split(', ') : []);
    const certs = Array.isArray(tech.certifications) ? tech.certifications : (tech.certifications ? tech.certifications.split(', ') : []);
    const languages = tech.languages ? tech.languages.split(', ') : ['English', 'Luganda'];

    document.getElementById('technicianViewSkills').innerHTML = skills.map(skill => `<span class="badge bg-light text-dark me-1 mb-1">${skill.trim()}</span>`).join('');
    document.getElementById('technicianViewCertifications').innerHTML = certs.map(cert => `<span class="badge bg-success me-1 mb-1">${cert.trim()}</span>`).join('');
    document.getElementById('technicianViewLanguages').innerHTML = languages.map(lang => `<span class="badge bg-light text-dark me-1 mb-1">${lang.trim()}</span>`).join('');
    document.getElementById('technicianViewTraining').innerHTML = `<span class="text-muted">${tech.training || 'Advanced Technical Training'}</span>`;
    
    document.getElementById('technicianViewOwnTools').textContent = tech.own_tools || 'Yes';
    document.getElementById('technicianViewVehicle').textContent = tech.has_vehicle || 'No';
    document.getElementById('technicianViewEquipmentList').innerHTML = `<span class="text-muted">${tech.equipment_list || 'Multimeter, Oscilloscope, Soldering Station'}</span>`;
    document.getElementById('technicianViewServiceAreas').innerHTML = `<span class="badge bg-info">${tech.service_areas || tech.location}</span>`;
    
    document.getElementById('technicianViewJobsCompleted').textContent = tech.jobs_completed || 0;
    document.getElementById('technicianViewRating').textContent = tech.rating || '5.0';
    document.getElementById('technicianViewResponseTime').textContent = tech.response_time || '2-4hrs';
    document.getElementById('technicianViewJoined').textContent = new Date(tech.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    document.getElementById('technicianViewLastActive').textContent = 'Just now';
    document.getElementById('technicianViewNotes').innerHTML = `<p class="text-muted">${tech.notes || 'Experienced technician with excellent customer service skills.'}</p>`;

    technicianViewModal.show();
}

// View appliance details
async function fetchAppliancePartsCount(applianceId) {
    try {
        const response = await fetch('/api/parts');
        const parts = await response.json();
        return parts.filter(part => part.applianceType && part.applianceType.toLowerCase().includes(applianceId)).length;
    } catch (error) {
        return 0;
    }
}

function viewApplianceDetails(id, name, brand, model, power, sku, status, description, icon, color, price, created_at, partsCount) {
    const applianceViewIcon = document.getElementById('applianceViewIcon');
    applianceViewIcon.innerHTML = `<i class="fas fa-${icon || 'tools'}"></i>`;
    applianceViewIcon.className = `bg-${color || 'primary'} rounded d-inline-flex align-items-center justify-content-center text-white`;

    document.getElementById('applianceViewImageName').textContent = name;

    const statusBadge = document.getElementById('applianceViewStatus');
    const statusText = statusBadge.querySelector('.status-text');
    if (status === 'Available') {
        statusBadge.className = 'badge bg-success';
        statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i><span class="status-text">Available</span>';
    } else if (status === 'In Use') {
        statusBadge.className = 'badge bg-info';
        statusBadge.innerHTML = '<i class="fas fa-play-circle me-1"></i><span class="status-text">In Use</span>';
    } else {
        statusBadge.className = 'badge bg-danger';
        statusBadge.innerHTML = '<i class="fas fa-times-circle me-1"></i><span class="status-text">Not Available</span>';
    }

    document.getElementById('applianceViewName').textContent = name;
    document.getElementById('applianceViewBrand').textContent = brand;
    document.getElementById('applianceViewSku').textContent = sku || 'N/A';
    document.getElementById('applianceViewModel').textContent = model || 'N/A';
    document.getElementById('applianceViewPower').textContent = power || 'N/A';
    document.getElementById('applianceViewDescription').textContent = description || 'No description available';

    const priceElement = document.getElementById('applianceViewPrice');
    const priceTextElement = priceElement.querySelector('.price-text');
    if (price && parseFloat(price) > 0) {
        priceTextElement.textContent = 'UGX ' + parseFloat(price).toLocaleString();
        priceElement.className = 'badge bg-primary mb-3';
    } else {
        priceTextElement.textContent = 'Not Set';
        priceElement.className = 'badge bg-secondary mb-3';
    }

    document.getElementById('applianceViewId').textContent = `#${id}`;
    document.getElementById('applianceViewCreated').textContent = new Date(created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    document.getElementById('applianceViewPartsCount').textContent = partsCount || 0;

    const applianceViewModal = new bootstrap.Modal(document.getElementById('applianceViewModal'));
    applianceViewModal.show();
}

// Call technician
function callTechnician() {
    const phone = document.getElementById('technicianViewPhone1').textContent;
    window.location.href = `tel:${phone}`;
}

// WhatsApp technician
function whatsappTechnician() {
    const phone = document.getElementById('technicianViewWhatsapp').textContent;
    const cleanPhone = phone.replace(/[^0-9]/g, '');
    window.open(`https://wa.me/${cleanPhone}`, '_blank');
}

// Send email to technician
function sendEmailToTechnician() {
    const email = document.getElementById('technicianViewEmail').textContent;
    window.location.href = `mailto:${email}`;
}

// Print technician profile
function printTechnicianProfile() {
    window.print();
}

// Open technician form modal for add/edit
function openTechnicianForm(initials, firstName, middleName, lastName, email, phone, specialty, location, experience, license) {
    const modal = document.getElementById('technicianModal');
    const form = document.getElementById('technicianForm');
    const modalTitle = document.getElementById('technicianModalLabel');

    // Set modal title
    modalTitle.innerHTML = '<i class="fas fa-user-edit me-2"></i>Edit Technician';

    // Populate form fields with technician data
    document.getElementById('technicianTitle').value = 'Mr.';
    document.getElementById('technicianFirstName').value = firstName;
    document.getElementById('technicianMiddleName').value = middleName;
    document.getElementById('technicianLastName').value = lastName;
    document.getElementById('technicianEmail').value = email;
    document.getElementById('technicianPhone1').value = phone;
    document.getElementById('technicianSpecialty').value = specialty;
    document.getElementById('technicianDistrict').value = location;
    document.getElementById('technicianExperience').value = experience;
    document.getElementById('technicianLicenseNumber').value = license;

    // Set default values for other fields
    document.getElementById('technicianGender').value = 'Male';
    document.getElementById('technicianCountry').value = 'Uganda';
    document.getElementById('technicianRegion').value = 'Central';
    document.getElementById('technicianStatus').value = 'Available';
    document.getElementById('technicianEmploymentType').value = 'Full-Time';
    document.getElementById('technicianOwnTools').value = 'Yes';
    document.getElementById('technicianVehicle').value = 'No';
    document.getElementById('technicianSkills').value = getSkillsBySpecialty(specialty);
    document.getElementById('technicianCertifications').value = getCertificationsBySpecialty(specialty);

    // Show the modal
    technicianModal.show();
}

// Get skills by specialty
function getSkillsBySpecialty(specialty) {
    const skillsMap = {
        'Refrigeration Systems': 'AC Repair, Refrigerator Maintenance, Cold Room Installation',
        'Electronics Repair': 'TV Repair, Audio Systems, Microwave Servicing',
        'Washing Machine Repair': 'Washing Machine Repair, Dryer Maintenance, Spin Motor Replacement',
        'Kitchen Appliances': 'Microwave Repair, Oven Maintenance, Blender Servicing',
        'Audio-Visual Equipment': 'TV Repair, Sound System Installation, Home Theater Setup',
        'Air Conditioning': 'AC Installation, AC Repair, Ventilation Systems',
        'Microwave & Oven Repair': 'Microwave Repair, Oven Maintenance, Heating Element Replacement',
        'Dishwasher Repair': 'Dishwasher Repair, Pump Replacement, Spray Arm Maintenance',
        'Small Appliances': 'Small Appliance Repair, Component Level Repair',
        'Industrial Equipment': 'Industrial Equipment Maintenance, Heavy Machinery Repair'
    };
    return skillsMap[specialty] || 'General Appliance Repair';
}

// Get certifications by specialty
function getCertificationsBySpecialty(specialty) {
    const certsMap = {
        'Refrigeration Systems': 'Certified HVAC Technician, Refrigeration License Class A',
        'Electronics Repair': 'Electronics Technician Certificate, Appliance Repair License',
        'Washing Machine Repair': 'Laundry Equipment Specialist, Appliance Technology Certificate',
        'Kitchen Appliances': 'Kitchen Appliance Repair Certificate, Safety Compliance License',
        'Audio-Visual Equipment': 'Audio-Visual Systems Expert, Electronics Engineering Diploma',
        'Air Conditioning': 'HVAC Certification, EPA Section 608 Certified',
        'Microwave & Oven Repair': 'Appliance Repair Technician Certificate, Electrical Safety Certified',
        'Dishwasher Repair': 'Dishwasher Specialist Certification, Plumbing License',
        'Small Appliances': 'Small Appliance Repair Certificate, Electrical Safety Certification',
        'Industrial Equipment': 'Industrial Equipment Certification, Mechanical Engineering Degree'
    };
    return certsMap[specialty] || 'Standard Certification';
}


// Set up event listeners
function setupEventListeners() {
    // Search and filter events
    searchInput.addEventListener('input', debounce(filterData, 300));
    applianceFilter.addEventListener('change', filterData);
    brandFilter.addEventListener('change', filterData);
    availabilityFilter.addEventListener('change', filterData);

    // View filter change event
    viewFilter.addEventListener('change', () => {
        currentView = viewFilter.value;
        if (currentView === 'grid') {
            inventoryGrid.classList.remove('d-none');
            document.getElementById('inventoryList').classList.add('d-none');
        } else {
            inventoryGrid.classList.add('d-none');
            document.getElementById('inventoryList').classList.remove('d-none');
        }
        renderInventory();
    });

    // Trainer events
    if (trainerSearchInput) trainerSearchInput.addEventListener('input', debounce(filterTrainersData, 300));
    if (trainerSpecialtyFilter) trainerSpecialtyFilter.addEventListener('change', filterTrainersData);
    if (trainerSortFilter) trainerSortFilter.addEventListener('change', filterTrainersData);

    // Trainer view filter change event
    if (trainerViewFilter) {
        trainerViewFilter.addEventListener('change', () => {
            currentTrainerView = trainerViewFilter.value;
            if (currentTrainerView === 'grid') {
                trainersGrid.classList.remove('d-none');
                document.getElementById('trainersList').classList.add('d-none');
            } else {
                trainersGrid.classList.add('d-none');
                document.getElementById('trainersList').classList.remove('d-none');
            }
            renderTrainers();
        });
    }

    // Technician events
    if (technicianSearchInput) technicianSearchInput.addEventListener('input', debounce(filterTechniciansData, 300));
    if (technicianSpecialtyFilter) technicianSpecialtyFilter.addEventListener('change', filterTechniciansData);
    if (technicianStatusFilter) technicianStatusFilter.addEventListener('change', filterTechniciansData);
    if (technicianSortFilter) technicianSortFilter.addEventListener('change', filterTechniciansData);

    // Technician view filter change event
    if (technicianViewFilter) {
        technicianViewFilter.addEventListener('change', () => {
            currentTechnicianView = technicianViewFilter.value;
            if (currentTechnicianView === 'grid') {
                techniciansGrid.classList.remove('d-none');
                document.getElementById('techniciansList').classList.add('d-none');
            } else {
                techniciansGrid.classList.add('d-none');
                document.getElementById('techniciansList').classList.remove('d-none');
            }
            renderTechnicians();
        });
    }

    // Appliance events
    if (applianceSearchInput) applianceSearchInput.addEventListener('input', debounce(filterAppliancesData, 300));
    if (applianceStatusFilter) applianceStatusFilter.addEventListener('change', filterAppliancesData);
    if (applianceBrandFilter) applianceBrandFilter.addEventListener('change', filterAppliancesData);
    if (applianceSortFilter) applianceSortFilter.addEventListener('change', filterAppliancesData);

    // Appliance view filter change event
    if (applianceViewFilter) {
        applianceViewFilter.addEventListener('change', () => {
            currentApplianceView = applianceViewFilter.value;
            if (currentApplianceView === 'grid') {
                appliancesGrid.classList.remove('d-none');
                document.getElementById('appliancesList').classList.add('d-none');
            } else {
                appliancesGrid.classList.add('d-none');
                document.getElementById('appliancesList').classList.remove('d-none');
            }
            renderAppliances();
        });
    }


    // Chat events
    chatButton.addEventListener('click', async () => {
        chatModal.show();
        if (!chatId) {
            await startChat();
        } else {
            await loadMessages();
        }
        // Start polling for new messages
        if (!messageInterval) {
            messageInterval = setInterval(loadMessages, 3000); // Poll every 3 seconds
        }
        // Request notification permission
        if (Notification.permission === 'default') {
            Notification.requestPermission();
        }
    });

    sendMessageBtn.addEventListener('click', () => sendMessage());
    chatMessageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    attachBtn.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            let type = 'document';
            if (file.type.startsWith('image/')) type = 'image';
            else if (file.type.startsWith('video/')) type = 'video';
            else if (file.type.startsWith('audio/')) type = 'audio';
            sendMessage(type, file);
        }
    });

    emojiBtn.addEventListener('click', () => {
        emojiPicker.classList.toggle('d-none');
    });

    emojiPicker.addEventListener('click', (e) => {
        if (e.target.textContent) {
            chatMessageInput.value += e.target.textContent;
            emojiPicker.classList.add('d-none');
        }
    });

    voiceBtn.addEventListener('click', () => {
        voiceRecorder.classList.toggle('d-none');
    });

    recordBtn.addEventListener('click', startRecording);
    stopRecordBtn.addEventListener('click', stopRecording);

    gifBtn.addEventListener('click', () => {
        gifPicker.classList.toggle('d-none');
    });

    gifSearch.addEventListener('input', debounce(searchGifs, 500));

    locationBtn.addEventListener('click', shareLocation);

    contactBtn.addEventListener('click', () => {
        const name = prompt('Contact Name:');
        const phone = prompt('Contact Phone:');
        if (name && phone) {
            sendContact(name, phone);
        }
    });

    searchBtn.addEventListener('click', searchMessages);

    // Stop polling when chat modal is closed
    document.getElementById('chatModal').addEventListener('hidden.bs.modal', () => {
        if (messageInterval) {
            clearInterval(messageInterval);
            messageInterval = null;
        }
    });

    // Sidebar navigation
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const targetId = link.getAttribute('href').substring(1);

            // Remove active class from all links
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            // Add active to clicked link
            link.classList.add('active');

            if (targetId === 'dashboard') {
                document.getElementById('dashboard-section').style.display = 'block';
                document.getElementById('inventory').style.display = 'none';
                document.getElementById('appliances').style.display = 'none';
                document.getElementById('trainers').style.display = 'none';
                document.getElementById('qualified-technicians').style.display = 'none';
                document.getElementById('reports').style.display = 'none';
                window.scrollTo(0, 0);
            } else if (targetId === 'inventory') {
                document.getElementById('dashboard-section').style.display = 'none';
                document.getElementById('inventory').style.display = 'block';
                document.getElementById('appliances').style.display = 'none';
                document.getElementById('trainers').style.display = 'none';
                document.getElementById('qualified-technicians').style.display = 'none';
                document.getElementById('reports').style.display = 'none';
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            } else if (targetId === 'appliances') {
                document.getElementById('dashboard-section').style.display = 'none';
                document.getElementById('inventory').style.display = 'none';
                document.getElementById('appliances').style.display = 'block';
                document.getElementById('trainers').style.display = 'none';
                document.getElementById('qualified-technicians').style.display = 'none';
                document.getElementById('reports').style.display = 'none';
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
                // Load appliances data when section is opened
                loadAppliancesData();
            } else if (targetId === 'trainers') {
                document.getElementById('dashboard-section').style.display = 'none';
                document.getElementById('inventory').style.display = 'none';
                document.getElementById('appliances').style.display = 'none';
                document.getElementById('trainers').style.display = 'block';
                document.getElementById('qualified-technicians').style.display = 'none';
                document.getElementById('reports').style.display = 'none';
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
                // Load trainers data when section is opened
                loadTrainersData();
            } else if (targetId === 'qualified-technicians') {
                document.getElementById('dashboard-section').style.display = 'none';
                document.getElementById('inventory').style.display = 'none';
                document.getElementById('appliances').style.display = 'none';
                document.getElementById('trainers').style.display = 'none';
                document.getElementById('qualified-technicians').style.display = 'block';
                document.getElementById('reports').style.display = 'none';
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
                loadTechniciansData();
            } else if (targetId === 'reports') {
                document.getElementById('dashboard-section').style.display = 'none';
                document.getElementById('inventory').style.display = 'none';
                document.getElementById('appliances').style.display = 'none';
                document.getElementById('trainers').style.display = 'none';
                document.getElementById('qualified-technicians').style.display = 'none';
                document.getElementById('reports').style.display = 'block';
                document.getElementById('settings').style.display = 'none';
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
                loadReportsData();
            } else if (targetId === 'settings') {
                document.getElementById('dashboard-section').style.display = 'none';
                document.getElementById('inventory').style.display = 'none';
                document.getElementById('appliances').style.display = 'none';
                document.getElementById('trainers').style.display = 'none';
                document.getElementById('qualified-technicians').style.display = 'none';
                document.getElementById('reports').style.display = 'none';
                document.getElementById('settings').style.display = 'block';
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
                loadSettings();
            } else {
                document.getElementById('dashboard-section').style.display = 'none';
                document.getElementById('inventory').style.display = 'none';
                document.getElementById('appliances').style.display = 'none';
                document.getElementById('trainers').style.display = 'none';
                document.getElementById('qualified-technicians').style.display = 'none';
                document.getElementById('reports').style.display = 'none';
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }

            // Close offcanvas on mobile
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('sidebar'));
            if (offcanvas) offcanvas.hide();
        });
    });
}

// Debounce function for search input
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

// Adjust sidebar width
function adjustSidebarWidth() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    if (sidebar && mainContent) {
        sidebar.style.width = '160px';
        mainContent.style.marginLeft = '160px';
    }
}

// Create footer
function createFooter() {
    const footer = document.createElement('div');
    footer.id = 'customFooter';
    footer.style.position = 'fixed';
    footer.style.left = '160px';
    footer.style.bottom = '0';
    footer.style.right = '0';
    footer.style.height = '30px';
    footer.style.backgroundColor = '#343a40';
    footer.style.color = 'white';
    footer.style.display = 'flex';
    footer.style.alignItems = 'center';
    footer.style.justifyContent = 'center';
    footer.style.padding = '0 10px';
    footer.style.fontSize = '14px';
    footer.style.zIndex = '1050';
    footer.textContent = 'E-Cooking Inventory Management System | CREEC © 2026. All rights reserved.';
    document.body.appendChild(footer);
}

// Style navbar
function styleNavbar() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        navbar.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        navbar.style.borderBottom = '1px solid rgba(255, 255, 255, 0.1)';
    }
}

// Update current date in footer
function updateCurrentDate() {
    const now = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', options);
}

// Render charts
function renderCharts() {
    if (typeof chartData !== 'undefined') {
        renderApplianceChart();
        renderBrandChart();
        renderAvailabilityChart();
        renderDistributionChart();
        renderBrandAvailabilityChart();
    }
}

function renderApplianceChart() {
    const ctx = document.getElementById('applianceChart').getContext('2d');
    const labels = Object.keys(chartData.appliances);
    const data = Object.values(chartData.appliances);
    const total = data.reduce((a, b) => a + b, 0);

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ],
                hoverBackgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Parts Distribution by Appliance Type'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

function renderBrandChart() {
    const ctx = document.getElementById('brandChart').getContext('2d');
    const labels = Object.keys(chartData.brands);
    const data = Object.values(chartData.brands);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Parts',
                data: data,
                backgroundColor: '#36A2EB',
                borderColor: '#36A2EB',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Parts by Brand'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Parts: ${context.parsed.y}`;
                        }
                    }
                }
            }
        }
    });
}

function renderAvailabilityChart() {
    const ctx = document.getElementById('availabilityChart').getContext('2d');
    const labels = Object.keys(chartData.availability);
    const data = Object.values(chartData.availability);
    const total = data.reduce((a, b) => a + b, 0);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#4BC0C0',
                    '#FF6384'
                ],
                hoverBackgroundColor: [
                    '#4BC0C0',
                    '#FF6384'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Overall Part Availability'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

function renderDistributionChart() {
    const ctx = document.getElementById('distributionChart').getContext('2d');
    const labels = Object.keys(chartData.availabilityByAppliance);
    const availableData = labels.map(label => chartData.availabilityByAppliance[label]['Available']);
    const notAvailableData = labels.map(label => chartData.availabilityByAppliance[label]['Not Available']);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Available',
                data: availableData,
                backgroundColor: '#4BC0C0',
                borderColor: '#4BC0C0',
                borderWidth: 1
            }, {
                label: 'Not Available',
                data: notAvailableData,
                backgroundColor: '#FF6384',
                borderColor: '#FF6384',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Availability by Appliance Type'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.parsed.y}`;
                        }
                    }
                }
            }
        }
    });
}

function renderBrandAvailabilityChart() {
    const ctx = document.getElementById('brandAvailabilityChart').getContext('2d');
    const labels = Object.keys(chartData.availabilityByBrand);
    const availableData = labels.map(label => chartData.availabilityByBrand[label]['Available']);
    const notAvailableData = labels.map(label => chartData.availabilityByBrand[label]['Not Available']);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Available',
                data: availableData,
                backgroundColor: '#36A2EB',
                borderColor: '#36A2EB',
                borderWidth: 1
            }, {
                label: 'Not Available',
                data: notAvailableData,
                backgroundColor: '#FFCE56',
                borderColor: '#FFCE56',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Availability by Brand'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.parsed.y}`;
                        }
                    }
                }
            }
        }
    });
}

// Chat functions
async function startChat() {
    try {
        const response = await fetch('/api/chat/start', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ user_name: userName })
        });
        const data = await response.json();
        chatId = data.chat_id;
        await loadMessages();
    } catch (error) {
        console.error('Error starting chat:', error);
    }
}

async function sendMessage(type = 'text', file = null, message = null, gifUrl = null) {
    if (!chatId) return;

    const formData = new FormData();
    formData.append('chat_id', chatId);
    formData.append('sender', 'user');
    formData.append('type', type);

    if (type === 'text') {
        const msg = message || chatMessageInput.value.trim();
        if (!msg) return;
        formData.append('message', msg);
        chatMessageInput.value = '';
    } else if (type === 'gif') {
        formData.append('gif_url', gifUrl);
    } else if (file) {
        formData.append('file', file);
    }

    try {
        const response = await fetch('/api/chat/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: formData
        });
        await loadMessages();
        notifyNewMessage();
    } catch (error) {
        console.error('Error sending message:', error);
    }
}

async function loadMessages() {
    if (!chatId) return;

    try {
        const response = await fetch(`/api/chat/messages?chat_id=${chatId}`);
        const messages = await response.json();
        renderMessages(messages);
    } catch (error) {
        console.error('Error loading messages:', error);
    }
}

function renderMessages(messages) {
    chatMessages.innerHTML = '';
    messages.forEach(message => {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${message.sender === 'user' ? 'text-end' : 'text-start'} mb-2`;
        messageDiv.setAttribute('data-message-id', message.id);

        let content = '';
        if (message.type === 'text') {
            content = message.message;
        } else if (message.type === 'image') {
            content = `<img src="/storage/${message.file_path}" class="img-fluid rounded" style="max-width: 200px;">`;
        } else if (message.type === 'video') {
            content = `<video controls class="rounded" style="max-width: 200px;"><source src="/storage/${message.file_path}"></video>`;
        } else if (message.type === 'audio') {
            content = `<audio controls><source src="/storage/${message.file_path}"></audio>`;
        } else if (message.type === 'document') {
            content = `<a href="/storage/${message.file_path}" target="_blank"><i class="fas fa-file"></i> ${message.file_name}</a>`;
        } else if (message.type === 'location') {
            content = `<a href="https://www.google.com/maps?q=${message.location_lat},${message.location_lng}" target="_blank"><i class="fas fa-map-marker-alt"></i> Location</a>`;
        } else if (message.type === 'contact') {
            content = `<i class="fas fa-user"></i> ${message.contact_name} - ${message.contact_phone}`;
        } else if (message.type === 'gif') {
            content = `<img src="${message.gif_url}" class="img-fluid rounded" style="max-width: 200px;">`;
        }

        if (message.reply_to) {
            content = `<div class="border-start border-3 ps-2 mb-1 text-muted small">${message.reply_to.message}</div>` + content;
        }

        let statusIcon = '';
        if (message.sender === 'user') {
            if (message.status === 'sent') statusIcon = '<i class="fas fa-check text-muted"></i>';
            else if (message.status === 'delivered') statusIcon = '<i class="fas fa-check-double text-muted"></i>';
            else if (message.status === 'read') statusIcon = '<i class="fas fa-check-double text-primary"></i>';
        }

        messageDiv.innerHTML = `
            <div class="d-inline-block p-2 rounded ${message.sender === 'user' ? 'bg-primary text-white' : 'bg-light text-dark'} position-relative">
                <small class="d-block fw-bold">${message.sender === 'user' ? 'You' : 'Support'}</small>
                ${content}
                <small class="d-block text-muted">${new Date(message.created_at).toLocaleTimeString()} ${statusIcon}</small>
                ${message.reaction ? `<div class="reaction">${message.reaction}</div>` : ''}
                <div class="message-actions d-none">
                    <button class="btn btn-sm btn-outline-secondary react-btn" title="React"><i class="fas fa-smile"></i></button>
                    <button class="btn btn-sm btn-outline-secondary pin-btn" title="Pin"><i class="fas fa-thumbtack"></i></button>
                    <button class="btn btn-sm btn-outline-secondary forward-btn" title="Forward"><i class="fas fa-share"></i></button>
                    <button class="btn btn-sm btn-outline-secondary delete-btn" title="Delete"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        `;

        // Add event listeners for reactions and deletion
        messageDiv.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            const actions = messageDiv.querySelector('.message-actions');
            actions.classList.toggle('d-none');
        });

        messageDiv.querySelector('.react-btn')?.addEventListener('click', () => reactToMessage(message.id, '👍'));
        messageDiv.querySelector('.pin-btn')?.addEventListener('click', () => pinMessage(message.id));
        messageDiv.querySelector('.forward-btn')?.addEventListener('click', () => forwardMessage(message.id));
        messageDiv.querySelector('.delete-btn')?.addEventListener('click', () => deleteMessage(message.id));

        chatMessages.appendChild(messageDiv);
    });
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

async function reactToMessage(messageId, reaction) {
    try {
        await fetch('/api/chat/react', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ message_id: messageId, reaction: reaction })
        });
        await loadMessages();
    } catch (error) {
        console.error('Error reacting to message:', error);
    }
}

async function deleteMessage(messageId) {
    try {
        await fetch('/api/chat/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ message_id: messageId })
        });
        await loadMessages();
    } catch (error) {
        console.error('Error deleting message:', error);
    }
}

function notifyNewMessage() {
    if (Notification.permission === 'granted') {
        new Notification('New message', { body: 'You have a new message in chat' });
    }
}

let mediaRecorder;
let audioChunks = [];

function startRecording() {
    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(stream => {
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.start();
            audioChunks = [];

            mediaRecorder.addEventListener('dataavailable', event => {
                audioChunks.push(event.data);
            });

            mediaRecorder.addEventListener('stop', () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const audioFile = new File([audioBlob], 'voice_message.wav', { type: 'audio/wav' });
                sendMessage('audio', audioFile);
            });

            recordBtn.classList.add('d-none');
            stopRecordBtn.classList.remove('d-none');
        });
}

function stopRecording() {
    mediaRecorder.stop();
    recordBtn.classList.remove('d-none');
    stopRecordBtn.classList.add('d-none');
}

async function searchGifs() {
    const query = gifSearch.value;
    if (!query) return;

    // For demo, use placeholder GIFs
    const gifs = [
        'https://media.giphy.com/media/3o7TKz9bX9Z9Z9Z9Z9/giphy.gif',
        'https://media.giphy.com/media/l0MYJnJQ4EiYLxvQ8/giphy.gif',
    ];

    gifResults.innerHTML = '';
    gifs.forEach(gif => {
        const img = document.createElement('img');
        img.src = gif;
        img.className = 'm-1';
        img.style.width = '100px';
        img.style.cursor = 'pointer';
        img.addEventListener('click', () => sendGif(gif));
        gifResults.appendChild(img);
    });
}

async function sendGif(gifUrl) {
    await sendMessage('gif', null, null, gifUrl);
    gifPicker.classList.add('d-none');
}

async function shareLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(async (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            await sendLocation(lat, lng);
        });
    }
}

async function sendLocation(lat, lng) {
    try {
        const response = await fetch('/api/chat/location', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ chat_id: chatId, lat: lat, lng: lng })
        });
        await loadMessages();
    } catch (error) {
        console.error('Error sending location:', error);
    }
}

async function sendContact(name, phone) {
    try {
        const response = await fetch('/api/chat/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ chat_id: chatId, name: name, phone: phone })
        });
        await loadMessages();
    } catch (error) {
        console.error('Error sending contact:', error);
    }
}

async function searchMessages() {
    const query = chatSearchInput.value.trim();
    if (!query) return;

    try {
        const response = await fetch(`/api/chat/search?chat_id=${chatId}&query=${encodeURIComponent(query)}`);
        const messages = await response.json();
        renderMessages(messages);
    } catch (error) {
        console.error('Error searching messages:', error);
    }
}

async function pinMessage(messageId) {
    try {
        await fetch('/api/chat/pin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ message_id: messageId })
        });
        await loadMessages();
    } catch (error) {
        console.error('Error pinning message:', error);
    }
}

async function forwardMessage(messageId) {
    // For simplicity, forward to same chat
    try {
        await fetch('/api/chat/forward', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ message_id: messageId, chat_id: chatId })
        });
        await loadMessages();
    } catch (error) {
        console.error('Error forwarding message:', error);
    }
}

// Initialize the application
document.addEventListener('DOMContentLoaded', init);

// Load statistics from API
async function loadStatistics() {
    try {
        const response = await fetch('/api/statistics');
        const data = await response.json();
        updateStatisticsCards(data.statistics, data.overviewStats);
    } catch (error) {
        console.error('Error loading statistics:', error);
    }
}

// Load trainer statistics
async function loadTrainerStatistics() {
    try {
        const response = await fetch('/api/trainers/statistics');
        const data = await response.json();
        updateTrainerStatisticsCards(data);
    } catch (error) {
        console.error('Error loading trainer statistics:', error);
    }
}

// Update statistics cards with data from API
function updateStatisticsCards(statistics, overviewStats) {
    // Update Total Parts Group
    document.getElementById('totalParts').textContent = statistics.total_parts;
    
    // Update sub-cards for Total Parts
    const totalPartsCards = document.querySelectorAll('.stat-group')[0].querySelectorAll('.stat-number');
    if (totalPartsCards.length >= 4) {
        totalPartsCards[1].textContent = statistics.epc_parts;
        totalPartsCards[2].textContent = statistics.air_fryer_parts;
        totalPartsCards[3].textContent = statistics.induction_parts;
    }

    // Update Available Parts Group
    document.getElementById('availableParts').textContent = statistics.available_parts;
    
    // Update sub-cards for Available Parts
    const availablePartsCards = document.querySelectorAll('.stat-group')[1].querySelectorAll('.stat-number');
    if (availablePartsCards.length >= 4) {
        availablePartsCards[1].textContent = statistics.available_epc_parts;
        availablePartsCards[2].textContent = statistics.available_air_fryer_parts;
        availablePartsCards[3].textContent = statistics.available_induction_parts;
    }

    // Update Overview Group
    const overviewCards = document.querySelectorAll('.stat-group')[2].querySelectorAll('.stat-number');
    if (overviewCards.length >= 3) {
        overviewCards[0].textContent = overviewStats.stock_percentage + '%';
        overviewCards[1].textContent = overviewStats.total_brands;
        overviewCards[2].textContent = overviewStats.total_appliances;
    }
}

// Update trainer statistics cards
function updateTrainerStatisticsCards(data) {
    const trainerDetailsStats = document.querySelectorAll('[id^="trainerDetails"]');
    if (document.getElementById('trainerDetailsTrainingsCompleted')) {
        document.getElementById('trainerDetailsTrainingsCompleted').textContent = data.total_trainings || 0;
    }
    if (document.getElementById('trainerDetailsStudents')) {
        document.getElementById('trainerDetailsStudents').textContent = data.total_students || 0;
    }
    if (document.getElementById('trainerDetailsTrainings')) {
        document.getElementById('trainerDetailsTrainings').textContent = data.total_sessions || 0;
    }
    if (document.getElementById('trainerDetailsId')) {
        document.getElementById('trainerDetailsId').textContent = data.total_trainers || 0;
    }
}


// Load reports data
async function loadReportsData() {
    if (dataLoaded.reports) return;
    try {
        const [parts, appliances, trainers, technicians] = await Promise.all([
            inventoryData.length > 0 ? Promise.resolve(inventoryData) : fetch('/api/parts').then(r => r.json()),
            appliancesData.length > 0 ? Promise.resolve(appliancesData) : fetch('/api/appliances').then(r => r.json()),
            trainersData.length > 0 ? Promise.resolve(trainersData) : fetch('/api/trainers').then(r => r.json()),
            techniciansData.length > 0 ? Promise.resolve(techniciansData) : fetch('/api/technicians').then(r => r.json())
        ]);
        
        dataLoaded.reports = true;
        updateReportMetrics(parts, appliances, trainers, technicians);
        renderReportCharts(parts, appliances);
        populateReportTables(parts, appliances, trainers, technicians);
    } catch (error) {
        console.error('Error loading reports:', error);
    }
}

function updateReportMetrics(parts, appliances, trainers, technicians) {
    const reportDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    document.getElementById('reportDate').textContent = reportDate;
    document.getElementById('reportFooterDate').textContent = reportDate;
    
    const availableParts = parts.filter(p => p.availability).length;
    const brands = [...new Set(parts.flatMap(p => p.brands))];
    const availabilityRate = parts.length > 0 ? ((availableParts / parts.length) * 100).toFixed(1) : 0;
    const unavailableParts = parts.length - availableParts;
    const availableTechs = technicians.filter(t => t.status === 'Available').length;
    const techAvailabilityRate = technicians.length > 0 ? ((availableTechs / technicians.length) * 100).toFixed(1) : 0;
    const topBrands = Object.entries(brands.reduce((acc, b) => ({ ...acc, [b]: parts.filter(p => p.brands.includes(b)).length }), {})).sort((a, b) => b[1] - a[1]).slice(0, 2);

    document.getElementById('reportTotalParts').textContent = parts.length;
    document.getElementById('reportTotalAppliances').textContent = appliances.length;
    document.getElementById('reportTotalTrainers').textContent = trainers.length;
    document.getElementById('reportTotalTechnicians').textContent = technicians.length;
    document.getElementById('reportAvailableParts').textContent = availableParts;
    document.getElementById('reportUnavailableParts').textContent = unavailableParts;
    document.getElementById('reportTotalBrands').textContent = brands.length;
    document.getElementById('reportAvailabilityRate').textContent = availabilityRate + '%';

    document.getElementById('reportTrainersActive').textContent = trainers.filter(t => t.status === 'Active').length;
    document.getElementById('reportTrainersInactive').textContent = trainers.filter(t => t.status === 'Inactive').length;
    document.getElementById('reportTrainersLeave').textContent = trainers.filter(t => t.status === 'On Leave').length;

    document.getElementById('reportTechniciansAvailable').textContent = availableTechs;
    document.getElementById('reportTechniciansBusy').textContent = technicians.filter(t => t.status === 'Busy').length;
    document.getElementById('reportTechniciansUnavailable').textContent = technicians.filter(t => t.status === 'Unavailable').length;

    const trainerSpecialties = {};
    trainers.forEach(t => trainerSpecialties[t.specialty] = (trainerSpecialties[t.specialty] || 0) + 1);
    document.getElementById('reportTrainersSpecialties').innerHTML = Object.entries(trainerSpecialties)
        .map(([s, c]) => `<span class="badge bg-secondary me-1">${s}: ${c}</span>`).join('');

    const techSpecialties = {};
    technicians.forEach(t => techSpecialties[t.specialty] = (techSpecialties[t.specialty] || 0) + 1);
    document.getElementById('reportTechniciansSpecialties').innerHTML = Object.entries(techSpecialties)
        .map(([s, c]) => `<span class="badge bg-secondary me-1">${s}: ${c}</span>`).join('');

    // Populate recommendations
    document.getElementById('recAvailabilityRate').textContent = availabilityRate + '%';
    document.getElementById('recUnavailableParts').textContent = unavailableParts;
    document.getElementById('recBrand1').textContent = topBrands[0]?.[0] || 'N/A';
    document.getElementById('recBrand2').textContent = topBrands[1]?.[0] || 'N/A';
    document.getElementById('recTechAvailability').textContent = techAvailabilityRate + '%';
    document.getElementById('recTotalTrainers').textContent = trainers.length;
}

function renderReportCharts(parts, appliances) {
    const partsByAppliance = {};
    parts.forEach(p => partsByAppliance[p.applianceType] = (partsByAppliance[p.applianceType] || 0) + 1);
    
    new Chart(document.getElementById('reportPartsChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(partsByAppliance),
            datasets: [{ label: 'Parts Count', data: Object.values(partsByAppliance), backgroundColor: ['#667eea', '#f093fb', '#4facfe', '#11998e', '#ffa726'] }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    const available = parts.filter(p => p.availability).length;
    const total = parts.length;
    
    new Chart(document.getElementById('reportAvailabilityChart'), {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Not Available'],
            datasets: [{ data: [available, total - available], backgroundColor: ['#11998e', '#f5576c'] }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                datalabels: { color: '#fff', font: { weight: 'bold', size: 14 }, formatter: (v) => total > 0 ? ((v / total) * 100).toFixed(1) + '%' : '0%' }
            }
        },
        plugins: [ChartDataLabels]
    });

    const appStatus = {};
    appliances.forEach(a => appStatus[a.status] = (appStatus[a.status] || 0) + 1);
    const appTotal = Object.values(appStatus).reduce((a, b) => a + b, 0);
    
    new Chart(document.getElementById('reportAppliancesChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(appStatus),
            datasets: [{ data: Object.values(appStatus), backgroundColor: ['#11998e', '#4facfe', '#f5576c'] }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                datalabels: { color: '#fff', font: { weight: 'bold', size: 14 }, formatter: (v) => appTotal > 0 ? ((v / appTotal) * 100).toFixed(1) + '%' : '0%' }
            }
        },
        plugins: [ChartDataLabels]
    });

    const brands = {};
    parts.forEach(p => p.brands.forEach(b => brands[b] = (brands[b] || 0) + 1));
    const topBrands = Object.entries(brands).sort((a, b) => b[1] - a[1]).slice(0, 5);
    
    new Chart(document.getElementById('reportBrandsChart'), {
        type: 'bar',
        data: {
            labels: topBrands.map(b => b[0]),
            datasets: [{ label: 'Parts Count', data: topBrands.map(b => b[1]), backgroundColor: '#ffa726' }]
        },
        options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } } }
    });
}

function populateReportTables(parts, appliances, trainers, technicians) {
    document.getElementById('reportPartsTable').innerHTML = parts.slice(0, 20).map(p => `
        <tr>
            <td>${p.partNumber}</td>
            <td>${p.name}</td>
            <td><span class="badge bg-primary">${p.applianceType}</span></td>
            <td>${p.brands.join(', ')}</td>
            <td><span class="badge ${p.availability ? 'bg-success' : 'bg-secondary'}">${p.availability ? 'Available' : 'Not Available'}</span></td>
        </tr>
    `).join('');

    document.getElementById('reportAppliancesTable').innerHTML = appliances.slice(0, 20).map(a => `
        <tr>
            <td>${a.name}</td>
            <td>${a.brand || 'N/A'}</td>
            <td>${a.model || 'N/A'}</td>
            <td>${a.power || 'N/A'}</td>
            <td><span class="badge ${a.status === 'Available' ? 'bg-success' : a.status === 'In Use' ? 'bg-info' : 'bg-danger'}">${a.status}</span></td>
        </tr>
    `).join('');

    document.getElementById('reportTrainersTable').innerHTML = trainers.slice(0, 20).map(t => `
        <tr>
            <td>${t.name}</td>
            <td>${t.specialty}</td>
            <td>${t.experience} years</td>
            <td>${t.location || 'N/A'}</td>
            <td><span class="badge ${t.status === 'Active' ? 'bg-success' : 'bg-secondary'}">${t.status}</span></td>
        </tr>
    `).join('');

    document.getElementById('reportTechniciansTable').innerHTML = technicians.slice(0, 20).map(t => `
        <tr>
            <td>${t.name}</td>
            <td>${t.specialty}</td>
            <td>${t.experience} years</td>
            <td>${t.location}</td>
            <td><span class="badge ${t.status === 'Available' ? 'bg-success' : t.status === 'Busy' ? 'bg-warning' : 'bg-danger'}">${t.status}</span></td>
        </tr>
    `).join('');
}

// Settings functions
function loadSettings() {
    document.getElementById('lastUpdated').textContent = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    updateSystemInfo();
}

function updateSystemInfo() {
    const totalRecords = (inventoryData.length || 0) + (appliancesData.length || 0) + (trainersData.length || 0) + (techniciansData.length || 0);
    document.getElementById('totalRecords').textContent = totalRecords;
    document.getElementById('dbSize').textContent = (totalRecords * 0.5).toFixed(2) + ' KB';
}

function exportData(format) {
    alert(`Exporting data to ${format.toUpperCase()} format...`);
}

function backupData() {
    alert('Creating database backup...');
}

function clearCache() {
    if (confirm('Are you sure you want to clear the cache?')) {
        dataLoaded = { inventory: false, appliances: false, trainers: false, technicians: false, reports: false };
        alert('Cache cleared successfully!');
    }
}


// Login form handler
document.getElementById('loginForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    // Simple validation (replace with actual authentication)
    if (email && password) {
        alert('Login functionality will be implemented with backend authentication.');
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('loginModal')).hide();
    }
});
