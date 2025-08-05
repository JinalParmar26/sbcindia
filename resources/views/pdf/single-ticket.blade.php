@extends('pdf.layout')

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <h3>Ticket Information</h3>
            <table>
                <tr>
                    <td><strong>Ticket ID:</strong></td>
                    <td>{{ $ticket->id }}</td>
                </tr>
                <tr>
                    <td><strong>Title:</strong></td>
                    <td>{{ $ticket->title }}</td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td>
                        @switch($ticket->status)
                            @case('open')
                                <span class="badge badge-success">Open</span>
                                @break
                            @case('closed')
                                <span class="badge badge-danger">Closed</span>
                                @break
                            @case('pending')
                                <span class="badge badge-warning">Pending</span>
                                @break
                            @case('resolved')
                                <span class="badge badge-info">Resolved</span>
                                @break
                            @default
                                <span class="badge badge-primary">{{ ucfirst($ticket->status) }}</span>
                        @endswitch
                    </td>
                </tr>
                <tr>
                    <td><strong>Priority:</strong></td>
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
                </tr>
                <tr>
                    <td><strong>Created:</strong></td>
                    <td>{{ $ticket->created_at ? $ticket->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Updated:</strong></td>
                    <td>{{ $ticket->updated_at ? $ticket->updated_at->format('Y-m-d H:i') : 'N/A' }}</td>
                </tr>
            </table>
        </div>

        <div>
            <h3>Customer & Assignment</h3>
            <table>
                <tr>
                    <td><strong>Customer:</strong></td>
                    <td>{{ $ticket->customer->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $ticket->customer->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Phone:</strong></td>
                    <td>{{ $ticket->customer->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Assigned To:</strong></td>
                    <td>{{ $ticket->assignedTo->name ?? 'Unassigned' }}</td>
                </tr>
                <tr>
                    <td><strong>Category:</strong></td>
                    <td>{{ $ticket->category ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    @if($ticket->description)
        <div style="margin-bottom: 20px;">
            <h3>Description</h3>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
                {{ $ticket->description }}
            </div>
        </div>
    @endif

    @if($ticket->comments && count($ticket->comments) > 0)
        <div class="page-break">
            <h3>Comments History</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ticket->comments as $comment)
                        <tr>
                            <td>{{ $comment->created_at ? $comment->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                            <td>{{ $comment->user->name ?? 'N/A' }}</td>
                            <td>{{ $comment->comment }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($ticket->resolution)
        <div style="margin-top: 20px;">
            <h3>Resolution</h3>
            <div style="background-color: #d1ecf1; padding: 15px; border-radius: 5px; border: 1px solid #b3d4db;">
                {{ $ticket->resolution }}
            </div>
        </div>
    @endif
@endsection
