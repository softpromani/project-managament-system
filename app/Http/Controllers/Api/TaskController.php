<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use App\Services\TaskAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    protected TaskAssignmentService $taskAssignmentService;

    public function __construct(TaskAssignmentService $taskAssignmentService)
    {
        $this->taskAssignmentService = $taskAssignmentService;
    }

    public function index(Project $project, Request $request): JsonResponse
    {
        $query = Task::where('project_id', $project->id)
            ->with(['assignedUser', 'comments']);

        if ($request->has('status')) {
            $query->filterByStatus($request->status);
        }

        if ($request->has('title')) {
            $query->searchByTitle($request->title);
        }

        $tasks = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $tasks,
        ]);
    }

    public function show(Task $task): JsonResponse
    {
        $user = auth()->user();

        // Check if user can view this task
        if (!$user->hasPermissionTo('read-task') && $task->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $task->load(['project', 'assignedUser', 'comments.user']);

        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }

    public function store(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:today',
            'assigned_to' => 'required|exists:users,id',
        ]);

        $task = $this->taskAssignmentService->assignTask(
            $project,
            $validated['assigned_to'],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task->load(['project', 'assignedUser']),
        ], 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $user = auth()->user();

        // Check if user can update this task
        if (!$user->hasPermissionTo('update-task') && $task->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:pending,in-progress,done',
            'due_date' => 'sometimes|required|date|after:today',
        ]);

        $task->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task->load(['project', 'assignedUser']),
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
        ]);
    }
}
