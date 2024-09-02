<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use App\Models\TourDestination;
use App\Models\TourDestinationImageSlide;
use App\Models\User;
use Faker\Factory as Faker;

class TourDestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch statuses
        $statuses = Status::all();

        // Fetch users
        $users = User::all();

        // Check if statuses and users are available
        if ($statuses->isEmpty() || $users->isEmpty()) {
            $this->command->info('Statuses or Users table is empty.');
            return;
        }

        $count = 20;
        // Create sample tour destinations
        for ($i = 0; $i < $count; $i++) {

            $creator_id = null;
            $random = rand(1, 4);
            if ($random <= 3) {
                // 75% chance to assign a creator
                $creator_id = $users->random()->id;
            }

            $destination = TourDestination::updateOrCreate(
                [
                    'name' => $faker->city,
                    'slug' => $faker->slug,
                ],
                [
                    'name' => $faker->city,
                    'slug' => $faker->slug,
                    'description' => $faker->paragraph,
                    'featured_image' => $faker->imageUrl(640, 480, 'destination', true, 'Faker'),
                    'creator_id' => $creator_id,
                    'status_id' => $statuses->random()->id,
                ]
            );

            // Create image slides for the created destination with a 70% chance
            if (rand(1, 100) >= 30) {
                for ($j = 1; $j <= rand(1, 10); $j++) {
                    TourDestinationImageSlide::create([
                        'tour_destination_id' => $destination->id,
                        'image_path' => $faker->imageUrl(640, 480, 'destination', true, 'Faker'),
                    ]);
                }
            }
        }
    }
}
