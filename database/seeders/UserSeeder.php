<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch or create roles
        $adminRole = Role::where(['name' => 'admin'])->first();
        $userRole = Role::where(['name' => 'user'])->first();

        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('Passw0rd@1234!'),
            'status_id' => activeStatusId()
        ]);

        // Find the Admin User and assign the 'admin' role
        $user = User::where('email', 'admin@example.com')->first();
        if ($user) {
            $user->syncRoles([$adminRole]);
        }

        $counts = 100;
        // Create users
        for ($i = 0; $i <= $counts; $i++) {
            // Determine role assignment based on weighted percentages
            $random = rand(1, 100);

            if ($random <= 20) {
                // 20% chance for admin
                $selectedRole = $adminRole;
            } elseif ($random <= 80) {
                // 60% chance for user
                $selectedRole = $userRole;
            } else {
                // 20% chance for no role
                $selectedRole = null;
            }

            // Create user
            $email = $faker->unique()->safeEmail;
            $user = User::create([
                'name' => $faker->name,
                'email' => $email,
                'password' => Hash::make($email),
                'phone' => $faker->phoneNumber,
                'avatar' => $faker->imageUrl(640, 480, 'people', true, 'avatar'), // example avatar
                'last_login_date' => $faker->dateTimeThisYear(),
                'default_role_id' => $selectedRole ? $selectedRole->id : 0,
                'creator_id' => null,
                'status_id' => 1,
            ]);

            // Assign role if applicable
            if ($selectedRole) {
                $user->syncRoles([$selectedRole]);
            }
        }
    }
}
