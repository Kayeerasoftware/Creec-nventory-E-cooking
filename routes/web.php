<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\TechnicianController;

Route::get('/', [InventoryController::class, 'index']);
Route::get('/reports', [ReportsController::class, 'index']);
Route::get('/api/reports/data', [ReportsController::class, 'apiReportData']);
Route::get('/api/reports/export/csv', [ReportsController::class, 'exportCSV']);
Route::get('/api/parts', [InventoryController::class, 'apiParts']);
Route::get('/api/appliances', [InventoryController::class, 'apiAppliances']);
Route::get('/api/statistics', [InventoryController::class, 'apiStatistics']);

// Trainer routes
Route::resource('api/trainers', TrainerController::class);
Route::get('/api/trainers/statistics', [TrainerController::class, 'statistics']);

// Technician routes
Route::resource('api/technicians', TechnicianController::class);
Route::get('/api/technicians/specialties', [TechnicianController::class, 'specialties']);

Route::post('/api/chat/start', [ChatController::class, 'startChat']);
Route::post('/api/chat/send', [ChatController::class, 'sendMessage']);
Route::get('/api/chat/messages', [ChatController::class, 'getMessages']);
Route::post('/api/chat/status', [ChatController::class, 'updateMessageStatus']);
Route::post('/api/chat/react', [ChatController::class, 'reactToMessage']);
Route::post('/api/chat/delete', [ChatController::class, 'deleteMessage']);
Route::post('/api/chat/typing', [ChatController::class, 'typingIndicator']);
Route::post('/api/chat/pin', [ChatController::class, 'pinMessage']);
Route::post('/api/chat/forward', [ChatController::class, 'forwardMessage']);
Route::get('/api/chat/search', [ChatController::class, 'searchMessages']);
Route::post('/api/chat/location', [ChatController::class, 'sendLocation']);
Route::post('/api/chat/contact', [ChatController::class, 'sendContact']);
