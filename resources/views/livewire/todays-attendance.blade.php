<div>
   <style>
   
    </style>



    <!-- Header & Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
                <div class="d-block mb-4 mb-md-0">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                            <li class="breadcrumb-item">
                                <a href="/dashboard">
                                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
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
                    <button type="button" class="btn btn-sm btn-outline-primary" wire:click="$refresh">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        @php
            $checkedIn = $staffMembers->filter(fn($staff) => $staff->userAttendances->first() && !$staff->userAttendances->first()->check_out)->count();
            $checkedOut = $staffMembers->filter(fn($staff) => $staff->userAttendances->first() && $staff->userAttendances->first()->check_out)->count();
            $notCheckedIn = $staffMembers->filter(fn($staff) => $staff->userAttendances->isEmpty())->count();
        @endphp

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="icon-shape icon-md bg-primary text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $checkedIn }}</h3>
                    <p class="text-muted mb-0">Currently Checked In</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="icon-shape icon-md bg-success text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $checkedOut }}</h3>
                    <p class="text-muted mb-0">Checked Out Today</p>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center">
                <div class="card-body">
                    <div class="icon-shape icon-md bg-secondary text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2L3 7v11a2 2 0 002 2h4v-6h2v6h4a2 2 0 002-2V7l-7-5z"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $notCheckedIn }}</h3>
                    <p class="text-muted mb-0">Not Checked In</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Staff Members ({{ $attendances->total() }} total)</h2>
                    <span class="badge bg-info">{{ date('l, F j, Y') }}</span>
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
                        @forelse ($attendances as $staff)
                            @php
                                $todayAttendance = $staff->userAttendances->first();
                                $workingHours = 'N/A';
                                if($todayAttendance){
                                    $checkIn = \Carbon\Carbon::parse($todayAttendance->check_in);
                                    $checkOut = $todayAttendance->check_out ? \Carbon\Carbon::parse($todayAttendance->check_out) : now();
                                    $workingHours = $checkOut->diff($checkIn)->format('%h:%I');
                                    if(!$todayAttendance->check_out) $workingHours .= ' (ongoing)';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($staff->profile_photo)
                                            <img src="{{ asset('storage/' . $staff->profile_photo) }}" class="avatar rounded-circle me-3">
                                        @else
                                            <div class="avatar rounded-circle me-3 bg-primary text-white d-flex align-items-center justify-content-center">
                                                {{ strtoupper(substr($staff->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $staff->name }}</h6>
                                            <small class="text-muted">ID: {{ $staff->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $staff->email }}<br>{{ $staff->phone_number ?? 'No phone' }}</td>
                                <td>
                                    @if($todayAttendance && $todayAttendance->check_in)
                                        <span class="time-display check-in-time">{{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('g:i A') }}</span>
                                    @else
                                        <span class="text-muted">Not checked in</span>
                                    @endif
                                </td>
                                <td>
                                    @if($todayAttendance && $todayAttendance->check_out)
                                        <span class="time-display check-out-time">{{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('g:i A') }}</span>
                                    @else
                                        <span class="text-muted">Not checked out</span>
                                    @endif
                                </td>
                                <td>
                                    @if($todayAttendance)
                                        @if($todayAttendance->check_out)
                                            <span class="badge bg-success status-badge">Completed</span>
                                        @else
                                            <span class="badge bg-primary status-badge">Active</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary status-badge">Absent</span>
                                    @endif
                                </td>
                                <td><span class="time-display">{{ $workingHours }}</span></td>
                                <td class="staff-actions">
                                    @if($todayAttendance && !$todayAttendance->check_out)
                                        <button class="btn btn-sm btn-primary checkout-btn" wire:click="checkOutUser({{ $staff->id }})">
                                            Check Out
                                        </button>
                                    @else
                                        <span class="text-muted">No action needed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No staff members found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3 d-flex justify-content-end">
                    {{ $attendances->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
<style>
/* Fix Livewire pagination arrow size */
.page-link svg {
    width: 1em !important;
    height: 1em !important;
}
</style>
