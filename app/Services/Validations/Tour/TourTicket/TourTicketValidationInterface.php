<?php

namespace App\Services\Validations\Tour\TourTicket;

use Illuminate\Http\Request;

interface TourTicketValidationInterface
{
    public function store(Request $request): mixed;    
}
