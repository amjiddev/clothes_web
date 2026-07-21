<?php

namespace App\Http\Controllers\Tailor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display tailor profile settings
     */
    public function edit()
    {
        $user = Auth::user();
        $tailor = $user->tailor;

        // Create tailor record if it doesn't exist
        if (!$tailor) {
            $tailor = \App\Models\Tailor::create([
                'user_id' => $user->id,
                'status' => 'active',
                'experience_years' => 0,
                'total_orders' => 0,
                'average_rating' => 0,
                'completed_orders' => 0,
                'pending_orders' => 0,
            ]);
        }

        return view('tailor.profile.edit', [
            'user' => $user,
            'tailor' => $tailor,
        ]);
    }

    /**
     * Update tailor profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $tailor = $user->tailor;

        // Create tailor record if it doesn't exist
        if (!$tailor) {
            $tailor = \App\Models\Tailor::create([
                'user_id' => $user->id,
                'status' => 'active',
                'experience_years' => 0,
                'total_orders' => 0,
                'average_rating' => 0,
                'completed_orders' => 0,
                'pending_orders' => 0,
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'experience_years' => 'nullable|integer|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update user data
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        // Update tailor data
        $tailor->phone = $validated['phone'] ?? $tailor->phone;
        $tailor->specialization = $validated['specialization'] ?? $tailor->specialization;
        $tailor->bio = $validated['bio'] ?? $tailor->bio;
        $tailor->experience_years = $validated['experience_years'] ?? $tailor->experience_years ?? 0;

        // Handle profile image
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($tailor->profile_image) {
                Storage::disk('public')->delete($tailor->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('tailors/profiles', 'public');
            $tailor->profile_image = $path;
        }

        $tailor->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z\d!@#$%^&*]{8,}$/',
        ], [
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character (!@#$%^&*)'
        ]);

        $user = Auth::user();
        $user->password = bcrypt($validated['password']);
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Display change password form
     */
    public function showChangePasswordForm()
    {
        return view('tailor.profile.change-password');
    }
}
