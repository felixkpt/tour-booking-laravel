<?php

namespace App\Services\Validations\Tour\TourBooking;

use Illuminate\Http\Request;

interface TourBookingValidationInterface
{
    public function store(Request $request): mixed;    
}
