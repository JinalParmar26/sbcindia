@extends('pdf.layout')

@section('content')
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $tickets->count() }}</div>
            <div class="stat-label">Total Tickets</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $tickets->whereNull('start')->count() }}</div>
            <div class="stat-label">Open</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $tickets->whereNotNull('start')->whereNull('end')->count() }}</div>
            <div class="stat-label">In Progress</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $tickets->whereNotNull('start')->whereNotNull('end')->count() }}</div>
            <div class="stat-label">Resolved</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $tickets->where('priority', 'high')->count() }}</div>
            <div class="stat-label">High Priority</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Title</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Assigned To</th>
                <th>Created</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->title }}</td>
                    <td>{{ $ticket->customer->name ?? 'N/A' }}</td>
                    <td>
                        @if(is_null($ticket->start))
                            <span class="badge badge-success">Open</span>
                        @elseif(!is_null($ticket->start) && is_null($ticket->end))
                            <span class="badge badge-warning">In Progress</span>
                        @elseif(!is_null($ticket->start) && !is_null($ticket->end))
                            <span class="badge badge-info">Resolved</span>
                        @else
                            <span class="badge badge-primary">Unknown</span>
                        @endif
                    </td>
                    <td>
                        @switch($ticket->priority)
                            @case('high')
                                <span class="badge badge-danger">High</span>
                                @break
                            @case('medium')
                                <span class="badge badge-warning">Medium</span>
                                @break
                            @case('low')
                                <span class="badge badge-success">Low</span>
                                @break
                            @default
                                <span class="badge badge-info">{{ ucfirst($ticket->priority) }}</span>
                        @endswitch
                    </td>
                    <td>{{ $ticket->assignedTo->name ?? 'Unassigned' }}</td>
                    <td>{{ $ticket->created_at ? $ticket->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                    <td>{{ $ticket->updated_at ? $ticket->updated_at->format('Y-m-d H:i') : 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($tickets->count() == 0)
        <div class="text-center" style="padding: 40px;">
            <h3>No tickets found</h3>
            <p>No tickets match the current filters.</p>
        </div>
    @endif
@endsection
