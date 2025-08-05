<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissions extends Component
{
    protected $layout = 'layouts.app';
    public $role;
    public $roleId;
    public $selectedPermissions = [];
    public $permissionGroups = [];
    public $selectAll = false;
    public $allPermissionIds = [];

    protected $rules = [
        'selectedPermissions' => 'array',
        'selectedPermissions.*' => 'integer|exists:permissions,id'
    ];

    public function mount($roleId)
    {
        try {
            $this->roleId = $roleId;
            $this->role = Role::with('permissions')->findOrFail($roleId);
            
            // If this is Super Admin, always assign all permissions
            if ($this->role->name === 'Super Admin') {
                $this->ensureSuperAdminHasAllPermissions();
            }
            
            $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
            $this->loadPermissionGroups();
            $this->updateSelectAllState();
        } catch (\Exception $e) {
            session()->flash('error', 'Error loading role: ' . $e->getMessage());
            // Redirect to roles page if role not found
            return redirect()->route('roles');
        }
    }

    public function loadPermissionGroups()
    {
        try {
            $permissions = Permission::all();
            $this->allPermissionIds = $permissions->pluck('id')->toArray();
            $groups = [];
            
            foreach ($permissions as $permission) {
                $parts = explode('_', $permission->name);
                $action = $parts[0];
                $module = isset($parts[1]) ? implode('_', array_slice($parts, 1)) : 'general';
                
                if (!isset($groups[$module])) {
                    $groups[$module] = [];
                }
                
                $groups[$module][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'guard_name' => $permission->guard_name,
                ];
            }
            
            $this->permissionGroups = $groups;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error loading permissions: ' . $e->getMessage());
        }
    }

    public function updatedSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedPermissions = $this->allPermissionIds;
        } else {
            $this->selectedPermissions = [];
        }
    }

    public function updatedSelectedPermissions()
    {
        $this->updateSelectAllState();
    }

    private function updateSelectAllState()
    {
        $this->selectAll = count($this->selectedPermissions) === count($this->allPermissionIds);
    }

    public function savePermissions()
    {
        $this->validate();

        try {
            // If this is Super Admin, always assign all permissions
            if ($this->role->name === 'Super Admin') {
                $this->ensureSuperAdminHasAllPermissions();
                session()->flash('message', 'Super Admin role automatically has all permissions.');
                return;
            }
            
            $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
            $this->role->syncPermissions($permissions);
            
            session()->flash('message', 'Permissions updated successfully for role: ' . $this->role->name);
            
            // Refresh role data
            $this->role = Role::with('permissions')->findOrFail($this->roleId);
            $this->selectedPermissions = $this->role->permissions->pluck('id')->toArray();
            $this->updateSelectAllState();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving permissions: ' . $e->getMessage());
        }
    }

    private function ensureSuperAdminHasAllPermissions()
    {
        try {
            $allPermissions = Permission::all();
            $this->role->syncPermissions($allPermissions);
            
            // Update local state
            $this->selectedPermissions = $allPermissions->pluck('id')->toArray();
            $this->selectAll = true;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error ensuring Super Admin permissions: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.role-permissions');
    }
}
