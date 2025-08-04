@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-lg-flex">
                    <div>
                        <h5 class="mb-0">Location Management</h5>
                        <p class="text-sm mb-0">
                            Track and manage user locations from mobile app
                        </p>
                    </div>
                    <div class="ms-auto my-auto mt-lg-0 mt-4">
                        <div class="ms-auto my-auto">
                            <a href="{{ route('location.live.tracking') }}" class="btn bg-gradient-success btn-sm mb-0 me-2">
                                <i class="fas fa-broadcast-tower"></i>&nbsp;&nbsp;Live Tracking
                            </a>
                            <button type="button" class="btn bg-gradient-info btn-sm mb-0" onclick="refreshData()">
                                <i class="fas fa-sync"></i>&nbsp;&nbsp;Refresh
                            </button>
                            <button type="button" class="btn bg-gradient-warning btn-sm mb-0" onclick="cleanupOldData()">
                                <i class="fas fa-trash"></i>&nbsp;&nbsp;Cleanup Old Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pb-0">
                <div class="table-responsive">
                    <table class="table table-flush" id="users-table">
                        <thead class="thead-light">
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Total Locations</th>
                                <th>Last Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-gradient-info me-2">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm">{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-info" id="location-count-{{ $user->id }}">
                                        <i class="fas fa-spinner fa-spin"></i> Loading...
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm" id="last-location-{{ $user->id }}">
                                        <i class="fas fa-spinner fa-spin"></i> Loading...
                                    </span>
                                </td>
                                <td class="text-sm">
                                    <a href="{{ route('location.user.show', $user->id) }}" class="btn btn-link text-info p-0 mb-0">
                                        <i class="fas fa-map-marker-alt"></i> View Locations
                                    </a>
                                    <a href="{{ route('location.user.summary', $user->id) }}" class="btn btn-link text-success p-0 mb-0 ms-2">
                                        <i class="fas fa-chart-line"></i> Summary
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cleanup Modal -->
<div class="modal fade" id="cleanupModal" tabindex="-1" role="dialog" aria-labelledby="cleanupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cleanupModalLabel">Cleanup Old Location Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label">Keep data for how many days?</label>
                        <input type="number" class="form-control" id="cleanup-days" value="90" min="1" max="365">
                        <small class="form-text text-muted">
                            This will delete location records older than the specified number of days.
                        </small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="performCleanup()">
                    <i class="fas fa-trash"></i> Delete Old Data
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    loadUserLocationStats();
});

function loadUserLocationStats() {
    @foreach($users as $user)
    $.ajax({
        url: '/api/v1/locations/user/stats',
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + '{{ auth()->user()->createToken("location-stats")->plainTextToken }}',
            'Accept': 'application/json'
        },
        data: {
            user_id: {{ $user->id }}
        },
        success: function(response) {
            if (response.success) {
                const stats = response.data;
                $('#location-count-{{ $user->id }}').html(stats.total_locations || 0);
                
                if (stats.last_location) {
                    const lastDate = new Date(stats.last_location.location_timestamp);
                    $('#last-location-{{ $user->id }}').html(lastDate.toLocaleDateString() + ' ' + lastDate.toLocaleTimeString());
                } else {
                    $('#last-location-{{ $user->id }}').html('No data');
                }
            }
        },
        error: function() {
            $('#location-count-{{ $user->id }}').html('Error');
            $('#last-location-{{ $user->id }}').html('Error loading');
        }
    });
    @endforeach
}

function refreshData() {
    loadUserLocationStats();
    showToast('Data refreshed successfully', 'success');
}

function cleanupOldData() {
    $('#cleanupModal').modal('show');
}

function performCleanup() {
    const days = $('#cleanup-days').val();
    
    if (!days || days < 1) {
        showToast('Please enter a valid number of days', 'error');
        return;
    }
    
    $.ajax({
        url: '{{ route("location.cleanup") }}',
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            days: days
        },
        success: function(response) {
            if (response.success) {
                $('#cleanupModal').modal('hide');
                showToast(response.message, 'success');
                refreshData();
            }
        },
        error: function(xhr) {
            showToast('Error during cleanup: ' + xhr.responseJSON?.message || 'Unknown error', 'error');
        }
    });
}

function showToast(message, type) {
    // Assuming you have a toast notification system
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
