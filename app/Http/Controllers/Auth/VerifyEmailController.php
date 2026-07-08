<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function __invoke(EmailVerificationRequest $request)
    // {
    //     if ($request->user()->hasVerifiedEmail()) {
    //         return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    //     }

    //     if ($request->user()->markEmailAsVerified()) {
    //         event(new Verified($request->user()));
    //     }

    //     return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    // }


    public function __invoke($id, $hash)
    {
        // Find the user
        $user = User::findOrFail($id);

        // Check if URL is properly signed
        if (! URL::hasValidSignature(request())) {
            abort(403, 'Invalid or expired verification link.');
        }

        // Check if hash matches user's email
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        // Mark email as verified
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        // Redirect to login page with success message
        return redirect('/login')->with('verified', true)
            ->with('message', 'Your email has been successfully verified. You can now login.');
    }
}
