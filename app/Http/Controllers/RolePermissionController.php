<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function show($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        
        return view('role-permissions', compact('role', 'roleId'));
    }
}
