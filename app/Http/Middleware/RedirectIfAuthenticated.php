<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Check user roles and redirect accordingly
                if ($user->hasRole('super_admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->hasRole('receptionist') || $user->hasRole('tailor')) {
                    return redirect()->route('admin.dashboard');
                }

                // Default: customers and other users go to home
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}
