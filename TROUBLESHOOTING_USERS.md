# Troubleshooting Guide: "Error loading users"

## Problem Description
The application is showing an "Error loading users" message, which indicates that the system is unable to retrieve user data from the database.

## Possible Causes
1. Database connection issues
2. Missing users table
3. Empty users table
4. Permission issues
5. Missing or corrupted User model

## Step-by-Step Troubleshooting

### Step 1: Test Database Connection
Run the test script to verify database connectivity:

```bash
php test_users.php
```

This will check:
- Database connection status
- Users table existence
- Number of users in the database
- List all users with their details

### Step 2: Check Database Configuration
Verify your `.env` file has correct database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spare_parts_inventory
DB_USERNAME=root
DB_PASSWORD=
```

Make sure:
- MySQL server is running
- Database `spare_parts_inventory` exists
- Username and password are correct

### Step 3: Run Migrations
If the users table doesn't exist, run migrations:

```bash
php artisan migrate
```

### Step 4: Seed Users
If the users table is empty, seed it with default users:

```bash
php artisan db:seed --class=ComprehensiveUserSeeder
```

This will create:
- Admin user: admin@creec.com / admin123
- Trainer user: trainer@creec.com / trainer123
- Technician user: technician@creec.com / tech123

### Step 5: Clear Cache
Clear all Laravel caches:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 6: Check Logs
Check Laravel logs for detailed error messages:

```bash
# Windows
type storage\logs\laravel.log

# Unix/Linux/Mac
tail -f storage/logs/laravel.log
```

### Step 7: Test API Endpoint
Test the users API endpoint directly:

```bash
# Using curl
curl http://localhost:8000/api/users

# Or visit in browser
http://localhost:8000/api/users
```

Expected response:
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Admin User",
            "email": "admin@creec.com",
            "role": "admin",
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "count": 1
}
```

### Step 8: Verify File Permissions
Ensure Laravel has write permissions:

```bash
# Windows (run as Administrator)
icacls "storage" /grant Users:F /t
icacls "bootstrap\cache" /grant Users:F /t

# Unix/Linux/Mac
chmod -R 775 storage bootstrap/cache
```

## Quick Fixes

### Fix 1: Recreate Database
If all else fails, recreate the database:

```bash
# Drop and recreate database
php artisan migrate:fresh --seed
```

**Warning:** This will delete all existing data!

### Fix 2: Check MySQL Service
Ensure MySQL is running:

```bash
# Windows
net start MySQL80

# Unix/Linux
sudo systemctl start mysql

# Mac
brew services start mysql
```

### Fix 3: Verify User Model
Check that `app/Models/User.php` exists and is properly configured.

## Common Error Messages and Solutions

### "SQLSTATE[HY000] [1049] Unknown database"
**Solution:** Create the database:
```sql
CREATE DATABASE spare_parts_inventory;
```

### "SQLSTATE[HY000] [2002] Connection refused"
**Solution:** Start MySQL service (see Fix 2 above)

### "SQLSTATE[42S02]: Base table or view not found"
**Solution:** Run migrations (see Step 3 above)

### "Class 'App\Models\User' not found"
**Solution:** Run composer autoload:
```bash
composer dump-autoload
```

## Verification Steps

After applying fixes, verify the system is working:

1. Run the test script again:
   ```bash
   php test_users.php
   ```

2. Access the application:
   ```
   http://localhost:8000/users
   ```

3. Check the API endpoint:
   ```
   http://localhost:8000/api/users
   ```

4. Try logging in with test credentials:
   - Email: admin@creec.com
   - Password: admin123

## Additional Resources

- Laravel Documentation: https://laravel.com/docs
- MySQL Documentation: https://dev.mysql.com/doc/
- Project README: See README.md in project root

## Still Having Issues?

If you're still experiencing problems:

1. Check the Laravel log file: `storage/logs/laravel.log`
2. Enable debug mode in `.env`: `APP_DEBUG=true`
3. Check browser console for JavaScript errors
4. Verify all dependencies are installed: `composer install`

## Contact Support

For additional help, please provide:
- Error message from `storage/logs/laravel.log`
- Output from `php test_users.php`
- Your `.env` configuration (without passwords)
- PHP version: `php -v`
- Laravel version: `php artisan --version`
