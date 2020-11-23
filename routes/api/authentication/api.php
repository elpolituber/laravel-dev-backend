<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\UserController;
use App\Http\Controllers\Authentication\PermissionController;
use App\Http\Controllers\Authentication\RouteController;
use App\Http\Controllers\Authentication\ShortcutController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::group(['prefix' => 'auth'], function () {
    Route::post('forgot_password', [AuthController::class, 'forgotPassword'])->withoutMiddleware(['auth:api', 'check-attempts', 'check-status']);
    Route::post('user_unlock', [AuthController::class, 'unlockUser'])->withoutMiddleware(['auth:api', 'check-attempts', 'check-status']);
    Route::post('reset_password', [AuthController::class, 'resetPassword'])->withoutMiddleware(['auth:api', 'check-attempts', 'check-status']);
    Route::post('unlock', [AuthController::class, 'unlock'])->withoutMiddleware(['auth:api', 'check-attempts', 'check-status']);
    Route::get('attempts/{username}', [AuthController::class, 'attempts'])->withoutMiddleware(['auth:api', 'check-attempts', 'check-status']);
});
Route::group(['prefix' => 'auth'], function () {
    Route::get('transactional_code/{username}', [AuthController::class, 'transactionalCode']);
    Route::get('reset_attempts/{username}', [AuthController::class, 'resetAttempts']);
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('logout_all', [AuthController::class, 'logoutAll']);
    Route::put('change_password', [AuthController::class, 'changePassword']);
});

Route::apiResource('permissions', PermissionController::class);
Route::apiResource('routes', RouteController::class);
Route::apiResource('shortcuts', ShortcutController::class);
Route::apiResource('users', UserController::class)->except(['index']);

Route::group(['prefix' => 'users'], function () {
    Route::post('filters', [UserController::class, 'index']);
    Route::post('avatar', [UserController::class, 'uploadAvatarUri']);
    Route::get('export/', [UserController::class, 'export']);
});





