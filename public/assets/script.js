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
let currentUser = null;

// Authentication functions
async function login(email, password) {
    try {
        const response = await fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ email, password }),
            redirect: 'manual' // Don't follow redirects automatically
        });

        if (response.status === 200) {
            const data = await response.json();
            if (data.success) {
                currentUser = data.user;
                updateUIForLoggedInUser();
                return { success: true, user: data.user };
            } else {
                return { success: false, message: data.message };
            }
        } else if (response.status === 302 || response.status === 301) {
            // Redirect response, login successful
            window.location.href = response.headers.get('Location') || '/';
            return { success: true };
        } else {
            const data = await response.json().catch(() => ({ message: 'Login failed' }));
            return { success: false, message: data.message };
        }
    } catch (error) {
        console.error('Login error:', error);
        return { success: false, message: 'Login failed. Please try again.' };
    }
}

async function logout() {
    try {
        const response = await fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        if (response.ok) {
            currentUser = null;
            updateUIForLoggedOutUser();
            location.reload();
        }
    } catch (error) {
        console.error('Logout error:', error);
    }
}

async function checkAuthStatus() {
    try {
        const response = await fetch('/user');
        if (response.ok) {
            currentUser = await response.json();
            updateUIForLoggedInUser();
        } else {
            updateUIForLoggedOutUser();
        }
    } catch (error) {
        updateUIForLoggedOutUser();
    }
}

function updateUIForLoggedInUser() {
    if (!currentUser) return;

    // Hide login section and show user info section
    const loginSection = document.getElementById('loginSection');
    const userInfoSection = document.getElementById('userInfoSection');

    if (loginSection) loginSection.classList.add('d-none');
    if (userInfoSection) userInfoSection.classList.remove('d-none');

    // Update user info
    const userName = document.getElementById('userName');
    const userRole = document.getElementById('userRole');

    if (userName) userName.textContent = currentUser.name;
    if (userRole) {
        userRole.textContent = currentUser.role.toUpperCase();
        // Set role badge color
        userRole.className = 'badge ' + getRoleBadgeClass(currentUser.role);
    }

    // Update guest access section to show logged in status
    const guestAccessSection = document.getElementById('guestAccessSection');
    if (guestAccessSection) {
        guestAccessSection.innerHTML = `
            <span class="d-flex align-items-center">
                <i class="fas fa-check-circle me-1 text-success" style="font-size: 0.7rem;"></i>
                <span class="fw-bold text-success" style="font-size: 0.7rem;">Authenticated User</span>
            </span>
        `;
    }

    // Update user access section
    const userAccessSection = document.getElementById('userAccessSection');
    if (userAccessSection) {
        userAccessSection.innerHTML = `
            <span class="d-flex align-items-center">
                <i class="fas fa-shield-alt me-1 text-success" style="font-size: 0.7rem;"></i>
                <span class="fw-bold text-success" style="font-size: 0.7rem;">${getAccessLevel(currentUser.role)}</span>
            </span>
        `;
    }

    // Set up logout functionality
    const logoutLink = document.getElementById('logoutLink');
    if (logoutLink) {
        logoutLink.onclick = (e) => {
            e.preventDefault();
            logout();
        };
    }

    // Show/hide sections based on role
    updateSectionVisibility();
}

function updateUIForLoggedOutUser() {
    // Show login section and hide user info section
    const loginSection = document.getElementById('loginSection');
    const userInfoSection = document.getElementById('userInfoSection');

    if (loginSection) loginSection.classList.remove('d-none');
    if (userInfoSection) userInfoSection.classList.add('d-none');

    // Reset guest access section
    const guestAccessSection = document.getElementById('guestAccessSection');
    if (guestAccessSection) {
        guestAccessSection.innerHTML = `
            <span class="d-flex align-items-center">
                <i class="fas fa-user-friends me-1 text-info" style="font-size: 0.7rem;"></i>
                <span class="fw-bold text-info" style="font-size: 0.7rem;">User access</span>
            </span>
        `;
    }

    // Reset user access section
    const userAccessSection = document.getElementById('userAccessSection');
    if (userAccessSection) {
        userAccessSection.innerHTML = `
            <span class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-1 text-warning" style="font-size: 0.7rem;"></i>
                <span class="fw-bold text-warning" style="font-size: 0.7rem;">Authentication Required</span>
            </span>
        `;
    }
}

function getRoleBadgeClass(role) {
    switch(role) {
        case 'admin': return 'bg-danger';
        case 'trainer': return 'bg-warning text-dark';
        case 'technician': return 'bg-info';
        default: return 'bg-secondary';
    }
}

function getAccessLevel(role) {
    switch(role) {
        case 'admin': return 'Full System Access';
        case 'trainer': return 'Training & Reports Access';
        case 'technician': return 'Inventory Access Only';
        default: return 'Limited Access';
    }
}

function updateSectionVisibility() {
    if (!currentUser) return;

    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');

        // Admin can access everything
        if (currentUser.role === 'admin') {
            link.style.display = '';
            return;
        }

        // Trainer access
        if (currentUser.role === 'trainer') {
            if (href === '#trainers' || href === '#qualified-technicians' || href === '#reports') {
                link.style.display = '';
            } else if (href === '#settings') {
                link.style.display = 'none';
            }
            return;
        }

        // Technician access (most restricted)
        if (currentUser.role === 'technician') {
            if (href === '#dashboard' || href === '#inventory' || href === '#appliances') {
                link.style.display = '';
            } else {
                link.style.display = 'none';
            }
        }
    });
}

function hasPermission(section) {
    if (!currentUser) return true; // Guest can view all

    const role = currentUser.role;

    // Admin has access to everything
    if (role === 'admin') return true;

    // Trainer access
    if (role === 'trainer') {
        return ['dashboard-section', 'inventory', 'appliances', 'trainers', 'qualified-technicians', 'reports'].includes(section);
    }

    // Technician access (most restricted)
    if (role === 'technician') {
        return ['dashboard-section', 'inventory', 'appliances'].includes(section);
    }

    return true; // Default allow for guests
}

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

