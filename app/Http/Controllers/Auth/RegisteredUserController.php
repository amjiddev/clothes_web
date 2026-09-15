<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        addJavascriptFile('assets/js/custom/authentication/sign-up/general.js');

        return view('pages/auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => app()->environment('local') ? now() : null,
            'last_login_at' => \Illuminate\Support\Carbon::now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);

        // Assign CUSTOMER role (not 'user' - 'customer' is defined in RoleAndPermissionSeeder)
        $user->assignRole('customer');

        if (! $user->hasVerifiedEmail()) {
            event(new Registered($user));
        }

        // Return success response
        return response()->json([
            'message' => $user->hasVerifiedEmail()
                ? 'Registration successful. You can now sign in.'
                : 'Please check your email to verify your account.'
        ]);
    }

    public function changePassword()
    {
        return view('pages.auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'old_password' => 'required',
                'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
            ]);

            $user = Auth::user();

            // Check if the old password matches the user's current password
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->withErrors([
                    'old_password' => __('The provided old password does not match our records.'),
                ]);
            }

            // Update the user's password
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success',  __('Password updated successfully!'));
        } catch (ValidationException $e) {
            // Handle validation errors
            return redirect()->back()->withErrors($e->errors());
        } catch (\Exception $e) {
            // Handle any other exceptions
            return redirect()->back()->with('error', __('An error occurred while updating the password. Please try again later.'));
        }
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $oldEmail = strtolower($user->email);
        $email = strtolower(trim($request->input('email')));
        $request->merge(['email' => $email]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'contact_number' => ['nullable', 'string', 'max:30'],
        ], [
            'email.unique' => 'This email is already used by another account. Please choose a different email.',
        ]);

        $validated['email'] = $email;

        $user->update($validated);

        if ($email !== $oldEmail && app()->environment('local')) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
