<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Tours\TourBookingController;
use App\Http\Controllers\Tours\TourDestinationController;
use App\Http\Controllers\Tours\ToursController;
use App\Http\Controllers\Tours\TourTicketController;
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
// Routes accessible to authenticated users (both admin and regular users)


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('tours')->group(function () {
        Route::get('/', [ToursController::class, 'index']); // View available tours
        Route::get('/view/{id}', [ToursController::class, 'show']); // View a specific tour

        Route::get('/destinations', [TourDestinationController::class, 'index']); // View all tour destinations
        Route::get('/destinations/{id}', [TourDestinationController::class, 'show']); // View a specific destination

        Route::get('/bookings', [TourBookingController::class, 'getSelf']); // View all bookings
        Route::post('/bookings', [TourBookingController::class, 'store']); // Book a tour
        Route::get('/bookings/{id}', [TourBookingController::class, 'show']); // View a specific booking

    });

    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
        // Routes accessible only to admins with 'admin' prefix
        Route::get('/stats', [AdminController::class, 'stats']);

        Route::get('/destinations', [TourDestinationController::class, 'index']); // list tour destination
        Route::post('/destinations', [TourDestinationController::class, 'store']); // Create a new tour destination
        Route::put('/destinations/view/{id}', [TourDestinationController::class, 'update']); // Update a destination
        Route::patch('/destinations/view/{id}', [TourDestinationController::class, 'updateStatus']); // Updatestatus a tour ticket
        Route::delete('/destinations/{id}', [TourDestinationController::class, 'destroy']); // Delete a destination

        Route::prefix('tours')->group(function () {
            Route::get('/', [ToursController::class, 'index']); // list tours
            Route::post('/', [ToursController::class, 'store']); // Create a new tour
            Route::put('/view/{id}', [ToursController::class, 'update']); // Update a tour
            Route::patch('/view/{id}', [ToursController::class, 'updateStatus']); // Updatestatus a tour ticket
            Route::delete('/view/{id}', [ToursController::class, 'destroy']); // Delete a tour

            Route::get('/tickets', [TourTicketController::class, 'index']); // View all tour tickets
            Route::post('/tickets', [TourTicketController::class, 'store']); // Create a new tour ticket
            Route::put('/tickets/view/{id}', [TourTicketController::class, 'update']); // Update a tour ticket
            Route::patch('/tickets/view/{id}/update-status', [TourTicketController::class, 'updateStatus']); // Updatestatus a tour ticket
            Route::delete('/tickets/view/{id}', [TourTicketController::class, 'destroy']); // Delete a tour ticket

            Route::get('/bookings', [TourBookingController::class, 'index']); // View all bookings
            Route::post('/bookings', [TourBookingController::class, 'store']); // Book a tour for user
            Route::put('/bookings/view/{id}', [TourBookingController::class, 'update']); // Update a tour book
            Route::patch('/bookings/view/{id}/update-status', [TourBookingController::class, 'updateStatus']); // Updatestatus a tour book
            Route::delete('/bookings/view/{id}', [TourBookingController::class, 'destroy']); // Delete a tour book
        });
    });
});

// Include authentication routes
require 'auth.php';
