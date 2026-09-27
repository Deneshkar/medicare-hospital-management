<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You do not have access to this resource.',
                ], 403);
            }

            if (! $request->user()) {
                return redirect()->route('login');
            }

            return redirect()->route('dashboard')->with('error', 'You do not have access to this resource.');
        }

        return $next($request);
    }
}
