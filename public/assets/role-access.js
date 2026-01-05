// Role-Based Access Control Helper
const RoleBasedAccess = {
    currentUser: null,
    
    // Initialize with current user data
    init: async function() {
        try {
            const response = await fetch('/user');
            if (response.ok) {
                this.currentUser = await response.json();
            }
        } catch (error) {
            console.error('Failed to fetch user data:', error);
        }
    },

    // Check if user has specific role
    hasRole: function(role) {
        return this.currentUser && this.currentUser.role === role;
    },

    // Check if user is admin
    isAdmin: function() {
        return this.hasRole('admin');
    },

    // Check if user is trainer
    isTrainer: function() {
        return this.hasRole('trainer');
    },

    // Check if user is technician
    isTechnician: function() {
        return this.hasRole('technician');
    },

    // Get editable fields based on role and resource type
    getEditableFields: function(resourceType) {
        if (this.isAdmin()) {
            return '*'; // All fields
        }

        const fieldPermissions = {
            trainer: {
                trainer: ['phone', 'whatsapp', 'emergency_contact', 'emergency_phone', 'village', 'postal_code', 'skills', 'qualifications', 'certifications', 'languages', 'notes'],
                technician: ['phone_1', 'phone_2', 'whatsapp', 'emergency_contact', 'emergency_phone', 'village', 'postal_code', 'skills', 'certifications', 'training', 'languages', 'equipment_list', 'service_areas', 'notes']
            },
            technician: {
                technician: ['phone_1', 'phone_2', 'whatsapp', 'emergency_contact', 'emergency_phone', 'village', 'postal_code', 'skills', 'certifications', 'training']
            }
        };

        const role = this.currentUser?.role;
        return fieldPermissions[role]?.[resourceType] || [];
    },

    // Disable form fields based on role permissions
    applyFieldRestrictions: function(formId, resourceType) {
        const editableFields = this.getEditableFields(resourceType);
        
        if (editableFields === '*') {
            return; // Admin can edit all fields
        }

        const form = document.getElementById(formId);
        if (!form) return;

        // Disable all input fields first
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            const fieldName = input.name || input.id;
            if (!editableFields.includes(fieldName)) {
                input.disabled = true;
                input.style.backgroundColor = '#f0f0f0';
                input.style.cursor = 'not-allowed';
            }
        });
    },

    // Show/hide action buttons based on role
    applyButtonRestrictions: function() {
        if (this.isAdmin()) {
            return; // Admin sees all buttons
        }

        // Hide create/delete buttons for non-admins
        document.querySelectorAll('[data-action="create"], [data-action="delete"]').forEach(btn => {
            btn.style.display = 'none';
        });

        // Show only edit buttons for own records
        if (this.isTechnician() || this.isTrainer()) {
            document.querySelectorAll('[data-action="edit"]').forEach(btn => {
                const recordUserId = btn.getAttribute('data-user-id');
                if (recordUserId && recordUserId !== String(this.currentUser.id)) {
                    btn.style.display = 'none';
                }
            });
        }
    },

    // Show role-specific message
    showRoleMessage: function() {
        const role = this.currentUser?.role;
        const messages = {
            admin: 'You have full access to all features',
            trainer: 'You can update trainer profiles and some technician fields',
            technician: 'You can update your technician profile'
        };

        return messages[role] || 'Limited access';
    }
};

// Initialize on page load
document.addEventListener('DOMContentLoaded', async function() {
    await RoleBasedAccess.init();
    RoleBasedAccess.applyButtonRestrictions();
});
