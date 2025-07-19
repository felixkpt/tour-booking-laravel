<?php

namespace App\Services\Validations\Tour\TourDestination;

use Illuminate\Http\Request;

interface TourDestinationValidationInterface
{
    public function store(Request $request): mixed;
    public function storeFromJson(Request $request): mixed;
}
