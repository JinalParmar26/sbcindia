<div>
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Leads</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Leads Management</h1>
            <p class="mb-0">Manage your sales leads and track their progress</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('leads.create') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Lead
            </a>
            <div class="btn-group ms-2 ms-lg-3" role="group">
                <button type="button" class="btn btn-outline-gray-600" onclick="window.location.href='{{ route('leads.export.csv') }}'">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-3">
                <input type="text" 
                       wire:model="search" 
                       class="form-control" 
                       placeholder="Search leads..."
                       wire:keydown.enter="$refresh">
            </div>
            <div class="col-md-2">
                <select wire:model="statusFilter" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select wire:model="leadOwnerFilter" class="form-select">
                    <option value="">All Owners</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select wire:model="visitStatusFilter" class="form-select">
                    <option value="">All Visit Statuses</option>
                    @foreach($visitStatuses as $visitStatus)
                        <option value="{{ $visitStatus }}">{{ $visitStatus }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select wire:model="perPage" class="form-select">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
            </div>
            <div class="col-md-1">
                <button wire:click="$refresh" class="btn btn-outline-primary">
                    <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-centered table-nowrap mb-0 rounded">
                <thead class="thead-light">
                    <tr>
                        <th class="border-0 rounded-start">Lead Name</th>
                        <th class="border-0">Lead Owner</th>
                        <th class="border-0">Status</th>
                        <th class="border-0">Industry</th>
                        <th class="border-0">Email</th>
                        <th class="border-0">Visit Status</th>
                        <th class="border-0">Deal Amount</th>
                        <th class="border-0">Created</th>
                        <th class="border-0 rounded-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leads as $lead)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h6 class="text-sm">{{ $lead->lead_name }}</h6>
                                        @if($lead->deal_title)
                                            <p class="text-xs text-gray-600 mb-0">{{ $lead->deal_title }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($lead->leadOwner)
                                    <span class="fw-normal">{{ $lead->leadOwner->first_name }} {{ $lead->leadOwner->last_name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($lead->status)
                                    <span class="badge {{ $lead->status_badge_class }}">{{ $lead->status }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-normal">{{ $lead->industry ?? '-' }}</span>
                            </td>
                            <td>
                                @if($lead->email)
                                    <a href="mailto:{{ $lead->email }}" class="fw-normal">{{ $lead->email }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($lead->visit_status)
                                    <span class="badge {{ $lead->visit_status_badge_class }}">{{ $lead->visit_status }}</span>
                                    @if($lead->is_visit_ongoing)
                                        <small class="text-warning d-block">
                                            Started: {{ $lead->visit_started_at->format('g:i A') }}
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($lead->deal_amount)
                                    <span class="fw-bold text-success">${{ number_format($lead->deal_amount, 2) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-normal">{{ $lead->created_at->format('M d, Y') }}</span>
                                <small class="text-gray-600 d-block">{{ $lead->created_at->format('g:i A') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('leads.show', $lead->uuid) }}" 
                                       class="btn btn-link text-dark" 
                                       title="View">
                                        <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('leads.edit', $lead->uuid) }}" 
                                       class="btn btn-link text-warning" 
                                       title="Edit">
                                        <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <button wire:click="deleteLead('{{ $lead->uuid }}')" 
                                            class="btn btn-link text-danger" 
                                            title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this lead?')">
                                        <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <svg class="icon icon-xl text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h6 class="text-gray-600 mb-2">No leads found</h6>
                                    <p class="text-sm text-gray-400 mb-3">Get started by creating your first lead</p>
                                    <a href="{{ route('leads.create') }}" class="btn btn-sm btn-primary">Create Lead</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($leads->hasPages())
            <div class="card-footer px-3 border-0 d-flex flex-column flex-lg-row align-items-center justify-content-between">
                <nav aria-label="Page navigation">
                    {{ $leads->links() }}
                </nav>
            </div>
        @endif
    </div>
</div>
</div>
