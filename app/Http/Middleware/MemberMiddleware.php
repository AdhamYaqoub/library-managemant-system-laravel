<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MemberMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->role !== 'member') {

            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        return $next($request);
    }
}