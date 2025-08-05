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
            <button type="button" class="btn btn-sm btn-outline-gray-600 d-inline-flex align-items-center" onclick="toggleAutoRefresh()">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-9-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="auto-refresh-text">Auto Refresh</span>
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="icon-shape icon-md bg-success text-white rounded-circle mb-3 mx-auto">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="h4 mb-1" id="active-staff-count">{{ $users ? $users->count() : 0 }}</h3>
                <p class="text-muted mb-0">Active Staff Today</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="icon-shape icon-md bg-info text-white rounded-circle mb-3 mx-auto">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="h4 mb-1" id="total-locations-count">Loading...</h3>
                <p class="text-muted mb-0">Total Locations</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="icon-shape icon-md bg-warning text-white rounded-circle mb-3 mx-auto">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <h3 class="h4 mb-1" id="last-update-time">{{ date('H:i:s') }}</h3>
                <p class="text-muted mb-0">Last Update</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="icon-shape icon-md bg-primary text-white rounded-circle mb-3 mx-auto">
                    <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                </div>
                <h3 class="h4 mb-1" id="online-staff-count">0</h3>
                <p class="text-muted mb-0">Online Staff</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Map Section -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="h5">Live Map View</h2>
                        <p class="mb-0">Real-time staff locations on map</p>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="centerMapOnAll()">
                                <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Center All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleTrails()">
                                <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"></path>
                                </svg>
                                Toggle Trails
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

    <!-- Staff List Section -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="h5">Staff List</h2>
                        <p class="mb-0">Active staff with location data</p>
                    </div>
                </div>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                <div id="staff-list">
                    @if($users && $users->count() > 0)
                        @foreach($users as $user)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="avatar rounded-circle me-3 bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <p class="mb-0 text-muted small">{{ $user->email }}</p>
                                    <span class="badge bg-success small">Active Today</span>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewStaffDetails({{ $user->id }})">
                                        <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <svg class="icon icon-lg mb-3 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h6 class="text-muted">No staff with location data today</h6>
                            <p class="text-muted mb-0">Staff location data will appear here when available.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staff Details Modal -->
<div class="modal fade" id="staffDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staffDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffDetailsModalLabel">Staff Location Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="staffDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading staff details...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="viewFullHistory()">View Full History</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXi6lk-Tey8r9YjTyxBPYgbXx44sqrhgU&callback=initLiveMap"></script>
<script>
let liveMap;
let staffMarkers = [];
let autoRefreshInterval;
let isAutoRefreshActive = false;

// Initialize Google Maps
function initLiveMap() {
    liveMap = new google.maps.Map(document.getElementById('live-map'), {
        center: { lat: 23.0225, lng: 72.5714 }, // Default to Ahmedabad, India
        zoom: 12,
        mapTypeId: 'roadmap'
    });

    // Load initial data
    refreshLiveData();
}

// Refresh live location data
function refreshLiveData() {
    const date = document.getElementById('date-picker').value;
    
    // Update last update time
    document.getElementById('last-update-time').textContent = new Date().toLocaleTimeString();
    
    // Fetch live data via AJAX
    fetch(`{{ route('location.live.data') }}?date=${date}`)
        .then(response => response.json())
        .then(data => {
            updateMapMarkers(data.users || []);
            updateStatistics(data);
        })
        .catch(error => {
            console.error('Error fetching live data:', error);
            showAlert('Error loading location data', 'danger');
        });
}

// Update map markers
function updateMapMarkers(users) {
    // Clear existing markers
    staffMarkers.forEach(marker => marker.setMap(null));
    staffMarkers = [];

    // Add new markers
    users.forEach(user => {
        if (user.locations && user.locations.length > 0) {
            const location = user.locations[0]; // Latest location
            
            const marker = new google.maps.Marker({
                position: { 
                    lat: parseFloat(location.latitude), 
                    lng: parseFloat(location.longitude) 
                },
                map: liveMap,
                title: user.name,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 8,
                    fillColor: '#007bff',
                    fillOpacity: 0.8,
                    strokeColor: '#ffffff',
                    strokeWeight: 2
                }
            });

            // Add info window
            const infoWindow = new google.maps.InfoWindow({
                content: `
                    <div style="min-width: 200px;">
                        <h6 class="mb-2">${user.name}</h6>
                        <p class="mb-1"><strong>Last Update:</strong> ${new Date(location.location_timestamp).toLocaleString()}</p>
                        <p class="mb-1"><strong>Accuracy:</strong> ${location.accuracy || 'N/A'} meters</p>
                        <p class="mb-0"><strong>Address:</strong> ${location.address || 'Getting address...'}</p>
                    </div>
                `
            });

            marker.addListener('click', () => {
                infoWindow.open(liveMap, marker);
            });

            staffMarkers.push(marker);
        }
    });

    // Center map on all markers if any exist
    if (staffMarkers.length > 0) {
        centerMapOnAll();
    }
}

