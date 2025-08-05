<div>
    <style>
        .location-item {
            border-left: 4px solid #007bff;
            transition: all 0.2s ease;
        }
        
        .location-item:hover {
            transform: translateX(2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .stats-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .map-container {
            height: 400px;
            border-radius: 8px;
            overflow: hidden;
            position: sticky;
            top: 20px;
        }
        
        .location-timeline {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .badge-accuracy {
            font-size: 0.7rem;
        }
        
        .time-badge {
            font-size: 0.75rem;
            min-width: 70px;
        }
    </style>

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Staff Location Tracking</h1>
            <p class="text-muted">Monitor staff locations and movement history</p>
        </div>
        <div class="d-none d-sm-inline-block">
            <button wire:click="exportLocationData" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Export Data
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if($stats)
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-primary text-white rounded-circle mb-3 mx-auto">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['total_users'] }}</h3>
                    <p class="text-muted mb-0">Total Staff</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-success text-white rounded-circle mb-3 mx-auto">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['users_with_locations'] }}</h3>
                    <p class="text-muted mb-0">Active Today</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-info text-white rounded-circle mb-3 mx-auto">
                        <i class="fas fa-route"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['total_locations'] }}</h3>
                    <p class="text-muted mb-0">Total Locations</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stats-card">
                <div class="card-body text-center">
                    <div class="icon-shape icon-md bg-warning text-white rounded-circle mb-3 mx-auto">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <h3 class="h4 mb-1">{{ $stats['date'] }}</h3>
                    <p class="text-muted mb-0">Selected Date</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="selectedUserId" class="form-label">Select Staff</label>
                    <select wire:model="selectedUserId" class="form-control" id="selectedUserId">
                        <option value="">All Staff</option>
                        @foreach($staffList as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="selectedDate" class="form-label">Date</label>
                    <input type="date" wire:model="selectedDate" class="form-control" id="selectedDate">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" wire:model="search" class="form-control" id="search" 
                           placeholder="Search by name, email, or address...">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="perPage" class="form-label">Per Page</label>
                    <select wire:model="perPage" class="form-control" id="perPage">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Left Panel - Location List -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Location History
                        @if($selectedUserId)
                            @php
                                $selectedUser = $staffList->firstWhere('id', $selectedUserId);
                            @endphp
                            - {{ $selectedUser->name ?? 'Unknown' }}
                        @endif
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($locations->count() > 0)
                        <div class="location-timeline">
                            @foreach($locations as $location)
                            <div class="location-item border-bottom p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-user-circle text-primary me-2"></i>
                                            <strong>{{ $location->user_name }}</strong>
                                            <span class="badge bg-primary time-badge ms-2">
                                                {{ \Carbon\Carbon::parse($location->location_timestamp)->format('H:i:s') }}
                                            </span>
                                        </div>
                                        
                                        <div class="text-muted small mb-2">
                                            <i class="fas fa-envelope me-1"></i>
                                            {{ $location->user_email }}
                                        </div>
                                        
                                        @if($location->address)
                                        <div class="mb-2">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            {{ $location->address }}
                                        </div>
                                        @endif
                                        
                                        <div class="small text-muted">
                                            <span class="me-3">
                                                <i class="fas fa-crosshairs me-1"></i>
                                                {{ $location->latitude }}, {{ $location->longitude }}
                                            </span>
                                            @if($location->accuracy)
                                            <span class="badge badge-accuracy bg-secondary">
                                                ±{{ $location->accuracy }}m
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="text-end">
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($location->location_timestamp)->format('d M') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="p-3 border-top">
                            {{ $locations->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No location data found</h5>
                            <p class="text-muted">
                                @if($selectedUserId && $selectedDate)
                                    No locations recorded for the selected staff member on {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}.
                                @else
                                    Select a staff member and date to view location data.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Panel - Map -->
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Location Map</h6>
                </div>
                <div class="card-body p-0">
                    @if($selectedUserId && $selectedDate && count($mapLocations) > 0)
                        <div class="map-container" id="map"></div>
                        <div class="p-3 bg-light">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Showing {{ count($mapLocations) }} location points for 
                                {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
                            </small>
                        </div>
                    @else
                        <div class="text-center py-5" style="height: 400px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="fas fa-map fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Map View</h5>
                            <p class="text-muted">Select a staff member and date to view their location trail</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if($selectedUserId && $selectedDate && count($mapLocations) > 0)
<script>
// Initialize Google Maps when locations are available
function initMap() {
    const locations = @json($mapLocations);
    
    if (locations.length === 0) return;
    
    // Create map centered on first location
    const map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: { lat: locations[0].lat, lng: locations[0].lng },
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    
    // Create bounds to fit all markers
    const bounds = new google.maps.LatLngBounds();
    const path = [];
    
    // Add markers and build path
    locations.forEach((location, index) => {
        const position = { lat: location.lat, lng: location.lng };
        path.push(position);
        bounds.extend(position);
        
        // Create marker
        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: `${location.timestamp} - ${location.address || 'Unknown location'}`,
            label: {
                text: (index + 1).toString(),
                color: 'white',
                fontWeight: 'bold'
            },
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                fillColor: index === 0 ? '#28a745' : (index === locations.length - 1 ? '#dc3545' : '#007bff'),
                fillOpacity: 1,
                strokeColor: 'white',
                strokeWeight: 2,
                scale: 8
            }
        });
        
        // Add info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div>
                    <strong>Time: ${location.timestamp}</strong><br>
                    ${location.address || 'Address not available'}<br>
                    <small>Accuracy: ±${location.accuracy || 'N/A'}m</small>
                </div>
            `
        });
        
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
    });
    
    // Draw path between locations
    if (locations.length > 1) {
        const polyline = new google.maps.Polyline({
            path: path,
            geodesic: true,
            strokeColor: '#007bff',
            strokeOpacity: 0.8,
            strokeWeight: 3
        });
        polyline.setMap(map);
    }
    
    // Fit map to show all markers
    if (locations.length > 1) {
        map.fitBounds(bounds);
    }
}

// Load Google Maps API
if (!window.google || !window.google.maps) {
    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap';
    script.async = true;
    script.defer = true;
    document.head.appendChild(script);
} else {
    initMap();
}
</script>
@endif
@endpush
