<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourTicket extends Model
{
    use HasFactory, CommonModelRelationShips, ExcludeSystemFillable;

    protected $fillable = [
        'uuid',
        'tour_booking_id',
        'ticket_number',
        'creator_id',
        'status_id',
    ];

    // Define relationships
    public function tourBooking()
    {
        return $this->belongsTo(TourBooking::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function status()
    {
        return $this->belongsTo(TourTicketStatus::class, 'status_id');
    }
}
