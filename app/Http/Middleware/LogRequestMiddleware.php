<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->user() ? $request->user()->id : 'guest';
        $endpoint = $request->path();
        $timestamp = now()->toDateTimeString();

        Log::info("API Request", [
            'user_id' => $userId,
            'endpoint' => $endpoint,
            'timestamp' => $timestamp,
            'method' => $request->method(),
        ]);

        return $next($request);
    }
}
