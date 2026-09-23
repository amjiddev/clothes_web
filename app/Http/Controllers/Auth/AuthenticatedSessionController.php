<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('pages/auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        \Log::info('Login attempt started', [
            'email' => $request->input('email'),
            'has_iframe_param' => $request->input('_iframe'),
            'sec_fetch_dest' => $request->header('Sec-Fetch-Dest')
        ]);

        $request->authenticate();

        \Log::info('Authentication successful');

        $request->session()->regenerate();

        $user = $request->user();

        \Log::info('User loaded', [
            'user_id' => $user->id,
            'email' => $user->email,
            'email_verified' => $user->hasVerifiedEmail()
        ]);

        // Email verified check
        if (! $user->hasVerifiedEmail()) {
            Auth::logout();
            \Log::warning('Email not verified, logged out');
            return redirect()->route('verification.notice');
        }

        $request->user()->update([
            'last_login_at' => Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);

        // Get user roles
        $roles = $user->roles->pluck('name')->toArray();
        \Log::info('User roles', ['roles' => $roles]);

        // Determine redirect URL based on role
        if ($user->hasRole('super_admin')) {
            $redirectUrl = route('admin.dashboard');
        } elseif ($user->hasRole('receptionist')) {
            $redirectUrl = route('receptionist.dashboard');
        } elseif ($user->hasRole('tailor')) {
            $redirectUrl = route('tailor.dashboard');
        } else {
            // Customers and other users go to home page
            $redirectUrl = route('home');
        }

        \Log::info('Redirect URL determined', ['url' => $redirectUrl]);

        // Check if request is from iframe (modal login)
        // Return HTML with JavaScript to redirect parent window
        if ($request->header('Sec-Fetch-Dest') === 'iframe' || $request->input('_iframe') === '1') {
            \Log::info('Iframe login detected, returning redirect-parent view');
            return response()->view('auth.redirect-parent', [
                'redirectUrl' => $redirectUrl
            ]);
        }

        \Log::info('Normal redirect');
        // Normal redirect for non-iframe requests
        return redirect($redirectUrl);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
