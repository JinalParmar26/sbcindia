<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        
        // Group permissions by module
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            // Handle both formats: hyphenated (user-view) and underscore (View_users)
            if (strpos($permission->name, '-') !== false) {
                // Local format: user-view -> user (view will add "Management")
                $parts = explode('-', $permission->name);
                if (count($parts) >= 2) {
                    $module = $parts[0];
                    $moduleKey = ucfirst($module);  // Just "User", not "User Management"
                    $groupedPermissions[$moduleKey][] = $permission;
                }
            } else {
                // Live format: View_users -> User Management (removing 's' from users)
                $parts = explode('_', $permission->name);
                if (count($parts) >= 2) {
                    $module = $parts[1]; // users, customers, etc.
                    
                    // Remove trailing 's' if it exists (users -> user, customers -> customer)
                    if (str_ends_with($module, 's') && $module !== 'permissions') {
                        $module = rtrim($module, 's');
                    }
                    
                    $moduleKey = ucfirst($module) . ' Management';
                    $groupedPermissions[$moduleKey][] = $permission;
                } else {
                    // Single word permissions
                    $groupedPermissions['Other Management'][] = $permission;
                }
            }
        }
        
        return view('roles.create', ['permissions' => $groupedPermissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
            'description' => $validated['description'] ?? null
        ]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            // Handle both formats: hyphenated (user-view) and underscore (View_users)
            if (strpos($permission->name, '-') !== false) {
                // Local format: user-view -> User (view will add "Management")
                $parts = explode('-', $permission->name);
                if (count($parts) >= 2) {
                    return ucfirst($parts[0]); // Just "User", not "User Management"
                }
                return 'Other';
            } else {
                // Live format: View_users -> User Management (removing 's' from users)
                $parts = explode('_', $permission->name);
                if (count($parts) >= 2) {
                    $module = $parts[1]; // users, customers, etc.
                    
                    // Remove trailing 's' if it exists (users -> user, customers -> customer)
                    if (str_ends_with($module, 's') && $module !== 'permissions') {
                        $module = rtrim($module, 's');
                    }
                    
                    return ucfirst($module) . ' Management';
                } else {
                    return 'Other Management';
                }
            }
        });
        
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of admin role
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')->with('error', 'Cannot delete admin role.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
