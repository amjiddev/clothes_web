<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TailorOnly
{
    /**
     * Handle an incoming request.
     * Only tailor role with active status can access tailor routes.
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

        // Only allow tailor role
        $user = auth()->user();
        if (!$user->hasRole('tailor')) {
            // Redirect based on user role
            if ($user->hasRole('super_admin')) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->hasRole('receptionist')) {
                return redirect()->route('receptionist.dashboard');
            }

            // Default redirect for customers and others
            return redirect()->route('home');
        }

        // Verify tailor exists and is active
        $tailor = $user->tailor;
        if (!$tailor || $tailor->status !== 'active') {
            return redirect()->route('home')->with('error', 'Your tailor account is inactive. Please contact the administrator.');
        }

        return $next($request);
    }
}