// Update statistics
function updateStatistics(data) {
    document.getElementById('total-locations-count').textContent = data.total_locations || 0;
    document.getElementById('online-staff-count').textContent = data.online_staff || 0;
}

// Center map on all markers
function centerMapOnAll() {
    if (staffMarkers.length === 0) return;

    const bounds = new google.maps.LatLngBounds();
    staffMarkers.forEach(marker => {
        bounds.extend(marker.getPosition());
    });
    
    liveMap.fitBounds(bounds);
    
    if (staffMarkers.length === 1) {
        liveMap.setZoom(15);
    }
}

// Toggle auto refresh
function toggleAutoRefresh() {
    if (isAutoRefreshActive) {
        clearInterval(autoRefreshInterval);
        isAutoRefreshActive = false;
        document.getElementById('auto-refresh-text').textContent = 'Auto Refresh';
    } else {
        autoRefreshInterval = setInterval(refreshLiveData, 30000); // Refresh every 30 seconds
        isAutoRefreshActive = true;
        document.getElementById('auto-refresh-text').textContent = 'Stop Auto Refresh';
    }
}

// View staff details
function viewStaffDetails(userId) {
    const modal = new bootstrap.Modal(document.getElementById('staffDetailsModal'));
    
    // Show loading content
    document.getElementById('staffDetailsContent').innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading staff details...</p>
        </div>
    `;
    
    modal.show();
    
    // Fetch staff details
    const date = document.getElementById('date-picker').value;
    fetch(`{{ route('location.user.show', ['userId' => '__USER_ID__']) }}?date=${date}`.replace('__USER_ID__', userId))
        .then(response => response.text())
        .then(html => {
            document.getElementById('staffDetailsContent').innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading staff details:', error);
            document.getElementById('staffDetailsContent').innerHTML = `
                <div class="alert alert-danger">
                    <h6>Error Loading Details</h6>
                    <p class="mb-0">Unable to load staff location details. Please try again.</p>
                </div>
            `;
        });
}

// Show alert messages
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.content') || document.body;
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Date picker change handler
document.getElementById('date-picker').addEventListener('change', refreshLiveData);

// Toggle trails (placeholder)
function toggleTrails() {
    showAlert('Trail visualization feature coming soon!', 'info');
}

// View full history (placeholder)  
function viewFullHistory() {
    showAlert('Full history view feature coming soon!', 'info');
}
</script>
@endpush
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card bg-gradient-info">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold opacity-7">Last Updated</p>
                                            <h6 class="text-white font-weight-bolder" id="last-updated">--:--:--</h6>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                            <i class="fas fa-clock text-dark text-lg opacity-10"></i>
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
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold opacity-7">Auto Refresh</p>
                                            <h6 class="text-white font-weight-bolder" id="auto-refresh-status">OFF</h6>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                            <i class="fas fa-broadcast-tower text-dark text-lg opacity-10"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card bg-gradient-primary">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-white text-sm mb-0 text-uppercase font-weight-bold opacity-7">Map View</p>
                                            <h6 class="text-white font-weight-bolder">LIVE</h6>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                            <i class="fas fa-map text-dark text-lg opacity-10"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="row">
                    <!-- Map Column -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-map-marked-alt text-primary"></i>
                                    Live Staff Locations
                                </h6>
                                <div class="float-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="centerMap()">
                                        <i class="fas fa-crosshairs"></i> Center Map
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="showAllTrails()">
                                        <i class="fas fa-route"></i> Show Trails
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="live-map" style="height: 600px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff List Column -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-users text-success"></i>
                                    Active Staff <span class="badge bg-gradient-success" id="staff-count-badge">0</span>
                                </h6>
                            </div>
                            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                                <div id="staff-list">
                                    <!-- Staff list will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Staff Details Modal -->
<div class="modal fade" id="staffDetailsModal" tabindex="-1" role="dialog" aria-labelledby="staffDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffDetailsModalLabel">Staff Location Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="staffDetailsContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="viewFullHistory()">View Full History</button>
            </div>
        </div>
    </div>
</div>
@endsection

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
        mapTypeId: 'roadmap',
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });

    // Load initial data
    loadLiveLocationData();

    // Set up auto-refresh every 30 seconds
    // toggleAutoRefresh();
}

function loadLiveLocationData() {
    const date = document.getElementById('date-picker').value;
    
    // Show loading state
    document.getElementById('refresh-icon').classList.add('fa-spin');
    
    fetch(`{{ route('location.live.data') }}?date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateMapWithStaffLocations(data.locations);
                updateStaffList(data.locations);
                updateStatistics(data);
            } else {
                showToast('Failed to load location data', 'error');
            }
        })
        .catch(error => {
            console.error('Error loading live data:', error);
            showToast('Error loading location data', 'error');
        })
        .finally(() => {
            document.getElementById('refresh-icon').classList.remove('fa-spin');
        });
}

