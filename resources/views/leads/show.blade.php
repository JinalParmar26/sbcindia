@extends('layouts.app')

@section('content')
<div class="py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('leads') }}">Leads</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $lead->name }}</li>
        </ol>
    </nav>
    
    <!-- Page Header -->
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">{{ $lead->name }}</h1>
            <p class="mb-0">Lead details and information</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <!-- <a href="{{ route('leads.edit', $lead->uuid) }}" class="btn btn-warning d-inline-flex align-items-center me-2">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Lead
            </a> -->
            <a href="{{ route('leads') }}" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                </svg>
                Back to Leads
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-12 col-lg-8">
        <!-- Lead Info -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lead Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-gray-600 mb-1">Name</h6>
                        <p class="mb-0">{{ $lead->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-gray-600 mb-1">Lead Owner</h6>
                        <p class="mb-0">{{ $lead->user->name ?? '-' }}</p>
                    </div>
                    @if($lead->company_name)
                    <div class="col-md-6 mb-3">
                        <h6 class="text-gray-600 mb-1">Company</h6>
                        <p class="mb-0">{{ $lead->company_name }}</p>
                    </div>
                    @endif
                    @if($lead->source)
                    <div class="col-md-6 mb-3">
                        <h6 class="text-gray-600 mb-1">Source</h6>
                        <p class="mb-0">{{ $lead->source }}</p>
                    </div>
                    @endif
                    @if($lead->industry)
                    <div class="col-md-6 mb-3">
                        <h6 class="text-gray-600 mb-1">Industry</h6>
                        <p class="mb-0">{{ $lead->industry }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header"><h5 class="mb-0">Contact Information</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-gray-600 mb-1">Email</h6>
                        <p class="mb-0">
                            @if($lead->email)
                                <a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h6 class="text-gray-600 mb-1">Contact</h6>
                        <p class="mb-0">{{ $lead->contact ?? '-' }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h6 class="text-gray-600 mb-1">WhatsApp</h6>
                        <p class="mb-0">{{ $lead->whatsapp_number ?? '-' }}</p>
                    </div>
                    <div class="col-12">
                        <h6 class="text-gray-600 mb-1">Address</h6>
                        <p class="mb-0">
                            {{ $lead->address ?? '-' }}
                            @php
                                $parts = array_filter([
                                    $lead->area ?? null,
                                    $lead->city ?? null,
                                    $lead->state ?? null,
                                    $lead->country ?? null,
                                ]);
                            @endphp
                            @if(!empty($parts))
                                <br>{{ implode(', ', $parts) }}
                            @endif
                            @if($lead->pincode)
                                <br><strong>Pincode:</strong> {{ $lead->pincode }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visit Logs -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header"><h5 class="mb-0">Visit Logs</h5></div>
            <div class="card-body">
                @forelse($lead->visitLogs as $log)
                    <div class="border rounded p-3 mb-3">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <strong>Date:</strong> {{ $log->visit_date }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Type:</strong> {{ $log->lead_type }}
                            </div>
                            <div class="col-md-4 mb-2">
                                <strong>Rating:</strong> {{ $log->rating }}
                            </div>
                        </div>

                        @if($log->notes)
                            <p class="mb-2"><strong>Notes:</strong> {{ $log->notes }}</p>
                        @endif
                        @if($log->presented_products)
                            <p class="mb-2"><strong>Presented Products:</strong> {{ $log->presented_products }}</p>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong>Visit Started:</strong>
                                    {{ $log->visit_start_time ?? '-' }}
                                    @if($log->visit_start_location_name)
                                        ({{ $log->visit_start_location_name }})
                                    @endif
                                    @if($log->visit_start_latitude && $log->visit_start_longitude)
                                        <br><small>Lat/Lng: {{ $log->visit_start_latitude }}, {{ $log->visit_start_longitude }}</small>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong>Visit Ended:</strong>
                                    {{ $log->visit_end_time ?? '-' }}
                                    @if($log->visit_end_location_name)
                                        ({{ $log->visit_end_location_name }})
                                    @endif
                                    @if($log->visit_end_latitude && $log->visit_end_longitude)
                                        <br><small>Lat/Lng: {{ $log->visit_end_latitude }}, {{ $log->visit_end_longitude }}</small>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="mt-2">
                            <strong>Images:</strong><br>
                            @forelse($log->images as $img)
                                <img
                                    src="{{ asset('storage/' . $img->image) }}"
                                    alt="Visit Image"
                                    class="img-thumbnail m-1"
                                    style="max-width: 140px;"
                                >
                            @empty
                                <p class="text-muted mb-0">No images uploaded.</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No visit logs available.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-12 col-lg-4">
        <!-- Quick Info -->
        <div class="card border-0 shadow mb-4">
            <div class="card-header"><h5 class="mb-0">Quick Info</h5></div>
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
                <br>
                <div class="row">
                    <div class="col-12">
                        <a href="{{ route('lead.single.pdf', $lead->uuid) }}" class="btn btn-outline-primary me-2" target="_blank">
                            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Lead
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- (Optional) Visit Actions card — keep UI spot, but hidden if no routes -->
        @php
            $hasStart = \Illuminate\Support\Facades\Route::has('leads.start-visit');
            $hasEnd   = \Illuminate\Support\Facades\Route::has('leads.end-visit');
        @endphp
        @if($hasStart || $hasEnd)
        <div class="card border-0 shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Visit Actions</h5>
            </div>
            <div class="card-body">
                @if($hasStart)
                    <button class="btn btn-primary btn-sm w-100 mb-2" onclick="startVisit()">Start Visit</button>
                @endif
                @if($hasEnd)
                    <button class="btn btn-warning btn-sm w-100" onclick="endVisit()">End Visit</button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Safe route URLs (won't break if not defined)
const START_VISIT_URL = @json(\Illuminate\Support\Facades\Route::has('leads.start-visit') ? route('leads.start-visit', $lead->uuid) : null);
const END_VISIT_URL   = @json(\Illuminate\Support\Facades\Route::has('leads.end-visit')   ? route('leads.end-visit',   $lead->uuid) : null);

// CSRF token (fallback to blade token if meta not present)
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || @json(csrf_token());

function startVisit() {
    if (!START_VISIT_URL) return alert('Start visit is not available on this installation.');
    if (!confirm('Are you sure you want to start the visit?')) return;

    fetch(START_VISIT_URL, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) location.reload();
        else alert(d.message || 'Failed to start visit');
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred');
    });
}

function endVisit() {
    if (!END_VISIT_URL) return alert('End visit is not available on this installation.');
    if (!confirm('Are you sure you want to end the visit?')) return;

    fetch(END_VISIT_URL, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) location.reload();
        else alert(d.message || 'Failed to end visit');
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred');
    });
}
</script>
@endpush
@endsection
