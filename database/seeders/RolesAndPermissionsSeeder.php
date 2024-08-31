<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache to avoid issues with stale data
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Specify the guard name
        $guardName = 'api';

        // Creating or updating permissions with the guard name
        $createTours = Permission::updateOrCreate(
            ['name' => 'create tours', 'guard_name' => $guardName],
            ['name' => 'create tours', 'guard_name' => $guardName]
        );
        $viewBookings = Permission::updateOrCreate(
            ['name' => 'view bookings', 'guard_name' => $guardName],
            ['name' => 'view bookings', 'guard_name' => $guardName]
        );
        $bookTours = Permission::updateOrCreate(
            ['name' => 'book tours', 'guard_name' => $guardName],
            ['name' => 'book tours', 'guard_name' => $guardName]
        );

        // Creating or updating roles with the guard name
        $adminRole = Role::updateOrCreate(
            ['name' => 'admin', 'guard_name' => $guardName],
            ['name' => 'admin', 'guard_name' => $guardName]
        );
        $userRole = Role::updateOrCreate(
            ['name' => 'user', 'guard_name' => $guardName],
            ['name' => 'user', 'guard_name' => $guardName]
        );

        // Assigning permissions to roles
        $adminRole->syncPermissions([$createTours, $viewBookings]);
        $userRole->syncPermissions([$bookTours]);

        // Find the Admin User and assign the 'admin' role
        $user = User::where('email', 'admin@example.com')->first();
        if ($user) {
            $user->syncRoles([$adminRole]);
        }
    }
}
