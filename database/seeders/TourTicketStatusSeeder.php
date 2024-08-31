<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourTicketStatus;
use App\Models\User;
use Illuminate\Support\Str;

class TourTicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Define sample statuses
        $statuses = [
            ['name' => 'pending', 'icon' => 'ic:sharp-pending', 'class' => 'text-warning'],
            ['name' => 'confirmed', 'icon' => 'ic:sharp-check-circle', 'class' => 'text-success'],
            ['name' => 'cancelled', 'icon' => 'ic:sharp-cancel', 'class' => 'text-danger'],
        ];

        // Create statuses
        foreach ($statuses as $status) {
            TourTicketStatus::updateOrCreate(['name' => $status['name']], [
                'uuid' => Str::uuid(),
                'name' => $status['name'],
                'description' => ucfirst($status['name']) . ' status.',
                'icon' => $status['icon'],
                'class' => $status['class'],
                'creator_id' => null,
            ]);
        }
    }
}
