<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManagement extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    public $perPage = 10;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    
    // Form fields
    public $roleId;
    public $name = '';
    public $description = '';
    public $selectedPermissions = [];
    
    // Permissions grouped by module
    public $permissionGroups = [];
    
    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'description' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->loadPermissionGroups();
    }

    public function loadPermissionGroups()
    {
        try {
            $permissions = Permission::all();
            $groups = [];
            
            if ($permissions->isEmpty()) {
                session()->flash('error', 'No permissions found. Please run the seeder first.');
                $this->permissionGroups = [];
                return;
            }
            
            foreach ($permissions as $permission) {
                $parts = explode('_', $permission->name);
                $action = $parts[0];
                $module = isset($parts[1]) ? implode('_', array_slice($parts, 1)) : 'general';
                
                if (!isset($groups[$module])) {
                    $groups[$module] = [];
                }
                
                // Store as array with necessary properties to avoid Livewire serialization issues
                $groups[$module][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'guard_name' => $permission->guard_name,
                ];
            }
            
            $this->permissionGroups = $groups;
            
        } catch (\Exception $e) {
            $this->permissionGroups = [];
            session()->flash('error', 'Error loading permissions: ' . $e->getMessage());
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function createRole()
    {
        $this->resetForm();
        // Reload permission groups to ensure they're available for the modal
        $this->loadPermissionGroups();
        $this->showCreateModal = true;
    }

    public function editRole($roleId)
    {
        $role = Role::with('permissions')->findOrFail($roleId);
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->description = $role->description ?? '';
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->showEditModal = true;
    }

    public function saveRole()
    {
        if ($this->roleId) {
            $this->rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->roleId;
        }
        
        $this->validate();

        if ($this->roleId) {
            // Update existing role
            $role = Role::findOrFail($this->roleId);
            $role->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
        } else {
            // Create new role
            $role = Role::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);
        }

        // Sync permissions
        $permissions = Permission::whereIn('id', $this->selectedPermissions)->get();
        $role->syncPermissions($permissions);

        $this->resetForm();
        $this->showCreateModal = false;
        $this->showEditModal = false;
        
        session()->flash('message', $this->roleId ? 'Role updated successfully!' : 'Role created successfully!');
    }

    public function confirmDelete($roleId)
    {
        $this->roleId = $roleId;
        $this->showDeleteModal = true;
    }

    public function deleteRole()
    {
        $role = Role::findOrFail($this->roleId);
        
        // Check if role is assigned to any users
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Cannot delete role that is assigned to users!');
            $this->showDeleteModal = false;
            return;
        }
        
        $role->delete();
        $this->showDeleteModal = false;
        session()->flash('message', 'Role deleted successfully!');
    }

    public function resetForm()
    {
        $this->roleId = null;
        $this->name = '';
        $this->description = '';
        $this->selectedPermissions = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = Role::withCount('users');

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        $roles = $query->paginate($this->perPage);

        return view('livewire.role-management', [
            'roles' => $roles,
            'permissions' => Permission::all(),
        ]);
    }
}
