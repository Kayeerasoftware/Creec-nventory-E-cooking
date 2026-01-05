// Admin Layout JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeAdminLayout();
});

function initializeAdminLayout() {
    adjustLayoutForScreen();
    setupResponsiveHandlers();
    setupSidebarToggle();
    
    // Show first section by default
    showSection('parts-management');
}

function adjustLayoutForScreen() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (window.innerWidth >= 992) {
        // Desktop layout
        if (sidebar) {
            sidebar.classList.add('show');
            sidebar.style.width = '200px';
        }
        if (mainContent) {
            mainContent.style.marginLeft = '200px';
            mainContent.style.paddingTop = '60px';
        }
    } else {
        // Mobile layout
        if (mainContent) {
            mainContent.style.marginLeft = '0';
            mainContent.style.paddingTop = '60px';
        }
    }
}

function setupResponsiveHandlers() {
    window.addEventListener('resize', adjustLayoutForScreen);
    
    // Handle offcanvas events
    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.addEventListener('hidden.bs.offcanvas', function() {
            if (window.innerWidth >= 992) {
                this.classList.add('show');
            }
        });
    }
}

function setupSidebarToggle() {
    const toggleButton = document.querySelector('[data-bs-target="#sidebar"]');
    if (toggleButton) {
        toggleButton.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth >= 992) {
                // Desktop: toggle sidebar
                sidebar.classList.toggle('show');
                adjustMainContentMargin();
            }
        });
    }
}

function adjustMainContentMargin() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    if (sidebar && mainContent && window.innerWidth >= 992) {
        if (sidebar.classList.contains('show')) {
            mainContent.style.marginLeft = '200px';
        } else {
            mainContent.style.marginLeft = '0';
        }
    }
}

// Smooth scrolling for navigation
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Handle section visibility
function showSection(sectionId) {
    // Hide all management sections
    const sections = document.querySelectorAll('[id$="-management"]');
    sections.forEach(section => {
        section.style.display = 'none';
    });
    
    // Show target section
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.style.display = 'block';
        
        // Update active navigation
        updateActiveNavigation(sectionId);
        
        // Scroll to top of section
        targetSection.scrollIntoView({ behavior: 'smooth' });
    }
}

function updateActiveNavigation(sectionId) {
    // Remove active class from all nav links
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Add active class to current nav link
    const activeLink = document.querySelector(`[href="#${sectionId}"]`);
    if (activeLink) {
        activeLink.classList.add('active');
    }
}

// Export functions for global access
window.showSection = showSection;
window.smoothScrollTo = smoothScrollTo;