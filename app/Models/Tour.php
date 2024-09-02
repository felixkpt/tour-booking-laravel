<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory, CommonModelRelationShips, ExcludeSystemFillable;

    protected $fillable = ['uuid', 'name', 'destination_id', 'description', 'featured_image', 'price', 'slots', 'creator_id', 'status_id'];

    // Define relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function destination()
    {
        return $this->belongsTo(TourDestination::class, 'tour_destination_id');
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' (' . $this->destination->name . ')';
    }
}
