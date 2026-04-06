<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $clientKey = $request->header('X-API-KEY');
        $serverKey = env('API_KEY');

        if ($clientKey !== $serverKey) {
            //return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
