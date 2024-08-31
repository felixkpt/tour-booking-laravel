<?php

namespace Database\Seeders;

use App\Models\BookingStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TourBookingStatusSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Booking Statuses
        $statuses = [
            ['name' => 'Pending', 'description' => 'Booking is pending confirmation'],
            ['name' => 'Confirmed', 'description' => 'Booking has been confirmed'],
            ['name' => 'Cancelled', 'description' => 'Booking has been cancelled'],
            ['name' => 'Completed', 'description' => 'Tour has been completed'],
        ];

        foreach ($statuses as $status) {
            BookingStatus::updateOrCreate(
                ['name' => $status['name']],
                [
                    'name' => $status['name'],
                    'slug' => Str::slug($status['name']),
                    'description' => $status['description'],
                    'icon' => null,
                    'class' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }

        // Seed Tours
        $tours = [
            [
                'name' => 'Historical City Tour',
                'description' => 'Explore the rich history of the city with guided tours.',
                'price' => 120.00,
                'slots' => 10,
                'featured_image' => 'images/tours/city_tour.jpg',
                'creator_id' => 1, // Adjust based on your actual data
            ],
            [
                'name' => 'Adventure Hiking',
                'description' => 'A thrilling hiking experience in the mountains.',
                'price' => 150.00,
                'slots' => 8,
                'featured_image' => 'images/tours/hiking.jpg',
                'creator_id' => 1, // Adjust based on your actual data
            ],
        ];

        foreach ($tours as $tour) {
            $tourRecord = Tour::updateOrCreate(
                ['name' => $tour['name']], // Assuming `name` is unique
                [
                    'uuid' => (string) Str::uuid(),
                    'destination_id' => 1, // Adjust based on your actual data
                    'name' => $tour['name'],
                    'description' => $tour['description'],
                    'price' => $tour['price'],
                    'slots' => $tour['slots'],
                    'featured_image' => $tour['featured_image'],
                    'creator_id' => $tour['creator_id'],
                    'status_id' => 1, // Assuming default status ID
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );

            // Create or update image slides for the tour
            $imageSlides = [
                'images/tours/slides/slide1.jpg',
                'images/tours/slides/slide2.jpg',
                'images/tours/slides/slide3.jpg',
            ];

            foreach ($imageSlides as $image) {
                DB::table('tour_image_slides')->updateOrInsert(
                    ['tour_id' => $tourRecord->id, 'image_path' => $image],
                    ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]
                );
            }
        }
    }
}
