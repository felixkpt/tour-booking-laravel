<?php

namespace App\Services\Validations\Tour;

use Illuminate\Http\Request;

interface TourValidationInterface
{
    public function store(Request $request): mixed;
}
