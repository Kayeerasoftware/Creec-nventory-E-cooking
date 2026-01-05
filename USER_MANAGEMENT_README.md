# User Management System - Setup Guide

## Overview
This system provides full CRUD (Create, Read, Update, Delete) operations for user management with role-based access control.

## Features
- ✅ User authentication (Login/Logout)
- ✅ Role-based access control (Admin, Manager, User, Trainer, Technician)
- ✅ Full CRUD operations for users
- ✅ Secure password hashing
- ✅ Protected routes with middleware

## Default Users Created

| Email | Password | Role |
|-------|----------|------|
| admin@creec.or.ug | admin123 | admin |
| manager@creec.or.ug | manager123 | manager |
| user@creec.or.ug | user123 | user |

## Access Levels

### Admin
- Full access to all features
- Can create, edit, and delete users
- Can manage all roles

### Manager
- Can view all users
- Cannot create, edit, or delete users

### User
- No access to user management
- Can only access public pages

## How to Use

### 1. Access the Login Page
- Navigate to: `http://your-domain/login`
- Or click "Login" from the welcome page sidebar

### 2. Login with Credentials
- Use one of the default accounts above
- Check "Remember me" to stay logged in

### 3. Access User Management
- After login, you'll be redirected to `/users`
- Admin users will see "Add User" button
- Manager users can only view the list

### 4. Create New User (Admin Only)
- Click "Add User" button
- Fill in the form:
  - Name (required)
  - Email (required, must be unique)
  - Password (required, minimum 6 characters)
  - Role (required)
- Click "Save"

### 5. Edit User (Admin Only)
- Click the yellow edit button (pencil icon)
- Modify the fields
- Leave password blank to keep current password
- Click "Save"

### 6. Delete User (Admin Only)
- Click the red delete button (trash icon)
- Confirm deletion
- Note: You cannot delete yourself

### 7. Logout
- Click "Logout" button in the top navigation

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php      # Handles login/logout
│   │   └── UserController.php      # Handles CRUD operations
│   └── Middleware/
│       └── CheckRole.php            # Role-based access control
├── Models/
│   └── User.php                     # User model with role field
database/
├── migrations/
│   └── 2026_01_02_203844_add_role_to_users_table.php
└── seeders/
    └── AdminUserSeeder.php          # Creates default users
resources/
└── views/
    ├── login.blade.php              # Login page
    ├── users.blade.php              # User management page
    └── welcome.blade.php            # Updated with login link
routes/
└── web.php                          # Updated with auth routes
```

## API Endpoints

All user management endpoints require authentication:

- `GET /users` - User management page
- `GET /api/users` - Get all users
- `GET /api/users/{id}` - Get single user
- `POST /api/users` - Create user (Admin only)
- `PUT /api/users/{id}` - Update user (Admin only)
- `DELETE /api/users/{id}` - Delete user (Admin only)

## Security Features

1. **Password Hashing**: All passwords are hashed using bcrypt
2. **CSRF Protection**: All forms include CSRF tokens
3. **Role-Based Access**: Middleware checks user roles
4. **Session Management**: Secure session handling
5. **Self-Deletion Prevention**: Users cannot delete themselves

## Troubleshooting

### Cannot Login
- Check database connection
- Verify user exists in database
- Ensure password is correct

### 403 Forbidden Error
- Check your user role
- Verify you have permission for the action
- Contact admin for role upgrade

### Cannot Create/Edit/Delete Users
- Only Admin users can perform these actions
- Manager users have read-only access

## Next Steps

1. Change default passwords immediately
2. Create additional admin users if needed
3. Customize roles based on your needs
4. Add more fields to user profile if required

## Support

For issues or questions, contact the system administrator.
