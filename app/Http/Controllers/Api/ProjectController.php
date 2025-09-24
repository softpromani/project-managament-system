<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'projects_list_' . md5($request->getQueryString());

        $projects = Cache::remember($cacheKey, 3600, function () use ($request) {
            $query = Project::with(['creator', 'tasks']);

            if ($request->has('title')) {
                $query->searchByTitle($request->title);
            }

            return $query->paginate(10);
        });

        return response()->json([
            'success' => true,
            'data' => $projects,
        ]);
    }

    public function show(Project $project): JsonResponse
    {
        $project->load(['creator', 'tasks.assignedUser', 'tasks.comments']);

        return response()->json([
            'success' => true,
            'data' => $project,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => auth()->id(),
        ]);

        // Clear cache
        Cache::forget('projects_list_*');

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project->load('creator'),
        ], 201);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $project->update($request->validated());

        // Clear cache
        Cache::forget('projects_list_*');

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project->load('creator'),
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        // Clear cache
        Cache::forget('projects_list_*');

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
        ]);
    }
}
