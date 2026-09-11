<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\UsersAssignedRoleDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_roles', ['only' => ['index', 'show']]);
        $this->middleware('permission:create_roles', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_roles', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_roles', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of all roles with search and filter
     */
    public function index(Request $request)
    {
        $query = Role::query()
            ->where('name', '!=', 'customer')
            ->orderByRaw("CASE name WHEN 'super_admin' THEN 1 WHEN 'receptionist' THEN 2 WHEN 'tailor' THEN 3 ELSE 4 END")
            ->orderBy('name');

        // Search by role name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $roles = $query->paginate(15)->appends($request->query());

        // Get predefined roles count
        $predefinedRoles = ['super_admin', 'receptionist', 'tailor'];

        return view('admin.roles.index', compact('roles', 'predefinedRoles'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::all();
        $permissionGroups = $this->groupPermissions($permissions);

        return view('admin.roles.create', compact('permissions', 'permissionGroups'));
    }

    /**
     * Store a newly created role in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Create role
        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? ucfirst(str_replace('_', ' ', $validated['name'])),
            'description' => $validated['description'] ?? '',
            'guard_name' => 'web',
        ]);

        // Assign permissions
        if (!empty($validated['permissions'])) {
            $permissions = Permission::whereIn('id', $validated['permissions'])->get();
            $role->givePermissionTo($permissions);
        }

        return redirect()->route('admin.user-management.roles.index')
            ->with('success', 'Role created successfully!');
    }

    /**
     * Display the specified role with users
     */
    public function show(Role $role, UsersAssignedRoleDataTable $dataTable)
    {
        $permissions = $role->permissions;
        $permissionGroups = $this->groupPermissions($permissions);

        return $dataTable->with('role', $role)
            ->render('admin.roles.show', compact('role', 'permissions', 'permissionGroups'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->route('admin.user-management.roles.index')
                ->with('error', 'The super admin role cannot be edited.');
        }

        $isSystemRole = false;

        $allPermissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        $permissionGroups = $this->groupPermissions($allPermissions);

        return view('admin.roles.edit', compact('role', 'allPermissions', 'rolePermissions', 'permissionGroups', 'isSystemRole'));
    }

    /**
     * Update the specified role in database
     */
    public function update(Request $request, Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->back()->with('error', 'The super admin role cannot be modified.');
        }

        $validated = $request->validate([
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Update role
        $role->update([
            'display_name' => $validated['display_name'] ?? $role->display_name,
            'description' => $validated['description'] ?? $role->description,
        ]);

        // Update permissions
        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])->get();
        $role->syncPermissions($permissions);

        return redirect()->route('admin.user-management.roles.index')
            ->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified role from database
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'super_admin') {
            return redirect()->back()->with('error', 'The super admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('admin.user-management.roles.index')
            ->with('success', 'Role deleted successfully!');
    }

    /**
     * Group permissions by category
     */
    private function groupPermissions($permissions)
    {
        $groups = [];

        foreach ($permissions as $permission) {
            $parts = explode('_', $permission->name);
            $category = ucfirst($parts[0] ?? 'Other');

            if (!isset($groups[$category])) {
                $groups[$category] = [];
            }

            $groups[$category][] = $permission;
        }

        return $groups;
    }

    /**
     * Get all available permissions for API
     */
    public function getPermissions()
    {
        $permissions = Permission::all();
        $groups = $this->groupPermissions($permissions);

        return response()->json($groups);
    }
}
