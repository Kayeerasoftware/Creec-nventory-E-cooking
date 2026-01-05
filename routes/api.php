<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\ApplianceController;

Route::match(['put', 'post'], '/appliances/{id}', [ApplianceController::class, 'update']);

Route::prefix('chat')->group(function () {
    Route::post('/register-guest', [ChatController::class, 'registerGuest']);
    Route::post('/get-guest', [ChatController::class, 'getGuestBySession']);
    Route::get('/support-users', [ChatController::class, 'getSupportUsers']);
    Route::get('/current-user', [ChatController::class, 'getCurrentUser']);
    Route::post('/get-or-create', [ChatController::class, 'getOrCreateChat']);
    Route::get('/{chat}/messages', [ChatController::class, 'getMessages']);
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::post('/upload-file', [ChatController::class, 'uploadFile']);
    Route::post('/mark-read', [ChatController::class, 'markAsRead']);
    Route::get('/guest-contacts', [ChatController::class, 'getGuestContacts']);
    Route::get('/unread-counts', [ChatController::class, 'getUnreadCounts']);
    Route::post('/update-last-seen', [ChatController::class, 'updateLastSeen']);
});