// Cache flags - removed to always fetch fresh data
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

    // Check authentication status
    await checkAuthStatus();

    document.getElementById('inventory').style.display = 'none';
    document.getElementById('appliances').style.display = 'none';
    document.getElementById('trainers').style.display = 'none';
    document.getElementById('qualified-technicians').style.display = 'none';
    document.getElementById('reports').style.display = 'none';
    window.scrollTo(0, 0);

    // Check URL hash on load and load data
    const hash = window.location.hash;
    if (hash === '#inventory-stats' || hash === '#inventory') {
        document.getElementById('dashboard-section').style.display = 'none';
        document.getElementById('inventory').style.display = 'block';
        loadInventoryData();
    }

    Promise.all([
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
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        techniciansData = await response.json();
        filteredTechniciansData = [...techniciansData];
        dataLoaded.technicians = true;
        updateTechnicianStatistics();
        renderTechnicians();
    } catch (error) {
        console.error('Error loading technicians data:', error);
        showNotification('Failed to load technicians data', 'error');
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
    if (!inventoryGrid) {
        console.error('Inventory grid element not found');
        return;
    }
    
    inventoryGrid.innerHTML = `
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 text-muted">Loading parts data...</p>
        </div>
    `;
    
    try {
        const response = await fetch('/api/parts');
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        
        inventoryData = await response.json();
        filteredData = [...inventoryData];
        dataLoaded.inventory = true;
        
        console.log('Parts loaded:', inventoryData.length);
        
        if (inventoryData.length === 0) {
            inventoryGrid.innerHTML = `
                <div class="col-12"><div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>No parts available.
                </div></div>
            `;
        } else {
            renderInventory();
        }
    } catch (error) {
        console.error('Error:', error);
        inventoryGrid.innerHTML = `
            <div class="col-12"><div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>Error: ${error.message}
                <button class="btn btn-sm btn-outline-danger ms-3" onclick="loadInventoryData()">
                    <i class="fas fa-redo me-1"></i>Retry
                </button>
            </div></div>
        `;
    }
}

// Load appliances data from API
async function loadAppliancesData() {
    if (dataLoaded.appliances) return;
    try {
        const response = await fetch('/api/appliances');
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        appliancesData = await response.json();
        dataLoaded.appliances = true;
        renderAppliances();
    } catch (error) {
        console.error('Error loading appliances data:', error);
        showNotification('Failed to load appliances data', 'error');
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

    const imageHtml = appliance.image 
        ? `<img src="/storage/${appliance.image}" alt="${appliance.name}" style="width:100%;height:150px;object-fit:cover;border-radius:8px 8px 0 0;">`
        : `<div class="bg-${appliance.color || 'primary'} d-flex align-items-center justify-content-center" style="width:100%;height:150px;border-radius:8px 8px 0 0;">
            <i class="fas fa-${appliance.icon || 'tv'} text-white" style="font-size:3rem;"></i>
          </div>`;

    card.innerHTML = `
        <div class="card p-0 h-100" style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            ${imageHtml}
            <div class="p-3">
                <div class="d-flex align-items-center mb-3">
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
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-calendar me-2"></i>
                        <span>${new Date(appliance.created_at).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</span>
                    </div>
                    ${appliance.price ? `<div class="d-flex align-items-center">
                        <i class="fas fa-tag me-2"></i>
                        <span class="fw-bold text-success">UGX ${parseFloat(appliance.price).toLocaleString()}</span>
                    </div>` : ''}
                </div>
                ${appliance.description ? `<div class="mt-3 pt-3 border-top"><small class="text-muted">${appliance.description.substring(0, 100)}...</small></div>` : ''}
            </div>
        </div>
    `;

    card.addEventListener('click', async () => {
        const partsCount = await fetchAppliancePartsCount(appliance.id);
        viewApplianceDetails(appliance.id, appliance.name, appliance.brand || 'N/A', appliance.model || 'N/A',
            appliance.power || 'N/A', appliance.sku || 'N/A', appliance.status, appliance.description || '',
            appliance.icon || 'tools', appliance.color || 'primary', appliance.price || '', appliance.created_at, partsCount, appliance.image, appliance);
    });

    // Add edit button for admin
    if (currentUser && currentUser.role === 'admin') {
        const editBtn = document.createElement('button');
        editBtn.className = 'btn btn-sm btn-warning position-absolute top-0 end-0 m-2';
        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
        editBtn.onclick = (e) => {
            e.stopPropagation();
            editAppliance(appliance);
        };
        card.querySelector('.card').style.position = 'relative';
        card.querySelector('.card').appendChild(editBtn);
    }

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
            appliance.icon || 'tools', appliance.color || 'primary', appliance.price || '', appliance.created_at, partsCount, appliance.image, appliance);
    });

    return row;
}

// Load trainers data from API
async function loadTrainersData() {
    if (dataLoaded.trainers) return;
    try {
        const response = await fetch('/api/trainers');
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        trainersData = await response.json();
        filteredTrainersData = [...trainersData];
        dataLoaded.trainers = true;
        renderTrainers();
        populateTrainerFilters();
    } catch (error) {
        console.error('Error loading trainers data:', error);
        showNotification('Failed to load trainers data', 'error');
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

    let badgeClass = item.badgeClass || 'bg-secondary';

    card.innerHTML = `
        <div class="card h-100 shadow-sm" style="position:relative;">
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
                    ${item.price ? `<div class="mb-2"><span class="badge bg-success">UGX ${parseFloat(item.price).toLocaleString()}</span></div>` : ''}
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

    if (currentUser && currentUser.role === 'admin') {
        const editBtn = document.createElement('button');
        editBtn.className = 'btn btn-sm btn-warning position-absolute';
        editBtn.style.cssText = 'top: 8px; right: 8px; z-index: 10;';
        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
        editBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (typeof window.editPart === 'function') {
                window.editPart(item.id);
            } else {
                window.location.href = '/admin#parts-management';
            }
        });
        card.querySelector('.card').appendChild(editBtn);
    }
    
    card.addEventListener('click', (e) => {
        if (!e.target.closest('button')) {
            openModal(item);
        }
    });
    
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
        <td>${item.price ? 'UGX ' + parseFloat(item.price).toLocaleString() : 'N/A'}</td>
        <td>
            <span class="badge ${item.availability ? 'bg-success' : 'bg-secondary'}">
                ${item.availability ? 'Available' : 'Not Available'}
            </span>
        </td>
        <td>
            <button class="btn btn-sm btn-outline-primary view-details-btn me-1">
                <i class="fas fa-eye"></i> View
            </button>
            ${currentUser && currentUser.role === 'admin' ? `
                <button class="btn btn-sm btn-outline-warning edit-part-btn">
                    <i class="fas fa-edit"></i> Edit
                </button>
            ` : ''}
        </td>
    `;

    row.querySelector('.view-details-btn').addEventListener('click', (e) => {
        e.stopPropagation();
        openModal(item);
    });
    
    if (currentUser && currentUser.role === 'admin') {
        row.querySelector('.edit-part-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            if (typeof window.editPart === 'function') {
                window.editPart(item.id);
            } else {
                window.location.href = '/admin#parts-management';
            }
        });
    }

    return row;
}