function updateMapWithStaffLocations(locations) {
    // Clear existing markers
    staffMarkers.forEach(marker => marker.setMap(null));
    staffMarkers = [];
    
    if (locations.length === 0) {
        showToast('No active staff locations found for selected date', 'info');
        return;
    }
    
    const bounds = new google.maps.LatLngBounds();
    
    locations.forEach((staff, index) => {
        const position = { lat: staff.lat, lng: staff.lng };
        bounds.extend(position);
        
        // Create custom marker
        const marker = new google.maps.Marker({
            position: position,
            map: liveMap,
            title: `${staff.user_name} - ${staff.time_ago}`,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 12,
                fillColor: getStaffColor(index),
                fillOpacity: 1,
                strokeWeight: 3,
                strokeColor: '#ffffff'
            },
            animation: google.maps.Animation.DROP
        });
        
        // Info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="min-width: 250px;">
                    <h6 class="mb-2">${staff.user_name}</h6>
                    <p class="mb-1"><strong>Last Seen:</strong> ${staff.time_ago}</p>
                    <p class="mb-1"><strong>Time:</strong> ${staff.timestamp}</p>
                    <p class="mb-1"><strong>Location:</strong> ${staff.address || 'Address not available'}</p>
                    ${staff.accuracy ? `<p class="mb-1"><strong>Accuracy:</strong> ${staff.accuracy}m</p>` : ''}
                    <div class="mt-2">
                        <button class="btn btn-sm btn-primary" onclick="showStaffDetails(${staff.user_id})">
                            View Details
                        </button>
                        <button class="btn btn-sm btn-success" onclick="showStaffTrail(${staff.user_id})">
                            Show Trail
                        </button>
                    </div>
                </div>
            `
        });
        
        marker.addListener('click', () => {
            // Close all other info windows
            staffMarkers.forEach(m => {
                if (m.infoWindow) m.infoWindow.close();
            });
            infoWindow.open(liveMap, marker);
        });
        
        marker.infoWindow = infoWindow;
        staffMarkers.push(marker);
    });
    
    // Fit map to show all markers
    if (locations.length > 0) {
        liveMap.fitBounds(bounds);
        
        // Ensure minimum zoom level
        google.maps.event.addListenerOnce(liveMap, 'bounds_changed', function() {
            if (liveMap.getZoom() > 15) {
                liveMap.setZoom(15);
            }
        });
    }
}

function updateStaffList(locations) {
    const staffListHtml = locations.map((staff, index) => `
        <div class="staff-item mb-3 p-3 border rounded cursor-pointer" onclick="focusOnStaff(${staff.user_id})" 
             style="border-color: ${getStaffColor(index)}; border-width: 2px;">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-sm me-3" style="background-color: ${getStaffColor(index)};">
                    <span class="text-white text-sm font-weight-bold">
                        ${staff.user_name.split(' ').map(n => n[0]).join('')}
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 text-sm">${staff.user_name}</h6>
                    <p class="text-xs mb-0 text-muted">${staff.user_email}</p>
                    <p class="text-xs mb-0">
                        <i class="fas fa-clock text-info"></i> ${staff.time_ago}
                    </p>
                    <p class="text-xs mb-0">
                        <i class="fas fa-map-marker-alt text-success"></i> 
                        ${staff.address ? staff.address.substring(0, 40) + '...' : 'Location updating...'}
                    </p>
                </div>
            </div>
            <div class="mt-2">
                <button class="btn btn-xs btn-outline-primary me-1" onclick="event.stopPropagation(); showStaffDetails(${staff.user_id})">
                    <i class="fas fa-info"></i> Details
                </button>
                <button class="btn btn-xs btn-outline-success" onclick="event.stopPropagation(); showStaffTrail(${staff.user_id})">
                    <i class="fas fa-route"></i> Trail
                </button>
            </div>
        </div>
    `).join('');
    
    document.getElementById('staff-list').innerHTML = staffListHtml || `
        <div class="text-center py-4">
            <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
            <h6 class="text-muted mt-2">No active staff locations</h6>
            <p class="text-xs text-muted">Staff locations will appear here when available</p>
        </div>
    `;
}

function updateStatistics(data) {
    document.getElementById('active-staff-count').textContent = data.count;
    document.getElementById('staff-count-badge').textContent = data.count;
    document.getElementById('last-updated').textContent = data.last_updated;
}

function getStaffColor(index) {
    const colors = [
        '#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6',
        '#1abc9c', '#34495e', '#e67e22', '#ff6b6b', '#4ecdc4'
    ];
    return colors[index % colors.length];
}

function focusOnStaff(userId) {
    const marker = staffMarkers.find(m => m.getTitle().includes(userId));
    if (marker) {
        liveMap.setCenter(marker.getPosition());
        liveMap.setZoom(16);
        marker.infoWindow.open(liveMap, marker);
    }
}

function showStaffDetails(userId) {
    // Load staff details and show modal
    selectedStaffId = userId;
    // This would load detailed information about the staff member
    $('#staffDetailsModal').modal('show');
}

function showStaffTrail(userId) {
    const date = document.getElementById('date-picker').value;
    
    fetch(`{{ route('location.user.trail', ':userId') }}`.replace(':userId', userId) + `?date=${date}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.trail.length > 0) {
                displayTrail(data.trail, userId);
            } else {
                showToast('No trail data available for this staff member', 'info');
            }
        })
        .catch(error => {
            console.error('Error loading trail:', error);
            showToast('Error loading trail data', 'error');
        });
}

