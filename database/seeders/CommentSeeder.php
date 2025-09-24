<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = Task::all();
        $users = User::all();

        Comment::factory()->count(10)->create([
            'task_id' => $tasks->random()->id,
            'user_id' => $users->random()->id,
        ]);
    }
}
