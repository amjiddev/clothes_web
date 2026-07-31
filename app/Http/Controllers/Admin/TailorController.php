<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tailor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TailorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_tailors', ['only' => ['index', 'show']]);
        $this->middleware('permission:assign_tailors', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of tailors with search and filter
     */
    public function index(Request $request)
    {
        $query = Tailor::with('user');

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by specialization
        if ($request->has('specialization') && $request->specialization) {
            $query->where('specialization', 'like', "%{$request->specialization}%");
        }

        // Sort by active orders
        $tailors = $query->paginate(15)->appends($request->query());

        // Calculate statistics for each tailor
        $tailors->each(function ($tailor) {
            $tailor->total_assigned = $tailor->getTotalAssignedOrders();
            $tailor->completed = $tailor->getCompletedOrders();
            $tailor->pending = $tailor->getPendingOrders();
            $tailor->active = $tailor->getActiveOrders();
        });

        $specializations = Tailor::distinct()->pluck('specialization')->filter();

        return view('admin.tailors.index', compact('tailors', 'specializations'));
    }

    /**
     * Show the form for creating a new tailor
     */
    public function create()
    {
        $users = User::where(function ($query) {
            $query->whereDoesntHave('tailor')
                  ->orWhereHas('tailor', function ($q) {
                      $q->where('id', '!=', 0); // This condition is always true, so it acts as a placeholder
                  });
        })->get();

        $specializations = ['Shalwar Kameez', 'Suits', 'Kurta', 'Waistcoat', 'Sherwani', 'Formal Wear', 'Casual Wear'];
        
        return view('admin.tailors.create', compact('users', 'specializations'));
    }

    /**
     * Store a newly created tailor in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:tailors,user_id',
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'skills' => 'nullable|array',
            'skills.*' => 'string',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hourly_rate' => 'nullable|numeric|min:0',
            'experience_years' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,on_leave',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('tailors', 'public');
        }

        // Convert skills array to JSON
        if (isset($validated['skills'])) {
            $validated['skills'] = $validated['skills'];
        }

        Tailor::create($validated);
        User::find($validated['user_id'])->syncRoles('tailor');

        return redirect()->route('admin.tailors.index')->with('success', 'Tailor created successfully!');
    }

    /**
     * Display the specified tailor
     */
    public function show(Tailor $tailor)
    {
        $tailor->load('user');
        $tailor->total_assigned = $tailor->getTotalAssignedOrders();
        $tailor->completed = $tailor->getCompletedOrders();
        $tailor->pending = $tailor->getPendingOrders();
        $tailor->active = $tailor->getActiveOrders();

        $recentOrders = $tailor->user->tailorAssignments()
            ->with('order', 'measurement')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.tailors.show', compact('tailor', 'recentOrders'));
    }

    /**
     * Show the form for editing the specified tailor
     */
    public function edit(Tailor $tailor)
    {
        $tailor->load('user');
        $specializations = ['Shalwar Kameez', 'Suits', 'Kurta', 'Waistcoat', 'Sherwani', 'Formal Wear', 'Casual Wear'];
        
        return view('admin.tailors.edit', compact('tailor', 'specializations'));
    }

    /**
     * Update the specified tailor in database
     */
    public function update(Request $request, Tailor $tailor)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'specialization' => 'nullable|string|max:255',
            'skills' => 'nullable|array',
            'skills.*' => 'string',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hourly_rate' => 'nullable|numeric|min:0',
            'experience_years' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive,on_leave',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            if ($tailor->profile_image) {
                Storage::disk('public')->delete($tailor->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('tailors', 'public');
        }

        $tailor->update($validated);

        return redirect()->route('admin.tailors.index')->with('success', 'Tailor updated successfully!');
    }

    /**
     * Remove the specified tailor from database
     */
    public function destroy(Tailor $tailor)
    {
        // Delete profile image if exists
        if ($tailor->profile_image) {
            Storage::disk('public')->delete($tailor->profile_image);
        }

        $tailor->user->removeRole('tailor');
        $tailor->delete();

        return redirect()->route('admin.tailors.index')->with('success', 'Tailor deleted successfully!');
    }

    /**
     * Get tailor dashboard data (API endpoint)
     */
    public function getDashboard(Tailor $tailor)
    {
        $tailor->total_assigned = $tailor->getTotalAssignedOrders();
        $tailor->completed = $tailor->getCompletedOrders();
        $tailor->pending = $tailor->getPendingOrders();
        $tailor->active = $tailor->getActiveOrders();

        return response()->json($tailor);
    }
}
