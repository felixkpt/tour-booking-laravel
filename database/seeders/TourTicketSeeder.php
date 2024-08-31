<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\TourTicket;
use App\Models\TourBooking;
use App\Models\TourTicketStatus;
use Illuminate\Support\Str;

class TourTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all tour bookings, users, and ticket statuses
        $tourBookings = TourBooking::all();
        $statuses = TourTicketStatus::all();

        // Fetch users for ticket creation
        $admins = Role::where(['name' => 'admin'])->first()->users;

        // Check if bookings, users, and statuses are available
        if ($tourBookings->isEmpty() || $admins->isEmpty() || $statuses->isEmpty()) {
            $this->command->info('TourBookings, Users, or TicketStatuses table is empty.');
            return;
        }

        // Loop through each tour booking and create tickets
        foreach ($tourBookings as $booking) {
            // Create a number of tickets for each booking
            for ($i = 0; $i < 3; $i++) {
                TourTicket::create([
                    'tour_booking_id' => $booking->id,
                    'ticket_number' => 'TKT-' . Str::upper(Str::random(8)),
                    'creator_id' => $admins->random()->id,
                    'status_id' => $statuses->random()->id,
                ]);
            }
        }
    }
}
