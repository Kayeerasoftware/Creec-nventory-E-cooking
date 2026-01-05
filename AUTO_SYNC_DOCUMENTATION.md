# Automatic User Account Synchronization

## Overview
Model observers automatically sync trainer/technician data with user accounts.

## What Happens Automatically

### When a Trainer is Created:
✅ User account is **automatically created** with:
- Same name and email
- Password: `kayeera`
- Role: `trainer`
- Linked via `trainer_id`

### When a Trainer is Updated:
✅ User account is **automatically updated** with:
- Updated name
- Updated email

### When a Trainer is Deleted:
✅ User account is **automatically deleted**

### When a Technician is Created:
✅ User account is **automatically created** with:
- Same name and email
- Password: `kayeera`
- Role: `technician`
- Linked via `technician_id`

### When a Technician is Updated:
✅ User account is **automatically updated** with:
- Updated name
- Updated email

### When a Technician is Deleted:
✅ User account is **automatically deleted**

## Implementation

**Observers:**
- `app/Observers/TrainerObserver.php`
- `app/Observers/TechnicianObserver.php`

**Registered in:**
- `app/Providers/AppServiceProvider.php`

## Example Usage

```php
// Create a trainer - user account created automatically
$trainer = Trainer::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'specialty' => 'E-Cooking',
    'phone' => '0700000000',
    'experience' => 5,
    'qualifications' => 'Certified',
    'location' => 'Kampala'
]);

// User account now exists with email john@example.com
$user = User::where('email', 'john@example.com')->first();
// $user->role === 'trainer'
// $user->trainer_id === $trainer->id

// Update trainer - user updated automatically
$trainer->update(['name' => 'John Smith']);
// User name is now 'John Smith'

// Delete trainer - user deleted automatically
$trainer->delete();
// User account is also deleted
```

## Benefits
- No manual user creation needed
- Data always in sync
- Prevents orphaned records
- Simplifies trainer/technician management
