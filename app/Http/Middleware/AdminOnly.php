<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     * Only super_admin can access admin routes.
     * Receptionists and tailors have their own separate dashboards.
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

        // Only allow super_admin role for admin routes
        $user = auth()->user();
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Redirect based on user role
        if ($user->hasRole('receptionist')) {
            return redirect()->route('receptionist.dashboard');
        }

        if ($user->hasRole('tailor')) {
            return redirect()->route('tailor.dashboard');
        }

        // Customers and other users cannot access admin routes
        return redirect()->route('home');
    }
}
