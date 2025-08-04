@extends('layouts.app')

@section('content')
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
            <li class="breadcrumb-item">
                <a href="{{ route('leads') }}">Leads</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $lead->lead_name }}</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">{{ $lead->lead_name }}</h1>
            <p class="mb-0">Lead details and information</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('leads.edit', $lead->uuid) }}" class="btn btn-warning d-inline-flex align-items-center me-2">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Lead
            </a>
            <a href="{{ route('leads') }}" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                </svg>
                Back to Leads
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Lead Overview -->
    <div class="col-12 col-lg-8">
        <div class="card border-0 shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lead Information</h5>
                <div>
                    @if($lead->status)
                        <span class="badge {{ $lead->status_badge_class }}">{{ $lead->status }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-gray-600 mb-1">Lead Name</h6>
                        <p class="mb-3">{{ $lead->lead_name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-gray-600 mb-1">Lead Owner</h6>
                        <p class="mb-3">
                            @if($lead->leadOwner)
                                {{ $lead->leadOwner->first_name }} {{ $lead->leadOwner->last_name }}
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </p>
                    </div>
                    
                    @if($lead->industry)
                    <div class="col-md-6">
                        <h6 class="text-gray-600 mb-1">Industry</h6>
                        <p class="mb-3">{{ $lead->industry }}</p>
                    </div>
                    @endif
                    
                    @if($lead->lead_source)
                    <div class="col-md-6">
                        <h6 class="text-gray-600 mb-1">Lead Source</h6>
                        <p class="mb-3">{{ $lead->lead_source }}</p>
                    </div>
                    @endif
                    
                    @if($lead->price_group)
                    <div class="col-md-6">
                        <h6 class="text-gray-600 mb-1">Price Group</h6>
                        <p class="mb-3">{{ $lead->price_group }}</p>
                    </div>
                    @endif
                    
                    @if($lead->collaborators)
                    <div class="col-12">
                        <h6 class="text-gray-600 mb-1">Collaborators</h6>
                        <p class="mb-3">{{ $lead->collaborators }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($lead->title || $lead->email)
                    <div class="col-md-6">
                        <h6 class="text-gray-600 mb-1">Contact Details</h6>
                        <p class="mb-3">
                            @if($lead->title){{ $lead->title }} @endif
                            @if($lead->email)
                                <br><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                            @endif
                        </p>
                    </div>
                    @endif
                    
                    @if($lead->address)
                    <div class="col-md-6">
                        <h6 class="text-gray-600 mb-1">Address</h6>
                        <p class="mb-3">
                            {{ $lead->address }}
                            @if($lead->country || $lead->pincode)
                                <br>
                                @if($lead->country){{ $lead->country }}@endif
                                @if($lead->pincode) - {{ $lead->pincode }}@endif
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Deal Information -->
        @if($lead->deal_title || $lead->deal_amount || $lead->deal_status)
        <div class="card border-0 shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Deal Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($lead->deal_title)
                    <div class="col-md-6">
                        <h6 class="text-gray-600 mb-1">Deal Title</h6>
                        <p class="mb-3">{{ $lead->deal_title }}</p>
                    </div>
                    @endif
                    
                    @if($lead->deal_amount)
                    <div class="col-md-3">
                        <h6 class="text-gray-600 mb-1">Deal Amount</h6>
                        <p class="mb-3 text-success fw-bold">${{ number_format($lead->deal_amount, 2) }}</p>
                    </div>
                    @endif
                    
                    @if($lead->deal_status)
                    <div class="col-md-3">
                        <h6 class="text-gray-600 mb-1">Deal Status</h6>
                        <p class="mb-3">
                            <span class="badge badge-primary">{{ $lead->deal_status }}</span>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Attachment -->
        @if($lead->file_url)
        <div class="card border-0 shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Attachment</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <svg class="icon icon-sm text-gray-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    <a href="{{ $lead->file_url }}" target="_blank" class="text-primary">
                        View Attachment
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-12 col-lg-4">
        <!-- Visit Information -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Visit Information</h5>
                @if($lead->visit_status)
                    <span class="badge {{ $lead->visit_status_badge_class }}">{{ $lead->visit_status }}</span>
                @endif
            </div>
            <div class="card-body">
                @if($lead->visit_started_at)
                <div class="mb-3">
                    <h6 class="text-gray-600 mb-1">Visit Started</h6>
                    <p class="mb-0">{{ $lead->visit_started_at->format('M d, Y g:i A') }}</p>
                </div>
                @endif
                
                @if($lead->visit_ended_at)
                <div class="mb-3">
                    <h6 class="text-gray-600 mb-1">Visit Ended</h6>
                    <p class="mb-0">{{ $lead->visit_ended_at->format('M d, Y g:i A') }}</p>
                </div>
                @endif
                
                @if($lead->visit_duration)
                <div class="mb-3">
                    <h6 class="text-gray-600 mb-1">Duration</h6>
                    <p class="mb-0">{{ $lead->visit_duration }} minutes</p>
                </div>
                @endif

                <!-- Visit Actions -->
                <div class="mt-3">
                    @if($lead->visit_status === 'Not Started')
                        <button class="btn btn-primary btn-sm w-100" onclick="startVisit()">Start Visit</button>
                    @elseif($lead->visit_status === 'Started' && !$lead->visit_ended_at)
                        <button class="btn btn-warning btn-sm w-100" onclick="endVisit()">End Visit</button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Info</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <h6 class="text-gray-600 mb-1">Created</h6>
                        <p class="mb-0 small">{{ $lead->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="col-6">
                        <h6 class="text-gray-600 mb-1">Updated</h6>
                        <p class="mb-0 small">{{ $lead->updated_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function startVisit() {
    if (confirm('Are you sure you want to start the visit?')) {
        fetch('{{ route("leads.start-visit", $lead->uuid) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to start visit');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}

function endVisit() {
    if (confirm('Are you sure you want to end the visit?')) {
        fetch('{{ route("leads.end-visit", $lead->uuid) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to end visit');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred');
        });
    }
}
</script>
@endpush
@endsection
