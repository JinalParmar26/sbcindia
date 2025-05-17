<div>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">ERP</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tickets List</li>
                </ol>
            </nav>
            <h2 class="h4">Tickets List</h2>
            <p class="mb-0">Your support ticket management dashboard.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('tickets.create') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Ticket
            </a>
        </div>
    </div>

    <div class="table-settings mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-9 col-lg-8 d-md-flex">
                <div class="input-group me-2 me-lg-3 fmxw-300">
                    <span class="input-group-text">
                        <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search tickets">
                </div>
                <select wire:model="statusFilter" class="form-select fmxw-200 d-none d-md-inline" aria-label="Status filter">
                    <option value="all">All</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <div class="col-3 col-lg-4 d-flex justify-content-end">
                <div class="btn-group">
                    <div class="dropdown me-1">
                        <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-1"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"></path>
                            </svg>
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pb-0">
                            <span class="small ps-3 fw-bold text-dark">Show</span>
                            <a class="dropdown-item d-flex align-items-center fw-bold" href="#" wire:click.prevent="$set('perPage', 10)">10</a>
                            <a class="dropdown-item fw-bold" href="#" wire:click.prevent="$set('perPage', 20)">20</a>
                            <a class="dropdown-item fw-bold rounded-bottom" href="#" wire:click.prevent="$set('perPage', 30)">30</a>
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
                <th class="border-bottom">
                    <div class="form-check dashboard-check">
                        <input class="form-check-input" type="checkbox" id="selectAllTickets" wire:click="$toggle('selectAll')">
                        <label class="form-check-label" for="selectAllTickets"></label>
                    </div>
                </th>
                <th class="border-bottom">Ticket Title</th>
                <th class="border-bottom">Ticket Type</th>
                <th class="border-bottom">Customer</th>
                <th class="border-bottom">Assigned Staff</th>
                <th class="border-bottom">Created</th>
                <th class="border-bottom">Status</th>
                <th class="border-bottom">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($tickets as $ticket)
            <tr>
                <td>
                    <div class="form-check dashboard-check">
                        <input class="form-check-input" type="checkbox" id="ticketCheck{{ $ticket->id }}" wire:model="selectedTickets" value="{{ $ticket->id }}">
                        <label class="form-check-label" for="ticketCheck{{ $ticket->id }}"></label>
                    </div>
                </td>
                <td>
                    <a href="{{ route('tickets.edit', $ticket) }}" class="fw-bold text-dark">
                        {{ $ticket->subject }}
                    </a>
                </td>

                <td>
                    <a href="{{ route('tickets.edit', $ticket) }}" class="fw-bold text-dark">
                        {{ $ticket->type }}
                    </a>
                </td>                <td>
                    <span class="fw-normal">{{ $ticket->customer->name ?? '-' }}</span>
                </td>
                <td>
                            <span class="fw-normal">
                                {{ $ticket->assignedTo->name ?? '-' }}
                            </span>
                </td>
                <td>
                    <span class="fw-normal">{{ $ticket->created_at->format('M d, Y') }}</span>
                </td>
                <td>
                    @php
                    $badgeClass = match($ticket->status) {
                    'open' => 'bg-warning',
                    'in_progress' => 'bg-info',
                    'resolved' => 'bg-success',
                    'closed' => 'bg-secondary',
                    default => 'bg-light text-dark'
                    };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                </td>
                <td>
                    <div class="dropdown">
                        <a href="#" class="btn btn-sm btn-gray-600 d-inline-flex align-items-center dropdown-toggle"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('tickets.show', $ticket->uuid) }}">View</a></li>
                            <li><a class="dropdown-item" href="{{ route('tickets.edit', $ticket) }}">Edit</a></li>
                            <li><a class="dropdown-item text-danger" href="#" wire:click.prevent="confirmDelete({{ $ticket->id }})">Delete</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No tickets found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>

        <div wire:ignore.self class="modal fade" id="deleteTicketModal" tabindex="-1" aria-labelledby="deleteTicketModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTicketModalLabel">Delete Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this ticket?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" wire:click="deleteTicket()" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $tickets->links() }}
        </div>
    </div>
</div>

<script>
    window.addEventListener('show-delete-modal', event => {
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteTicketModal'));
        deleteModal.show();
    });
    window.addEventListener('hide-delete-modal', event => {
        var deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteTicketModal'));
        deleteModal.hide();
    });
</script>
