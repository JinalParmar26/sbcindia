@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <style>
        .staff-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        
        .staff-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .staff-card.active {
            border-color: #007bff;
            background-color: #f8f9ff;
        }
        
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        
        .map-container {
            height: 500px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .location-list {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .location-item {
            border-left: 4px solid #007bff;
            transition: all 0.2s ease;
        }
        
        .location-item:hover {
            background-color: #f8f9fa;
        }
        
        .time-badge {
            font-size: 0.75rem;
            min-width: 70px;
        }
        
        .refresh-btn {
            animation: none;
        }
        
        .refresh-btn.spinning {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .no-locations {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .stats-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
    </style>

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-map-marked-alt text-primary"></i>
                Live Staff Tracking
            </h1>
            <p class="text-muted">Real-time staff location monitoring - {{ $today->format('F d, Y') }}</p>
        </div>
        <div class="d-none d-sm-inline-block">
            <button id="refreshBtn" class="btn btn-primary btn-sm shadow-sm refresh-btn">
                <i class="fas fa-sync-alt fa-sm text-white-50"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="card stats-summary shadow mb-4">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="mb-2">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h4 class="mb-0" id="totalStaff">{{ $staffWithLocations->count() }}</h4>
                    <small>Total Staff</small>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <i class="fas fa-map-marker-alt fa-2x"></i>
                    </div>
                    <h4 class="mb-0" id="activeStaff">{{ $staffWithLocations->where('location_count', '>', 0)->count() }}</h4>
                    <small>Active Today</small>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <i class="fas fa-route fa-2x"></i>
                    </div>
                    <h4 class="mb-0" id="totalLocations">{{ $staffWithLocations->sum('location_count') }}</h4>
                    <small>Total Locations</small>
                </div>
                <div class="col-md-3">
                    <div class="mb-2">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h4 class="mb-0" id="lastUpdate">{{ now()->format('H:i') }}</h4>
                    <small>Last Update</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Panel - Staff List -->
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users"></i> Staff Members
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div id="staffList" class="staff-list" style="max-height: 600px; overflow-y: auto;">
                        @forelse($staffWithLocations as $staff)
                        <div class="staff-card p-3 border-bottom position-relative" 
                             data-user-id="{{ $staff->id }}" 
                             data-user-name="{{ $staff->name }}">
                            
                            <!-- Status Badge -->
                            @if($staff->location_count > 0)
                                <span class="badge bg-success status-badge">
                                    <i class="fas fa-circle"></i> Active
                                </span>
                            @else
                                <span class="badge bg-secondary status-badge">
                                    <i class="fas fa-circle"></i> Inactive
                                </span>
                            @endif
                            
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 40px; height: 40px;">
                                    {{ substr($staff->name, 0, 1) }}
                                </div>
                                
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 font-weight-bold">{{ $staff->name }}</h6>
                                    <p class="text-muted small mb-1">{{ $staff->email }}</p>
                                    
                                    @if($staff->location_count > 0)
                                        <div class="small text-success">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $staff->location_count }} locations today
                                        </div>
                                        @if($staff->last_location_time)
                                        <div class="small text-muted">
                                            Last seen: {{ \Carbon\Carbon::parse($staff->last_location_time)->format('g:i A') }}
                                        </div>
                                        @endif
                                        @if($staff->latest_address)
                                        <div class="small text-muted mt-1" title="{{ $staff->latest_address }}">
                                            <i class="fas fa-location-arrow"></i>
                                            {{ Str::limit($staff->latest_address, 40) }}
                                        </div>
                                        @endif
                                    @else
                                        <div class="small text-muted">
                                            <i class="fas fa-times-circle"></i>
                                            No locations today
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>No staff members found</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Middle Panel - Location Timeline -->
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list"></i> Today's Locations
                        <span id="selectedStaffName" class="text-muted"></span>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div id="locationsList" class="location-list">
                        <div class="no-locations">
                            <i class="fas fa-mouse-pointer fa-3x mb-3"></i>
                            <p>Select a staff member to view their locations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Map -->
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map"></i> Location Map
                        <button id="fitMapBtn" class="btn btn-sm btn-outline-primary float-right" style="display: none;">
                            <i class="fas fa-expand-arrows-alt"></i> Fit All
                        </button>
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div id="map" class="map-container"></div>
                    <div id="mapPlaceholder" class="no-locations">
                        <i class="fas fa-map fa-3x mb-3"></i>
                        <p>Map will appear when you select a staff member</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', 'YOUR_GOOGLE_MAPS_API_KEY') }}&callback=initMap"></script>

<script>
let map;
let markers = [];
let selectedUserId = null;

// Initialize Google Map
function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: { lat: 28.6139, lng: 77.2090 }, // Default to Delhi
        styles: [
            {
                featureType: "poi",
                elementType: "labels",
                stylers: [{ visibility: "off" }]
            }
        ]
    });
    
    // Hide map initially
    document.getElementById('map').style.display = 'none';
}

// Load user locations
function loadUserLocations(userId, userName) {
    if (selectedUserId === userId) return; // Already selected
    
    selectedUserId = userId;
    
    // Update UI
    document.querySelectorAll('.staff-card').forEach(card => card.classList.remove('active'));
    document.querySelector(`[data-user-id="${userId}"]`).classList.add('active');
    document.getElementById('selectedStaffName').textContent = `- ${userName}`;
    
    // Show loading
    document.getElementById('locationsList').innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading locations...</p>
        </div>
    `;
    
    // Fetch location data
    fetch(`/location-management/user/${userId}/trail?date={{ $today->format('Y-m-d') }}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayLocations(data.locations);
                displayMapLocations(data.locations, userName);
            } else {
                showError('Failed to load location data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Error loading location data');
        });
}

