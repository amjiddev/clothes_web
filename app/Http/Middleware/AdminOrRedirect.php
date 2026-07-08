<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOrRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and verified
        if (!auth()->check() || !auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('login');
        }

        // Check if user has admin role
        if (auth()->user()->hasRole('administrator')) {
            return $next($request);
        }

        // If not admin, redirect to home
        return redirect()->route('home');
    }
}
