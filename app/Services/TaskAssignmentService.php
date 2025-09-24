<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Jobs\SendTaskAssignmentNotification;
use Illuminate\Validation\ValidationException;

class TaskAssignmentService
{
    public function assignTask(Project $project, int $userId, array $taskData): Task
    {
        $user = User::findOrFail($userId);

        // Validate user can be assigned tasks
        if (!$this->canAssignTaskToUser($user)) {
            throw ValidationException::withMessages([
                'assigned_to' => ['User cannot be assigned tasks.'],
            ]);
        }

        // Create task
        $task = Task::create([
            'title' => $taskData['title'],
            'description' => $taskData['description'],
            'due_date' => $taskData['due_date'],
            'project_id' => $project->id,
            'assigned_to' => $userId,
        ]);

        // Dispatch notification job
        SendTaskAssignmentNotification::dispatch($task, $user);

        return $task;
    }

    private function canAssignTaskToUser(User $user): bool
    {
        // Add your business logic here
        // For example, check if user is active, has capacity, etc.
        return true;
    }

    public function reassignTask(Task $task, int $newUserId): Task
    {
        $newUser = User::findOrFail($newUserId);

        if (!$this->canAssignTaskToUser($newUser)) {
            throw ValidationException::withMessages([
                'assigned_to' => ['User cannot be assigned tasks.'],
            ]);
        }

        $task->update(['assigned_to' => $newUserId]);

        SendTaskAssignmentNotification::dispatch($task, $newUser);

        return $task;
    }
}
