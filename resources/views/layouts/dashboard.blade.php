@extends('layouts.app')

@section('content')
<title>Volt Laravel Dashboard</title>
<div class="py-4">
    <div class="dropdown">
        <button class="btn btn-gray-800 d-inline-flex align-items-center me-2 dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            New Task
        </button>
        <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1">
            <a class="dropdown-item d-flex align-items-center" href="#">
                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path></svg>
                Add User
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>                            
                Add Widget
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path><path d="M9 13h2v5a1 1 0 11-2 0v-5z"></path></svg>                            
                Upload Files
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <svg class="dropdown-icon text-gray-400 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                Preview Security
            </a>
            <div role="separator" class="dropdown-divider my-1"></div>
            <a class="dropdown-item d-flex align-items-center" href="#">
                <svg class="dropdown-icon text-danger me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg>
                Upgrade to Pro
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow" style="background-color: #fac0b9">
            <div class="card-header d-sm-flex flex-row align-items-center flex-0">
                <div class="d-block mb-3 mb-sm-0">
                    <div class="fs-5 fw-normal mb-2">Sales Value</div>
                    <h2 class="fs-3 fw-extrabold">₹{{ number_format($currentMonthOrdersValue, 2) }}</h2>
                    <div class="small mt-2"> 
                        <span class="fw-normal me-2">This Month (Estimated)</span>                              
                        <span class="fas fa-angle-{{ $salesGrowth >= 0 ? 'up text-success' : 'down text-danger' }}"></span>                                   
                        <span class="text-{{ $salesGrowth >= 0 ? 'success' : 'danger' }} fw-bold">{{ number_format(abs($salesGrowth), 2) }}%</span>
                    </div>
                </div>
                <div class="d-flex ms-auto">
                    <a href="{{ route('orders') }}" class="btn btn-secondary btn-sm me-2">View Orders</a>
                    <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">New Order</a>
                </div>
            </div>
            <div class="card-body p-2">
                <div class="text-center">
                    <p class="mb-0 text-muted">Estimated Orders Value for {{ date('F Y') }}</p>
                    <p class="small text-muted">Previous Month: ₹{{ number_format($currentMonthOrdersValue - ($currentMonthOrdersValue * $salesGrowth / 100), 2) }}</p>
                    <div class="mt-2">
                        <small class="text-muted">
                            <strong>{{ \App\Models\Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count() }}</strong> orders this month
                        </small>
                    </div>
                    <div class="mt-1">
                        <small class="text-info">
                            <em>*Based on estimated $10,000 per order</em>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                        </div>
                        <div class="d-sm-none">
                            <h2 class="h5">Customers</h2>
                            <h3 class="fw-extrabold mb-1">{{ number_format($totalCustomers) }}</h3>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h6 text-gray-400 mb-0">Total Customers</h2>
                            <h3 class="fw-extrabold mb-2">{{ number_format($totalCustomers) }}</h3>
                        </div>
                        <small class="d-flex align-items-center text-gray-500">
                            {{ date('M 1') }} - {{ date('M d') }},  
                            <svg class="icon icon-xxs text-gray-500 ms-2 me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path></svg>
                            Business
                        </small> 
                        <div class="small d-flex mt-1">                               
                            <div>Since last month 
                                <svg class="icon icon-xs text-{{ $customersGrowth >= 0 ? 'success' : 'danger' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="{{ $customersGrowth >= 0 ? 'M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z' : 'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' }}" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-{{ $customersGrowth >= 0 ? 'success' : 'danger' }} fw-bolder">{{ number_format(abs($customersGrowth), 1) }}%</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <strong>{{ \App\Models\Customer::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count() }}</strong> new this month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-secondary rounded me-4 me-sm-0">
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="d-sm-none">
                            <h2 class="fw-extrabold h5">Orders</h2>
                            <h3 class="mb-1">{{ number_format($totalOrders) }}</h3>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h6 text-gray-400 mb-0">Total Orders</h2>
                            <h3 class="fw-extrabold mb-2">{{ number_format($totalOrders) }}</h3>
                        </div>
                        <small class="d-flex align-items-center text-gray-500">
                            {{ date('M 1') }} - {{ date('M d') }},  
                            <svg class="icon icon-xxs text-gray-500 ms-2 me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path></svg>
                            Orders
                        </small> 
                        <div class="small d-flex mt-1">                               
                            <div>Since last month 
                                <svg class="icon icon-xs text-{{ $ordersGrowth >= 0 ? 'success' : 'danger' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="{{ $ordersGrowth >= 0 ? 'M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z' : 'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' }}" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-{{ $ordersGrowth >= 0 ? 'success' : 'danger' }} fw-bolder">{{ number_format(abs($ordersGrowth), 1) }}%</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <strong>{{ \App\Models\Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count() }}</strong> new this month
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-body">
                <div class="row d-block d-xl-flex align-items-center">
                    <div class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                        <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                            <svg class="icon" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div class="d-sm-none">
                            <h2 class="fw-extrabold h5">Support Tickets</h2>
                            <h3 class="mb-1">{{ number_format($totalTickets) }}</h3>
                        </div>
                    </div>
                    <div class="col-12 col-xl-7 px-xl-0">
                        <div class="d-none d-sm-block">
                            <h2 class="h6 text-gray-400 mb-0">Support Tickets</h2>
                            <h3 class="fw-extrabold mb-2">{{ number_format($totalTickets) }}</h3>
                        </div>
                        <small class="text-gray-500">
                            {{ date('M 1') }} - {{ date('M d') }}
                        </small> 
                        <div class="small d-flex mt-1">                               
                            <div>Since last month 
                                <svg class="icon icon-xs text-{{ $ticketsGrowth >= 0 ? 'success' : 'danger' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="{{ $ticketsGrowth >= 0 ? 'M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z' : 'M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' }}" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-{{ $ticketsGrowth >= 0 ? 'success' : 'danger' }} fw-bolder">{{ number_format(abs($ticketsGrowth), 1) }}%</span>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <strong>{{ \App\Models\Ticket::whereNull('end')->count() }}</strong> active tickets
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-xl-8">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h2 class="fs-5 fw-bold mb-0">Recent Tickets</h2>
                            </div>
                            <div class="col text-end">
                                <a href="{{ route('tickets') }}" class="btn btn-sm btn-primary">See all</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                            <tr>
                                <th class="border-bottom" scope="col">Subject</th>
                                <th class="border-bottom" scope="col">Customer</th>
                                <th class="border-bottom" scope="col">Assigned To</th>
                                <th class="border-bottom" scope="col">Status</th>
                                <th class="border-bottom" scope="col">Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentTickets as $ticket)
                            <tr>
                                <th class="text-gray-900" scope="row">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-decoration-none">
                                        {{ Str::limit($ticket->subject, 30) }}
                                    </a>
                                </th>
                                <td class="fw-bolder text-gray-500">
                                    {{ $ticket->customer->name ?? 'N/A' }}
                                </td>
                                <td class="fw-bolder text-gray-500">
                                    {{ $ticket->assignedTo->name ?? 'Unassigned' }}
                                </td>
                                <td class="fw-bolder text-gray-500">
                                    @if($ticket->end)
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-primary">Active</span>
                                    @endif
                                </td>
                                <td class="fw-bolder text-gray-500">
                                    {{ $ticket->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-4">
                                    No tickets found
                                </td>
                            </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xxl-6 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                       <h2 class="fs-5 fw-bold mb-0">Team members</h2>
                        <a href="{{ route('users') }}" class="btn btn-sm btn-primary">See all</a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush list my--3">
                            @forelse($teamMembers as $member)
                            <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                <div class="col-auto">
                                    <!-- Avatar -->
                                    <a href="{{ route('users.show', $member->id) }}" class="avatar">
                                        @if($member->profile_photo)
                                            <img class="rounded" alt="{{ $member->name }}" src="{{ asset('storage/' . $member->profile_photo) }}">
                                        @else
                                            <div class="avatar-initials rounded bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                {{ substr($member->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="col-auto ms--2">
                                    <h4 class="h6 mb-0">
                                        <a href="{{ route('users.show', $member->id) }}">{{ $member->name }}</a>
                                    </h4>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-{{ $member->isActive ? 'success' : 'danger' }} dot rounded-circle me-1"></div>
                                        <small>{{ $member->isActive ? 'Active' : 'Inactive' }}</small>
                                    </div>
                                    @if($member->roles->isNotEmpty())
                                        <small class="text-muted">{{ $member->roles->first()->name }}</small>
                                    @endif
                                </div>
                                <div class="col text-end">
                                    <a href="{{ route('users.show', $member->id) }}" class="btn btn-sm btn-secondary d-inline-flex align-items-center">
                                        <svg class="icon icon-xxs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z" clip-rule="evenodd"></path></svg>
                                        View
                                    </a>
                                </div>
                                </div>
                            </li>
                            @empty
                            <li class="list-group-item px-0 text-center text-muted py-4">
                                No team members found
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xxl-6 mb-4">
                <div class="card border-0 shadow">
                    <div class="card-header border-bottom d-flex align-items-center justify-content-between">
                        <h2 class="fs-5 fw-bold mb-0">Progress track</h2>
                         <a href="#" class="btn btn-sm btn-primary">See tasks</a>
                     </div>
                    <div class="card-body">
                        <!-- Project 1 -->
                        <div class="row mb-4">
                            <div class="col-auto">
                                <svg class="icon icon-sm text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="col">
                                <div class="progress-wrapper">
                                    <div class="progress-info">
                                        <div class="h6 mb-0">Rocket - SaaS Template</div>
                                        <div class="small fw-bold text-gray-500"><span>75 %</span></div>
                                    </div>
                                    <div class="progress mb-0">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Project 2 -->
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <svg class="icon icon-sm text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="col">
                                <div class="progress-wrapper">
                                    <div class="progress-info">
                                        <div class="h6 mb-0">Themesberg - Design System</div>
                                        <div class="small fw-bold text-gray-500"><span>60 %</span></div>
                                    </div>
                                    <div class="progress mb-0">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Project 3 -->
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <svg class="icon icon-sm text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="col">
                                <div class="progress-wrapper">
                                    <div class="progress-info">
                                        <div class="h6 mb-0">Homepage Design in Figma</div>
                                        <div class="small fw-bold text-gray-500"><span>45 %</span></div>
                                    </div>
                                    <div class="progress mb-0">
                                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Project 4 -->
                        <div class="row align-items-center mb-3">
                            <div class="col-auto">
                                <svg class="icon icon-sm text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="col">
                                <div class="progress-wrapper">
                                    <div class="progress-info">
                                        <div class="h6 mb-0">Backend for Themesberg v2</div>
                                        <div class="small fw-bold text-gray-500"><span>34 %</span></div>
                                    </div>
                                    <div class="progress mb-0">
                                        <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" style="width: 34%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="col-12 px-0 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header d-flex flex-row align-items-center flex-0 border-bottom">
                    <div class="d-block">
                        <div class="h6 fw-normal text-gray mb-2">Total orders</div>
                        <h2 class="h3 fw-extrabold">{{ number_format($totalOrders) }}</h2>
                        <div class="small mt-2">                               
                            <span class="fas fa-angle-{{ $ordersGrowth >= 0 ? 'up text-success' : 'down text-danger' }}"></span>                                   
                            <span class="text-{{ $ordersGrowth >= 0 ? 'success' : 'danger' }} fw-bold">{{ number_format(abs($ordersGrowth), 1) }}%</span>
                        </div>
                    </div>
                    <div class="d-block ms-auto">
                        <div class="d-flex align-items-center text-end mb-2">
                            <span class="dot rounded-circle bg-gray-800 me-2"></span>
                            <span class="fw-normal small">This Month</span>
                        </div>
                        <div class="d-flex align-items-center text-end">
                            <span class="dot rounded-circle bg-secondary me-2"></span>
                            <span class="fw-normal small">Last Month</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table class="table table-sm align-items-center">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">Recent Orders</th>
                                    <th class="border-bottom-0 text-end">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td class="text-gray-900">
                                        <a href="{{ route('orders.show', $order->uuid) }}" class="text-decoration-none">
                                            <div class="d-flex align-items-center">
                                                <div class="ms-2">
                                                    <div class="fw-bold">{{ Str::limit($order->title, 30) }}</div>
                                                    <div class="small text-muted">{{ $order->customer->name ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-bold">₹{{ number_format($order->orderProducts->sum(function($op) { return $op->product->price ?? 0; }), 2) }}</div>
                                        <div class="small text-muted">{{ $order->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">
                                        No recent orders found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 px-0 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3">
                        <div>
                            <div class="h6 mb-0 d-flex align-items-center">
                                <svg class="icon icon-xs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"></path></svg>
                                Global Rank
                            </div>
                        </div>
                        <div>
                            <a href="#" class="d-flex align-items-center fw-bold">
                                #755
                                <svg class="icon icon-xs text-gray-500 ms-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                        <div>
                            <div class="h6 mb-0 d-flex align-items-center">
                                <svg class="icon icon-xs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"></path></svg>
                                Country Rank
                            </div>
                            <div class="small card-stats">
                                United States
                                <svg class="icon icon-xs text-success" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                        <div>
                            <a href="#" class="d-flex align-items-center fw-bold">
                                #32
                                <svg class="icon icon-xs text-gray-500 ms-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between pt-3">
                        <div>
                            <div class="h6 mb-0 d-flex align-items-center">
                                <svg class="icon icon-xs text-gray-500 me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clip-rule="evenodd"></path><path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z"></path></svg>
                                Category Rank
                            </div>
                            <div class="small card-stats">
                                Computers Electronics > Technology
                                <svg class="icon icon-xs text-success" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                            </div>
                        </div>
                        <div>
                            <a href="#" class="d-flex align-items-center fw-bold">
                                #11
                                <svg class="icon icon-xs text-gray-500 ms-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 px-0">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <h2 class="fs-5 fw-bold mb-1">Acquisition</h2>
                    <p>Tells you where your visitors originated from, such as search engines, social networks or website referrals.</p>
                    <div class="d-block">
                        <div class="d-flex align-items-center me-5">
                            <div class="icon-shape icon-sm icon-shape-danger rounded me-3">
                                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div class="d-block">
                                <label class="mb-0">Bounce Rate</label>
                                <h4 class="mb-0">33.50%</h4>
                            </div>
                        </div>
                        <div class="d-flex align-items-center pt-3">
                            <div class="icon-shape icon-sm icon-shape-purple rounded me-3">
                                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path></svg>                                        </div>
                            <div class="d-block">
                                <label class="mb-0">Sessions</label>
                                <h4 class="mb-0">9,567</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection