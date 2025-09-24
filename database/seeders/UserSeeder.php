<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create 3 admins
        $admins = User::factory()->admin()->count(3)->create();
        foreach ($admins as $admin) {
            $admin->assignRole('admin');
        }

        // Create 3 managers
        $managers = User::factory()->manager()->count(3)->create();
        foreach ($managers as $manager) {
            $manager->assignRole('manager');
        }

        // Create 5 users
        $users = User::factory()->user()->count(5)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
    }
}
