<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\TourBooking;
use App\Models\Tour;
use App\Models\TourBookingStatus;

class TourBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all tours, users, and booking statuses
        $tours = Tour::all();
        $statuses = TourBookingStatus::all();

        // Fetch users with specific roles
        $users = Role::where(['name' => 'user'])->first()->users;
        $admins = Role::where(['name' => 'admin'])->first()->users;

        // Check if tours, users, and statuses are available
        if ($tours->isEmpty() || $users->isEmpty() || $admins->isEmpty() || $statuses->isEmpty()) {
            $this->command->info('Tours, Users, or BookingStatuses table is empty.');
            return;
        }

        // Loop through each tour and create bookings
        foreach ($tours as $tour) {
            $maxSlots = $tour->slots; // Assuming you have a 'slots' column in the Tour model

            // Create a number of bookings for each tour
            for ($i = 0; $i < 4; $i++) {
                // Generate a random number of slots between 1 and the maximum available slots
                $slots = rand(1, $maxSlots);

                // Create a booking entry
                TourBooking::create([
                    'user_id' => $users->random()->id,
                    'tour_id' => $tour->id,
                    'slots' => $slots,
                    'creator_id' => $users->random()->id,
                    'status_id' => $statuses->random()->id,
                ]);

                // Decrement the available slots for the tour
                $maxSlots -= $slots;

                // Break if no more slots are available
                if ($maxSlots <= 0) {
                    break;
                }
            }

            // Update the tour with the remaining slots
            $tour->update(['slots' => $maxSlots]);
        }
    }
}
