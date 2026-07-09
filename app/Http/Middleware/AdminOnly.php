<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     * Only super_admin, receptionist, and tailor can access admin routes.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if email is verified
        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // Only allow super_admin, receptionist, and tailor roles
        $user = auth()->user();
        if ($user->hasRole('super_admin') || $user->hasRole('receptionist') || $user->hasRole('tailor')) {
            return $next($request);
        }

        // Customers and other users cannot access admin routes
        return redirect()->route('home');
    }
}
