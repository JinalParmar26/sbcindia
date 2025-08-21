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
                    <li class="breadcrumb-item"><a href="#">Volt</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users List</li>
                </ol>
            </nav>
            <h2 class="h4">Users List</h2>
            <p class="mb-0">Your web analytics dashboard template.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            @can('create_users')
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                New User
            </a>
            @endcan
            @can('view_users')
            <div class="btn-group ms-2 ms-lg-3">
                <button type="button" class="btn btn-sm btn-outline-gray-600" onclick="exportUsersCsv()">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="exportUsersPdf()">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Download PDF
                </button>
            </div>
            @endcan
        </div>
    </div>

    <div class="table-settings mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-9 col-lg-8 d-md-flex">
                <div class="input-group me-2 me-lg-3 fmxw-300">
                    <span class="input-group-text">
                        <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search users">
                </div>
                <div class="input-group me-2 me-lg-3 fmxw-300">
                    <select wire:model="statusFilter" class="form-select fmxw-200 d-none d-md-inline" aria-label="Status filter">
                        <option value="all" selected>All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="input-group me-2 me-lg-3 fmxw-300">
                    <select wire:model="approvalFilter" class="form-control">
                        <option value="all">Any Login Status</option>
                        <option value="require_approval">Require Login Approval</option>
                        <option value="approved">Approved Login</option>
                    </select>
                </div>
                 <div class="input-group me-2 me-lg-3 fmxw-300">
                    <select wire:model="roleFilter" class="form-control">
                        <option value="all">All Roles</option>
                        @foreach($rolesList as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-3 col-lg-4 d-flex justify-content-end">
                <div class="btn-group">
                    <div class="dropdown me-1">
                        <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-1"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z">
                                </path>
                            </svg>
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pb-0">
                            <span class="small ps-3 fw-bold text-dark">Show</span>
                            <a class="dropdown-item d-flex align-items-center fw-bold" href="#" wire:click.prevent="$set('perPage', 10)">10 <svg
                                    class="icon icon-xxs ms-auto" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                          clip-rule="evenodd"></path>
                                </svg></a>
                            <a class="dropdown-item fw-bold" href="#" wire:click.prevent="$set('perPage', 20)">20</a>
                            <a class="dropdown-item fw-bold rounded-bottom" href="#" wire:click.prevent="$set('perPage', 30)">30</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table user-table table-hover align-items-center">
            <thead>
            <tr>
{{--                <th class="border-bottom">--}}
{{--                    <div class="form-check dashboard-check">--}}
{{--                        <input class="form-check-input" type="checkbox" id="selectAllUsers" wire:click="$toggle('selectAll')">--}}
{{--                        <label class="form-check-label" for="selectAllUsers"></label>--}}
{{--                    </div>--}}
{{--                </th>--}}
                <th class="border-bottom" wire:click="sortBy('name')" style="cursor: pointer;">
                    Name
                    @if ($sortField === 'name')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>
                <th class="border-bottom">
                    Role
                </th>
                <th class="border-bottom" wire:click="sortBy('created_at')" style="cursor: pointer;">
                    Date Created
                    @if ($sortField === 'created_at')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>
                <th class="border-bottom" wire:click="sortBy('isActive')" style="cursor: pointer;">
                    Status
                    @if ($sortField === 'isActive')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>
                <th class="border-bottom">Approve Login</th>
                <th class="border-bottom">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($users as $user)
            <tr>
{{--                <td>--}}
{{--                    <div class="form-check dashboard-check">--}}
{{--                        <input class="form-check-input" type="checkbox" id="userCheck{{ $user->id }}" wire:model="selectedUsers" value="{{ $user->id }}">--}}
{{--                        <label class="form-check-label" for="userCheck{{ $user->id }}"></label>--}}
{{--                    </div>--}}
{{--                </td>--}}
                <td>
                    @can('view_users')
                    <a href="{{ route('users.edit', $user) }}" class="d-flex align-items-center">
                        <div class="avatar avatar-md me-3">
                            @php
                                $signPath = $user->profile_photo ?? null;
                                $signFull = $signPath ? asset('storage/' . $signPath) : null;
                            @endphp
                            <img alt="user-avatar" src="{{ $signFull ? $signFull : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" class="rounded-circle">
                        </div>
                        <div class="d-block">
                            <span class="fw-bold">{{ $user->name }}</span>
                            <div class="small text-gray">{{ $user->email }}</div>
                        </div>
                    </a>
                    @else
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md me-3">
                            <img alt="user-avatar" src="{{ $user->profile_photo ? asset('storage/'.$user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" class="rounded-circle">
                        </div>
                        <div class="d-block">
                            <span class="fw-bold">{{ $user->name }}</span>
                            <div class="small text-gray">{{ $user->email }}</div>
                        </div>
                    </div>
                    @endcan
                </td>
                <td>
                    <span class="fw-normal">{{ $user->roles->pluck('name')->join(', ') }}</span>
                </td>
                <td>
                    <span class="fw-normal">{{ $user->created_at->format('M d, Y') }}</span>
                </td>
                <td>
                    @php
                    $status = $user->isActive ? 'active' : 'inactive';
                    $badgeClass = match($status) {
                    'active' => 'bg-success',
                    'inactive' => 'bg-secondary',
                    'pending' => 'bg-warning',
                    };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                </td>
                 <td>
                   @if($user->approval_required == "yes")
                    <button type="button" class="btn btn-secondary" wire:click="approveUser({{ $user->id }})">
                        Approve
                    </button>
                   @endif
                    @if($user->approval_required == "no")
                   <span class="text-dark">Approved</span>
                   @endif
                </td>
                <td>
                    @if(auth()->user()->can('view_users') || auth()->user()->can('edit_users') || auth()->user()->can('delete_users'))
                    <div class="dropdown">
                        <a href="#" class="btn btn-sm btn-gray-600 d-inline-flex align-items-center dropdown-toggle"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @can('view_users')
                            <li><a class="dropdown-item" href="{{ route('users.show', $user->uuid) }}">
                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </a></li>
                            @endcan
                            @can('edit_users')
                            <li><a class="dropdown-item" href="{{ route('users.edit', $user) }}">
                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a></li>
                            @endcan
                            @can('view_users')
                            <li><a class="dropdown-item" href="{{ route('staff.visiting-card', $user->uuid) }}" target="_blank">
                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 012-2h2a2 2 0 012 2v2m-4 0a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V8a2 2 0 012-2z"></path>
                                </svg>
                                Visiting Card
                            </a></li>
                            @endcan
                            @can('delete_users')
                            <li><a class="dropdown-item text-danger" href="#" wire:click.prevent="confirmDelete({{ $user->id }})">
                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </a></li>
                            @endcan
                        </ul>
                    </div>
                    @else
                    <span class="text-muted small">No actions available</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">No users found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
        <div wire:ignore.self class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this user?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" wire:click="deleteUser()" class="btn btn-danger">Yes,Delete</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
    window.addEventListener('show-delete-modal', event => {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
        deleteModal.show();
    });
    window.addEventListener('hide-delete-modal', event => {
        var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteUserModal'));
        deleteModal.hide();
    });
    window.addEventListener('alert', event => {
        alert(event.detail.message); // Replace with toast or SweetAlert if needed
    });

    // CSV Export function for Users
    function exportUsersCsv() {
        const currentFilters = {
            search: @this.get('search') || '',
            status_filter: @this.get('statusFilter') || 'all',
            role_filter: @this.get('roleFilter') || 'all'
        };

        // Build URL with current filters
        const params = new URLSearchParams();
        Object.keys(currentFilters).forEach(key => {
            if (currentFilters[key] && currentFilters[key] !== 'all') {
                params.append(key, currentFilters[key]);
            }
        });

        const url = '{{ route("users.export.csv") }}' + (params.toString() ? '?' + params.toString() : '');
        window.open(url, '_blank');
    }

    // PDF Export function for Users
    function exportUsersPdf() {
        const currentFilters = {
            search: @this.get('search') || '',
            status_filter: @this.get('statusFilter') || 'all',
            role_filter: @this.get('roleFilter') || 'all'
        };

        // Build URL with current filters
        const params = new URLSearchParams();
        Object.keys(currentFilters).forEach(key => {
            if (currentFilters[key] && currentFilters[key] !== 'all') {
                params.append(key, currentFilters[key]);
            }
        });

        const url = '{{ route("users.export.pdf") }}' + (params.toString() ? '?' + params.toString() : '');
        window.open(url, '_blank');
    }

    // Make function globally available
    window.exportUsersCsv = exportUsersCsv;
</script>
