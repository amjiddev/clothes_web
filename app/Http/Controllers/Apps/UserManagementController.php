<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_users', ['only' => ['index', 'show']]);
        $this->middleware('permission:create_users', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_users', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_users', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of all users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->role($request->role);
        }

        $users = $query->with('roles')->paginate(20)->appends($request->query());
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Super admin can create users with any role, including super_admin
        $roles = auth()->user()->hasRole('super_admin')
            ? Role::all()
            : Role::where('name', '!=', 'super_admin')->get();
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);

        // Verify super_admin role can only be assigned by super_admin users
        $role = Role::find($validated['role']);
        if ($role->name === 'super_admin' && !auth()->user()->hasRole('super_admin')) {
            return back()->withErrors(['role' => 'Only super admin users can assign the super_admin role.']);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'email_verified_at' => now(),  // ✅ Set to Active immediately (no Pending)
        ]);

        $user->assignRole($role);

        return redirect()->route('admin.user-management.users.show', $user)
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $userRoles = $user->roles;
        $permissions = $user->getAllPermissions();

        return view('admin.users.show', compact('user', 'userRoles', 'permissions'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Super admin can assign any role, including super_admin
        $roles = auth()->user()->hasRole('super_admin')
            ? Role::all()
            : Role::where('name', '!=', 'super_admin')->get();
        
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user in database
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
            'is_blocked' => 'nullable|boolean',
        ]);

        // Verify super_admin role can only be assigned by super_admin users
        $role = Role::find($validated['role']);
        if ($role->name === 'super_admin' && !auth()->user()->hasRole('super_admin')) {
            return back()->withErrors(['role' => 'Only super admin users can assign the super_admin role.']);
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_blocked' => $request->has('is_blocked') ? (bool)$request->input('is_blocked') : false,
        ]);

        if ($validated['password']) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        $user->syncRoles([$role]);

        return redirect()->route('admin.user-management.users.show', $user)
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from database
     */
    public function destroy(User $user)
    {
        // Prevent deletion of super admin user
        if ($user->hasRole('super_admin')) {
            return redirect()->back()->with('error', 'Cannot delete super admin user.');
        }

        $user->delete();

        return redirect()->route('admin.user-management.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
