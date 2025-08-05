@extends('layouts.app')

@section('content')
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
                <li class="breadcrumb-item active" aria-current="page">Roles Management</li>
            </ol>
        </nav>
        <h2 class="h4">Roles Management</h2>
        <p class="mb-0">Manage user roles and permissions.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Add New Role
        </a>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0 rounded-start">#</th>
                        <th class="border-0">Role Name</th>
                        <th class="border-0">Description</th>
                        <th class="border-0">Permissions</th>
                        <th class="border-0">Created At</th>
                        <th class="border-0 rounded-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td><span class="fw-normal">{{ $role->id }}</span></td>
                        <td>
                            <span class="badge {{ $role->name === 'admin' ? 'bg-primary' : ($role->name === 'manager' ? 'bg-secondary' : 'bg-info') }}">
                                {{ ucfirst($role->name) }}
                            </span>
                        </td>
                        <td><span class="fw-normal">{{ $role->description ?? 'No description' }}</span></td>
                        <td>
                            @if($role->permissions->count() > 0)
                                <span class="badge bg-success">{{ $role->permissions->count() }} Permissions</span>
                            @else
                                <span class="badge bg-secondary">No Permissions</span>
                            @endif
                        </td>
                        <td><span class="fw-normal">{{ $role->created_at->format('Y-m-d H:i:s') }}</span></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-1"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    </svg>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('roles.show', $role) }}">
                                        <span class="fas fa-eye me-2"></span>View
                                    </a>
                                    <a class="dropdown-item" href="{{ route('roles.edit', $role) }}">
                                        <span class="fas fa-edit me-2"></span>Edit
                                    </a>
                                    @if($role->name !== 'admin')
                                    <form action="{{ route('roles.destroy', $role) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this role?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <span class="fas fa-trash-alt me-2"></span>Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No roles found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
