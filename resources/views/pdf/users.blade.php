@extends('pdf.layout')

@section('content')
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $users->count() }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $users->where('email_verified_at', '!=', null)->count() }}</div>
            <div class="stat-label">Verified Users</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $users->where('created_at', '>=', now()->subMonth())->count() }}</div>
            <div class="stat-label">New This Month</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created</th>
                <th>Last Login</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role ?? 'User' }}</td>
                    <td>
                        @if($user->email_verified_at)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-warning">Unverified</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at ? $user->created_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'Never' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($users->count() == 0)
        <div class="text-center" style="padding: 40px;">
            <h3>No users found</h3>
            <p>No users match the current filters.</p>
        </div>
    @endif
@endsection