function displayTrail(trail, userId) {
    // Clear existing trails
    trailPolylines.forEach(polyline => polyline.setMap(null));
    trailPolylines = [];
    
    if (trail.length < 2) {
        showToast('Insufficient data points for trail', 'info');
        return;
    }
    
    const path = trail.map(point => ({ lat: point.lat, lng: point.lng }));
    
    const polyline = new google.maps.Polyline({
        path: path,
        geodesic: true,
        strokeColor: '#007bff',
        strokeOpacity: 1.0,
        strokeWeight: 4
    });
    
    polyline.setMap(liveMap);
    trailPolylines.push(polyline);
    
    // Add start and end markers
    if (trail.length > 0) {
        // Start marker
        new google.maps.Marker({
            position: { lat: trail[0].lat, lng: trail[0].lng },
            map: liveMap,
            title: `Start: ${trail[0].timestamp}`,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 8,
                fillColor: '#2ecc71',
                fillOpacity: 1,
                strokeWeight: 2,
                strokeColor: '#ffffff'
            }
        });
        
        // End marker
        const lastPoint = trail[trail.length - 1];
        new google.maps.Marker({
            position: { lat: lastPoint.lat, lng: lastPoint.lng },
            map: liveMap,
            title: `End: ${lastPoint.timestamp}`,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 8,
                fillColor: '#e74c3c',
                fillOpacity: 1,
                strokeWeight: 2,
                strokeColor: '#ffffff'
            }
        });
    }
    
    // Fit map to trail
    const bounds = new google.maps.LatLngBounds();
    trail.forEach(point => bounds.extend({ lat: point.lat, lng: point.lng }));
    liveMap.fitBounds(bounds);
    
    showToast(`Trail displayed for staff member (${trail.length} points)`, 'success');
}

function refreshLiveData() {
    loadLiveLocationData();
}

function toggleAutoRefresh() {
    if (isAutoRefreshActive) {
        clearInterval(autoRefreshInterval);
        isAutoRefreshActive = false;
        document.getElementById('auto-refresh-icon').className = 'fas fa-play';
        document.getElementById('auto-refresh-text').textContent = 'Auto Refresh';
        document.getElementById('auto-refresh-status').textContent = 'OFF';
        showToast('Auto refresh disabled', 'info');
    } else {
        autoRefreshInterval = setInterval(loadLiveLocationData, 30000); // 30 seconds
        isAutoRefreshActive = true;
        document.getElementById('auto-refresh-icon').className = 'fas fa-pause';
        document.getElementById('auto-refresh-text').textContent = 'Stop Auto';
        document.getElementById('auto-refresh-status').textContent = 'ON (30s)';
        showToast('Auto refresh enabled (30 seconds)', 'success');
    }
}

function centerMap() {
    if (staffMarkers.length > 0) {
        const bounds = new google.maps.LatLngBounds();
        staffMarkers.forEach(marker => bounds.extend(marker.getPosition()));
        liveMap.fitBounds(bounds);
    }
}

function showAllTrails() {
    // This would show trails for all staff members
    showToast('Feature coming soon: Show all staff trails', 'info');
}

function viewFullHistory() {
    if (selectedStaffId) {
        window.open(`{{ route('location.user.show', ':userId') }}`.replace(':userId', selectedStaffId), '_blank');
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('date-picker').addEventListener('change', function() {
        loadLiveLocationData();
    });
});

function showToast(message, type) {
    if (typeof notyf !== 'undefined') {
        if (type === 'success') {
            notyf.success(message);
        } else if (type === 'error') {
            notyf.error(message);
        } else {
            notyf.open({ type: 'info', message: message });
        }
    } else {
        alert(message);
    }
}

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
});
</script>

<style>
.staff-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.staff-item:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.cursor-pointer {
    cursor: pointer;
}

#live-map {
    border-radius: 8px;
}

.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    line-height: 1.25;
    border-radius: 0.25rem;
}
</style>
@endpush
