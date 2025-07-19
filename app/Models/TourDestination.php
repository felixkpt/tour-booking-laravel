<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourDestination extends Model
{
    use HasFactory, CommonModelRelationShips, ExcludeSystemFillable;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'link',
        'name',
        'location',
        'slug',
        'short_content',
        'content',
        'featured_image',
        'been_here',
        'wants_to_count',
        'added_to_list',
        'creator_id',
        'status_id',
    ];

    protected $systemFillable = ['slug'];

    // Define relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function imageSlides()
    {
        return $this->hasMany(TourDestinationImageSlide::class, 'tour_destination_id');
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    // Accessor to modify the image_path when accessed
    public function getFeaturedImageAttribute($value)
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
