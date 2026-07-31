<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReceptionistOnly
{
    /**
     * Handle an incoming request.
     * Only receptionist role can access receptionist routes.
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

        // Only allow receptionist role
        $user = auth()->user();
        if ($user->hasRole('receptionist')) {
            return $next($request);
        }

        // Redirect non-receptionists away
        if ($user->hasRole('super_admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('tailor')) {
            return redirect()->route('tailor.dashboard');
        }

        // Default redirect for customers and others
        return redirect()->route('home');
    }
}
