# Role-Based CRUD System Documentation

## Overview
This system implements role-based access control (RBAC) for the E-Cooking Inventory Management System, allowing different user roles to have different levels of access to data.

## User Roles

### 1. Admin
- **Full CRUD Access**: Can Create, Read, Update, and Delete all records
- **All Fields**: Can modify any field in the system
- **Pages**: Full access to all pages

### 2. Trainer
- **Read Access**: Can view trainer and technician data
- **Update Access**: 
  - Can update their own trainer profile
  - Can update limited technician fields
- **Allowed Fields**:
  - **Trainer Profile**: phone, whatsapp, emergency_contact, emergency_phone, village, postal_code, skills, qualifications, certifications, languages, notes
  - **Technician Profile**: phone_1, phone_2, whatsapp, emergency_contact, emergency_phone, village, postal_code, skills, certifications, training, languages, equipment_list, service_areas, notes

### 3. Technician
- **Read Access**: Can view technician data
- **Update Access**: Can only update their own technician profile
- **Allowed Fields**: phone_1, phone_2, whatsapp, emergency_contact, emergency_phone, village, postal_code, skills, certifications, training

## Implementation Details

### Backend (Laravel)

#### Middleware
- `RoleMiddleware`: Checks if user has required role
- `CheckPermission`: Validates specific permissions
- `MultiGuardAuth`: Handles authentication

#### Controllers
- `TechnicianController`: Implements role-based field restrictions in update method
- `TrainerController`: Implements role-based field restrictions in update method

#### Routes
```php
// Admin only
Route::middleware(['multiguard', 'role:admin'])->group(function () {
    Route::post('api/trainers', [TrainerController::class, 'store']);
    Route::delete('api/trainers/{trainer}', [TrainerController::class, 'destroy']);
});

// Trainer and Admin
Route::middleware(['multiguard', 'role:trainer,admin'])->group(function () {
    Route::put('api/trainers/{trainer}', [TrainerController::class, 'update']);
});

// Technician, Trainer, and Admin
Route::middleware(['multiguard', 'role:technician,trainer,admin'])->group(function () {
    Route::put('api/technicians/{technician}', [TechnicianController::class, 'update']);
});
```

### Frontend (JavaScript)

#### Role-Based Access Helper
Located in: `public/assets/role-access.js`

**Key Functions:**
- `init()`: Fetches current user data
- `hasRole(role)`: Check if user has specific role
- `getEditableFields(resourceType)`: Returns array of editable fields
- `applyFieldRestrictions(formId, resourceType)`: Disables non-editable fields
- `applyButtonRestrictions()`: Hides create/delete buttons for non-admins

#### Usage Example
```javascript
// In your form initialization
await RoleBasedAccess.init();
RoleBasedAccess.applyFieldRestrictions('technicianForm', 'technician');
```

### Database Schema

#### Users Table
```sql
- id
- name
- email
- password
- role (enum: 'admin', 'trainer', 'technician')
- last_seen
```

#### Technicians Table
```sql
- id
- user_id (foreign key to users)
- first_name, last_name, etc.
- (all technician fields)
```

#### Trainers Table
```sql
- id
- user_id (foreign key to users)
- first_name, last_name, etc.
- (all trainer fields)
```

## API Endpoints

### Technician Endpoints
- `GET /api/technicians` - List all (requires: technician, trainer, or admin)
- `GET /api/technicians/{id}` - View one (requires: technician, trainer, or admin)
- `PUT /api/technicians/{id}` - Update (requires: technician, trainer, or admin)
  - Technicians can only update their own record
  - Field restrictions apply based on role
- `POST /api/technicians` - Create (requires: admin only)
- `DELETE /api/technicians/{id}` - Delete (requires: admin only)

### Trainer Endpoints
- `GET /api/trainers` - List all (requires: trainer or admin)
- `GET /api/trainers/{id}` - View one (requires: trainer or admin)
- `PUT /api/trainers/{id}` - Update (requires: trainer or admin)
  - Trainers can only update their own record
  - Field restrictions apply based on role
- `POST /api/trainers` - Create (requires: admin only)
- `DELETE /api/trainers/{id}` - Delete (requires: admin only)

## Security Features

1. **Authentication Required**: All update operations require authentication
2. **Role Verification**: Middleware checks user role before allowing access
3. **Field-Level Security**: Controllers validate which fields can be updated
4. **Own Record Only**: Non-admins can only update their own records
5. **CSRF Protection**: All forms include CSRF tokens

## Testing

### Test as Admin
1. Login as admin user
2. Navigate to technician/trainer pages
3. Verify all fields are editable
4. Verify create/delete buttons are visible

### Test as Trainer
1. Login as trainer user
2. Navigate to trainer page
3. Verify only allowed fields are editable
4. Try to edit another trainer's record (should fail)
5. Navigate to technician page
6. Verify limited fields are editable

### Test as Technician
1. Login as technician user
2. Navigate to technician page
3. Verify only basic fields are editable
4. Try to edit another technician's record (should fail)

## Troubleshooting

### Issue: All fields are disabled
**Solution**: Check if user is authenticated and role is set correctly

### Issue: 403 Forbidden error
**Solution**: Verify user has correct role and is updating their own record

### Issue: Fields not saving
**Solution**: Check validation rules and ensure fields are in allowed list

## Future Enhancements

1. Add audit logging for all updates
2. Implement field-level permissions in database
3. Add email notifications for profile updates
4. Create admin dashboard for permission management
5. Add two-factor authentication for sensitive operations
