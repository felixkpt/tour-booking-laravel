<?php

namespace App\Services\Validations\Tour\TourBooking;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Tour; // Make sure to import your Tour model

class TourBookingValidation implements TourBookingValidationInterface
{
    public function store(Request $request): mixed
    {
        // First, validate basic fields
        $validateData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'tour_id' => 'required|integer|exists:tours,id',
            'slots' => [
                'required',
                'integer',
                'min:1',
                function ($attribute, $value, $fail) use ($request) {
                    $tour = Tour::find($request->tour_id);
                    
                    if ($tour && $value > $tour->slots) {
                        $fail('The number of slots exceeds the available slots for this tour.');
                    }
                }
            ],
        ]);

        return $validateData;
    }
}
