<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourDestination extends Model
{
    use HasFactory, CommonModelRelationShips;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
        'featured_image',
        'creator_id',
        'status_id',
    ];

    // Define relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
}