// Create a trainer card for grid view
function createTrainerCard(trainer) {
    const card = document.createElement('div');
    card.className = 'col-lg-4 col-md-6 col-sm-12 trainer-card';

    const initials = trainer.name.split(' ').map(n => n[0]).join('').toUpperCase();
    const profilePic = trainer.image || trainer.profile_picture;
    const avatarContent = profilePic 
        ? `<img src="/storage/${profilePic}" alt="${trainer.name}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`
        : `<span class="text-white fw-bold fs-5">${initials}</span>`;

    const canEdit = currentUser && (
        currentUser.role === 'admin' ||
        (currentUser.role === 'trainer' && currentUser.email === trainer.email)
    );

    card.innerHTML = `
        <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; overflow: hidden;">
                        ${avatarContent}
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
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-money-bill me-2"></i>
                        <span class="fw-bold text-success">UGX ${parseFloat(trainer.hourly_rate || 0).toLocaleString()}/hour</span>
                    </div>
                    <div class="d-flex align-items-center mb-1">
                        <i class="fas fa-box me-2"></i>
                        <span class="fw-bold">Available: ${trainer.quantity || trainer.available_stock || 0} in stock</span>
                    </div>
                </div>
                <div class="mt-auto">
                    <div class="text-muted small mb-2">
                        <strong>Qualifications:</strong> ${trainer.qualifications || 'Not specified'}
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary flex-fill view-trainer-btn" onclick="event.stopPropagation()">
                            <i class="fas fa-eye"></i> View
                        </button>
                        ${canEdit ? `<button class="btn btn-sm btn-outline-success flex-fill edit-trainer-btn" onclick="event.stopPropagation()">
                            <i class="fas fa-edit"></i> Edit
                        </button>` : ''}
                    </div>
                </div>
            </div>
        </div>
    `;

    card.querySelector('.view-trainer-btn').addEventListener('click', (e) => {
        e.stopPropagation();
        viewTrainerDetails(trainer);
    });

    if (canEdit) {
        card.querySelector('.edit-trainer-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            openTrainerEditModal(trainer);
        });
    }

    return card;
}

