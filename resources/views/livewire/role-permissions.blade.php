<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('roles') }}">Roles</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                </ol>
            </nav>
            <h2 class="h4">🔑 Manage Permissions</h2>
            <p class="mb-0">Configure permissions for role: <strong>{{ $role->name }}</strong></p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('roles') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Roles
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Super Admin Warning -->
    @if($role->name === 'Super Admin')
        <div class="alert alert-warning" role="alert">
            <h4 class="alert-heading">⚠️ Super Admin Role</h4>
            <p>The Super Admin role automatically has all permissions and cannot be modified. This role is designed to have complete access to all system features.</p>
            <hr>
            <p class="mb-0">If you need to create a role with limited permissions, please create a new role instead.</p>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="h6 mb-0">Permission Assignment</h4>
                </div>
                <div class="col-auto">
                    <div class="form-check">
                        <input type="checkbox" 
                               wire:model="selectAll" 
                               class="form-check-input" 
                               id="selectAll"
                               {{ $role->name === 'Super Admin' ? 'disabled checked' : '' }}>
                        <label class="form-check-label" for="selectAll">
                            <strong>Select All Permissions</strong>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <strong>Selected: {{ count($selectedPermissions) }} of {{ count($allPermissionIds) }} permissions</strong>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" 
                            class="btn btn-success" 
                            wire:click="savePermissions"
                            {{ $role->name === 'Super Admin' ? 'disabled' : '' }}>
                        <i class="fas fa-save me-2"></i>Save Permissions
                    </button>
                </div>
            </div>

            @if(!empty($permissionGroups))
                @foreach($permissionGroups as $module => $permissions)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                {{ ucfirst(str_replace('_', ' ', $module)) }} 
                                <span class="badge bg-secondary">{{ count($permissions) }} permissions</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($permissions as $permission)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   wire:model="selectedPermissions" 
                                                   value="{{ $permission['id'] }}"
                                                   class="form-check-input" 
                                                   id="perm_{{ $permission['id'] }}"
                                                   {{ $role->name === 'Super Admin' ? 'disabled checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission['id'] }}">
                                                <strong>{{ ucfirst(str_replace('_', ' ', $permission['name'])) }}</strong>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-warning text-center">
                    <h4>⚠️ No permissions found</h4>
                    <p>Please run the seeder to create permissions.</p>
                </div>
            @endif

            <!-- Save Button at Bottom -->
            <div class="text-center mt-4">
                <a href="{{ route('roles') }}" class="btn btn-secondary me-3">
                    <i class="fas fa-times me-2"></i>Cancel
                </a>
                <button type="button" class="btn btn-success btn-lg" wire:click="savePermissions">
                    <i class="fas fa-save me-2"></i>Save Permissions for {{ $role->name }}
                </button>
            </div>
        </div>
    </div>
</div>
