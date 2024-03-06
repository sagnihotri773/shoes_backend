<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create a role for the admin
        $adminRole = Role::create(['name' => 'admin']);

        // Create permissions if needed
        //$manageUsersPermission = Permission::create(['name' => 'manage-users']);
        // Add more permissions as needed

        // Attach permissions to the admin role
       // $adminRole->permissions()->attach([$manageUsersPermission->id]);

        // Create the admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // You may want to use Hash::make() instead
        ]);

        // Attach the admin role to the admin user
        $adminUser->roles()->attach([$adminRole->id]);
    }
}
