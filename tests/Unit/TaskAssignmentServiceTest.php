<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use App\Services\TaskAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_assign_task_to_user(): void
    {
        $service = new TaskAssignmentService();

        $project = Project::factory()->create();
        $user = User::factory()->create();

        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test task description',
            'due_date' => '2024-12-31',
        ];

        $task = $service->assignTask($project, $user->id, $taskData);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'project_id' => $project->id,
            'assigned_to' => $user->id,
        ]);

        $this->assertEquals($project->id, $task->project_id);
        $this->assertEquals($user->id, $task->assigned_to);
    }
}
