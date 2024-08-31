<?php

namespace App\Services\Validations\Status\BookingStatus;

use Illuminate\Http\Request;

interface BookingStatusValidationInterface
{
    public function store(Request $request): mixed;    
}
