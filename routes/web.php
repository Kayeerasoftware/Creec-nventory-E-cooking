<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataPopulateController;

// Public routes
Route::get('/', [InventoryController::class, 'index']);
Route::get('/populate-data', [DataPopulateController::class, 'populate']);
Route::get('/check-data', function() {
    return response()->json([
        'parts' => \App\Models\Part::count(),
        'appliances' => \App\Models\Appliance::count(),
        'brands' => \App\Models\Brand::count()
    ]);
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Public user routes
Route::get('/users', [UserController::class, 'index']);
Route::get('/api/users', [UserController::class, 'apiIndex']);

// Protected user management routes
Route::middleware('multiguard')->group(function () {
    Route::get('/api/users/{id}', [UserController::class, 'show']);
    Route::post('/api/users', [UserController::class, 'store']);
    Route::put('/api/users/{id}', [UserController::class, 'update']);
    Route::delete('/api/users/{id}', [UserController::class, 'destroy']);
    Route::get('/profile', [UserController::class, 'showProfile']);
    Route::post('/profile/upload-picture', [UserController::class, 'uploadProfilePicture']);
    Route::post('/profile/update', [UserController::class, 'updateProfile']);
});

// Admin-only routes
Route::middleware(['multiguard', 'role:admin'])->group(function () {
    Route::get('/admin/panel', function () {
        $statistics = [
            'total_parts' => \App\Models\Part::count(),
            'total_appliances' => \App\Models\Appliance::count(),
            'total_trainers' => \App\Models\Trainer::count(),
            'total_technicians' => \App\Models\Technician::count(),
        ];
        return view('admin-panel', compact('statistics'));
    });
    Route::get('/admin/home', function () {
        $parts = \App\Models\Part::with(['appliance', 'brands', 'specificAppliances'])->get();
        $appliances = \App\Models\Appliance::with('brand')->get();
        $brands = \App\Models\Brand::all();
        $statistics = [
            'total_parts' => $parts->count(),
            'available_parts' => $parts->where('availability', true)->count(),
            'epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker')->count(),
            'air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer')->count(),
            'induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker')->count(),
            'available_epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker' && $p->availability)->count(),
            'available_air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer' && $p->availability)->count(),
            'available_induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker' && $p->availability)->count(),
        ];
        $overviewStats = [
            'total_brands' => $brands->count(),
            'total_appliances' => $appliances->count(),
            'out_of_stock' => $parts->where('availability', false)->count(),
            'stock_percentage' => $parts->count() > 0 ? round(($parts->where('availability', true)->count() / $parts->count()) * 100) : 0,
        ];
        return view('admin', compact('parts', 'appliances', 'brands', 'statistics', 'overviewStats'));
    });
    Route::get('/admin', function () {
        $parts = \App\Models\Part::with(['appliance', 'brands', 'specificAppliances'])->get();
        $appliances = \App\Models\Appliance::with('brand')->get();
        $brands = \App\Models\Brand::all();
        $statistics = [
            'total_parts' => $parts->count(),
            'available_parts' => $parts->where('availability', true)->count(),
            'epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker')->count(),
            'air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer')->count(),
            'induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker')->count(),
            'available_epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker' && $p->availability)->count(),
            'available_air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer' && $p->availability)->count(),
            'available_induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker' && $p->availability)->count(),
        ];
        $overviewStats = [
            'total_brands' => $brands->count(),
            'total_appliances' => $appliances->count(),
            'out_of_stock' => $parts->where('availability', false)->count(),
            'stock_percentage' => $parts->count() > 0 ? round(($parts->where('availability', true)->count() / $parts->count()) * 100) : 0,
        ];
        $chartData = [
            'appliances' => $appliances,
            'parts' => $parts,
            'brands' => $brands
        ];
        return view('welcome', compact('parts', 'appliances', 'brands', 'statistics', 'overviewStats', 'chartData'));
    });
    Route::get('/admin/dashboard', [InventoryController::class, 'index']);
});

// Technician routes
Route::middleware(['auth:technician'])->group(function () {
    Route::get('/technician/panel', function () {
        $statistics = [
            'total_parts' => \App\Models\Part::count(),
            'total_appliances' => \App\Models\Appliance::count(),
            'total_trainers' => \App\Models\Trainer::count(),
            'total_technicians' => \App\Models\Technician::count(),
        ];
        return view('technician-panel', compact('statistics'));
    });
    Route::get('/technician/profile', [TechnicianController::class, 'profile']);
    Route::post('/technician/profile/update', [TechnicianController::class, 'updateProfile']);
});

