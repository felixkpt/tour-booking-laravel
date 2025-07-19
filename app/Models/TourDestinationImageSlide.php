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

    // Accessor to modify the image_path when accessed
    public function getImagePathAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        // Check if the path already starts with http:// or https://
        if (!preg_match('/^https?:\/\//', $value)) {
            return 'http://localhost:8000/assets/' . ltrim($value, '/');
        }

        return $value;
    }
}
