<?php

namespace App\Http\Controllers\Apps;

use App\DataTables\PermissionsDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_permissions', ['only' => ['index', 'show']]);
        $this->middleware('permission:manage_permissions', ['only' => ['create', 'store', 'edit', 'update', 'destroy']]);
    }

    /**
     * Display a listing of all permissions
     */
    public function index(Request $request)
    {
        $query = Permission::query();

        // Search by permission name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by category/prefix
        if ($request->has('category') && $request->category) {
            $category = $request->category;
            $query->where('name', 'like', "{$category}%");
        }

        $permissions = $query->paginate(20)->appends($request->query());

        // Get unique categories
        $categories = collect();
        Permission::all()->each(function ($permission) use ($categories) {
            $parts = explode('_', $permission->name);
            $category = $parts[0] ?? 'other';
            $categories->push($category);
        });
        $categories = $categories->unique();

        return view('admin.permissions.index', compact('permissions', 'categories'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        $categories = ['view', 'create', 'edit', 'delete', 'manage', 'assign', 'export'];

        return view('admin.permissions.create', compact('categories'));
    }

    /**
     * Store a newly created permission in database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'] ?? ucfirst(str_replace('_', ' ', $validated['name'])),
            'description' => $validated['description'] ?? '',
            'guard_name' => 'web',
        ]);

        return redirect()->route('admin.user-management.permissions.index')
            ->with('success', 'Permission created successfully!');
    }

    /**
     * Display the specified permission
     */
    public function show(Permission $permission)
    {
        $rolesWithPermission = $permission->roles;

        return view('admin.permissions.show', compact('permission', 'rolesWithPermission'));
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit(Permission $permission)
    {
        $categories = ['view', 'create', 'edit', 'delete', 'manage', 'assign', 'export'];

        return view('admin.permissions.edit', compact('permission', 'categories'));
    }

    /**
     * Update the specified permission in database
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $permission->update([
            'display_name' => $validated['display_name'] ?? $permission->display_name,
            'description' => $validated['description'] ?? $permission->description,
        ]);

        return redirect()->route('admin.user-management.permissions.index')
            ->with('success', 'Permission updated successfully!');
    }

    /**
     * Remove the specified permission from database
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any role
        $roleCount = $permission->roles()->count();

        if ($roleCount > 0) {
            return redirect()->back()->with('error', "Cannot delete permission assigned to {$roleCount} role(s).");
        }

        $permission->delete();

        return redirect()->route('admin.user-management.permissions.index')
            ->with('success', 'Permission deleted successfully!');
    }

    /**
     * Get all permissions grouped by category (API endpoint)
     */
    public function getGrouped()
    {
        $permissions = Permission::all();
        $groups = [];

        foreach ($permissions as $permission) {
            $parts = explode('_', $permission->name);
            $category = ucfirst($parts[0] ?? 'Other');

            if (!isset($groups[$category])) {
                $groups[$category] = [];
            }

            $groups[$category][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'display_name' => $permission->display_name ?? ucfirst(str_replace('_', ' ', $permission->name)),
            ];
        }

        return response()->json($groups);
    }
}
