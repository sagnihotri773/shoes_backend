<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the authenticated user has the 'admin' role
        if (auth()->check() && auth()->user()->hasRole('admin')) {
            return $next($request);
        }

        // If not an admin, redirect or return a response as needed
        return response()->json(['error' => 'Unauthorized You Have No Admin Role'], 403);
    }
}
