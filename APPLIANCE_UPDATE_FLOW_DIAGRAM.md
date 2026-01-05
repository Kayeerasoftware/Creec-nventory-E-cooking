# Appliance Update Flow Diagram

## Complete Update Process Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                         ADMIN USER INTERFACE                         │
│                        (admin.blade.php)                             │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ User clicks "Edit" button
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    JAVASCRIPT - editAppliance(id)                    │
│                           (admin.js)                                 │
├─────────────────────────────────────────────────────────────────────┤
│  1. Fetch appliance data: GET /api/appliances/{id}                  │
│  2. Populate form fields with existing data                          │
│  3. Display image preview if exists                                  │
│  4. Open modal with "Edit Appliance" title                           │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ User modifies data
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                         EDIT FORM MODAL                              │
│                    (appliance_modal.blade.php)                       │
├─────────────────────────────────────────────────────────────────────┤
│  Fields:                                                             │
│  • Name                    • Brand                                   │
│  • Model                   • Power                                   │
│  • Status                  • Price                                   │
│  • Image Upload            • [Extended fields...]                    │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ User clicks "Save"
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│              JAVASCRIPT - handleApplianceSubmit(e)                   │
│                           (admin.js)                                 │
├─────────────────────────────────────────────────────────────────────┤
│  1. Prevent default form submission                                  │
│  2. Create FormData object with all fields                           │
│  3. Add _method=PUT for Laravel                                      │
│  4. Add CSRF token for security                                      │
│  5. Send POST request to /api/appliances/{id}                        │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ HTTP Request
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        LARAVEL ROUTING                               │
│                         (web.php)                                    │
├─────────────────────────────────────────────────────────────────────┤
│  Route::middleware(['multiguard', 'role:admin'])->group(...)         │
│  Route::put('/api/appliances/{id}', [                               │
│      InventoryController::class, 'updateAppliance'                   │
│  ]);                                                                 │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ Route to controller
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│              CONTROLLER - updateAppliance($request, $id)             │
│                    (InventoryController.php)                         │
├─────────────────────────────────────────────────────────────────────┤
│  Step 1: Find appliance by ID                                        │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ $appliance = Appliance::findOrFail($id);                   │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Step 2: Validate incoming data                                      │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ $validated = $request->validate([                          │     │
│  │     'name' => 'nullable|string|max:255',                   │     │
│  │     'brand_id' => 'nullable|exists:brands,id',             │     │
│  │     'model' => 'nullable|string|max:255',                  │     │
│  │     'power' => 'nullable|string|max:100',                  │     │
│  │     'status' => 'nullable|in:Available,In Use,...',        │     │
│  │     'price' => 'nullable|numeric|min:0',                   │     │
│  │     'image' => 'nullable|image|max:2048',                  │     │
│  │     ... 30+ more fields                                    │     │
│  │ ]);                                                         │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Step 3: Filter out null/empty values                                │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ $data = array_filter($validated,                           │     │
│  │     fn($value, $key) => !is_null($value) &&                │     │
│  │     $value !== '' && $key !== 'image'                      │     │
│  │ );                                                          │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Step 4: Handle image upload                                         │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ if ($request->hasFile('image')) {                          │     │
│  │     // Delete old image                                    │     │
│  │     Storage::disk('public')->delete($appliance->image);    │     │
│  │     // Store new image                                     │     │
│  │     $data['image'] = $request->file('image')               │     │
│  │         ->store('appliances', 'public');                   │     │
│  │ }                                                           │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Step 5: Update database                                             │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ $appliance->update($data);                                 │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Step 6: Return JSON response                                        │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ return response()->json([                                  │     │
│  │     'success' => true,                                     │     │
│  │     'appliance' => $appliance->fresh()->load('brand'),     │     │
│  │     'message' => 'Appliance updated successfully'          │     │
│  │ ]);                                                         │     │
│  └────────────────────────────────────────────────────────────┘     │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ JSON Response
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│              JAVASCRIPT - Response Handling                          │
│                           (admin.js)                                 │
├─────────────────────────────────────────────────────────────────────┤
│  Success Path (response.ok && result.success !== false):            │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ 1. Show success notification                               │     │
│  │ 2. Close modal                                             │     │
│  │ 3. Refresh appliances table                                │     │
│  │ 4. Reset form                                              │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Error Path:                                                         │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ 1. Extract error message                                   │     │
│  │ 2. Show error notification                                 │     │
│  │ 3. Keep modal open for corrections                         │     │
│  └────────────────────────────────────────────────────────────┘     │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ Update UI
                                    ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    UPDATED APPLIANCES TABLE                          │
│                        (admin.blade.php)                             │
├─────────────────────────────────────────────────────────────────────┤
│  • Refreshed data from database                                      │
│  • Updated appliance visible in table                                │
│  • Success notification displayed                                    │
└─────────────────────────────────────────────────────────────────────┘
```

## Error Handling Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                         ERROR SCENARIOS                              │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                    ┌───────────────┼───────────────┐
                    │               │               │
                    ▼               ▼               ▼
        ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
        │ Validation   │  │   System     │  │   Network    │
        │   Error      │  │   Error      │  │    Error     │
        │   (422)      │  │   (500)      │  │              │
        └──────────────┘  └──────────────┘  └──────────────┘
                │                 │                 │
                ▼                 ▼                 ▼
        ┌──────────────┐  ┌──────────────┐  ┌──────────────┐
        │ Show field   │  │ Log error    │  │ Show retry   │
        │ errors to    │  │ Show generic │  │ message      │
        │ user         │  │ message      │  │              │
        └──────────────┘  └──────────────┘  └──────────────┘
```

