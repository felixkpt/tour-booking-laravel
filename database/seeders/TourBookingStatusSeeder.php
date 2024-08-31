<?php

namespace Database\Seeders;

use App\Models\TourBookingStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            TourBookingStatus::updateOrCreate(
                ['name' => $status['name']],
                [
                    'name' => $status['name'],
                    'slug' => Str::slug($status['name']),
                    'description' => $status['description'],
                    'icon' => null,
                    'class' => null,
                ]
            );
        }
    }
}
