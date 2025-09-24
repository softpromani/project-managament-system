<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();

        Task::factory()->count(10)->create([
            'project_id' => $projects->random()->id,
            'assigned_to' => $users->random()->id,
        ]);
    }
}