// Technician home - accessible by both technicians and admins
Route::middleware(['multiguard'])->group(function () {
    Route::get('/technician/home', function () {
        $parts = \App\Models\Part::with(['appliance', 'brands', 'specificAppliances'])->get();
        $appliances = \App\Models\Appliance::with('brand')->get();
        $brands = \App\Models\Brand::all();
        $statistics = [
            'total_parts' => $parts->count(),
            'available_parts' => $parts->where('availability', true)->count(),
            'epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker')->count(),
            'air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer')->count(),
            'induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker')->count(),
            'available_epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker' && $p->availability)->count(),
            'available_air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer' && $p->availability)->count(),
            'available_induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker' && $p->availability)->count(),
        ];
        $overviewStats = [
            'total_brands' => $brands->count(),
            'total_appliances' => $appliances->count(),
            'out_of_stock' => $parts->where('availability', false)->count(),
            'stock_percentage' => $parts->count() > 0 ? round(($parts->where('availability', true)->count() / $parts->count()) * 100) : 0,
        ];
        return view('technician', compact('parts', 'appliances', 'brands', 'statistics', 'overviewStats'));
    });
});

Route::middleware(['auth:technician'])->group(function () {
    Route::get('/technicians', function () {
        $parts = \App\Models\Part::with(['appliance', 'brands', 'specificAppliances'])->get();
        $appliances = \App\Models\Appliance::with('brand')->get();
        $brands = \App\Models\Brand::all();
        $statistics = [
            'total_parts' => $parts->count(),
            'available_parts' => $parts->where('availability', true)->count(),
            'epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker')->count(),
            'air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer')->count(),
            'induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker')->count(),
            'available_epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker' && $p->availability)->count(),
            'available_air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer' && $p->availability)->count(),
            'available_induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker' && $p->availability)->count(),
        ];
        $overviewStats = [
            'total_brands' => $brands->count(),
            'total_appliances' => $appliances->count(),
            'out_of_stock' => $parts->where('availability', false)->count(),
            'stock_percentage' => $parts->count() > 0 ? round(($parts->where('availability', true)->count() / $parts->count()) * 100) : 0,
        ];
        $chartData = [
            'appliances' => $appliances,
            'parts' => $parts,
            'brands' => $brands
        ];
        return view('technician-home', compact('parts', 'appliances', 'brands', 'statistics', 'overviewStats', 'chartData'));
    });
});

// Trainer routes
Route::middleware(['auth:trainer'])->group(function () {
    Route::get('/trainer/panel', function () {
        $statistics = [
            'total_parts' => \App\Models\Part::count(),
            'total_appliances' => \App\Models\Appliance::count(),
            'total_trainers' => \App\Models\Trainer::count(),
            'total_technicians' => \App\Models\Technician::count(),
        ];
        return view('trainer-panel', compact('statistics'));
    });
    Route::get('/trainer/home', function () {
        $parts = \App\Models\Part::with(['appliance', 'brands', 'specificAppliances'])->get();
        $appliances = \App\Models\Appliance::with('brand')->get();
        $brands = \App\Models\Brand::all();
        $statistics = [
            'total_parts' => $parts->count(),
            'available_parts' => $parts->where('availability', true)->count(),
            'epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker')->count(),
            'air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer')->count(),
            'induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker')->count(),
            'available_epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker' && $p->availability)->count(),
            'available_air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer' && $p->availability)->count(),
            'available_induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker' && $p->availability)->count(),
        ];
        $overviewStats = [
            'total_brands' => $brands->count(),
            'total_appliances' => $appliances->count(),
            'out_of_stock' => $parts->where('availability', false)->count(),
            'stock_percentage' => $parts->count() > 0 ? round(($parts->where('availability', true)->count() / $parts->count()) * 100) : 0,
        ];
        return view('trainer', compact('parts', 'appliances', 'brands', 'statistics', 'overviewStats'));
    });
    Route::get('/trainers', function () {
        $parts = \App\Models\Part::with(['appliance', 'brands', 'specificAppliances'])->get();
        $appliances = \App\Models\Appliance::with('brand')->get();
        $brands = \App\Models\Brand::all();
        $statistics = [
            'total_parts' => $parts->count(),
            'available_parts' => $parts->where('availability', true)->count(),
            'epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker')->count(),
            'air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer')->count(),
            'induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker')->count(),
            'available_epc_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Electric Pressure Cooker' && $p->availability)->count(),
            'available_air_fryer_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Air Fryer' && $p->availability)->count(),
            'available_induction_parts' => $parts->filter(fn($p) => $p->appliance && $p->appliance->name === 'Induction Cooker' && $p->availability)->count(),
        ];
        $overviewStats = [
            'total_brands' => $brands->count(),
            'total_appliances' => $appliances->count(),
            'out_of_stock' => $parts->where('availability', false)->count(),
            'stock_percentage' => $parts->count() > 0 ? round(($parts->where('availability', true)->count() / $parts->count()) * 100) : 0,
        ];
        $chartData = [
            'appliances' => $appliances,
            'parts' => $parts,
            'brands' => $brands
        ];
        return view('trainer-home', compact('parts', 'appliances', 'brands', 'statistics', 'overviewStats', 'chartData'));
    });
});

