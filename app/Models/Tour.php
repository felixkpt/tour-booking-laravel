<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'destination_id', 'name', 'description', 'price', 'slots', 'creator_id', 'status_id'];

    public function imageSlides()
    {
        return $this->hasMany(ImageSlide::class);
    }
}
