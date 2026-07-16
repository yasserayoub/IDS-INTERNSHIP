<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // User must be logged in
        if (!Auth::check()) {
            return redirect('/');
        }

        // Get the current user's role
        $userRole = Auth::user()->role->Name;

        // Check if the user's role is allowed
        if (!in_array($userRole, $roles)) {
            return redirect('/profile')
                ->with('error', 'You do not have permission to access this page.');
        }

        // Allow the request to continue
        return $next($request);
    }
}
