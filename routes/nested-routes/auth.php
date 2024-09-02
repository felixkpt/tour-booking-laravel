<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\Settings\RolePermissions\Roles\RolesController;
use App\Http\Controllers\Dashboard\Settings\RolePermissions\Roles\View\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes

$controller = AuthController::class;

// Register and Login routes
Route::post('register', [$controller, 'register']);
Route::post('login', [$controller, 'login']);

// Password-related routes
Route::prefix('password')->group(function () use ($controller) {
    Route::post('send-reset-link', [$controller, 'passwordResetLink']);
    Route::get('{token}', [$controller, 'getEmail'])->name('getEmail');
    Route::post('set', [$controller, 'passwordSet'])->name('password.set');
});

// Authenticated user routes
Route::middleware(['auth:sanctum'])->group(function () use ($controller) {
    Route::get('/get-user', function (Request $request) {
        $user = $request->user();
        // Generate and assign an API token
        if ($user) {
            $user->token = $user->createToken("API TOKEN")->plainTextToken;
        }
        return ['results' => $user];
    });

    Route::patch('update-profile', [$controller, 'profileUpdate']);
    Route::patch('update-password', [$controller, 'updatePassword']);
    Route::get('login-logs', [$controller, 'loginLogs']);
    Route::post('logout', [$controller, 'logout']);
});

// User role and permission routes
Route::prefix('roles')->group(function () use ($controller) {
    $controller = RolesController::class;
    Route::get('/get-user-roles-and-permissions', [$controller, 'getUserRolesAndPermissions']);
    Route::get('/get-user-roles', [$controller, 'getUserRoles']);

    $controller = RoleController::class;
    Route::get('/view/{id}/get-role-route-permissions', [$controller, 'getRoleRoutePermissions']);
    Route::get('/view/{id}/get-role-menu', [$controller, 'getRoleMenu']);
});