// Display locations in timeline
function displayLocations(locations) {
    const locationsList = document.getElementById('locationsList');
    
    if (locations.length === 0) {
        locationsList.innerHTML = `
            <div class="no-locations">
                <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                <p>No locations recorded today</p>
            </div>
        `;
        return;
    }
    
    let html = '';
    locations.forEach((location, index) => {
        html += `
            <div class="location-item p-3 border-bottom" data-location-index="${index}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <span class="badge bg-primary time-badge">
                                ${location.formatted_time}
                            </span>
                            ${location.accuracy ? `<span class="badge bg-secondary ms-2" style="font-size: 0.6rem;">±${location.accuracy}m</span>` : ''}
                        </div>
                        
                        ${location.address ? `
                        <div class="mb-2">
                            <i class="fas fa-location-arrow text-danger me-1"></i>
                            <small>${location.address}</small>
                        </div>` : ''}
                        
                        <div class="small text-muted">
                            <i class="fas fa-crosshairs me-1"></i>
                            ${location.lat.toFixed(6)}, ${location.lng.toFixed(6)}
                            ${location.speed ? ` | <i class="fas fa-tachometer-alt me-1"></i>${location.speed} km/h` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    locationsList.innerHTML = html;
    
    // Add click handlers for location items
    document.querySelectorAll('.location-item').forEach(item => {
        item.addEventListener('click', function() {
            const index = parseInt(this.dataset.locationIndex);
            if (markers[index]) {
                map.setCenter(markers[index].getPosition());
                map.setZoom(16);
                markers[index].setAnimation(google.maps.Animation.BOUNCE);
                setTimeout(() => {
                    markers[index].setAnimation(null);
                }, 1500);
            }
        });
    });
}

// Display locations on map
function displayMapLocations(locations, userName) {
    // Clear existing markers
    markers.forEach(marker => marker.setMap(null));
    markers = [];
    
    if (locations.length === 0) {
        document.getElementById('map').style.display = 'none';
        document.getElementById('mapPlaceholder').style.display = 'block';
        document.getElementById('fitMapBtn').style.display = 'none';
        return;
    }
    
    // Show map
    document.getElementById('map').style.display = 'block';
    document.getElementById('mapPlaceholder').style.display = 'none';
    document.getElementById('fitMapBtn').style.display = 'inline-block';
    
    const bounds = new google.maps.LatLngBounds();
    const path = [];
    
    locations.forEach((location, index) => {
        const position = { lat: location.lat, lng: location.lng };
        path.push(position);
        bounds.extend(position);
        
        // Create marker
        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: `${userName} - ${location.formatted_time}`,
            label: {
                text: (index + 1).toString(),
                color: 'white',
                fontWeight: 'bold'
            },
            icon: {
                url: index === 0 ? 'https://maps.google.com/mapfiles/ms/icons/green-dot.png' : 
                     index === locations.length - 1 ? 'https://maps.google.com/mapfiles/ms/icons/red-dot.png' :
                     'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                scaledSize: new google.maps.Size(40, 40)
            }
        });
        
        // Info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="max-width: 250px;">
                    <h6>${userName}</h6>
                    <p><strong>Time:</strong> ${location.formatted_time}</p>
                    ${location.address ? `<p><strong>Address:</strong> ${location.address}</p>` : ''}
                    <p><strong>Coordinates:</strong> ${location.lat.toFixed(6)}, ${location.lng.toFixed(6)}</p>
                    ${location.accuracy ? `<p><strong>Accuracy:</strong> ±${location.accuracy}m</p>` : ''}
                    ${location.speed ? `<p><strong>Speed:</strong> ${location.speed} km/h</p>` : ''}
                </div>
            `
        });
        
        marker.addListener('click', () => {
            // Close all info windows
            markers.forEach(m => {
                if (m.infoWindow) m.infoWindow.close();
            });
            infoWindow.open(map, marker);
        });
        
        marker.infoWindow = infoWindow;
        markers.push(marker);
    });
    
    // Draw path if multiple locations
    if (locations.length > 1) {
        const polyline = new google.maps.Polyline({
            path: path,
            geodesic: true,
            strokeColor: '#007bff',
            strokeOpacity: 1.0,
            strokeWeight: 3
        });
        polyline.setMap(map);
    }
    
    // Fit map to bounds
    if (locations.length > 1) {
        map.fitBounds(bounds);
    } else {
        map.setCenter(bounds.getCenter());
        map.setZoom(16);
    }
}

// Event handlers
document.addEventListener('DOMContentLoaded', function() {
    // Staff card click handlers
    document.querySelectorAll('.staff-card').forEach(card => {
        card.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            loadUserLocations(userId, userName);
        });
    });
    
    // Refresh button
    document.getElementById('refreshBtn').addEventListener('click', function() {
        this.classList.add('spinning');
        location.reload();
    });
    
    // Fit map button
    document.getElementById('fitMapBtn').addEventListener('click', function() {
        if (markers.length > 0) {
            const bounds = new google.maps.LatLngBounds();
            markers.forEach(marker => {
                bounds.extend(marker.getPosition());
            });
            
            if (markers.length > 1) {
                map.fitBounds(bounds);
            } else {
                map.setCenter(bounds.getCenter());
                map.setZoom(16);
            }
        }
    });
});

function showError(message) {
    document.getElementById('locationsList').innerHTML = `
        <div class="text-center p-4 text-danger">
            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
            <p>${message}</p>
        </div>
    `;
}

// Auto-refresh every 5 minutes
setInterval(() => {
    if (selectedUserId) {
        const userName = document.querySelector(`[data-user-id="${selectedUserId}"]`).dataset.userName;
        loadUserLocations(selectedUserId, userName);
    }
}, 300000); // 5 minutes
</script>
@endsection