// Public API routes (read-only)
Route::get('/api/parts', [InventoryController::class, 'apiParts']);
Route::get('/api/appliances', [InventoryController::class, 'apiAppliances']);
Route::get('/api/brands', function() {
    return response()->json(\App\Models\Brand::all());
});
Route::get('/api/statistics', [InventoryController::class, 'apiStatistics']);
Route::get('/api/trainers', [TrainerController::class, 'index']);
Route::get('/api/trainers/statistics', [TrainerController::class, 'statistics']);
Route::get('/api/trainers/{trainer}', [TrainerController::class, 'show']);
Route::get('/api/technicians', [TechnicianController::class, 'index']);
Route::get('/api/technicians/statistics', [TechnicianController::class, 'statistics']);
Route::get('/api/technicians/{technician}', [TechnicianController::class, 'show']);
Route::get('/api/technicians/specialties', [TechnicianController::class, 'specialties']);

// Technician routes - only authenticated users can update
Route::middleware(['multiguard', 'role:technician,trainer,admin'])->group(function () {
    Route::put('api/technicians/{technician}', [TechnicianController::class, 'update']);
});

// Trainer routes - only authenticated users can update
Route::middleware(['multiguard', 'role:trainer,admin'])->group(function () {
    Route::put('api/trainers/{trainer}', [TrainerController::class, 'update']);
});

// Admin-only routes - full CRUD
Route::middleware(['multiguard', 'role:admin'])->group(function () {
    Route::post('api/trainers', [TrainerController::class, 'store']);
    Route::delete('api/trainers/{trainer}', [TrainerController::class, 'destroy']);
    Route::post('api/technicians', [TechnicianController::class, 'store']);
    Route::delete('api/technicians/{technician}', [TechnicianController::class, 'destroy']);

    // Admin can manage all inventory
    Route::post('/api/parts', [InventoryController::class, 'storePart']);
    Route::get('/api/parts/{id}', [InventoryController::class, 'showPart']);
    Route::put('/api/parts/{id}', [InventoryController::class, 'updatePart']);
    Route::delete('/api/parts/{id}', [InventoryController::class, 'deletePart']);
    Route::post('/api/appliances', [InventoryController::class, 'storeAppliance']);
    Route::get('/api/appliances/{id}', [InventoryController::class, 'showAppliance']);
    Route::put('/api/appliances/{id}', [InventoryController::class, 'updateAppliance']);
    Route::delete('/api/appliances/{id}', [InventoryController::class, 'deleteAppliance']);
});

// Chat routes - accessible to both guests and authenticated users
Route::get('/chat', function () {
    return view('chat');
});
Route::post('/api/chat/register-guest', [ChatController::class, 'registerGuest']);
Route::post('/api/chat/get-guest', [ChatController::class, 'getGuestBySession']);
Route::get('/api/chat/guest-contacts', [ChatController::class, 'getGuestContacts']);
Route::get('/api/chat/support-users', [ChatController::class, 'getSupportUsers']);
Route::post('/api/chat/get-or-create', [ChatController::class, 'getOrCreateChat']);
Route::get('/api/chat/{chatId}/messages', [ChatController::class, 'getMessages']);
Route::post('/api/chat/send-message', [ChatController::class, 'sendMessage']);
Route::post('/api/chat/upload-file', [ChatController::class, 'uploadFile']);
Route::post('/api/chat/mark-read', [ChatController::class, 'markAsRead']);
Route::get('/api/chat/current-user', [ChatController::class, 'getCurrentUser']);
Route::post('/api/chat/update-last-seen', [ChatController::class, 'updateLastSeen']);
Route::get('/api/chat/unread-counts', [ChatController::class, 'getUnreadCounts']);

