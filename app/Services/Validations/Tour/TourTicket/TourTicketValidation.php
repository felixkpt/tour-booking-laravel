<?php

namespace App\Services\Validations\Tour\TourTicket;

use Illuminate\Http\Request;

class TourTicketValidation implements TourTicketValidationInterface
{
    public function store(Request $request): mixed
    {
        $validateData = $request->validate([
            'tour_booking_id' => 'required|integer|exists:tour_bookings,id',
        ]);

        return $validateData;
    }
}
