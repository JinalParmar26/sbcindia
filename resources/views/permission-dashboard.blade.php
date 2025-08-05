@extends('layouts.app')

@section('content')
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="#">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Permission Dashboard</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Permission-Based Dashboard</h1>
            <p class="mb-0">Welcome! You can access the following features based on your permissions:</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="h5">Your Access Permissions</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- User Management -->
                    @if(auth()->user()->can('view_users') || auth()->user()->can('create_users') || auth()->user()->can('edit_users') || auth()->user()->can('delete_users'))
                    <div class="col-12 col-sm-6 col-xl-4 mb-4">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <div class="row d-block d-xl-flex align-items-center">
                                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                        <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-7 px-xl-0">
                                        <div class="d-none d-sm-block">
                                            <h3 class="h6 text-gray-400 mb-0">User Management</h3>
                                            <div class="small">
                                                @can('view_users')<span class="badge bg-success me-1">View</span>@endcan
                                                @can('create_users')<span class="badge bg-success me-1">Create</span>@endcan
                                                @can('edit_users')<span class="badge bg-success me-1">Edit</span>@endcan
                                                @can('delete_users')<span class="badge bg-success me-1">Delete</span>@endcan
                                            </div>
                                        </div>
                                        @can('view_users')
                                        <div class="mt-2">
                                            <a href="{{ route('users') }}" class="btn btn-sm btn-secondary">Manage Users</a>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Customer Management -->
                    @if(auth()->user()->can('view_customers') || auth()->user()->can('create_customers') || auth()->user()->can('edit_customers') || auth()->user()->can('delete_customers'))
                    <div class="col-12 col-sm-6 col-xl-4 mb-4">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <div class="row d-block d-xl-flex align-items-center">
                                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                        <div class="icon-shape icon-shape-secondary rounded me-4 me-sm-0">
                                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-7 px-xl-0">
                                        <div class="d-none d-sm-block">
                                            <h3 class="h6 text-gray-400 mb-0">Customer Management</h3>
                                            <div class="small">
                                                @can('view_customers')<span class="badge bg-success me-1">View</span>@endcan
                                                @can('create_customers')<span class="badge bg-success me-1">Create</span>@endcan
                                                @can('edit_customers')<span class="badge bg-success me-1">Edit</span>@endcan
                                                @can('delete_customers')<span class="badge bg-success me-1">Delete</span>@endcan
                                            </div>
                                        </div>
                                        @can('view_customers')
                                        <div class="mt-2">
                                            <a href="{{ route('customers') }}" class="btn btn-sm btn-secondary">Manage Customers</a>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Order Management -->
                    @if(auth()->user()->can('view_orders') || auth()->user()->can('create_orders') || auth()->user()->can('edit_orders') || auth()->user()->can('delete_orders'))
                    <div class="col-12 col-sm-6 col-xl-4 mb-4">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <div class="row d-block d-xl-flex align-items-center">
                                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                        <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-7 px-xl-0">
                                        <div class="d-none d-sm-block">
                                            <h3 class="h6 text-gray-400 mb-0">Order Management</h3>
                                            <div class="small">
                                                @can('view_orders')<span class="badge bg-success me-1">View</span>@endcan
                                                @can('create_orders')<span class="badge bg-success me-1">Create</span>@endcan
                                                @can('edit_orders')<span class="badge bg-success me-1">Edit</span>@endcan
                                                @can('delete_orders')<span class="badge bg-success me-1">Delete</span>@endcan
                                            </div>
                                        </div>
                                        @can('view_orders')
                                        <div class="mt-2">
                                            <a href="{{ route('orders') }}" class="btn btn-sm btn-secondary">Manage Orders</a>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Ticket Management -->
                    @if(auth()->user()->can('view_tickets') || auth()->user()->can('create_tickets') || auth()->user()->can('edit_tickets') || auth()->user()->can('delete_tickets'))
                    <div class="col-12 col-sm-6 col-xl-4 mb-4">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <div class="row d-block d-xl-flex align-items-center">
                                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                        <div class="icon-shape icon-shape-info rounded me-4 me-sm-0">
                                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-7 px-xl-0">
                                        <div class="d-none d-sm-block">
                                            <h3 class="h6 text-gray-400 mb-0">Ticket Management</h3>
                                            <div class="small">
                                                @can('view_tickets')<span class="badge bg-success me-1">View</span>@endcan
                                                @can('create_tickets')<span class="badge bg-success me-1">Create</span>@endcan
                                                @can('edit_tickets')<span class="badge bg-success me-1">Edit</span>@endcan
                                                @can('delete_tickets')<span class="badge bg-success me-1">Delete</span>@endcan
                                            </div>
                                        </div>
                                        @can('view_tickets')
                                        <div class="mt-2">
                                            <a href="{{ route('tickets') }}" class="btn btn-sm btn-secondary">Manage Tickets</a>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Staff Management -->
                    @if(auth()->user()->can('view_staff') || auth()->user()->can('create_staff') || auth()->user()->can('edit_staff') || auth()->user()->can('manage_attendance'))
                    <div class="col-12 col-sm-6 col-xl-4 mb-4">
                        <div class="card border-light shadow-sm">
                            <div class="card-body">
                                <div class="row d-block d-xl-flex align-items-center">
                                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                                        <div class="icon-shape icon-shape-warning rounded me-4 me-sm-0">
                                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="col-12 col-xl-7 px-xl-0">
                                        <div class="d-none d-sm-block">
                                            <h3 class="h6 text-gray-400 mb-0">Staff Management</h3>
                                            <div class="small">
                                                @can('view_staff')<span class="badge bg-success me-1">View</span>@endcan
                                                @can('create_staff')<span class="badge bg-success me-1">Create</span>@endcan
                                                @can('edit_staff')<span class="badge bg-success me-1">Edit</span>@endcan
                                                @can('manage_attendance')<span class="badge bg-success me-1">Attendance</span>@endcan
                                            </div>
                                        </div>
                                        @can('view_staff')
                                        <div class="mt-2">
                                            <a href="{{ route('staff') }}" class="btn btn-sm btn-secondary">Manage Staff</a>
                                        </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Access Denied Card for users with no permissions -->
                    @if(!auth()->user()->can('view_users') && !auth()->user()->can('view_customers') && !auth()->user()->can('view_orders') && !auth()->user()->can('view_tickets') && !auth()->user()->can('view_staff') && !auth()->user()->can('view_marketing'))
                    <div class="col-12">
                        <div class="card border-light shadow-sm">
                            <div class="card-body text-center py-5">
                                <div class="icon-shape icon-shape-danger rounded mx-auto mb-3">
                                    <svg class="icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <h4 class="h5 text-gray-400">Limited Access</h4>
                                <p class="text-gray-600">You currently have access to the admin panel, but no specific module permissions have been assigned to your role. Please contact your administrator to request access to specific features.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="h5">Your Current Permissions</h2>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Permission</th>
                                <th>Status</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['view_users' => 'View Users', 'create_users' => 'Create Users', 'edit_users' => 'Edit Users', 'delete_users' => 'Delete Users', 'view_customers' => 'View Customers', 'create_customers' => 'Create Customers', 'edit_customers' => 'Edit Customers', 'delete_customers' => 'Delete Customers', 'view_orders' => 'View Orders', 'create_orders' => 'Create Orders', 'edit_orders' => 'Edit Orders', 'delete_orders' => 'Delete Orders', 'view_tickets' => 'View Tickets', 'create_tickets' => 'Create Tickets', 'edit_tickets' => 'Edit Tickets', 'delete_tickets' => 'Delete Tickets', 'view_staff' => 'View Staff', 'create_staff' => 'Create Staff', 'edit_staff' => 'Edit Staff', 'manage_attendance' => 'Manage Attendance', 'view_marketing' => 'View Marketing', 'create_marketing' => 'Create Marketing', 'edit_marketing' => 'Edit Marketing', 'delete_marketing' => 'Delete Marketing'] as $permission => $description)
                            <tr>
                                <td>{{ $permission }}</td>
                                <td>
                                    @can($permission)
                                        <span class="badge bg-success">Granted</span>
                                    @else
                                        <span class="badge bg-danger">Denied</span>
                                    @endcan
                                </td>
                                <td>{{ $description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
