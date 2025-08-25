<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Today's Attendance</li>
                </ol>
            </nav>
            <h2 class="h4">Today's Attendance</h2>
            <p class="mb-0">View today's attendance for all staff.</p>
        </div>
    </div>

    <div class="table-settings mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-9 col-lg-8 d-md-flex">
                <div class="input-group me-2 me-lg-3 fmxw-300">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search staff">
                </div>
            </div>
            <div class="col-3 col-lg-4 d-flex justify-content-end">
                <div class="btn-group">
                    <div class="dropdown me-1">
                        <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-1"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Record Count
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pb-0">
                            <a class="dropdown-item" href="#" wire:click.prevent="$set('perPage', 10)">10</a>
                            <a class="dropdown-item" href="#" wire:click.prevent="$set('perPage', 20)">20</a>
                            <a class="dropdown-item rounded-bottom" href="#" wire:click.prevent="$set('perPage', 30)">30</a>
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
                <th wire:click="sortBy('name')" style="cursor:pointer;">Staff Name
                    @if($sortField==='name') {{ $sortDirection==='asc' ? '↑':'↓' }} @endif
                </th>
                <th>Email</th>
                <th>Role</th>
                <th wire:click="sortBy('check_in')" style="cursor:pointer;">Check-in
                    @if($sortField==='check_in') {{ $sortDirection==='asc' ? '↑':'↓' }} @endif
                </th>
                <th wire:click="sortBy('check_out')" style="cursor:pointer;">Check-out
                    @if($sortField==='check_out') {{ $sortDirection==='asc' ? '↑':'↓' }} @endif
                </th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($attendances as $user)
                @php $attendance = $user->userAttendances->first(); @endphp
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>{{ $attendance ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</td>
                    <td>
                        @if($attendance && $attendance->check_out)
                            {{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}
                        @elseif($attendance && !$attendance->check_out)
                            <span class="text-danger">Missing</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if(!$attendance)
                            <span class="text-danger">Absent</span>
                        @elseif($attendance && !$attendance->check_out)
                            Present
                        @else
                            Left
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <a href="#" class="btn btn-sm btn-gray-600 d-inline-flex align-items-center dropdown-toggle"
                               data-bs-toggle="dropdown" aria-expanded="false">Actions</a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="{{ route('user.attendance.detail', $user->uuid) }}" class="dropdown-item text-danger">
                                        View Attendance
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No staff found.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{-- Fix pagination arrow size --}}
        <div class="mt-3">
            {{ $attendances->links('pagination::bootstrap-5') }}
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
