<div>
    <style>
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .ticket-actions {
            white-space: nowrap;
        }
        
        .staff-status {
            font-size: 0.8rem;
        }
        
        .ticket-priority-high {
            border-left: 4px solid #dc3545;
        }
        
        .ticket-priority-medium {
            border-left: 4px solid #ffc107;
        }
        
        .ticket-priority-low {
            border-left: 4px solid #28a745;
        }
    </style>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-primary text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['total'] }}</h3>
                    <p class="text-muted mb-0">Total Tickets</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-warning text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['open'] }}</h3>
                    <p class="text-muted mb-0">Open Tickets</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-info text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['in_progress'] }}</h3>
                    <p class="text-muted mb-0">In Progress</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-success text-white rounded-circle mb-3 mx-auto">
                        <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['resolved'] }}</h3>
                    <p class="text-muted mb-0">Resolved</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 mb-3 mb-md-0">
                    <input type="text" wire:model="search" class="form-control" placeholder="Search tickets...">
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <select wire:model="assignedStaffFilter" class="form-select">
                        <option value="all">All Staff</option>
                        @foreach($staffList as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <select wire:model="statusFilter" class="form-select">
                        <option value="all">All Status</option>
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <select wire:model="priorityFilter" class="form-select">
                        <option value="all">All Priority</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3 mb-md-0">
                    <select wire:model="perPage" class="form-select">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                        <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="card border-0 shadow">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="h5">Staff Tickets ({{ $tickets->total() }} total)</h2>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0 rounded-start">
                            <a href="#" wire:click.prevent="sortBy('subject')">
                                Ticket
                                @if($sortField === 'subject')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </a>
                        </th>
                        <th class="border-0">Customer</th>
                        <th class="border-0">
                            <a href="#" wire:click.prevent="sortBy('assigned_to')">
                                Assigned Staff
                                @if($sortField === 'assigned_to')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </a>
                        </th>
                        <th class="border-0">Status</th>
                        <th class="border-0">Priority</th>
                        <th class="border-0">Product</th>
                        <th class="border-0">
                            <a href="#" wire:click.prevent="sortBy('created_at')">
                                Created
                                @if($sortField === 'created_at')
                                    @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @endif
                            </a>
                        </th>
                        <th class="border-0 rounded-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr class="ticket-priority-{{ $ticket->priority ?? 'low' }}">
                            <td class="border-0">
                                <div>
                                    <h6 class="mb-1 text-dark">{{ $ticket->subject }}</h6>
                                    <small class="text-muted">ID: {{ $ticket->uuid }}</small>
                                </div>
                            </td>
                            <td class="border-0">
                                <div>
                                    <span class="fw-normal text-dark">{{ $ticket->customer->name ?? 'N/A' }}</span>
                                    @if($ticket->contactPerson)
                                        <br><small class="text-muted">{{ $ticket->contactPerson->name }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="border-0">
                                <div class="d-flex align-items-center">
                                    @if($ticket->assignedTo)
                                        <div class="avatar rounded-circle me-2 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px;">
                                            {{ strtoupper(substr($ticket->assignedTo->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-normal text-dark">{{ $ticket->assignedTo->name }}</span>
                                            <br>
                                            <small class="staff-status">
                                                @if($ticket->check_in && !$ticket->check_out)
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($ticket->check_in && $ticket->check_out)
                                                    <span class="badge bg-secondary">Completed</span>
                                                @else
                                                    <span class="badge bg-warning">Offline</span>
                                                @endif
                                            </small>
                                        </div>
                                    @else
                                        <span class="text-muted">Unassigned</span>
                                    @endif
                                </div>
                            </td>
                            <td class="border-0">
                                @if(is_null($ticket->start))
                                    <span class="badge bg-warning">Open</span>
                                @elseif(!is_null($ticket->start) && is_null($ticket->end))
                                    <span class="badge bg-info">In Progress</span>
                                @elseif(!is_null($ticket->start) && !is_null($ticket->end))
                                    <span class="badge bg-success">Resolved</span>
                                @else
                                    <span class="badge bg-light text-dark">Unknown</span>
                                @endif
                            </td>
                            <td class="border-0">
                                @switch($ticket->priority ?? 'low')
                                    @case('high')
                                        <span class="badge bg-danger">High</span>
                                        @break
                                    @case('medium')
                                        <span class="badge bg-warning">Medium</span>
                                        @break
                                    @case('low')
                                        <span class="badge bg-success">Low</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ ucfirst($ticket->priority) }}</span>
                                @endswitch
                            </td>
                            <td class="border-0">
                                @if($ticket->orderProduct && $ticket->orderProduct->product)
                                    <div>
                                        <span class="fw-normal text-dark">{{ $ticket->orderProduct->product->name }}</span>
                                        @if($ticket->orderProduct->model_number)
                                            <br><small class="text-muted">{{ $ticket->orderProduct->model_number }}</small>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">No product</span>
                                @endif
                            </td>
                            <td class="border-0">
                                <span class="text-muted">{{ $ticket->created_at->format('M j, Y') }}</span>
                                <br><small class="text-muted">{{ $ticket->created_at->format('g:i A') }}</small>
                            </td>
                            <td class="border-0 ticket-actions">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tickets.show', $ticket->uuid) }}" class="btn btn-sm btn-outline-primary" title="View Ticket">
                                        <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit Ticket">
                                        <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <svg class="icon icon-lg mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h6>No tickets found</h6>
                                    <p class="mb-0">No tickets match your current filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
            <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
