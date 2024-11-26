<?php

namespace App\Services\Validations\Tour\TourDestination;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TourDestinationValidation implements TourDestinationValidationInterface
{
    public function store(Request $request): mixed
    {
        $validateData = $request->validate([
            'name' => [
                'required',
                'string',
                // Rule::unique('tour_destinations', 'name')->ignore($request->id, 'id')
            ],
            'description' => 'required|string',
            'featured_image' => 'nullable|string',
        ]);

        $validateData['slug'] = Str::slug($validateData['name']);

        return $validateData;
    }
}
