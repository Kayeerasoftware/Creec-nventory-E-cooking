# Appliance Update - Quick Reference Guide

## Files Modified

1. **ApplianceController.php** - Basic update logic with validation
2. **InventoryController.php** - Comprehensive update with extended fields + Storage import
3. **admin.js** - Enhanced error handling and field population

## Key Changes Summary

### ApplianceController.php
```php
// BEFORE: Manual field checking
if ($request->filled('name')) $data['name'] = $request->name;

// AFTER: Validation + smart filtering
$validated = $request->validate([...]);
$data = array_filter($validated, fn($value) => !is_null($value) && $value !== '');
```

### InventoryController.php
```php
// ADDED: Storage facade import
use Illuminate\Support\Facades\Storage;

// IMPROVED: Smart data filtering
$data = array_filter($validated, fn($value, $key) => 
    !is_null($value) && $value !== '' && $key !== 'image' && $key !== 'manual', 
    ARRAY_FILTER_USE_BOTH
);

// IMPROVED: File cleanup
if ($appliance->image && Storage::disk('public')->exists($appliance->image)) {
    Storage::disk('public')->delete($appliance->image);
}
```

### admin.js
```php
// IMPROVED: Better error handling
const result = await response.json();
if (response.ok && result.success !== false) {
    // Success handling
} else {
    // Error handling with detailed messages
}

// IMPROVED: Field population with defaults
document.getElementById('applianceName').value = appliance.name || '';
document.getElementById('applianceStatus').value = appliance.status || 'Available';

// ADDED: Image preview
if (appliance.image) {
    imagePreview.innerHTML = `<img src="/storage/${appliance.image}" ...>`;
}
```

## Update Logic Flow

```
User Action → editAppliance(id) → Fetch Data → Populate Form
                                                      ↓
User Edits → Submit Form → handleApplianceSubmit() → Validate
                                                      ↓
Backend → updateAppliance() → Validate → Filter → Update DB
                                                      ↓
Response → Success/Error → Notification → Refresh Table
```

## Validation Rules

| Field | Type | Rules |
|-------|------|-------|
| name | string | max:255 |
| brand_id | integer | exists:brands,id |
| model | string | max:255 |
| power | string | max:100 |
| status | enum | Available, In Use, Maintenance, Discontinued |
| price | numeric | min:0 |
| image | file | jpeg,png,jpg,gif, max:2MB |

## API Usage

### Fetch Appliance
```javascript
GET /api/appliances/{id}
Headers: { 'X-CSRF-TOKEN': token }
```

### Update Appliance
```javascript
POST /api/appliances/{id}
Headers: { 'X-CSRF-TOKEN': token }
Body: FormData with _method=PUT
```

## Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| 422 Validation Error | Invalid data | Check validation rules |
| 403 Forbidden | Not admin | Verify role and auth |
| 500 Server Error | Backend issue | Check logs |
| Image not saving | File too large | Max 2MB |
| Old data showing | Cache issue | Hard refresh (Ctrl+F5) |

## Testing Commands

```bash
# Check routes
php artisan route:list | grep appliances

# Clear cache
php artisan cache:clear
php artisan config:clear

# Check logs
tail -f storage/logs/laravel.log

# Test storage permissions
php artisan storage:link
```

## Quick Debug Checklist

- [ ] User is authenticated as admin
- [ ] CSRF token is present
- [ ] Form data is valid
- [ ] Storage directory is writable
- [ ] Database connection is active
- [ ] Routes are registered
- [ ] JavaScript console shows no errors
- [ ] Network tab shows 200 response

## Code Snippets

### Add New Field to Update
```php
// 1. Add to validation in InventoryController
'new_field' => 'nullable|string|max:255',

// 2. Add to form in appliance_modal.blade.php
<input type="text" name="new_field" id="applianceNewField">

// 3. Add to editAppliance in admin.js
document.getElementById('applianceNewField').value = appliance.new_field || '';
```

### Custom Validation Rule
```php
$validated = $request->validate([
    'custom_field' => ['nullable', 'string', function ($attribute, $value, $fail) {
        if (!preg_match('/^[A-Z]/', $value)) {
            $fail('The '.$attribute.' must start with uppercase.');
        }
    }],
]);
```

## Performance Tips

1. **Eager Loading:** Always load relationships
   ```php
   $appliance->fresh()->load('brand')
   ```

2. **Selective Updates:** Only update changed fields
   ```php
   array_filter($validated, fn($v) => !is_null($v))
   ```

3. **File Optimization:** Compress images before upload
   ```javascript
   // Use image compression library
   ```

4. **Caching:** Cache frequently accessed data
   ```php
   Cache::remember('appliances', 3600, fn() => Appliance::all())
   ```

## Security Checklist

- [x] CSRF protection enabled
- [x] Role-based access control
- [x] Input validation
- [x] SQL injection prevention (Eloquent)
- [x] File type validation
- [x] File size limits
- [x] XSS prevention (Laravel escaping)
- [x] Secure file storage

## Maintenance

### Regular Tasks
- Monitor storage usage
- Clean up orphaned files
- Review error logs
- Update validation rules as needed
- Test with different user roles

### Backup Strategy
- Database: Daily automated backups
- Files: Sync to cloud storage
- Code: Git version control

## Support

For issues or questions:
1. Check this documentation
2. Review error logs
3. Test in isolation
4. Contact development team

---

**Last Updated:** 2025
**Version:** 1.0
**Maintained By:** Development Team
