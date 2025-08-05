@extends('pdf.layout')

@section('title', $title)

@section('content')
<div class="header">
    <h1>{{ $title }}</h1>
    <p>Generated on {{ $generated_at }}</p>
</div>

<div class="user-details">
    <h2>User Information</h2>
    <div class="info-section">
        <div class="info-row">
            <span class="label">Name:</span>
            <span class="value">{{ $user->name }} {{ $user->last_name ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">{{ $user->email }}</span>
        </div>
        <div class="info-row">
            <span class="label">Phone:</span>
            <span class="value">{{ $user->phone_number ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Role:</span>
            <span class="value">{{ $user->roles->pluck('name')->join(', ') ?: 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Status:</span>
            <span class="value">{{ $user->isActive ? 'Active' : 'Inactive' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Working Days:</span>
            <span class="value">
                @if($user->working_days)
                    @foreach(is_array($user->working_days) ? $user->working_days : json_decode($user->working_days) as $day)
                        {{ ucfirst($day) }}@if(!$loop->last), @endif
                    @endforeach
                @else
                    N/A
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="label">Created:</span>
            <span class="value">{{ $user->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Updated:</span>
            <span class="value">{{ $user->updated_at->format('Y-m-d H:i') }}</span>
        </div>
    </div>
</div>

@if($user->overtimeLogs && $user->overtimeLogs->count() > 0)
<div class="overtime-logs">
    <h2>Overtime Logs</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Hours</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->overtimeLogs as $log)
                <tr>
                    <td>{{ $log->date->format('Y-m-d') }}</td>
                    <td>{{ $log->hours }}</td>
                    <td>{{ $log->description ?? 'N/A' }}</td>
                    <td>{{ ucfirst($log->status ?? 'N/A') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($user->assignedTickets && $user->assignedTickets->count() > 0)
<div class="assigned-tickets">
    <h2>Assigned Tickets</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->assignedTickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ $ticket->customer->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($ticket->status ?? 'N/A') }}</td>
                    <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($user->orders && $user->orders->count() > 0)
<div class="orders">
    <h2>Related Orders</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                    <td>{{ ucfirst($order->status ?? 'N/A') }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
