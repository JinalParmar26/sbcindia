@extends('pdf.layout')

@section('content')
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $customers->count() }}</div>
            <div class="stat-label">Total Customers</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $customers->where('status', 'active')->count() }}</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $customers->where('created_at', '>=', now()->subMonth())->count() }}</div>
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
                <th>Company</th>
                <th>Status</th>
                <th>Created</th>
                <th>Last Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                    <td>{{ $customer->company ?? 'N/A' }}</td>
                    <td>
                        @switch($customer->status)
                            @case('active')
                                <span class="badge badge-success">Active</span>
                                @break
                            @case('inactive')
                                <span class="badge badge-danger">Inactive</span>
                                @break
                            @default
                                <span class="badge badge-info">{{ ucfirst($customer->status) }}</span>
                        @endswitch
                    </td>
                    <td>{{ $customer->created_at ? $customer->created_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $customer->last_order_at ? $customer->last_order_at->format('Y-m-d') : 'Never' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($customers->count() == 0)
        <div class="text-center" style="padding: 40px;">
            <h3>No customers found</h3>
            <p>No customers match the current filters.</p>
        </div>
    @endif
@endsection
