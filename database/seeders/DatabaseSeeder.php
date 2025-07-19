<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StatusSeeder::class,
            TourBookingStatusSeeder::class,
            TourTicketStatusSeeder::class,

            RolesAndPermissionsSeeder::class,

            UserSeeder::class,
            // TourDestinationSeeder::class,
            TourSeeder::class,
            TourBookingSeeder::class,
            TourTicketSeeder::class,

        ]);
    }
}
