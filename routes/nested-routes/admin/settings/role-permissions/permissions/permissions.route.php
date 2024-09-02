<?php

use App\Http\Controllers\Dashboard\Settings\RolePermissions\Permissions\PermissionsController;
use Illuminate\Support\Facades\Route;

$controller = PermissionsController::class;
Route::get('/', [$controller, 'index'])->name('List Permissions');
Route::post('/', [$controller, 'store'])->hidden();
Route::get('/get-role-permissions/{role_id}', [$controller, 'getRolePermissions'])->hidden();
Route::patch('/update-statuses', [$controller, 'updateStatuses'])->hidden(); // Update statuses of multiple records (hidden)
