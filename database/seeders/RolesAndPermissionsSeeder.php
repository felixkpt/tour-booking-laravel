<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache to avoid issues with stale data
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Creating or updating permissions
        $createTours = Permission::updateOrCreate(['name' => 'create tours'], ['name' => 'create tours']);
        $viewBookings = Permission::updateOrCreate(['name' => 'view bookings'], ['name' => 'view bookings']);
        $bookTours = Permission::updateOrCreate(['name' => 'book tours'], ['name' => 'book tours']);

        // Creating or updating roles
        $adminRole = Role::updateOrCreate(['name' => 'admin'], ['name' => 'admin']);
        $userRole = Role::updateOrCreate(['name' => 'user'], ['name' => 'user']);

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
