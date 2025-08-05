@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-lg-flex">
                    <div>
                        <h5 class="mb-0">Location Summary for {{ $user->name }}</h5>
                        <p class="text-sm mb-0">
                            Daily location summary from {{ $startDate }} to {{ $endDate }}
                        </p>
                    </div>
                    <div class="ms-auto my-auto mt-lg-0 mt-4">
                        <div class="ms-auto my-auto">
                            <input type="date" id="start-date" class="form-control form-control-sm d-inline-block w-auto" 
                                   value="{{ $startDate }}">
                            <span class="mx-2">to</span>
                            <input type="date" id="end-date" class="form-control form-control-sm d-inline-block w-auto" 
                                   value="{{ $endDate }}">
                            <button type="button" class="btn bg-gradient-info btn-sm mb-0 ms-2" onclick="updateDateRange()">
                                <i class="fas fa-sync"></i>&nbsp;&nbsp;Update
                            </button>
                            <a href="{{ route('location.user.show', $user->id) }}" class="btn bg-gradient-secondary btn-sm mb-0 ms-2">
                                <i class="fas fa-map-marker-alt"></i>&nbsp;&nbsp;View Locations
                            </a>
                            <a href="{{ route('location.index') }}" class="btn bg-gradient-secondary btn-sm mb-0 ms-2">
                                <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    <table class="table table-flush" id="summary-table">
                        <thead class="thead-light">
                            <tr>
                                <th>Date</th>
                                <th>Total Locations</th>
                                <th>First Location</th>
                                <th>Last Location</th>
                                <th>Work Duration</th>
                                <th>Unique Addresses</th>
                                <th>Avg Accuracy</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailySummaries as $summary)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ \Carbon\Carbon::parse($summary->date)->format('M d, Y') }}</h6>
                                        <span class="text-xs text-secondary">{{ \Carbon\Carbon::parse($summary->date)->format('l') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-info">{{ $summary->location_count }}</span>
                                </td>
                                <td>
                                    <span class="text-sm">
                                        {{ $summary->first_location ? \Carbon\Carbon::parse($summary->first_location)->format('H:i:s') : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm">
                                        {{ $summary->last_location ? \Carbon\Carbon::parse($summary->last_location)->format('H:i:s') : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    @if($summary->first_location && $summary->last_location)
                                        @php
                                            $start = \Carbon\Carbon::parse($summary->first_location);
                                            $end = \Carbon\Carbon::parse($summary->last_location);
                                            $duration = $start->diffInMinutes($end);
                                            $hours = floor($duration / 60);
                                            $minutes = $duration % 60;
                                        @endphp
                                        <span class="text-sm">{{ $hours }}h {{ $minutes }}m</span>
                                    @else
                                        <span class="text-sm text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-sm">{{ $summary->unique_addresses }}</span>
                                </td>
                                <td>
                                    <span class="text-sm">
                                        {{ $summary->avg_accuracy ? round($summary->avg_accuracy, 1) . 'm' : 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-sm">
                                    <a href="{{ route('location.user.show', ['userId' => $user->id, 'date' => $summary->date]) }}" 
                                       class="btn btn-link text-info p-0 mb-0">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <a href="{{ route('location.user.export', ['userId' => $user->id, 'date' => $summary->date]) }}" 
                                       class="btn btn-link text-success p-0 mb-0 ms-2">
                                        <i class="fas fa-download"></i> Export
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                                    <h6 class="text-muted mt-2">No location data found for the selected date range</h6>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics Card -->
@if($dailySummaries->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Summary Statistics</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card bg-gradient-info">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold opacity-7">Total Days</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ $dailySummaries->count() }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                            <i class="fas fa-calendar text-dark text-lg opacity-10"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card bg-gradient-success">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold opacity-7">Total Locations</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ $dailySummaries->sum('location_count') }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                            <i class="fas fa-map-marker-alt text-dark text-lg opacity-10"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card bg-gradient-warning">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold opacity-7">Avg Locations/Day</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ round($dailySummaries->avg('location_count'), 1) }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                            <i class="fas fa-chart-line text-dark text-lg opacity-10"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card bg-gradient-danger">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold opacity-7">Avg Accuracy</p>
                                            <h5 class="text-white font-weight-bolder">
                                                {{ round($dailySummaries->avg('avg_accuracy'), 1) }}m
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                            <i class="fas fa-crosshairs text-dark text-lg opacity-10"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#summary-table').DataTable({
        "order": [[ 0, "desc" ]],
        "responsive": true,
        "language": {
            "paginate": {
                "previous": '<i class="fas fa-angle-left"></i>',
                "next": '<i class="fas fa-angle-right"></i>'
            }
        }
    });
});

function updateDateRange() {
    const startDate = $('#start-date').val();
    const endDate = $('#end-date').val();
    
    if (!startDate || !endDate) {
        showToast('Please select both start and end dates', 'error');
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        showToast('Start date cannot be later than end date', 'error');
        return;
    }
    
    const url = new URL(window.location);
    url.searchParams.set('start_date', startDate);
    url.searchParams.set('end_date', endDate);
    window.location.href = url.toString();
}

function showToast(message, type) {
    if (typeof notyf !== 'undefined') {
        if (type === 'success') {
            notyf.success(message);
        } else {
            notyf.error(message);
        }
    } else {
        alert(message);
    }
}
</script>
@endpush
