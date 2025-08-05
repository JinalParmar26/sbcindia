@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
    <div class="d-block mb-4 mb-md-0">
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="#">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Live Location Tracking</li>
            </ol>
        </nav>
        <h2 class="h4">Live Location Tracking</h2>
        <p class="mb-0">Monitor real-time locations of staff members for {{ date('F d, Y') }}</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <input type="date" id="date-picker" class="form-control form-control-sm" value="{{ date('Y-m-d') }}">
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-gray-600 d-inline-flex align-items-center" onclick="refreshLiveData()">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
            <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center" onclick="toggleAutoRefresh()">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="auto-refresh-text">Auto-refresh</span>
            </button>
        </div>
    </div>
</div>

<!-- Status Indicators -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body py-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="badge bg-success">{{ $users->count() }} Active Staff</span>
                    </div>
                    <div class="col-auto">
                        <small class="text-muted">Last updated: <span id="last-refresh-time">{{ date('H:i:s') }}</span></small>
                    </div>
                    <div class="col-auto">
                        <div id="auto-refresh-status" class="badge bg-secondary">Auto-refresh: Off</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Map Container -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header bg-primary text-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">
                            <i class="fas fa-map-marked-alt me-2"></i>Live Location Map
                        </h5>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-light btn-sm" onclick="centerMapOnAllStaff()">
                                <i class="fas fa-expand-arrows-alt"></i> Fit All
                            </button>
                            <button type="button" class="btn btn-light btn-sm" onclick="clearMapTrails()">
                                <i class="fas fa-eraser"></i> Clear Trails
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="live-map" style="height: 500px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Staff List Panel -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Staff with Location Data Today</h5>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="refreshStaffList()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="toggleAutoRefresh()">
                                <i class="fas fa-play"></i> <span id="auto-refresh-text-2">Start Auto-refresh</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="row" id="staff-list">
                        @foreach($users as $user)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card staff-card h-100" data-user-id="{{ $user->id }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-primary text-white me-3">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div class="flex-fill">
                                                <h6 class="mb-1">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->email }}</small>
                                                <div class="mt-2">
                                                    <span class="badge bg-success status-badge">Active Today</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3 d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary" onclick="focusOnUser({{ $user->id }})">
                                                <i class="fas fa-map-marker-alt"></i> View on Map
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="showUserTrail({{ $user->id }})">
                                                <i class="fas fa-route"></i> Trail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Location Data Available</h5>
                        <p class="text-muted mb-4">No staff members have shared their location today.</p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle"></i> How to get location data:
                                    </h6>
                                    <ul class="mb-0 text-start">
                                        <li>Staff must have the mobile app installed</li>
                                        <li>Location services must be enabled</li>
                                        <li>Staff must be checked in for today</li>
                                        <li>Location sharing must be enabled in app settings</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="checkForNewData()">
                            <i class="fas fa-sync-alt"></i> Check for New Data
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Panel -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <button type="button" class="btn btn-outline-primary w-100" onclick="exportLocationData()">
                            <i class="fas fa-download"></i><br>
                            <small>Export Data</small>
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="button" class="btn btn-outline-info w-100" onclick="toggleTrails()">
                            <i class="fas fa-route"></i><br>
                            <small>Show All Trails</small>
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="button" class="btn btn-outline-success w-100" onclick="centerMapOnAllStaff()">
                            <i class="fas fa-expand-arrows-alt"></i><br>
                            <small>Center Map</small>
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button type="button" class="btn btn-outline-warning w-100" onclick="viewFullHistory()">
                            <i class="fas fa-history"></i><br>
                            <small>View History</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar {
    width: 40px;
    height: 40px;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.staff-card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.status-badge {
    min-width: 80px;
    text-align: center;
}

.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    line-height: 1.25;
    border-radius: 0.25rem;
}

#live-map {
    border-radius: 0 0 8px 8px;
}
</style>
@endpush

@push('scripts')
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXi6lk-Tey8r9YjTyxBPYgbXx44sqrhgU&callback=initLiveMap"></script>
<script>
let liveMap;
let staffMarkers = [];
let trailPolylines = [];
let autoRefreshInterval;
let isAutoRefreshActive = false;
let selectedStaffId = null;

function initLiveMap() {
    // Initialize map
    liveMap = new google.maps.Map(document.getElementById('live-map'), {
        center: { lat: 23.0225, lng: 72.5714 }, // Default to Ahmedabad
        zoom: 12,
        mapTypeId: 'roadmap'
    });

    // Load initial staff data
    loadStaffLocations();
}

function loadStaffLocations() {
    const selectedDate = document.getElementById('date-picker').value;
    
    fetch(`{{ route('staff.locations.data') }}?date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            updateMapWithStaffData(data.users || []);
            updateLastRefreshTime();
        })
        .catch(error => {
            console.error('Error loading staff locations:', error);
            showAlert('Failed to load location data', 'danger');
        });
}

function updateMapWithStaffData(users) {
    // Clear existing markers
    staffMarkers.forEach(marker => marker.setMap(null));
    staffMarkers = [];

    if (users.length === 0) {
        showAlert('No location data available for the selected date', 'info');
        return;
    }

    users.forEach(user => {
        if (user.latest_location) {
            const marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(user.latest_location.latitude),
                    lng: parseFloat(user.latest_location.longitude)
                },
                map: liveMap,
                title: user.name,
                icon: {
                    url: 'data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="%23007bff"><circle cx="12" cy="12" r="10" fill="%23007bff"/><text x="12" y="16" text-anchor="middle" fill="white" font-size="8">' + user.name.charAt(0).toUpperCase() + '</text></svg>',
                    scaledSize: new google.maps.Size(30, 30),
                    anchor: new google.maps.Point(15, 15)
                }
            });

            // Add info window
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="padding: 5px;">
                        <h6 style="margin: 0 0 5px 0;">${user.name}</h6>
                        <p style="margin: 0; font-size: 12px;">Last seen: ${new Date(user.latest_location.location_timestamp).toLocaleString()}</p>
                        <p style="margin: 0; font-size: 12px;">Accuracy: ${user.latest_location.accuracy || 'N/A'}m</p>
                        <button class="btn btn-sm btn-primary mt-2" onclick="showUserTrail(${user.id})">View Trail</button>
                    </div>
                `
            });

            marker.addListener('click', () => {
                infoWindow.open(liveMap, marker);
            });

            staffMarkers.push(marker);
        }
    });

    // Auto-fit map to show all markers
    if (staffMarkers.length > 0) {
        const bounds = new google.maps.LatLngBounds();
        staffMarkers.forEach(marker => bounds.extend(marker.getPosition()));
        liveMap.fitBounds(bounds);
    }
}

function refreshLiveData() {
    loadStaffLocations();
    showAlert('Location data refreshed', 'success');
}

function toggleAutoRefresh() {
    if (isAutoRefreshActive) {
        clearInterval(autoRefreshInterval);
        isAutoRefreshActive = false;
        document.getElementById('auto-refresh-text').textContent = 'Auto-refresh';
        document.getElementById('auto-refresh-text-2').textContent = 'Start Auto-refresh';
        document.getElementById('auto-refresh-status').textContent = 'Auto-refresh: Off';
        document.getElementById('auto-refresh-status').className = 'badge bg-secondary';
        showAlert('Auto-refresh stopped', 'info');
    } else {
        autoRefreshInterval = setInterval(() => {
            loadStaffLocations();
        }, 30000); // 30 seconds
        isAutoRefreshActive = true;
        document.getElementById('auto-refresh-text').textContent = 'Stop Auto-refresh';
        document.getElementById('auto-refresh-text-2').textContent = 'Stop Auto-refresh';
        document.getElementById('auto-refresh-status').textContent = 'Auto-refresh: On (30s)';
        document.getElementById('auto-refresh-status').className = 'badge bg-success';
        showAlert('Auto-refresh started (30s intervals)', 'success');
    }
}

function focusOnUser(userId) {
    const marker = staffMarkers.find(m => m.getTitle().includes('User ' + userId));
    if (marker) {
        liveMap.setCenter(marker.getPosition());
        liveMap.setZoom(15);
        google.maps.event.trigger(marker, 'click');
    }
}

function showUserTrail(userId) {
    const selectedDate = document.getElementById('date-picker').value;
    
    fetch(`{{ url('staff/locations') }}/${userId}/trail?date=${selectedDate}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.locations.length > 0) {
                drawUserTrail(data.locations, data.user.name);
            } else {
                showAlert('No trail data available for this user', 'info');
            }
        })
        .catch(error => {
            console.error('Error loading user trail:', error);
            showAlert('Failed to load trail data', 'danger');
        });
}

function drawUserTrail(locations, userName) {
    // Clear existing trails
    clearMapTrails();

    if (locations.length < 2) {
        showAlert('Not enough location points to draw a trail', 'info');
        return;
    }

    const path = locations.map(location => ({
        lat: parseFloat(location.latitude),
        lng: parseFloat(location.longitude)
    }));

    const polyline = new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: '#FF0000',
        strokeOpacity: 1.0,
        strokeWeight: 3
    });

    polyline.setMap(liveMap);
    trailPolylines.push(polyline);

    // Fit map to show the trail
    const bounds = new google.maps.LatLngBounds();
    path.forEach(point => bounds.extend(point));
    liveMap.fitBounds(bounds);

    showAlert(`Trail for ${userName} displayed`, 'success');
}

function clearMapTrails() {
    trailPolylines.forEach(polyline => polyline.setMap(null));
    trailPolylines = [];
}

function centerMapOnAllStaff() {
    if (staffMarkers.length > 0) {
        const bounds = new google.maps.LatLngBounds();
        staffMarkers.forEach(marker => bounds.extend(marker.getPosition()));
        liveMap.fitBounds(bounds);
    }
}

function refreshStaffList() {
    loadStaffLocations();
}

function checkForNewData() {
    loadStaffLocations();
}

function updateLastRefreshTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString();
    document.getElementById('last-refresh-time').textContent = timeString;
}

// Date picker change handler
document.addEventListener('DOMContentLoaded', function() {
    const datePicker = document.getElementById('date-picker');
    if (datePicker) {
        datePicker.addEventListener('change', function() {
            loadStaffLocations();
        });
    }
});

// Show alert messages
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

function exportLocationData() {
    showAlert('Export feature coming soon!', 'info');
}

function toggleTrails() {
    showAlert('Trail visualization feature coming soon!', 'info');
}

function viewFullHistory() {
    showAlert('Full history view feature coming soon!', 'info');
}
</script>
@endpush
