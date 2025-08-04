@extends('layouts.main')

@section('content')
<style>
    .avatar {
        width: 40px;
        height: 40px;
        font-size: 14px;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    .staff-actions {
        white-space: nowrap;
    }
    
    .status-badge {
        min-width: 80px;
        text-align: center;
    }
    
    .time-display {
        font-family: monospace;
        font-size: 0.9rem;
    }
    
    .checkout-btn {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        transition: all 0.3s ease;
    }
    
    .checkout-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }
    
    .check-in-time {
        color: #28a745;
        font-weight: 600;
    }
    
    .check-out-time {
        color: #dc3545;
        font-weight: 600;
    }
    
    .attendance-stats {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.375rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
            <div class="d-block mb-4 mb-md-0">
                <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                    <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">
                                <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Staff Attendance</li>
                    </ol>
                </nav>
                <h2 class="h4">Staff Attendance Management</h2>
                <p class="mb-0">Manage staff check-in and check-out times for {{ date('l, F j, Y') }}</p>
            </div>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
                <div class="btn-group ms-2">
                    <a href="{{ route('staff.export.pdf') }}" class="btn btn-sm btn-danger" target="_blank">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    @php
        $checkedIn = $staffMembers->filter(function($staff) {
            $attendance = $staff->userAttendances->first();
            return $attendance && !$attendance->check_out;
        })->count();
        
        $checkedOut = $staffMembers->filter(function($staff) {
            $attendance = $staff->userAttendances->first();
            return $attendance && $attendance->check_out;
        })->count();
        
        $notCheckedIn = $staffMembers->filter(function($staff) {
            return $staff->userAttendances->isEmpty();
        })->count();
    @endphp
    
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="icon-shape icon-md bg-primary text-white rounded-circle mb-3 mx-auto">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="h4 mb-1">{{ $checkedIn }}</h3>
                <p class="text-muted mb-0">Currently Checked In</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="icon-shape icon-md bg-success text-white rounded-circle mb-3 mx-auto">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <h3 class="h4 mb-1">{{ $checkedOut }}</h3>
                <p class="text-muted mb-0">Checked Out Today</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="icon-shape icon-md bg-secondary text-white rounded-circle mb-3 mx-auto">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2L3 7v11a2 2 0 002 2h4v-6h2v6h4a2 2 0 002-2V7l-7-5z"></path>
                    </svg>
                </div>
                <h3 class="h4 mb-1">{{ $notCheckedIn }}</h3>
                <p class="text-muted mb-0">Not Checked In</p>
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
                        <h2 class="h5">Staff Members ({{ $staffMembers->count() }} total)</h2>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-info">{{ date('l, F j, Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-centered table-nowrap mb-0 rounded">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0 rounded-start">Staff Member</th>
                            <th class="border-0">Contact</th>
                            <th class="border-0">Check-in</th>
                            <th class="border-0">Check-out</th>
                            <th class="border-0">Status</th>
                            <th class="border-0">Working Hours</th>
                            <th class="border-0 rounded-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($staffMembers as $staff)
                            @php
                                $todayAttendance = $staff->userAttendances->first();
                                $workingHours = 'N/A';
                                
                                if ($todayAttendance) {
                                    if ($todayAttendance->check_out) {
                                        $checkIn = \Carbon\Carbon::parse($todayAttendance->check_in);
                                        $checkOut = \Carbon\Carbon::parse($todayAttendance->check_out);
                                        $workingHours = $checkOut->diff($checkIn)->format('%h:%I');
                                    } else {
                                        $checkIn = \Carbon\Carbon::parse($todayAttendance->check_in);
                                        $now = \Carbon\Carbon::now();
                                        $workingHours = $now->diff($checkIn)->format('%h:%I') . ' (ongoing)';
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">
                                        @if($staff->profile_photo)
                                            <img src="{{ asset('storage/' . $staff->profile_photo) }}" class="avatar rounded-circle me-3" alt="{{ $staff->name }}">
                                        @else
                                            <div class="avatar rounded-circle me-3 bg-primary text-white d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr($staff->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 text-dark">{{ $staff->name }}</h6>
                                            <small class="text-muted">ID: {{ $staff->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <div>
                                        <span class="fw-normal text-dark">{{ $staff->email }}</span><br>
                                        <small class="text-muted">{{ $staff->phone_number ?? 'No phone' }}</small>
                                    </div>
                                </td>
                                <td class="border-0">
                                    @if($todayAttendance && $todayAttendance->check_in)
                                        <span class="time-display check-in-time">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('g:i A') }}</span>
                                        <br><small class="text-muted">{{ $todayAttendance->check_in_location_name ?? 'Location not recorded' }}</small>
                                    @else
                                        <span class="text-muted">Not checked in</span>
                                    @endif
                                </td>
                                <td class="border-0">
                                    @if($todayAttendance && $todayAttendance->check_out)
                                        <span class="time-display check-out-time">{{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('g:i A') }}</span>
                                        <br><small class="text-muted">{{ $todayAttendance->check_out_location_name ?? 'Location not recorded' }}</small>
                                    @else
                                        <span class="text-muted">Not checked out</span>
                                    @endif
                                </td>
                                <td class="border-0">
                                    @if($todayAttendance)
                                        @if($todayAttendance->check_out)
                                            <span class="badge bg-success status-badge">
                                                <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Completed
                                            </span>
                                        @else
                                            <span class="badge bg-primary status-badge">
                                                <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2L3 7v11a2 2 0 002 2h4v-6h2v6h4a2 2 0 002-2V7l-7-5z"></path>
                                                </svg>
                                                Active
                                            </span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary status-badge">
                                            <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9zM13.73 21a2 2 0 01-3.46 0"></path>
                                            </svg>
                                            Absent
                                        </span>
                                    @endif
                                </td>
                                <td class="border-0">
                                    <span class="time-display">{{ $workingHours }}</span>
                                </td>
                                <td class="border-0 staff-actions">
                                    @if($todayAttendance && !$todayAttendance->check_out)
                                        <form method="POST" action="{{ route('staff.checkout', $staff) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary checkout-btn" onclick="return confirm('Are you sure you want to check out {{ $staff->name }}?')">
                                                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Check Out
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">No action needed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="text-muted">
                                        <svg class="icon icon-lg mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <h6>No staff members found</h6>
                                        <p class="mb-0">Add some staff members to track their attendance.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
    <style>
        .avatar {
            width: 40px;
            height: 40px;
            font-size: 14px;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .staff-actions {
            white-space: nowrap;
        }
        
        .status-badge {
            min-width: 80px;
            text-align: center;
        }
        
        .time-display {
            font-family: monospace;
            font-size: 0.9rem;
        }
        
        .checkout-btn {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            transition: all 0.3s ease;
        }
        
        .checkout-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        }
        
        .check-in-time {
            color: #28a745;
            font-weight: 600;
        }
        
        .check-out-time {
            color: #dc3545;
            font-weight: 600;
        }
        
        .attendance-stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.375rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
    </style>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
                <div class="d-block mb-4 mb-md-0">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Staff Attendance</li>
                        </ol>
                    </nav>
                    <h2 class="h4">Staff Attendance Management</h2>
                    <p class="mb-0">Manage staff check-in and check-out times for {{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="location.reload()">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $checkedIn = $staffMembers->filter(function($staff) {
                $attendance = $staff->userAttendances->first();
                return $attendance && !$attendance->check_out;
            })->count();
            
            $checkedOut = $staffMembers->filter(function($staff) {
                $attendance = $staff->userAttendances->first();
                return $attendance && $attendance->check_out;
            })->count();
            
            $notCheckedIn = $staffMembers->filter(function($staff) {
                return $staff->userAttendances->isEmpty();
            })->count();
        @endphp
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-primary text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $checkedIn }}</h3>
                    <p class="text-muted mb-0">Currently Checked In</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-success text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $checkedOut }}</h3>
                    <p class="text-muted mb-0">Checked Out Today</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-secondary text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7v11a2 2 0 002 2h4v-6h2v6h4a2 2 0 002-2V7l-7-5z"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $notCheckedIn }}</h3>
                    <p class="text-muted mb-0">Not Checked In</p>
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
                            <h2 class="h5">Staff Members ({{ $staffMembers->count() }} total)</h2>
                        </div>
                        <div class="col-auto">
                            <span class="badge bg-info">{{ now()->format('l, F j, Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0 rounded-start">Staff Member</th>
                                <th class="border-0">Contact</th>
                                <th class="border-0">Check-in</th>
                                <th class="border-0">Check-out</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Working Hours</th>
                                <th class="border-0 rounded-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($staffMembers as $staff)
                                @php
                                    $todayAttendance = $staff->userAttendances->first();
                                    $workingHours = 'N/A';
                                    
                                    if ($todayAttendance) {
                                        if ($todayAttendance->check_out) {
                                            $checkIn = \Carbon\Carbon::parse($todayAttendance->check_in);
                                            $checkOut = \Carbon\Carbon::parse($todayAttendance->check_out);
                                            $workingHours = $checkOut->diff($checkIn)->format('%h:%I');
                                        } else {
                                            $checkIn = \Carbon\Carbon::parse($todayAttendance->check_in);
                                            $now = \Carbon\Carbon::now();
                                            $workingHours = $now->diff($checkIn)->format('%h:%I') . ' (ongoing)';
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="border-0">
                                        <div class="d-flex align-items-center">
                                            @if($staff->profile_photo)
                                                <img src="{{ asset('storage/' . $staff->profile_photo) }}" class="avatar rounded-circle me-3" alt="{{ $staff->name }}">
                                            @else
                                                <div class="avatar rounded-circle me-3 bg-primary text-white d-flex align-items-center justify-content-center">
                                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 text-dark">{{ $staff->name }}</h6>
                                                <small class="text-muted">ID: {{ $staff->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        <div>
                                            <span class="fw-normal text-dark">{{ $staff->email }}</span><br>
                                            <small class="text-muted">{{ $staff->phone_number ?? 'No phone' }}</small>
                                        </div>
                                    </td>
                                    <td class="border-0">
                                        @if($todayAttendance && $todayAttendance->check_in)
                                            <span class="time-display check-in-time">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('g:i A') }}</span>
                                            <br><small class="text-muted">{{ $todayAttendance->check_in_location_name ?? 'Location not recorded' }}</small>
                                        @else
                                            <span class="text-muted">Not checked in</span>
                                        @endif
                                    </td>
                                    <td class="border-0">
                                        @if($todayAttendance && $todayAttendance->check_out)
                                            <span class="time-display check-out-time">{{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('g:i A') }}</span>
                                            <br><small class="text-muted">{{ $todayAttendance->check_out_location_name ?? 'Location not recorded' }}</small>
                                        @else
                                            <span class="text-muted">Not checked out</span>
                                        @endif
                                    </td>
                                    <td class="border-0">
                                        @if($todayAttendance)
                                            @if($todayAttendance->check_out)
                                                <span class="badge bg-success status-badge">
                                                    <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Completed
                                                </span>
                                            @else
                                                <span class="badge bg-primary status-badge">
                                                    <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 2L3 7v11a2 2 0 002 2h4v-6h2v6h4a2 2 0 002-2V7l-7-5z"></path>
                                                    </svg>
                                                    Active
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary status-badge">
                                                <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9zM13.73 21a2 2 0 01-3.46 0"></path>
                                                </svg>
                                                Absent
                                            </span>
                                        @endif
                                    </td>
                                    <td class="border-0">
                                        <span class="time-display">{{ $workingHours }}</span>
                                    </td>
                                    <td class="border-0 staff-actions">
                                        @if($todayAttendance && !$todayAttendance->check_out)
                                            <form method="POST" action="{{ route('staff.checkout', $staff) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary checkout-btn" onclick="return confirm('Are you sure you want to check out {{ $staff->name }}?')">
                                                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                    </svg>
                                                    Check Out
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted">No action needed</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <svg class="icon icon-lg mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <h6>No staff members found</h6>
                                            <p class="mb-0">Add some staff members to track their attendance.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