// Create a trainer table row for list view
function createTrainerListRow(trainer) {
    const row = document.createElement('tr');

    // Check if current user can edit this trainer
    const canEdit = currentUser && (
        currentUser.role === 'admin' ||
        (currentUser.role === 'trainer' && currentUser.email === trainer.email)
    );

    row.innerHTML = `
        <td>${trainer.name}</td>
        <td>${trainer.specialty}</td>
        <td>${trainer.email}</td>
        <td>${trainer.phone}</td>
        <td>${trainer.experience} years</td>
        <td><span class="fw-bold text-success">UGX ${parseFloat(trainer.hourly_rate || 0).toLocaleString()}/hour</span></td>
        <td>${trainer.location || 'Not specified'}</td>
        <td>
            <button class="btn btn-sm btn-outline-primary view-trainer-btn me-1" data-id="${trainer.id}">
                <i class="fas fa-eye"></i> View
            </button>
            ${canEdit ? `<button class="btn btn-sm btn-outline-success edit-trainer-btn" data-id="${trainer.id}">
                <i class="fas fa-edit"></i> Edit
            </button>` : ''}
        </td>
    `;

    row.querySelector('.view-trainer-btn').addEventListener('click', () => viewTrainerDetails(trainer));
    if (canEdit) {
        row.querySelector('.edit-trainer-btn').addEventListener('click', () => openTrainerEditModal(trainer));
    }

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

// Open trainer edit modal with pre-filled data
function openTrainerEditModal(trainer) {
    const modal = document.getElementById('trainerModal');
    const form = document.getElementById('trainerForm');
    const modalTitle = document.getElementById('trainerModalLabel');

    modalTitle.innerHTML = '<i class="fas fa-user-edit me-2"></i>Edit Trainer Details';
    document.getElementById('trainerId').value = trainer.id;

    // Fill all form fields
    document.getElementById('trainerFirstName').value = trainer.first_name || trainer.name.split(' ')[0] || '';
    document.getElementById('trainerMiddleName').value = trainer.middle_name || '';
    document.getElementById('trainerLastName').value = trainer.last_name || trainer.name.split(' ').slice(1).join(' ') || '';
    document.getElementById('trainerGender').value = trainer.gender || '';
    document.getElementById('trainerDOB').value = trainer.date_of_birth || '';
    document.getElementById('trainerNationality').value = trainer.nationality || 'Ugandan';
    document.getElementById('trainerIDNumber').value = trainer.id_number || '';
    document.getElementById('trainerEmail').value = trainer.email;
    document.getElementById('trainerPhone').value = trainer.phone;
    document.getElementById('trainerWhatsapp').value = trainer.whatsapp || '';
    document.getElementById('trainerEmergencyContact').value = trainer.emergency_contact || '';
    document.getElementById('trainerEmergencyPhone').value = trainer.emergency_phone || '';
    document.getElementById('trainerCountry').value = trainer.country || 'Uganda';
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
    document.getElementById('trainerNotes').value = trainer.notes || '';

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

    const profilePic = trainer.image || trainer.profile_picture;
    const trainerDetailsImage = document.getElementById('trainerDetailsImage');
    
    if (profilePic) {
        trainerDetailsImage.innerHTML = `<img src="/storage/${profilePic}" alt="${trainer.name}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
    } else {
        const initials = trainer.name.split(' ').map(n => n[0]).join('').toUpperCase();
        trainerDetailsImage.textContent = initials;
    }
    document.getElementById('trainerDetailsName').textContent = trainer.name;
    document.getElementById('trainerDetailsSpecialty').textContent = trainer.specialty;
    document.getElementById('trainerDetailsEmail').textContent = trainer.email;
    document.getElementById('trainerDetailsPhone').textContent = trainer.phone;
    document.getElementById('trainerDetailsWhatsapp').textContent = trainer.whatsapp || trainer.phone;
    document.getElementById('trainerDetailsLocation').textContent = trainer.location || 'Not specified';
    document.getElementById('trainerDetailsExperience').textContent = `${trainer.experience} years`;
    document.getElementById('trainerDetailsRate').textContent = trainer.hourly_rate ? `UGX ${parseFloat(trainer.hourly_rate).toLocaleString()}/hr` : 'Not set';
    document.getElementById('trainerDetailsLicense').textContent = trainer.license_number || 'N/A';
    document.getElementById('trainerDetailsStock').textContent = trainer.quantity || trainer.available_stock || 0;
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

    document.getElementById('trainerDetailsRating').textContent = trainer.rating || 'N/A';
    document.getElementById('trainerDetailsStatus').textContent = trainer.status || 'Active';

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

    const canEdit = currentUser && (
        currentUser.role === 'admin' ||
        currentUser.role === 'trainer' ||
        (currentUser.role === 'technician' && currentUser.email === tech.email)
    );

    const profilePic = tech.profile_photo || tech.image || tech.profile_picture;
    const avatarContent = profilePic 
        ? `<img src="/storage/${profilePic}" alt="${tech.name}" style="width:100%;height:100%;object-fit:cover;">`
        : `<span class="text-white fw-bold">${initials}</span>`;

    card.innerHTML = `
        <div class="card p-4 technician-card">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-${badgeClass} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; overflow: hidden;">
                    ${avatarContent}
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
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-money-bill me-2"></i>
                    <span class="fw-bold text-success">UGX ${parseFloat(tech.hourly_rate || tech.rate || 0).toLocaleString()}/hour</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <i class="fas fa-box me-2"></i>
                    <span class="fw-bold">Available: ${tech.quantity || tech.available_stock || 0} in stock</span>
                </div>
            </div>
            <div class="text-muted small mb-2">
                <strong>Skills:</strong> ${skills}
            </div>
            <div class="text-muted small mb-3">
                <strong>Certifications:</strong> ${certs}
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-primary flex-fill view-tech-btn">
                    <i class="fas fa-eye"></i> View
                </button>
                ${canEdit ? `<button class="btn btn-sm btn-outline-success flex-fill edit-tech-btn">
                    <i class="fas fa-edit"></i> Edit
                </button>` : ''}
            </div>
        </div>
    `;

    card.querySelector('.view-tech-btn').addEventListener('click', () => viewTechnicianDetails(tech));
    if (canEdit) {
        card.querySelector('.edit-tech-btn').addEventListener('click', () => openTechnicianEditModal(tech));
    }
    return card;
}

// Create technician list row
function createTechnicianListRow(tech) {
    const row = document.createElement('tr');
    const initials = tech.name.split(' ').map(n => n[0]).join('').toUpperCase();
    const statusClass = tech.status === 'Available' ? 'success' : (tech.status === 'Busy' ? 'warning' : 'danger');
    const badgeClass = tech.status === 'Busy' ? 'warning' : 'info';

    // Check if current user can edit this technician
    const canEdit = currentUser && (
        currentUser.role === 'admin' ||
        currentUser.role === 'trainer' ||
        (currentUser.role === 'technician' && currentUser.email === tech.email)
    );

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
        <td><span class="fw-bold text-success">UGX ${parseFloat(tech.hourly_rate || tech.rate || 0).toLocaleString()}/hour</span></td>
        <td><span class="badge bg-${statusClass}">${tech.status}</span></td>
        <td>
            <button class="btn btn-sm btn-outline-primary view-technician-btn me-1"><i class="fas fa-eye"></i> View</button>
            ${canEdit ? `<button class="btn btn-sm btn-outline-success edit-technician-btn"><i class="fas fa-edit"></i> Edit</button>` : ''}
        </td>
    `;

    row.querySelector('.view-technician-btn').addEventListener('click', () => viewTechnicianDetails(tech));
    if (canEdit) {
        row.querySelector('.edit-technician-btn').addEventListener('click', () => openTechnicianEditModal(tech));
    }
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
    const profilePic = tech.profile_photo || tech.image || tech.profile_picture;
    const technicianViewPhoto = document.getElementById('technicianViewPhoto');
    
    if (profilePic) {
        technicianViewPhoto.innerHTML = `<img src="/storage/${profilePic}" alt="${tech.name}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`;
    } else {
        const initials = tech.name.split(' ').map(n => n[0]).join('').toUpperCase();
        technicianViewPhoto.innerHTML = initials;
    }

    document.getElementById('technicianViewName').textContent = tech.name;
    document.getElementById('technicianViewSpecialty').textContent = tech.specialty;
    document.getElementById('technicianViewLicense').textContent = tech.license || 'N/A';
    document.getElementById('technicianViewExperience').textContent = tech.experience + ' years';
    document.getElementById('technicianViewRate').textContent = 'UGX ' + parseFloat(tech.hourly_rate || tech.rate || 0).toLocaleString() + '/hour';
    document.getElementById('technicianViewEmployment').textContent = tech.employment_type || 'Full-Time';
    document.getElementById('technicianViewStock').textContent = tech.quantity || tech.available_stock || 0;

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
        const appliancesResponse = await fetch('/api/appliances');
        const allAppliances = await appliancesResponse.json();
        
        const currentAppliance = allAppliances.find(a => a.id == applianceId);
        if (!currentAppliance) return 0;
        
        const sameModelAvailableCount = allAppliances.filter(a => 
            a.id != applianceId && 
            a.model === currentAppliance.model && 
            a.status === 'Available'
        ).length;
        
        const applianceQuantity = parseInt(currentAppliance.quantity) || 0;
        
        return sameModelAvailableCount + applianceQuantity;
    } catch (error) {
        console.error('Error fetching appliance parts count:', error);
        return 0;
    }
}

function viewApplianceDetails(id, name, brand, model, power, sku, status, description, icon, color, price, created_at, partsCount, image, appliance) {
    const applianceViewIcon = document.getElementById('applianceViewIcon');
    
    if (image) {
        applianceViewIcon.innerHTML = `<img src="/storage/${image}" alt="${name}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">`;
        applianceViewIcon.className = 'rounded d-inline-flex align-items-center justify-content-center';
        applianceViewIcon.style.width = '100%';
        applianceViewIcon.style.height = '200px';
    } else {
        applianceViewIcon.innerHTML = `<i class="fas fa-${icon || 'tools'}"></i>`;
        applianceViewIcon.className = `bg-${color || 'primary'} rounded d-inline-flex align-items-center justify-content-center text-white`;
        applianceViewIcon.style.width = '80px';
        applianceViewIcon.style.height = '80px';
    }

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
        priceTextElement.textContent = 'Price: UGX ' + parseFloat(price).toLocaleString();
        priceElement.className = 'badge bg-primary mb-3';
    } else {
        priceTextElement.textContent = 'Not Set';
        priceElement.className = 'badge bg-secondary mb-3';
    }

    document.getElementById('applianceViewId').textContent = `#${id}`;
    document.getElementById('applianceViewCreated').textContent = new Date(created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    document.getElementById('applianceViewPartsCount').textContent = partsCount || 0;

    // Add edit button for admin users
    const editBtn = document.getElementById('applianceEditBtn');
    if (editBtn && currentUser && currentUser.role === 'admin') {
        editBtn.style.display = 'inline-block';
        editBtn.onclick = () => {
            applianceViewModal.hide();
            const appliance = appliancesData.find(a => a.id === id);
            if (appliance) editAppliance(appliance);
        };
    }

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

// Open technician edit modal with pre-filled data
function openTechnicianEditModal(tech) {
    const modal = document.getElementById('technicianModal');
    const form = document.getElementById('technicianForm');
    const modalTitle = document.getElementById('technicianModalLabel');

    modalTitle.innerHTML = '<i class="fas fa-user-edit me-2"></i>Edit Technician Details';
    document.getElementById('technicianId').value = tech.id;

    // Populate all form fields
    document.getElementById('technicianTitle').value = tech.title || 'Mr.';
    document.getElementById('technicianFirstName').value = tech.first_name || tech.name.split(' ')[0] || '';
    document.getElementById('technicianMiddleName').value = tech.middle_name || '';
    document.getElementById('technicianLastName').value = tech.last_name || tech.name.split(' ').slice(1).join(' ') || '';
    document.getElementById('technicianGender').value = tech.gender || 'Male';
    document.getElementById('technicianDOB').value = tech.date_of_birth || '';
    document.getElementById('technicianNationality').value = tech.nationality || 'Ugandan';
    document.getElementById('technicianIDNumber').value = tech.id_number || '';
    document.getElementById('technicianEmail').value = tech.email;
    document.getElementById('technicianPhone1').value = tech.phone_1 || tech.phone;
    document.getElementById('technicianPhone2').value = tech.phone_2 || '';
    document.getElementById('technicianWhatsapp').value = tech.whatsapp || '';
    document.getElementById('technicianEmergencyContact').value = tech.emergency_contact || '';
    document.getElementById('technicianEmergencyPhone').value = tech.emergency_phone || '';
    document.getElementById('technicianCountry').value = tech.country || 'Uganda';
    document.getElementById('technicianRegion').value = tech.region || 'Central';
    document.getElementById('technicianDistrict').value = tech.district || tech.location || '';
    document.getElementById('technicianSubCounty').value = tech.sub_county || '';
    document.getElementById('technicianParish').value = tech.parish || '';
    document.getElementById('technicianVillage').value = tech.village || '';
    document.getElementById('technicianPostalCode').value = tech.postal_code || '';
    document.getElementById('technicianSpecialty').value = tech.specialty;
    document.getElementById('technicianSubSpecialty').value = tech.sub_specialty || '';
    document.getElementById('technicianLicenseNumber').value = tech.license_number || tech.license || '';
    document.getElementById('technicianLicenseExpiry').value = tech.license_expiry || '';
    document.getElementById('technicianExperience').value = tech.experience;
    document.getElementById('technicianHourlyRate').value = tech.hourly_rate || tech.rate || '';
    document.getElementById('technicianDailyRate').value = tech.daily_rate || '';
    document.getElementById('technicianStatus').value = tech.status || 'Available';
    document.getElementById('technicianEmploymentType').value = tech.employment_type || 'Full-Time';
    document.getElementById('technicianStartDate').value = tech.start_date || '';
    document.getElementById('technicianSkills').value = Array.isArray(tech.skills) ? tech.skills.join(', ') : (tech.skills || '');
    document.getElementById('technicianCertifications').value = Array.isArray(tech.certifications) ? tech.certifications.join(', ') : (tech.certifications || '');
    document.getElementById('technicianTraining').value = tech.training || '';
    document.getElementById('technicianLanguages').value = tech.languages || '';
    document.getElementById('technicianOwnTools').value = tech.own_tools || 'Yes';
    document.getElementById('technicianVehicle').value = tech.has_vehicle || 'No';
    document.getElementById('technicianVehicleType').value = tech.vehicle_type || '';
    document.getElementById('technicianEquipmentList').value = tech.equipment_list || '';
    document.getElementById('technicianServiceAreas').value = tech.service_areas || '';
    document.getElementById('technicianPreviousEmployer').value = tech.previous_employer || '';
    document.getElementById('technicianPreviousPosition').value = tech.previous_position || '';
    document.getElementById('technicianYearsAtPrevious').value = tech.years_at_previous || '';
    document.getElementById('technicianReferenceName').value = tech.reference_name || '';
    document.getElementById('technicianReferencePhone').value = tech.reference_phone || '';
    document.getElementById('technicianNotes').value = tech.notes || '';
    document.getElementById('technicianMedicalConditions').value = tech.medical_conditions || '';

    const technicianModal = new bootstrap.Modal(modal);
    technicianModal.show();
}

// Edit my profile function
async function editMyProfile(type) {
    // Ensure we have current user
    if (!currentUser) {
        try {
            const response = await fetch('/user');
            if (response.ok) {
                currentUser = await response.json();
            } else {
                showNotification('Please login first', 'error');
                return;
            }
        } catch (error) {
            showNotification('Please login first', 'error');
            return;
        }
    }

    if (type === 'trainer' && currentUser.role === 'trainer') {
        // Ensure trainers data is loaded
        if (!trainersData || trainersData.length === 0) {
            await loadTrainersData();
        }
        const myProfile = trainersData.find(t => t.email === currentUser.email);
        if (myProfile) {
            openTrainerEditModal(myProfile);
        } else {
            showNotification('Profile not found', 'error');
        }
    } else if (type === 'technician' && currentUser.role === 'technician') {
        // Ensure technicians data is loaded
        if (!techniciansData || techniciansData.length === 0) {
            await loadTechniciansData();
        }
        const myProfile = techniciansData.find(t => t.email === currentUser.email);
        if (myProfile) {
            openTechnicianEditModal(myProfile);
        } else {
            showNotification('Profile not found', 'error');
        }
    }
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




    // Sidebar navigation
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            // Don't prevent default for external links (not starting with #)
            if (!href || !href.startsWith('#')) {
                return; // Let the browser handle the navigation
            }
            e.preventDefault();
            const targetId = href.substring(1);

            // Remove active class from all links
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            // Add active to clicked link
            link.classList.add('active');

            if (targetId === 'dashboard-section') {
                document.getElementById('dashboard-section').style.display = 'block';
                document.getElementById('inventory').style.display = 'none';
                document.getElementById('appliances').style.display = 'none';
                document.getElementById('trainers').style.display = 'none';
                document.getElementById('qualified-technicians').style.display = 'none';
                document.getElementById('reports').style.display = 'none';
                document.getElementById('settings').style.display = 'none';
                window.scrollTo(0, 0);
            } else if (targetId === 'inventory' || targetId === 'inventory-stats') {
                document.getElementById('dashboard-section').style.display = 'none';
                document.getElementById('inventory').style.display = 'block';
                document.getElementById('appliances').style.display = 'none';
                document.getElementById('trainers').style.display = 'none';
                document.getElementById('qualified-technicians').style.display = 'none';
                document.getElementById('reports').style.display = 'none';
                document.getElementById('settings').style.display = 'none';
                document.getElementById('inventory').scrollIntoView({ behavior: 'smooth' });
                if (!dataLoaded.inventory) loadInventoryData();
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
                if (!hasPermission('trainers')) {
                    alert('Access denied. You do not have permission to view this section.');
                    return;
                }
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
                if (!hasPermission('qualified-technicians')) {
                    alert('Access denied. You do not have permission to view this section.');
                    return;
                }
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
                if (!hasPermission('reports')) {
                    alert('Access denied. You do not have permission to view this section.');
                    return;
                }
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
                if (!hasPermission('settings')) {
                    alert('Access denied. You do not have permission to view this section.');
                    return;
                }
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
    footer.style.bottom = '0';
    footer.style.right = '0';
    footer.style.height = '30px';
    footer.style.backgroundColor = '#343a40';
    footer.style.color = 'white';
    footer.style.display = 'flex';
    footer.style.alignItems = 'center';
    footer.style.justifyContent = 'center';
    footer.style.padding = '0 10px';
    footer.style.fontSize = 'clamp(0.6rem, 2.5vw, 1rem)';
    footer.style.zIndex = '1050';
    footer.style.whiteSpace = 'nowrap';
    footer.style.overflow = 'hidden';
    footer.textContent = 'E-Cooking Inventory Management System | CREEC © 2026. All rights reserved.';

    // Responsive left positioning
    const mediaQuery = window.matchMedia('(min-width: 992px)');
    function handleScreenChange(e) {
        footer.style.left = e.matches ? '160px' : '0';
    }
    mediaQuery.addListener(handleScreenChange);
    handleScreenChange(mediaQuery);

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



// Initialize the application
document.addEventListener('DOMContentLoaded', init);

// Handle hash changes
window.addEventListener('hashchange', () => {
    const hash = window.location.hash;
    if (hash === '#inventory-stats' || hash === '#inventory') {
        ['dashboard-section', 'appliances', 'trainers', 'qualified-technicians', 'reports', 'settings'].forEach(id => {
            document.getElementById(id).style.display = 'none';
        });
        document.getElementById('inventory').style.display = 'block';
        document.getElementById('inventory').scrollIntoView({ behavior: 'smooth' });
        if (!dataLoaded.inventory) loadInventoryData();
    }
});

// Update live clock
function updateLiveClock() {
    const now = new Date();

    // Large screen - two lines
    const dateElement = document.getElementById('currentDate');
    if (dateElement) {
        dateElement.textContent = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    const timeElement = document.getElementById('currentTime');
    if (timeElement) {
        timeElement.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
    }

    // Small screen - one line
    const dateSmallElement = document.getElementById('currentDateSmall');
    if (dateSmallElement) {
        dateSmallElement.textContent = now.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    const timeSmallElement = document.getElementById('currentTimeSmall');
    if (timeSmallElement) {
        timeSmallElement.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    }
}

// Start live clock
setInterval(updateLiveClock, 1000);
updateLiveClock();

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
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            const submitBtn = e.target.querySelector('button[type="submit"]');

            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Logging in...';
            submitBtn.disabled = true;

            try {
                const result = await login(email, password);

                if (result.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
                    modal.hide();

                    // Show success message
                    showNotification(`Welcome ${result.user.name}! Logged in as ${result.user.role.toUpperCase()}`, 'success');

                    // Reset form
                    loginForm.reset();

                    // Redirect to dashboard immediately
                    window.location.href = '/';
                } else {
                    showNotification(result.message || 'Login failed', 'error');
                }
            } catch (error) {
                showNotification('Login failed. Please try again.', 'error');
            } finally {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    }

    // Trainer form handler
    const trainerForm = document.getElementById('trainerForm');
    if (trainerForm) {
        trainerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await saveTrainerData();
        });
    }

    // Technician form handler
    const technicianForm = document.getElementById('technicianForm');
    if (technicianForm) {
        technicianForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await saveTechnicianData();
        });
    }

    // Add click handlers for demo credentials
    addDemoCredentialHandlers();
});

