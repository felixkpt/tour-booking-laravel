<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Tour;

class MaxAvailableSlots implements Rule
{
    protected $tourId;

    public function __construct($tourId)
    {
        $this->tourId = $tourId;
    }

    public function passes($attribute, $value)
    {
        $tour = Tour::find($this->tourId);
        return $tour && $value <= $tour->slots;
    }

    public function message()
    {
        return 'The number of slots exceeds the available slots.';
    }
}
