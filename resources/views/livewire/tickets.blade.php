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
            <div class="btn-group me-2">
                <button id="exportTicketsBtn" class="btn btn-sm btn-outline-gray-600 d-inline-flex align-items-center">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
                <button id="exportTicketsPdfBtn" class="btn btn-sm btn-outline-gray-600 d-inline-flex align-items-center">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </button>
            </div>
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
        <div class="row gy-2 gx-3 align-items-center">
            <!-- Search box -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="input-group">
                <span class="input-group-text">
                    <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                              clip-rule="evenodd"></path>
                    </svg>
                </span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search tickets">
                </div>
            </div>

            <!-- Customer Filter -->
            <div class="col-6 col-md-3 col-lg-2">
                <select wire:model="customerFilter" class="form-select">
                    <option value="all">All Customers</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Staff Filter -->
            <div class="col-6 col-md-3 col-lg-2">
                <select wire:model="assignedStaffFilter" class="form-select">
                    <option value="all">All Staff</option>
                    @foreach($staffList as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year Filter -->
            <div class="col-6 col-md-3 col-lg-2">
                <select wire:model="yearFilter" class="form-select">
                    <option value="all">All Years</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Month Filter -->
            <div class="col-6 col-md-3 col-lg-2">
                <select wire:model="monthFilter" class="form-select">
                    <option value="all">All Months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
            </div>

            <!-- Pagination dropdown -->
            <div class="col-12 col-md-auto ms-auto text-end">
                <div class="dropdown">
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
                        <a class="dropdown-item fw-bold" href="#" wire:click.prevent="$set('perPage', 10)">10</a>
                        <a class="dropdown-item fw-bold" href="#" wire:click.prevent="$set('perPage', 20)">20</a>
                        <a class="dropdown-item fw-bold rounded-bottom" href="#" wire:click.prevent="$set('perPage', 30)">30</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table user-table table-hover align-items-center">
            <thead>
            <tr>
{{--                <th class="border-bottom">--}}
{{--                    <div class="form-check dashboard-check">--}}
{{--                        <input class="form-check-input" type="checkbox" id="selectAllTickets" wire:click="$toggle('selectAll')">--}}
{{--                        <label class="form-check-label" for="selectAllTickets"></label>--}}
{{--                    </div>--}}
{{--                </th>--}}
                <th wire:click="sortBy('subject')" style="cursor: pointer;">
                    Subject
                    @if ($sortField === 'subject')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>
                <th wire:click="sortBy('customer_name')" style="cursor: pointer;">
                    Customer
                    @if ($sortField === 'customer_name')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>

                <th wire:click="sortBy('assigned_staff_name')" style="cursor: pointer;">
                    Assigned Staff
                    @if ($sortField === 'assigned_staff_name')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>


                <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                    Created At
                    @if ($sortField === 'created_at')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>

                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($tickets as $ticket)
            <tr>
{{--                <td>--}}
{{--                    <div class="form-check dashboard-check">--}}
{{--                        <input class="form-check-input" type="checkbox" id="ticketCheck{{ $ticket->id }}" wire:model="selectedTickets" value="{{ $ticket->id }}">--}}
{{--                        <label class="form-check-label" for="ticketCheck{{ $ticket->id }}"></label>--}}
{{--                    </div>--}}
{{--                </td>--}}
                <td>
                    <a href="{{ route('tickets.edit', $ticket) }}" class="fw-bold text-dark">
                        {{ $ticket->subject }}
                    </a>
                </td>
                <td>
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

    // Export functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('exportTicketsBtn').addEventListener('click', function() {
            // Build query parameters from current filters
            const params = new URLSearchParams();
            
            // Get filter values from the Livewire component
            const searchInput = document.querySelector('input[wire\\:model\\.debounce\\.300ms="search"]');
            const customerSelect = document.querySelector('select[wire\\:model="customerFilter"]');
            const staffSelect = document.querySelector('select[wire\\:model="assignedStaffFilter"]');
            const yearSelect = document.querySelector('select[wire\\:model="yearFilter"]');
            const monthSelect = document.querySelector('select[wire\\:model="monthFilter"]');
            
            if (searchInput && searchInput.value) {
                params.append('search', searchInput.value);
            }
            if (customerSelect && customerSelect.value && customerSelect.value !== 'all') {
                params.append('customerFilter', customerSelect.value);
            }
            if (staffSelect && staffSelect.value && staffSelect.value !== 'all') {
                params.append('assignedStaffFilter', staffSelect.value);
            }
            if (yearSelect && yearSelect.value && yearSelect.value !== 'all') {
                params.append('yearFilter', yearSelect.value);
            }
            if (monthSelect && monthSelect.value && monthSelect.value !== 'all') {
                params.append('monthFilter', monthSelect.value);
            }
            
            // Create download URL
            const exportUrl = '{{ route("tickets.export") }}' + (params.toString() ? '?' + params.toString() : '');
            
            // Create temporary link and trigger download
            const link = document.createElement('a');
            link.href = exportUrl;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        // PDF Export functionality
        document.getElementById('exportTicketsPdfBtn').addEventListener('click', function() {
            // Build query parameters from current filters
            const params = new URLSearchParams();
            
            // Get filter values from the Livewire component
            const searchInput = document.querySelector('input[wire\\:model\\.debounce\\.300ms="search"]');
            const customerSelect = document.querySelector('select[wire\\:model="customerFilter"]');
            const staffSelect = document.querySelector('select[wire\\:model="assignedStaffFilter"]');
            const yearSelect = document.querySelector('select[wire\\:model="yearFilter"]');
            const monthSelect = document.querySelector('select[wire\\:model="monthFilter"]');
            
            if (searchInput && searchInput.value) {
                params.append('search', searchInput.value);
            }
            if (customerSelect && customerSelect.value && customerSelect.value !== 'all') {
                params.append('customerFilter', customerSelect.value);
            }
            if (staffSelect && staffSelect.value && staffSelect.value !== 'all') {
                params.append('assignedStaffFilter', staffSelect.value);
            }
            if (yearSelect && yearSelect.value && yearSelect.value !== 'all') {
                params.append('yearFilter', yearSelect.value);
            }
            if (monthSelect && monthSelect.value && monthSelect.value !== 'all') {
                params.append('monthFilter', monthSelect.value);
            }
            
            // Create download URL
            const exportUrl = '{{ route("tickets.export.pdf") }}' + (params.toString() ? '?' + params.toString() : '');
            
            // Open PDF in new window
            window.open(exportUrl, '_blank');
        });
    });
</script>
