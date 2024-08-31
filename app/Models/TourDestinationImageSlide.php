<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourDestinationImageSlide extends Model
{
    use HasFactory;

    protected $fillable = ['tour_destination_id', 'image_path'];

    public function destination()
    {
        return $this->belongsTo(TourDestination::class);
    }
}
