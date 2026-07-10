<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receptionist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ReceptionistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
        // Super admin can always manage receptionists
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasRole('super_admin')) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of receptionists with search and filter
     */
    public function index(Request $request)
    {
        $query = Receptionist::with('user');

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

        // Filter by department
        if ($request->has('department') && $request->department) {
            $query->where('department', 'like', "%{$request->department}%");
        }

        $receptionists = $query->paginate(15)->appends($request->query());

        $departments = Receptionist::distinct()->pluck('department')->filter();

        return view('admin.receptionists.index', compact('receptionists', 'departments'));
    }

    /**
     * Show the form for creating a new receptionist
     */
    public function create()
    {
        // Get all users that don't have a receptionist record
        $receptionistUserIds = Receptionist::pluck('user_id')->toArray();
        $users = User::whereNotIn('id', $receptionistUserIds)->get();

        $departments = ['Front Desk', 'Customer Service', 'Order Management', 'Tailor Coordination', 'General'];

        return view('admin.receptionists.create', compact('users', 'departments'));
    }

    /**
     * Store a newly created receptionist in database
     */
    public function store(Request $request)
    {
        // Check if creating new user or using existing
        $isNewUser = !$request->filled('user_id');

        if ($isNewUser) {
            // Validate for new user
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
                'department' => 'required|string|max:255',
                'status' => 'required|in:active,inactive',
            ]);
        } else {
            // Validate for existing user
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id|unique:receptionists,user_id',
                'phone' => 'nullable|string|max:20',
                'department' => 'required|string|max:255',
                'status' => 'required|in:active,inactive',
            ]);
        }

        $userId = $validated['user_id'] ?? null;

        // Create new user if not selected
        if ($isNewUser) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(), // Auto-verify admin-created users
            ]);
            $userId = $user->id;
        }

        // Create receptionist record
        $receptionist = Receptionist::create([
            'user_id' => $userId,
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'],
            'status' => $validated['status'],
            'assigned_date' => now(),
        ]);

        // Assign receptionist role and permissions
        $user = User::find($userId);
        $user->syncRoles('receptionist');
        $user->givePermissionTo($receptionist->getPermissions());

        return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist created successfully!');
    }

    /**
     * Display the specified receptionist
     */
    public function show(Receptionist $receptionist)
    {
        $receptionist->load('user');
        return view('admin.receptionists.show', compact('receptionist'));
    }

    /**
     * Show the form for editing the specified receptionist
     */
    public function edit(Receptionist $receptionist)
    {
        $receptionist->load('user');
        $departments = ['Front Desk', 'Customer Service', 'Order Management', 'Tailor Coordination', 'General'];

        return view('admin.receptionists.edit', compact('receptionist', 'departments'));
    }

    /**
     * Update the specified receptionist in database
     */
    public function update(Request $request, Receptionist $receptionist)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $receptionist->update($validated);
        $receptionist->last_action_date = now();
        $receptionist->save();

        return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist updated successfully!');
    }

    /**
     * Remove the specified receptionist from database
     */
    public function destroy(Receptionist $receptionist)
    {
        $receptionist->user->removeRole('receptionist');
        $receptionist->user->revokePermissionTo($receptionist->getPermissions());
        $receptionist->delete();

        return redirect()->route('admin.receptionists.index')->with('success', 'Receptionist deleted successfully!');
    }

    /**
     * Activate receptionist
     */
    public function activate(Receptionist $receptionist)
    {
        $receptionist->activate();
        return redirect()->back()->with('success', 'Receptionist activated successfully!');
    }

    /**
     * Deactivate receptionist
     */
    public function deactivate(Receptionist $receptionist)
    {
        $receptionist->deactivate();
        return redirect()->back()->with('success', 'Receptionist deactivated successfully!');
    }

    /**
     * Get receptionist permissions (API endpoint)
     */
    public function getPermissions(Receptionist $receptionist)
    {
        return response()->json([
            'permissions' => $receptionist->getPermissions(),
            'status' => $receptionist->status,
            'user' => $receptionist->user->only(['id', 'name', 'email']),
        ]);
    }
}
