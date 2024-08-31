<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory, CommonModelRelationShips;

    protected $fillable = ['uuid', 'destination_id', 'name', 'description', 'featured_image', 'price', 'slots', 'creator_id', 'status_id'];

    public function destination()
    {
        return $this->hasMany(TourDestination::class);
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->destination->name . ')';
    }
}