## Data Flow Diagram

```
┌──────────────┐
│   Browser    │
│   (Client)   │
└──────┬───────┘
       │
       │ 1. GET /api/appliances/{id}
       │
       ▼
┌──────────────┐      ┌──────────────┐      ┌──────────────┐
│   Routes     │─────▶│  Controller  │─────▶│   Database   │
│  (web.php)   │      │ (Inventory)  │      │   (MySQL)    │
└──────────────┘      └──────┬───────┘      └──────────────┘
       ▲                     │
       │                     │ 2. Return appliance data
       │                     ▼
       │              ┌──────────────┐
       │              │   JSON       │
       │              │   Response   │
       │              └──────┬───────┘
       │                     │
       │ 3. Populate form    │
       │◀────────────────────┘
       │
       │ 4. User edits & submits
       │
       │ 5. POST /api/appliances/{id} (_method=PUT)
       │
       ▼
┌──────────────┐      ┌──────────────┐      ┌──────────────┐
│   Routes     │─────▶│  Controller  │─────▶│   Database   │
│  (web.php)   │      │ (Inventory)  │      │   (UPDATE)   │
└──────────────┘      └──────┬───────┘      └──────────────┘
       ▲                     │
       │                     │ 6. Return success
       │                     ▼
       │              ┌──────────────┐
       │              │   JSON       │
       │              │   Response   │
       │              └──────┬───────┘
       │                     │
       │ 7. Update UI        │
       │◀────────────────────┘
       │
       ▼
┌──────────────┐
│   Updated    │
│   Table      │
└──────────────┘
```

## Security Layers

```
┌─────────────────────────────────────────────────────────────────────┐
│                         SECURITY LAYERS                              │
├─────────────────────────────────────────────────────────────────────┤
│                                                                       │
│  Layer 1: Authentication                                             │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ • User must be logged in                                   │     │
│  │ • Session validation                                       │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Layer 2: Authorization                                              │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ • Role check: Must be 'admin'                              │     │
│  │ • Middleware: 'role:admin'                                 │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Layer 3: CSRF Protection                                            │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ • Token validation on every request                        │     │
│  │ • Prevents cross-site request forgery                      │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Layer 4: Input Validation                                           │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ • Type checking (string, numeric, etc.)                    │     │
│  │ • Length limits                                            │     │
│  │ • Format validation                                        │     │
│  │ • Whitelist allowed values                                 │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Layer 5: File Validation                                            │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ • MIME type checking                                       │     │
│  │ • File size limits                                         │     │
│  │ • Extension validation                                     │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Layer 6: SQL Injection Prevention                                   │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ • Eloquent ORM (parameterized queries)                     │     │
│  │ • No raw SQL with user input                               │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
│  Layer 7: XSS Prevention                                             │
│  ┌────────────────────────────────────────────────────────────┐     │
│  │ • Laravel automatic escaping                               │     │
│  │ • Blade template engine                                    │     │
│  └────────────────────────────────────────────────────────────┘     │
│                                                                       │
└─────────────────────────────────────────────────────────────────────┘
```

## File Upload Process

```
┌─────────────────────────────────────────────────────────────────────┐
│                      FILE UPLOAD WORKFLOW                            │
└─────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
                        ┌───────────────────────┐
                        │  User selects image   │
                        └───────────┬───────────┘
                                    │
                                    ▼
                        ┌───────────────────────┐
                        │  Validate file type   │
                        │  (jpeg, png, jpg, gif)│
                        └───────────┬───────────┘
                                    │
                                    ▼
                        ┌───────────────────────┐
                        │  Check file size      │
                        │  (max 2MB)            │
                        └───────────┬───────────┘
                                    │
                                    ▼
                        ┌───────────────────────┐
                        │  Upload to server     │
                        └───────────┬───────────┘
                                    │
                                    ▼
                        ┌───────────────────────┐
                        │  Check if old image   │
                        │  exists               │
                        └───────────┬───────────┘
                                    │
                        ┌───────────┴───────────┐
                        │                       │
                    Yes │                       │ No
                        ▼                       ▼
            ┌───────────────────┐   ┌───────────────────┐
            │  Delete old image │   │  Skip deletion    │
            └───────────┬───────┘   └───────────┬───────┘
                        │                       │
                        └───────────┬───────────┘
                                    │
                                    ▼
                        ┌───────────────────────┐
                        │  Store new image      │
                        │  in storage/app/      │
                        │  public/appliances/   │
                        └───────────┬───────────┘
                                    │
                                    ▼
                        ┌───────────────────────┐
                        │  Update database      │
                        │  with new path        │
                        └───────────┬───────────┘
                                    │
                                    ▼
                        ┌───────────────────────┐
                        │  Return success       │
                        └───────────────────────┘
```

---

**Note:** This diagram represents the complete flow of the appliance update functionality in the E-Cooking Inventory system. Each box represents a distinct step in the process, with error handling and security measures integrated throughout.
