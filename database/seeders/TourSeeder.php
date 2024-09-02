<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tour;
use App\Models\TourDestination;
use App\Models\User;
use Faker\Factory as Faker;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Sample tour data
        $toursData = [
            ['name' => 'Adventure Tour', 'description' => 'An exciting adventure tour through the mountains.'],
            ['name' => 'City Sightseeing', 'description' => 'Explore the best sights of the city.'],
            ['name' => 'Beach Relaxation', 'description' => 'Enjoy a relaxing time at the beach.'],
            ['name' => 'Cultural Tour', 'description' => 'Immerse yourself in the local culture and traditions.'],
            ['name' => 'Safari Adventure', 'description' => 'Experience the thrill of a safari in the wild.'],
            ['name' => 'Historical Walk', 'description' => 'Discover the rich history of the region.'],
            ['name' => 'Wine Tasting', 'description' => 'Taste the finest wines from local vineyards.'],
            ['name' => 'Mountain Hiking', 'description' => 'Hike through breathtaking mountain trails.'],
            ['name' => 'Desert Safari', 'description' => 'Embark on an adventurous desert safari.'],
            ['name' => 'Jungle Expedition', 'description' => 'Explore the deep jungle and its wildlife.'],
            ['name' => 'Underwater Diving', 'description' => 'Dive into the ocean and explore underwater life.'],
            ['name' => 'Culinary Tour', 'description' => 'Taste the best local cuisine on this culinary tour.'],
            ['name' => 'Winter Sports', 'description' => 'Enjoy skiing and snowboarding in a winter wonderland.'],
            ['name' => 'Island Hopping', 'description' => 'Explore beautiful islands on this island-hopping tour.'],
            ['name' => 'Extreme Sports', 'description' => 'Get your adrenaline pumping with extreme sports.'],
            ['name' => 'Photography Tour', 'description' => 'Capture the most stunning views on this photography tour.'],
            ['name' => 'River Rafting', 'description' => 'Experience the thrill of river rafting.'],
            ['name' => 'Wildlife Safari', 'description' => 'Get up close with wildlife on this safari tour.'],
            ['name' => 'Hot Air Balloon Ride', 'description' => 'Soar high above the landscape in a hot air balloon.'],
            ['name' => 'Scuba Diving', 'description' => 'Explore the underwater world with scuba diving.'],
            ['name' => 'Paragliding', 'description' => 'Experience the thrill of paragliding.'],
            ['name' => 'Luxury Yacht Tour', 'description' => 'Enjoy a luxurious yacht tour.'],
            ['name' => 'Forest Camping', 'description' => 'Camp in the serene forest environment.'],
            ['name' => 'City Nightlife Tour', 'description' => 'Explore the vibrant nightlife of the city.'],
            ['name' => 'Biking Expedition', 'description' => 'Join a biking expedition through scenic routes.'],
            ['name' => 'Sailing Adventure', 'description' => 'Sail the seas on an adventure.'],
            ['name' => 'Art and Craft Tour', 'description' => 'Explore local art and craft traditions.'],
            ['name' => 'Rock Climbing', 'description' => 'Challenge yourself with a rock climbing adventure.'],
            ['name' => 'Fishing Trip', 'description' => 'Relax and enjoy a fishing trip.'],
            ['name' => 'Nature Photography', 'description' => 'Capture stunning photos of nature.'],
            ['name' => 'Spelunking', 'description' => 'Explore underground caves and tunnels.'],
            ['name' => 'Horseback Riding', 'description' => 'Experience the thrill of horseback riding.'],
            ['name' => 'Zip Lining', 'description' => 'Fly through the treetops on a zip line.'],
            ['name' => 'Kayaking', 'description' => 'Navigate rivers and lakes in a kayak.'],
            ['name' => 'Glamping', 'description' => 'Enjoy glamorous camping with added comforts.'],
            ['name' => 'Surfing', 'description' => 'Ride the waves on a surfing adventure.'],
            ['name' => 'Trekking', 'description' => 'Embark on a long-distance trek through nature.'],
            ['name' => 'Historical Reenactment', 'description' => 'Experience historical events through reenactments.'],
            ['name' => 'Craft Beer Tour', 'description' => 'Taste craft beers from local breweries.'],
            ['name' => 'Skiing', 'description' => 'Enjoy skiing on snowy slopes.'],
            ['name' => 'Snowboarding', 'description' => 'Experience the thrill of snowboarding.'],
            ['name' => 'Thermal Springs', 'description' => 'Relax in natural thermal hot springs.'],
            ['name' => 'Bird Watching', 'description' => 'Observe and identify various bird species.'],
            ['name' => 'Dune Bashing', 'description' => 'Experience the thrill of driving on sand dunes.'],
            ['name' => 'Volcano Tour', 'description' => 'Explore the features of an active volcano.'],
            ['name' => 'Glacier Hiking', 'description' => 'Hike on stunning glaciers.'],
            ['name' => 'Gastronomy Tour', 'description' => 'Enjoy a tour focused on gourmet food and drinks.'],
            ['name' => 'Spa Retreat', 'description' => 'Relax with a luxury spa experience.'],
            ['name' => 'Camping Under the Stars', 'description' => 'Enjoy a night camping under the stars.'],
            ['name' => 'Fishing Expedition', 'description' => 'Embark on a fishing adventure in deep waters.'],
            ['name' => 'Cooking Class', 'description' => 'Learn to cook local dishes with expert chefs.'],
            ['name' => 'Mountain Biking', 'description' => 'Ride challenging mountain biking trails.'],
            ['name' => 'Caving', 'description' => 'Explore underground caves and rock formations.'],
            ['name' => 'Cultural Workshop', 'description' => 'Participate in local cultural workshops.'],
            ['name' => 'Bungee Jumping', 'description' => 'Feel the thrill of bungee jumping from heights.'],
            ['name' => 'Farm Tour', 'description' => 'Experience life on a local farm.'],
            ['name' => 'River Cruise', 'description' => 'Enjoy a relaxing cruise along scenic rivers.'],
            ['name' => 'Art Gallery Tour', 'description' => 'Explore local art galleries and exhibitions.'],
            ['name' => 'Music Festival', 'description' => 'Attend a lively music festival with various genres.'],
            ['name' => 'Historical Tour', 'description' => 'Learn about historical landmarks and sites.'],
            ['name' => 'Adventure Racing', 'description' => 'Participate in an adventurous race.'],
            ['name' => 'Urban Exploration', 'description' => 'Discover hidden gems and urban landscapes.'],
            ['name' => 'Luxury Train Journey', 'description' => 'Travel in style on a luxury train journey.'],
            ['name' => 'Wine and Dine', 'description' => 'Enjoy a wine tasting paired with fine dining.'],
            ['name' => 'Hot Springs and Spa', 'description' => 'Relax in hot springs followed by a spa treatment.'],
            ['name' => 'Scenic Flight', 'description' => 'Take a flight for stunning aerial views.'],
            ['name' => 'Astronomy Tour', 'description' => 'Observe the night sky with guided astronomy tours.'],
            ['name' => 'Whale Watching', 'description' => 'Witness the majestic sight of whales in their natural habitat.'],
            ['name' => 'Sunset Cruise', 'description' => 'Enjoy a beautiful sunset from a boat.'],
            ['name' => 'Tango Dance Class', 'description' => 'Learn to dance the tango with professional instructors.'],
            ['name' => 'Street Food Tour', 'description' => 'Taste a variety of street foods from local vendors.'],
            ['name' => 'Meditation Retreat', 'description' => 'Relax and rejuvenate with guided meditation sessions.'],
            ['name' => 'Brewery Tour', 'description' => 'Visit local breweries and sample craft beers.'],
            ['name' => 'Wine Harvesting', 'description' => 'Participate in a wine harvest and learn about winemaking.'],
            ['name' => 'Artisan Craft Tour', 'description' => 'Explore local artisan crafts and handmade goods.'],
            ['name' => 'River Kayaking', 'description' => 'Kayak down scenic rivers and waterways.'],
            ['name' => 'Rock Concert', 'description' => 'Attend a thrilling rock music concert.'],
        ];

        // Fetch all available destinations
        $destinations = TourDestination::all();

        // Fetch users
        $users = User::all();

        // Check if there are destinations available
        if ($destinations->isEmpty()) {
            $this->command->info('No destinations found. Please seed TourDestinations first.');
            return;
        }

        foreach ($toursData as $tourData) {
            $creator_id = null;
            $random = rand(1, 4);
            if ($random <= 3) {
                // 75% chance
                $creator_id = $users->random()->id;
            }
            // Select a random destination for each tour
            $destination = $destinations->random();


            // Generate 1 to 3 paragraphs and wrap each in <p> tags
            $extraParagraphs = implode('', array_map(fn($p) => "<p>$p</p>", $faker->paragraphs(rand(1, 3))));
            $extendedDescription = '<p>' . $tourData['description'] . '</p>' . $extraParagraphs;

            $name = $tourData['name'] . ' (' . $destination->name . ')';
            // Create a tour with the randomly selected destination
            Tour::updateOrCreate(
                [
                    'name' => $name,
                ],
                [
                    'name' => $name,
                    'tour_destination_id' => $destination->id,
                    'name' => $tourData['name'] . ' (' . $destination->name . ')',
                    'description' => $extendedDescription,
                    'price' => $faker->randomFloat(2, 50, 300),
                    'slots' => $faker->randomNumber(2),
                    'creator_id' => $creator_id,
                    'status_id' => activeStatusId(),
                    'featured_image' => $faker->imageUrl(640, 480, 'tour', true),
                ]
            );
        }
    }
}
