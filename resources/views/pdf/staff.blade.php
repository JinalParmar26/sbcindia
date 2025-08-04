@extends('pdf.layout')

@section('content')
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $staff->count() }}</div>
            <div class="stat-label">Total Staff</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $staff->where('status', 'active')->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $staff->where('created_at', '>=', now()->subMonth())->count() }}</div>
            <div class="stat-label">New This Month</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Position</th>
                <th>Status</th>
                <th>Join Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staff as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->email }}</td>
                    <td>{{ $member->phone ?? 'N/A' }}</td>
                    <td>{{ $member->department ?? 'N/A' }}</td>
                    <td>{{ $member->position ?? 'N/A' }}</td>
                    <td>
                        @switch($member->status)
                            @case('active')
                                <span class="badge badge-success">Active</span>
                                @break
                            @case('inactive')
                                <span class="badge badge-danger">Inactive</span>
                                @break
                            @case('on_leave')
                                <span class="badge badge-warning">On Leave</span>
                                @break
                            @default
                                <span class="badge badge-info">{{ ucfirst($member->status) }}</span>
                        @endswitch
                    </td>
                    <td>{{ $member->created_at ? $member->created_at->format('Y-m-d') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($staff->count() == 0)
        <div class="text-center" style="padding: 40px;">
            <h3>No staff members found</h3>
            <p>No staff members match the current filters.</p>
        </div>
    @endif
@endsection
