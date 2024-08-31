<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImageSlide;
use App\Models\Tour;
use Illuminate\Support\Str;

class ImageSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $tourIds = Tour::pluck('id');

        foreach ($tourIds as $tourId) {
            ImageSlide::create([
                'tour_id' => $tourId,
                'image_url' => 'https://example.com/image' . Str::random(5) . '.jpg',
            ]);
        }
    }
}
