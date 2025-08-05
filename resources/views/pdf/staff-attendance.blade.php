@extends('pdf.layout')

@section('content')
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $attendanceData->count() }}</div>
            <div class="stat-label">Total Records</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $attendanceData->whereNotNull('check_out')->count() }}</div>
            <div class="stat-label">Completed</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $attendanceData->whereNull('check_out')->count() }}</div>
            <div class="stat-label">Ongoing</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Staff Member</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Working Hours</th>
                <th>Status</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceData as $record)
                @php
                    $workingHours = 'N/A';
                    if ($record->check_in) {
                        if ($record->check_out) {
                            $checkIn = \Carbon\Carbon::parse($record->check_in);
                            $checkOut = \Carbon\Carbon::parse($record->check_out);
                            $workingHours = $checkOut->diff($checkIn)->format('%h:%I');
                        } else {
                            $checkIn = \Carbon\Carbon::parse($record->check_in);
                            $now = \Carbon\Carbon::now();
                            $workingHours = $now->diff($checkIn)->format('%h:%I') . ' (ongoing)';
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $record->user->name ?? 'N/A' }}</td>
                    <td>{{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $record->check_in ? \Carbon\Carbon::parse($record->check_in)->format('H:i') : 'N/A' }}</td>
                    <td>{{ $record->check_out ? \Carbon\Carbon::parse($record->check_out)->format('H:i') : 'N/A' }}</td>
                    <td>{{ $workingHours }}</td>
                    <td>
                        @if($record->check_out)
                            <span class="badge badge-success">Completed</span>
                        @elseif($record->check_in)
                            <span class="badge badge-warning">Ongoing</span>
                        @else
                            <span class="badge badge-danger">No Record</span>
                        @endif
                    </td>
                    <td>{{ $record->check_in_location_name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($attendanceData->count() == 0)
        <div class="text-center" style="padding: 40px;">
            <h3>No attendance records found</h3>
            <p>No attendance records match the current filters.</p>
        </div>
    @endif
@endsection
