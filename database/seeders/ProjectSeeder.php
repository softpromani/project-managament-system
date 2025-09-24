<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $admins = User::where('role', 'admin')->get();

        Project::factory()->count(5)->create([
            'created_by' => $admins->random()->id,
        ]);
    }
}