// Save trainer data
async function saveTrainerData() {
    const trainerId = document.getElementById('trainerId').value;
    const isEdit = !!trainerId;

    const data = {
        first_name: document.getElementById('trainerFirstName').value,
        middle_name: document.getElementById('trainerMiddleName').value,
        last_name: document.getElementById('trainerLastName').value,
        gender: document.getElementById('trainerGender').value,
        date_of_birth: document.getElementById('trainerDOB').value,
        nationality: document.getElementById('trainerNationality').value,
        id_number: document.getElementById('trainerIDNumber').value,
        email: document.getElementById('trainerEmail').value,
        phone: document.getElementById('trainerPhone').value,
        whatsapp: document.getElementById('trainerWhatsapp').value,
        emergency_contact: document.getElementById('trainerEmergencyContact').value,
        emergency_phone: document.getElementById('trainerEmergencyPhone').value,
        country: document.getElementById('trainerCountry').value,
        region: document.getElementById('trainerRegion').value,
        district: document.getElementById('trainerDistrict').value,
        sub_county: document.getElementById('trainerSubCounty').value,
        village: document.getElementById('trainerVillage').value,
        postal_code: document.getElementById('trainerPostalCode').value,
        specialty: document.getElementById('trainerSpecialty').value,
        experience: document.getElementById('trainerExperience').value,
        license_number: document.getElementById('trainerLicenseNumber').value,
        hourly_rate: document.getElementById('trainerHourlyRate').value,
        daily_rate: document.getElementById('trainerDailyRate').value,
        status: document.getElementById('trainerStatus').value,
        skills: document.getElementById('trainerSkills').value,
        qualifications: document.getElementById('trainerQualifications').value,
        certifications: document.getElementById('trainerCertifications').value,
        languages: document.getElementById('trainerLanguages').value,
        notes: document.getElementById('trainerNotes').value
    };

    try {
        const response = await fetch(`/api/trainers${isEdit ? `/${trainerId}` : ''}`, {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            const result = await response.json();
            showNotification('Trainer updated successfully!', 'success');
            trainerModal.hide();
            dataLoaded.trainers = false;
            await loadTrainersData();
        } else {
            const errors = await response.json();
            showNotification('Error saving trainer: ' + (errors.message || JSON.stringify(errors.errors)), 'error');
        }
    } catch (error) {
        console.error('Error saving trainer:', error);
        showNotification('Error saving trainer. Please try again.', 'error');
    }
}

// Save technician data
async function saveTechnicianData() {
    const technicianId = document.getElementById('technicianId').value;
    const isEdit = !!technicianId;

    const data = {
        title: document.getElementById('technicianTitle').value,
        first_name: document.getElementById('technicianFirstName').value,
        middle_name: document.getElementById('technicianMiddleName').value,
        last_name: document.getElementById('technicianLastName').value,
        gender: document.getElementById('technicianGender').value,
        date_of_birth: document.getElementById('technicianDOB').value,
        nationality: document.getElementById('technicianNationality').value,
        id_number: document.getElementById('technicianIDNumber').value,
        email: document.getElementById('technicianEmail').value,
        phone_1: document.getElementById('technicianPhone1').value,
        phone_2: document.getElementById('technicianPhone2').value,
        whatsapp: document.getElementById('technicianWhatsapp').value,
        emergency_contact: document.getElementById('technicianEmergencyContact').value,
        emergency_phone: document.getElementById('technicianEmergencyPhone').value,
        country: document.getElementById('technicianCountry').value,
        region: document.getElementById('technicianRegion').value,
        district: document.getElementById('technicianDistrict').value,
        sub_county: document.getElementById('technicianSubCounty').value,
        parish: document.getElementById('technicianParish').value,
        village: document.getElementById('technicianVillage').value,
        postal_code: document.getElementById('technicianPostalCode').value,
        specialty: document.getElementById('technicianSpecialty').value,
        sub_specialty: document.getElementById('technicianSubSpecialty').value,
        license_number: document.getElementById('technicianLicenseNumber').value,
        license_expiry: document.getElementById('technicianLicenseExpiry').value,
        experience: document.getElementById('technicianExperience').value,
        hourly_rate: document.getElementById('technicianHourlyRate').value,
        daily_rate: document.getElementById('technicianDailyRate').value,
        status: document.getElementById('technicianStatus').value,
        employment_type: document.getElementById('technicianEmploymentType').value,
        start_date: document.getElementById('technicianStartDate').value,
        skills: document.getElementById('technicianSkills').value,
        certifications: document.getElementById('technicianCertifications').value,
        training: document.getElementById('technicianTraining').value,
        languages: document.getElementById('technicianLanguages').value,
        own_tools: document.getElementById('technicianOwnTools').value,
        has_vehicle: document.getElementById('technicianVehicle').value,
        vehicle_type: document.getElementById('technicianVehicleType').value,
        equipment_list: document.getElementById('technicianEquipmentList').value,
        service_areas: document.getElementById('technicianServiceAreas').value,
        previous_employer: document.getElementById('technicianPreviousEmployer').value,
        previous_position: document.getElementById('technicianPreviousPosition').value,
        years_at_previous: document.getElementById('technicianYearsAtPrevious').value,
        reference_name: document.getElementById('technicianReferenceName').value,
        reference_phone: document.getElementById('technicianReferencePhone').value,
        notes: document.getElementById('technicianNotes').value,
        medical_conditions: document.getElementById('technicianMedicalConditions').value
    };

    try {
        const response = await fetch(`/api/technicians${isEdit ? `/${technicianId}` : ''}`, {
            method: isEdit ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            const result = await response.json();
            showNotification('Technician updated successfully!', 'success');
            const technicianModal = bootstrap.Modal.getInstance(document.getElementById('technicianModal'));
            technicianModal.hide();
            dataLoaded.technicians = false;
            await loadTechniciansData();
        } else {
            const errors = await response.json();
            showNotification('Error saving technician: ' + (errors.message || JSON.stringify(errors.errors)), 'error');
        }
    } catch (error) {
        console.error('Error saving technician:', error);
        showNotification('Error saving technician. Please try again.', 'error');
    }
}

function openSupportChat() {
    window.open('/chat', 'SupportChat', 'width=1200,height=800,resizable=yes,scrollbars=yes');
}

function addDemoCredentialHandlers() {
    // Add click handlers to demo credential sections
    const demoCredentials = [
        { email: 'admin@creec.com', password: 'admin123', role: 'Admin' },
        { email: 'trainer@creec.com', password: 'trainer123', role: 'Trainer' },
        { email: 'technician@creec.com', password: 'tech123', role: 'Technician' }
    ];

    // Add click handlers to demo credential areas in modal
    document.addEventListener('click', (e) => {
        const target = e.target;

        // Check if clicked on demo credentials area
        if (target.closest('.alert-warning')) {
            const alertWarning = target.closest('.alert-warning');
            const codeElements = alertWarning.querySelectorAll('code');

            // Find which credential set was clicked
            let clickedCredential = null;

            if (target.textContent.includes('admin@creec.com') || target.closest('div')?.textContent.includes('Admin Access')) {
                clickedCredential = demoCredentials[0];
            } else if (target.textContent.includes('trainer@creec.com') || target.closest('div')?.textContent.includes('Trainer Access')) {
                clickedCredential = demoCredentials[1];
            } else if (target.textContent.includes('technician@creec.com') || target.closest('div')?.textContent.includes('Technician Access')) {
                clickedCredential = demoCredentials[2];
            }

            if (clickedCredential) {
                // Auto-fill the form
                const emailInput = document.getElementById('loginEmail');
                const passwordInput = document.getElementById('loginPassword');

                if (emailInput && passwordInput) {
                    emailInput.value = clickedCredential.email;
                    passwordInput.value = clickedCredential.password;

                    // Show feedback
                    showNotification(`Demo credentials loaded for ${clickedCredential.role}`, 'info');

                    // Focus on login button
                    const loginBtn = document.querySelector('#loginForm button[type="submit"]');
                    if (loginBtn) {
                        loginBtn.focus();
                    }
                }
            }
        }
    });

    // Add click handlers to sidebar demo credentials
    const sidebarCredentials = document.querySelectorAll('.demo-credentials div');
    sidebarCredentials.forEach((credDiv, index) => {
        credDiv.style.cursor = 'pointer';
        credDiv.title = 'Click to use these credentials';

        credDiv.addEventListener('click', () => {
            // Open login modal
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();

            // Wait for modal to open then fill credentials
            setTimeout(() => {
                const emailInput = document.getElementById('loginEmail');
                const passwordInput = document.getElementById('loginPassword');

                if (emailInput && passwordInput && demoCredentials[index]) {
                    emailInput.value = demoCredentials[index].email;
                    passwordInput.value = demoCredentials[index].password;
                    showNotification(`Demo credentials loaded for ${demoCredentials[index].role}`, 'info');
                }
            }, 300);
        });
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 80px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}


// Edit appliance function
function editAppliance(appliance) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'editApplianceModal';
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Appliance</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editApplianceForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" id="editName" value="${appliance.name}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" class="form-control" id="editModel" value="${appliance.model || ''}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Power</label>
                            <input type="text" class="form-control" id="editPower" value="${appliance.power || ''}" placeholder="e.g., 1500W">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price (UGX)</label>
                            <input type="number" class="form-control" id="editPrice" value="${appliance.price || ''}" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity in Stock</label>
                            <input type="number" class="form-control" id="editQuantity" value="${appliance.quantity || 0}" min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="editStatus">
                                <option value="Available" ${appliance.status === 'Available' ? 'selected' : ''}>Available</option>
                                <option value="In Use" ${appliance.status === 'In Use' ? 'selected' : ''}>In Use</option>
                                <option value="Maintenance" ${appliance.status === 'Maintenance' ? 'selected' : ''}>Maintenance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" id="editImage" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
    
    document.getElementById('editApplianceForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const submitBtn = e.target.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
        
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('name', document.getElementById('editName').value);
        formData.append('model', document.getElementById('editModel').value);
        formData.append('power', document.getElementById('editPower').value);
        formData.append('price', document.getElementById('editPrice').value);
        formData.append('quantity', document.getElementById('editQuantity').value);
        formData.append('status', document.getElementById('editStatus').value);
        
        const imageFile = document.getElementById('editImage').files[0];
        if (imageFile) {
            formData.append('image', imageFile);
        }
        
        try {
            const response = await fetch(`/api/appliances/${appliance.id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (response.ok && result.success) {
                showNotification('Appliance updated successfully!', 'success');
                modalInstance.hide();
                modal.remove();
                dataLoaded.appliances = false;
                await loadAppliancesData();
            } else {
                showNotification('Error: ' + (result.message || 'Update failed'), 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save Changes';
            }
        } catch (error) {
            showNotification('Error: ' + error.message, 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save Changes';
        }
    });
    
    modal.addEventListener('hidden.bs.modal', () => modal.remove());
}


// Edit part function
function editPart(partId) {
    window.location.href = '/admin#parts-management';
    setTimeout(() => {
        if (typeof window.editPart !== 'undefined') {
            window.editPart(partId);
        }
    }, 500);
}
