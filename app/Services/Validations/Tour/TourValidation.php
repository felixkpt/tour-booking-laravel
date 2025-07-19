<?php

namespace App\Services\Validations\Tour;

use App\Models\Tour;
use Illuminate\Http\Request;

class TourValidation implements TourValidationInterface
{
    public function store(Request $request): mixed
    {
        $validateData = $request->validate(
            [
                'name' => 'required|string|unique:tours,name,' . $request->id . ',id',
                'description' => 'required|string',
                'tour_destination_id' => 'required|integer|exists:tour_destinations,id',
                'featured_image' => 'nullable|string',
                'price' => 'required|numeric',
                'slots' => 'required|integer|min:1',
            ]
        );
        return $validateData;
    }
}
