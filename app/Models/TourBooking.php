<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourBooking extends Model
{
    use HasFactory, CommonModelRelationShips, ExcludeSystemFillable;

    protected $fillable = [
        'uuid',
        'user_id',
        'tour_id',
        'slots',
        'creator_id',
        'status_id',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function status()
    {
        return $this->belongsTo(TourBookingStatus::class, 'status_id');
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
}
