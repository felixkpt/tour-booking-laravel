<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TourController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes requiring sanctum auth
Route::middleware('auth:sanctum')->group(function () {

    // Routes accessible to authenticated users (both admin and regular users)
    Route::get('/tours', [TourController::class, 'index']); // View available tours

    Route::group(['middleware' => ['role:user', 'role:admin']], function () {
        // Routes accessible only to users and admins
        Route::post('/bookings', [BookingController::class, 'store']); // Book a tour
        Route::get('/bookings/{id}', [BookingController::class, 'show']); // View a specific booking
        Route::post('/tickets', [TicketController::class, 'store']); // Generate a ticket
        Route::get('/tickets/{id}', [TicketController::class, 'show']); // View a specific ticket
    });

    Route::group(['middleware' => ['role:admin']], function () {
        // Routes accessible only to admins
        Route::post('/tours', [TourController::class, 'store']); // Create a new tour
        Route::put('/tours/{id}', [TourController::class, 'update']); // Update a tour
        Route::delete('/tours/{id}', [TourController::class, 'destroy']); // Delete a tour

        Route::get('/bookings', [BookingController::class, 'index']); // View all bookings
        Route::get('/tickets', [TicketController::class, 'index']); // View all tickets
    });
});

require 'api-auth.php';