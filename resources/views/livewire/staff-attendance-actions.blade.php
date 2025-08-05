<div>
    <style>
        .filter-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stats-card {
            border: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .table-header {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        }
        
        .sort-header {
            cursor: pointer;
            user-select: none;
            transition: all 0.3s ease;
        }
        
        .sort-header:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .sort-arrow {
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        
        .badge-status {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 0.3rem;
        }
        
        .time-badge {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            font-family: monospace;
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
            border-radius: 0.3rem;
        }
        
        .working-hours-badge {
            background: linear-gradient(45deg, #007bff, #6610f2);
            color: white;
            font-family: monospace;
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
            border-radius: 0.3rem;
        }
        
        .filter-section {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .custom-select {
            border: 2px solid #e9ecef;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .custom-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .date-range-buttons .btn {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .pagination-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .nav-tabs .nav-link {
            border-color: #e9ecef;
            color: #6c757d;
            transition: all 0.3s ease;
        }
        
        .nav-tabs .nav-link:hover {
            border-color: #e9ecef #e9ecef #dee2e6;
            color: #495057;
        }
        
        .nav-tabs .nav-link.active {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-color: #667eea;
            color: white;
        }
        
        .tab-content {
            margin-top: 2rem;
        }
        
        .person-selector {
            background: #f8f9fa;
            border-radius: 0.5rem;
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
                                <a href="/dashboard">
                                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="/staff">Staff Attendance</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Actions & Reports</li>
                        </ol>
                    </nav>
                    <h2 class="h4">Staff Attendance Actions & Reports</h2>
                    <p class="mb-0">Comprehensive attendance tracking with advanced filtering and sorting</p>
                </div>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary me-2" wire:click="resetFilters">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset Filters
                    </button>
                    <a href="{{ route('staff.locations.live') }}" class="btn btn-sm btn-outline-success me-2">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Live Tracking
                    </a>
                    <button type="button" class="btn btn-sm btn-danger me-2" onclick="exportAttendancePdf()">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Download PDF
                    </a>
                    <a href="/staff" class="btn btn-sm btn-primary">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Back to Live View
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}" 
                            wire:click="switchTab('all')" 
                            type="button">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        All Attendance Records
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'person' ? 'active' : '' }}" 
                            wire:click="switchTab('person')" 
                            type="button">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Person-wise History
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $activeTab === 'staff_list' ? 'active' : '' }}" 
                            wire:click="switchTab('staff_list')" 
                            type="button">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Staff List
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $currentStats = $activeTab === 'person' ? $personWiseStatistics : $statistics;
        @endphp
        
        <div class="col-md-2">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-primary text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="h5 mb-1">{{ $currentStats['total_records'] }}</h4>
                    <p class="text-muted mb-0 small">Total Records</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-success text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="h5 mb-1">{{ $currentStats['completed_shifts'] }}</h4>
                    <p class="text-muted mb-0 small">Completed</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-warning text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7v11a2 2 0 002 2h4v-6h2v6h4a2 2 0 002-2V7l-7-5z"></path>
                        </svg>
                    </div>
                    <h4 class="h5 mb-1">{{ $currentStats['active_shifts'] }}</h4>
                    <p class="text-muted mb-0 small">Active</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-info text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                        </svg>
                    </div>
                    <h4 class="h5 mb-1">
                        @if($activeTab === 'person')
                            {{ $currentStats['total_days_worked'] ?? 0 }}
                        @else
                            {{ $currentStats['unique_users'] }}
                        @endif
                    </h4>
                    <p class="text-muted mb-0 small">
                        @if($activeTab === 'person')
                            Days Worked
                        @else
                            Unique Users
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-secondary text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.414L11 9.586V6z"></path>
                        </svg>
                    </div>
                    <h4 class="h5 mb-1">{{ number_format($currentStats['total_working_hours'] / 60, 1) }}</h4>
                    <p class="text-muted mb-0 small">Total Hours</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-purple text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h4 class="h5 mb-1">{{ number_format($currentStats['average_working_hours'] / 60, 1) }}</h4>
                    <p class="text-muted mb-0 small">Avg Hours</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filter-section">
        <div class="row align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold">Search Staff</label>
                <input type="text" class="form-control custom-select" wire:model="search" placeholder="Name or email...">
            </div>
            
            <div class="col-md-2">
                <label class="form-label fw-bold">Staff Member</label>
                <select class="form-control searchable-dropdown livewire-select" wire:model="selectedUser">
                    <option value="">All Staff</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label fw-bold">Date Range</label>
                <select class="form-control searchable-dropdown livewire-select" wire:model="dateRange">
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="this_week">This Week</option>
                    <option value="last_week">Last Week</option>
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="custom_day">Custom Day</option>
                    <option value="custom_month">Custom Month</option>
                    <option value="custom_year">Custom Year</option>
                </select>
            </div>
            
            @if($dateRange === 'custom_day')
                <div class="col-md-1">
                    <label class="form-label fw-bold">Day</label>
                    <select class="form-control searchable-dropdown livewire-select" wire:model="selectedDay">
                        @for($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            @endif
            
            @if(in_array($dateRange, ['custom_day', 'custom_month']))
                <div class="col-md-1">
                    <label class="form-label fw-bold">Month</label>
                    <select class="form-control searchable-dropdown livewire-select" wire:model="selectedMonth">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ date('M', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                </div>
            @endif
            
            @if(in_array($dateRange, ['custom_day', 'custom_month', 'custom_year']))
                <div class="col-md-1">
                    <label class="form-label fw-bold">Year</label>
                    <select class="form-control searchable-dropdown livewire-select" wire:model="selectedYear">
                        @for($i = 2020; $i <= date('Y') + 1; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            @endif
            
            <div class="col-md-2">
                <label class="form-label fw-bold">Per Page</label>
                <select class="form-control searchable-dropdown livewire-select" wire:model="perPage">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tab Content -->
    @if($activeTab === 'all')
        <!-- All Attendance Records Tab -->
        <!-- Attendance Table -->
        <div class="card border-0 shadow">
            <div class="card-header table-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">All Attendance Records</h5>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-primary">{{ $attendanceData->total() }} total records</span>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="sort-header" wire:click="sortBy('date')">
                                Date
                                @if($sortBy === 'date')
                                    <span class="sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="sort-header" wire:click="sortBy('user')">
                                Staff Member
                                @if($sortBy === 'user')
                                    <span class="sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="sort-header" wire:click="sortBy('check_in')">
                                Check In
                                @if($sortBy === 'check_in')
                                    <span class="sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="sort-header" wire:click="sortBy('check_out')">
                                Check Out
                                @if($sortBy === 'check_out')
                                    <span class="sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="sort-header" wire:click="sortBy('working_hours')">
                                Working Hours
                                @if($sortBy === 'working_hours')
                                    <span class="sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceData as $record)
                            @php
                                $workingHours = 'N/A';
                                $workingMinutes = 0;
                                if ($record->check_out) {
                                    $workingMinutes = \Carbon\Carbon::parse($record->check_in)->diffInMinutes(\Carbon\Carbon::parse($record->check_out));
                                    $hours = floor($workingMinutes / 60);
                                    $minutes = $workingMinutes % 60;
                                    $workingHours = sprintf('%02d:%02d', $hours, $minutes);
                                } elseif ($record->check_in) {
                                    $workingMinutes = \Carbon\Carbon::parse($record->check_in)->diffInMinutes(\Carbon\Carbon::now());
                                    $hours = floor($workingMinutes / 60);
                                    $minutes = $workingMinutes % 60;
                                    $workingHours = sprintf('%02d:%02d (ongoing)', $hours, $minutes);
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div>
                                        <span class="fw-bold">{{ \Carbon\Carbon::parse($record->check_in)->format('M d, Y') }}</span><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($record->check_in)->format('l') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($record->user->profile_photo)
                                            <img src="{{ asset('storage/' . $record->user->profile_photo) }}" class="rounded-circle me-2" width="30" height="30" alt="{{ $record->user->name }}">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                                {{ strtoupper(substr($record->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-bold">{{ $record->user->name }}</span><br>
                                            <small class="text-muted">{{ $record->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($record->check_in)
                                        <span class="time-badge">{{ \Carbon\Carbon::parse($record->check_in)->format('g:i A') }}</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->check_out)
                                        <span class="time-badge">{{ \Carbon\Carbon::parse($record->check_out)->format('g:i A') }}</span>
                                    @else
                                        <span class="badge bg-warning">Not checked out</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->check_out)
                                        <span class="working-hours-badge">{{ $workingHours }}</span>
                                    @elseif($record->check_in)
                                        <span class="working-hours-badge">{{ $workingHours }}</span>
                                    @else
                                        <span class="badge bg-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->check_out)
                                        <span class="badge bg-success badge-status">Completed</span>
                                    @elseif($record->check_in)
                                        <span class="badge bg-primary badge-status">Active</span>
                                    @else
                                        <span class="badge bg-secondary badge-status">Incomplete</span>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        @if($record->check_in_location_name)
                                            <small class="text-success">In: {{ $record->check_in_location_name }}</small><br>
                                        @endif
                                        @if($record->check_out_location_name)
                                            <small class="text-danger">Out: {{ $record->check_out_location_name }}</small>
                                        @endif
                                        @if(!$record->check_in_location_name && !$record->check_out_location_name)
                                            <small class="text-muted">No location</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            wire:click="selectPerson({{ $record->user_id }})">
                                        <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View History
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <svg class="icon icon-lg mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h6>No attendance records found</h6>
                                        <p class="mb-0">Try adjusting your filters or date range.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($attendanceData->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">
                            Showing {{ $attendanceData->firstItem() }} to {{ $attendanceData->lastItem() }} of {{ $attendanceData->total() }} results
                        </span>
                        {{ $attendanceData->links() }}
                    </div>
                </div>
            @endif
        </div>
        
    @elseif($activeTab === 'person')
        <!-- Person-wise History Tab -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header table-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Person-wise Attendance History</h5>
                    </div>
                    <div class="col-auto">
                        @if($selectedPerson)
                            <div class="d-flex align-items-center">
                                @if($selectedPerson->profile_photo)
                                    <img src="{{ asset('storage/' . $selectedPerson->profile_photo) }}" class="rounded-circle me-2" width="30" height="30" alt="{{ $selectedPerson->name }}">
                                @else
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                        {{ strtoupper(substr($selectedPerson->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="fw-bold">{{ $selectedPerson->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(!$selectedPersonId)
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Select a staff member to view their attendance history:</h6>
                            <select class="form-control searchable-dropdown livewire-select" wire:model="selectedPersonId">
                                <option value="">Choose a staff member...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="sort-header" wire:click="sortBy('date')">
                                        Date
                                        @if($sortBy === 'date')
                                            <span class="sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th class="sort-header" wire:click="sortBy('check_in')">
                                        Check In
                                        @if($sortBy === 'check_in')
                                            <span class="sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th class="sort-header" wire:click="sortBy('check_out')">
                                        Check Out
                                        @if($sortBy === 'check_out')
                                            <span class="sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th class="sort-header" wire:click="sortBy('working_hours')">
                                        Working Hours
                                        @if($sortBy === 'working_hours')
                                            <span class="sort-arrow">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                        @endif
                                    </th>
                                    <th>Status</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($personWiseData as $record)
                                    @php
                                        $workingHours = 'N/A';
                                        $workingMinutes = 0;
                                        if ($record->check_out) {
                                            $workingMinutes = \Carbon\Carbon::parse($record->check_in)->diffInMinutes(\Carbon\Carbon::parse($record->check_out));
                                            $hours = floor($workingMinutes / 60);
                                            $minutes = $workingMinutes % 60;
                                            $workingHours = sprintf('%02d:%02d', $hours, $minutes);
                                        } elseif ($record->check_in) {
                                            $workingMinutes = \Carbon\Carbon::parse($record->check_in)->diffInMinutes(\Carbon\Carbon::now());
                                            $hours = floor($workingMinutes / 60);
                                            $minutes = $workingMinutes % 60;
                                            $workingHours = sprintf('%02d:%02d (ongoing)', $hours, $minutes);
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div>
                                                <span class="fw-bold">{{ \Carbon\Carbon::parse($record->check_in)->format('M d, Y') }}</span><br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($record->check_in)->format('l') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($record->check_in)
                                                <span class="time-badge">{{ \Carbon\Carbon::parse($record->check_in)->format('g:i A') }}</span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->check_out)
                                                <span class="time-badge">{{ \Carbon\Carbon::parse($record->check_out)->format('g:i A') }}</span>
                                            @else
                                                <span class="badge bg-warning">Not checked out</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->check_out)
                                                <span class="working-hours-badge">{{ $workingHours }}</span>
                                            @elseif($record->check_in)
                                                <span class="working-hours-badge">{{ $workingHours }}</span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->check_out)
                                                <span class="badge bg-success badge-status">Completed</span>
                                            @elseif($record->check_in)
                                                <span class="badge bg-primary badge-status">Active</span>
                                            @else
                                                <span class="badge bg-secondary badge-status">Incomplete</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                @if($record->check_in_location_name)
                                                    <small class="text-success">In: {{ $record->check_in_location_name }}</small><br>
                                                @endif
                                                @if($record->check_out_location_name)
                                                    <small class="text-danger">Out: {{ $record->check_out_location_name }}</small>
                                                @endif
                                                @if(!$record->check_in_location_name && !$record->check_out_location_name)
                                                    <small class="text-muted">No location</small>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="text-muted">
                                                <svg class="icon icon-lg mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <h6>No attendance records found</h6>
                                                <p class="mb-0">This person has no attendance records for the selected period.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($personWiseData->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    Showing {{ $personWiseData->firstItem() }} to {{ $personWiseData->lastItem() }} of {{ $personWiseData->total() }} results
                                </span>
                                {{ $personWiseData->links() }}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        
    @elseif($activeTab === 'staff_list')
        <!-- Staff List Tab -->
        <div class="card border-0 shadow">
            <div class="card-header table-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Staff Members</h5>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-primary">{{ $users->count() }} staff members</span>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Staff Member</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Total Records</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            @php
                                $totalRecords = \App\Models\UserAttendance::where('user_id', $user->id)->count();
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="rounded-circle me-3" width="40" height="40" alt="{{ $user->name }}">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-size: 14px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 text-dark">{{ $user->name }}</h6>
                                            <small class="text-muted">ID: {{ $user->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-normal text-dark">{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $user->phone_number ?? 'No phone' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $totalRecords }} records</span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            wire:click="selectPerson({{ $user->id }})">
                                        <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View History
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="text-muted">
                                        <svg class="icon icon-lg mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <h6>No staff members found</h6>
                                        <p class="mb-0">Add some staff members to view their attendance history.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<style>
/* Searchable dropdown styles */
.searchable-dropdown-wrapper {
    position: relative;
}

.searchable-dropdown-input {
    width: 100%;
    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    cursor: pointer;
}

.searchable-dropdown-input:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.searchable-dropdown-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ced4da;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.searchable-dropdown-item {
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.searchable-dropdown-item:hover {
    background-color: #f8f9fa;
}

.searchable-dropdown-item:last-child {
    border-bottom: none;
}

.searchable-dropdown-item.selected {
    background-color: #e9ecef;
}

.searchable-dropdown-no-results {
    padding: 0.5rem 0.75rem;
    color: #6c757d;
    font-style: italic;
}
</style>

<script>
// Initialize searchable dropdowns on page load and after Livewire updates
document.addEventListener('DOMContentLoaded', function() {
    initializeSearchableDropdowns();
});

document.addEventListener('livewire:navigated', function() {
    initializeSearchableDropdowns();
});

document.addEventListener('livewire:load', function() {
    initializeSearchableDropdowns();
});

// Re-initialize after Livewire updates
Livewire.hook('message.processed', (message, component) => {
    setTimeout(() => {
        initializeSearchableDropdowns();
    }, 100);
});

// Function to initialize searchable dropdowns
function initializeSearchableDropdowns() {
    document.querySelectorAll('.searchable-dropdown:not(.searchable-dropdown-initialized)').forEach(function(select) {
        createSearchableDropdown(select);
        select.classList.add('searchable-dropdown-initialized');
    });
}

function createSearchableDropdown(selectElement) {
    const wrapper = document.createElement('div');
    wrapper.className = 'searchable-dropdown-wrapper';
    
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control searchable-dropdown-input';
    input.placeholder = selectElement.options[0] ? selectElement.options[0].text : 'Select an option';
    input.readOnly = false;
    
    const list = document.createElement('div');
    list.className = 'searchable-dropdown-list';
    
    // Store original options
    const options = Array.from(selectElement.options);
    
    // Set initial value if option is selected
    updateInputValue();
    
    // Replace select with wrapper
    selectElement.style.display = 'none';
    selectElement.parentNode.insertBefore(wrapper, selectElement);
    wrapper.appendChild(input);
    wrapper.appendChild(list);
    wrapper.appendChild(selectElement);
    
    // Function to update input value based on select value
    function updateInputValue() {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        if (selectedOption && selectedOption.value) {
            input.value = selectedOption.text;
        } else {
            input.value = '';
        }
    }
    
    // Watch for changes to the select element (from Livewire)
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                updateInputValue();
            }
        });
    });
    
    observer.observe(selectElement, {
        attributes: true,
        attributeFilter: ['value']
    });
    
    // Also watch for option changes
    const optionObserver = new MutationObserver(function(mutations) {
        updateInputValue();
    });
    
    optionObserver.observe(selectElement, {
        childList: true,
        subtree: true
    });
    
    // Show/hide dropdown
    input.addEventListener('click', function() {
        toggleDropdown(list, options, input, selectElement);
    });
    
    input.addEventListener('focus', function() {
        showDropdown(list, options, input, selectElement);
    });
    
    // Filter options as user types
    input.addEventListener('input', function() {
        filterOptions(list, options, input.value, input, selectElement);
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!wrapper.contains(e.target)) {
            list.style.display = 'none';
        }
    });
}

function toggleDropdown(list, options, input, selectElement) {
    if (list.style.display === 'block') {
        list.style.display = 'none';
    } else {
        showDropdown(list, options, input, selectElement);
    }
}

function showDropdown(list, options, input, selectElement) {
    filterOptions(list, options, input.value, input, selectElement);
    list.style.display = 'block';
}

function filterOptions(list, options, searchTerm, input, selectElement) {
    list.innerHTML = '';
    
    const filteredOptions = options.filter(option => {
        if (!option.value) return true; // Keep placeholder option
        return option.text.toLowerCase().includes(searchTerm.toLowerCase());
    });
    
    if (filteredOptions.length === 0 || (filteredOptions.length === 1 && !filteredOptions[0].value)) {
        const noResults = document.createElement('div');
        noResults.className = 'searchable-dropdown-no-results';
        noResults.textContent = 'No results found';
        list.appendChild(noResults);
    } else {
        filteredOptions.forEach(option => {
            if (!option.value && searchTerm) return; // Hide placeholder when searching
            
            const item = document.createElement('div');
            item.className = 'searchable-dropdown-item';
            item.textContent = option.text;
            item.dataset.value = option.value;
            
            if (option.selected) {
                item.classList.add('selected');
            }
            
            item.addEventListener('click', function() {
                selectOption(option, input, selectElement, list);
            });
            
            list.appendChild(item);
        });
    }
}

function selectOption(option, input, selectElement, list) {
    input.value = option.text;
    selectElement.value = option.value;
    
    // Remove selected class from all items
    list.querySelectorAll('.searchable-dropdown-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selected class to current item
    const currentItem = list.querySelector(`[data-value="${option.value}"]`);
    if (currentItem) {
        currentItem.classList.add('selected');
    }
    
    list.style.display = 'none';
    
    // Trigger change event for Livewire
    selectElement.dispatchEvent(new Event('change', { bubbles: true }));
    
    // If this is a Livewire select, also dispatch input event
    if (selectElement.classList.contains('livewire-select')) {
        selectElement.dispatchEvent(new Event('input', { bubbles: true }));
    }
}

// PDF Export function for Staff Attendance
function exportAttendancePdf() {
    const currentFilters = {
        date_from: @this.get('startDate') || '',
        date_to: @this.get('endDate') || '',
        user_id: @this.get('selectedUser') || 'all'
    };

    // Build URL with current filters
    const params = new URLSearchParams();
    Object.keys(currentFilters).forEach(key => {
        if (currentFilters[key] && currentFilters[key] !== 'all') {
            params.append(key, currentFilters[key]);
        }
    });

    const url = '{{ route("staff.attendance.export.pdf") }}' + (params.toString() ? '?' + params.toString() : '');
    window.open(url, '_blank');
}
</script>
