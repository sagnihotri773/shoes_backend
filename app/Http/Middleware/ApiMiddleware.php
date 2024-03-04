<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
class ApiMiddleware extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$guards)
    {
        //$this->authenticate($request, $guards);
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // For non-JSON requests, you can customize the response accordingly
        // For example, you can redirect to a login page or return a view

        return $next($request);
    }
}
