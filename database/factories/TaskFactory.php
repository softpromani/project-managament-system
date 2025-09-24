<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'in-progress', 'done']),
            'due_date' => fake()->dateTimeBetween('+1 day', '+2 months'),
            'project_id' => Project::factory(),
            'assigned_to' => User::factory(),
        ];
    }
}
