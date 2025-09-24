<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function index(Task $task): JsonResponse
    {
        $comments = Comment::where('task_id', $task->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $comments,
        ]);
    }

    public function store(Request $request, Task $task): JsonResponse
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = Comment::create([
            'body' => $request->body,
            'task_id' => $task->id,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'data' => $comment->load('user'),
        ], 201);
    }
}
