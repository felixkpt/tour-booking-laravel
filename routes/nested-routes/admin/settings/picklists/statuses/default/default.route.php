<?php

use App\Http\Controllers\Dashboard\Settings\Picklists\Statuses\StatusesController;
use Illuminate\Support\Facades\Route;

$controller = StatusesController::class;
Route::get('/', [$controller, 'index'])->name('List Record statuses');
Route::post('/', [$controller, 'store'])->hidden();
