<?php

namespace App\Http\Controllers;

use App\Models\TourDestination;
use App\Models\Tour;
use App\Models\TourBooking;
use App\Models\TourTicket;
use App\Models\User;

class AdminController extends Controller
{
    public function stats()
    {
        $stats = [
            'destinations' => TourDestination::count(),
            'tours' => Tour::count(),
            'bookings' => TourBooking::count(),
            'tickets' => TourTicket::count(),
            'users' => User::count(),
        ];

        return response()->json($stats);
    }
}
