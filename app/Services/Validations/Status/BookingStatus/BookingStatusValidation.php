<?php

namespace App\Services\Validations\Status\BookingStatus;

use Illuminate\Http\Request;

class BookingStatusValidation implements BookingStatusValidationInterface
{

    public function store(Request $request): mixed
    {

        $validateData = $request->validate(
            [
                'name' => 'required|string|unique:statuses,name,' . $request->id . ',id',
                'description' => 'required|string',
                'icon' => 'required|string',
                'class' => 'nullable|string',
            ]
        );

        return $validateData;
    }
}
