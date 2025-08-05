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
                <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $role->name }}</li>
            </ol>
        </nav>
        <h2 class="h4">Role: {{ $role->name }}</h2>
        <p class="mb-0">{{ $role->description ?? 'No description available' }}</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center me-2">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                </path>
            </svg>
            Edit Role
        </a>
        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-gray-600 d-inline-flex align-items-center">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back to Roles
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Role Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Name:</strong></td>
                        <td>{{ $role->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Description:</strong></td>
                        <td>{{ $role->description ?? 'No description' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Guard:</strong></td>
                        <td>{{ $role->guard_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $role->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Updated:</strong></td>
                        <td>{{ $role->updated_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Permissions ({{ $role->permissions->count() }})</h5>
            </div>
            <div class="card-body">
                @if($role->permissions->count() > 0)
                    <div class="row">
                        @foreach($role->permissions->groupBy(function($permission) { return explode('-', $permission->name)[0]; }) as $module => $modulePermissions)
                        <div class="col-12 mb-3">
                            <h6 class="text-primary">{{ ucfirst($module) }} Management</h6>
                            @foreach($modulePermissions as $permission)
                            <span class="badge bg-light text-dark me-1 mb-1">
                                {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                            </span>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No permissions assigned to this role.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
