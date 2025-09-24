<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommentController;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'log.request'])->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Project routes
    Route::get('/projects', [ProjectController::class, 'index'])
        ->middleware('permission:read-project');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])
        ->middleware('permission:read-project');
    Route::post('/projects', [ProjectController::class, 'store'])
        ->middleware('permission:create-project');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])
        ->middleware('permission:update-project');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])
        ->middleware('permission:delete-project');

    // Task routes
    Route::get('/projects/{project}/tasks', [TaskController::class, 'index'])
        ->middleware('permission:read-task');
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])
        ->middleware('permission:create-task');
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->middleware('permission:delete-task');

    // Comment routes
    Route::get('/tasks/{task}/comments', [CommentController::class, 'index'])
        ->middleware('permission:read-comment');
    Route::post('/tasks/{task}/comments', [CommentController::class, 'store'])
        ->middleware('permission:create-comment');
});
