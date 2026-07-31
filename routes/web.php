<?php

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Dashboard redirect route (authenticated users only)
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();

    // Super Admin go to admin dashboard
    if ($user->hasRole('super_admin')) {
        return redirect()->route('admin.dashboard');
    }

    // Receptionist go to receptionist dashboard
    if ($user->hasRole('receptionist')) {
        return redirect()->route('receptionist.dashboard');
    }

    // Tailor go to tailor dashboard
    if ($user->hasRole('tailor')) {
        return redirect()->route('tailor.dashboard');
    }

    // Customers go to home
    return redirect()->route('home');
})->name('dashboard');

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';

// Admin routes (Admin only)
require __DIR__ . '/admin.php';

// Tailor routes (Tailor only)
require __DIR__ . '/tailor.php';

// Receptionist routes (Receptionist only)
require __DIR__ . '/receptionist.php';

// Frontend routes (For guests and users)
require __DIR__ . '/frontend.php';
