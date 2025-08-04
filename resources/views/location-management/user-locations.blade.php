@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-lg-flex">
                    <div>
                        <h5 class="mb-0">Locations for {{ $user->name }}</h5>
                        <p class="text-sm mb-0">
                            Location tracking data for {{ $date }}
                        </p>
                    </div>
                    <div class="ms-auto my-auto mt-lg-0 mt-4">
                        <div class="ms-auto my-auto">
                            <input type="date" id="date-picker" class="form-control form-control-sm d-inline-block w-auto" 
                                   value="{{ $date }}" 
                                   min="{{ $dateRange->start_date ?? '' }}" 
                                   max="{{ $dateRange->end_date ?? '' }}">
                            <button type="button" class="btn bg-gradient-info btn-sm mb-0 ms-2" onclick="loadLocationData()">
                                <i class="fas fa-sync"></i>&nbsp;&nbsp;Refresh
                            </button>
                            <a href="{{ route('location.user.export', ['userId' => $user->id, 'date' => $date]) }}" 
                               class="btn bg-gradient-success btn-sm mb-0 ms-2">
                                <i class="fas fa-download"></i>&nbsp;&nbsp;Export CSV
                            </a>
                            <a href="{{ route('location.index') }}" class="btn bg-gradient-secondary btn-sm mb-0 ms-2">
                                <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Statistics Row -->
                <div class="row mb-4">
                    <div class="col-lg-2 col-sm-6 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Locations</p>
                                            <h5 class="font-weight-bolder">
                                                {{ $stats['total_locations'] }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                            <i class="fas fa-map-marker-alt text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Distance</p>
                                            <h5 class="font-weight-bolder">
                                                {{ $stats['total_distance'] }} km
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                            <i class="fas fa-route text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">First Location</p>
                                            <h6 class="font-weight-bolder text-sm">
                                                {{ $stats['first_location_time'] ? \Carbon\Carbon::parse($stats['first_location_time'])->format('H:i') : 'N/A' }}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                            <i class="fas fa-clock text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Last Location</p>
                                            <h6 class="font-weight-bolder text-sm">
                                                {{ $stats['last_location_time'] ? \Carbon\Carbon::parse($stats['last_location_time'])->format('H:i') : 'N/A' }}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                            <i class="fas fa-clock text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6 mb-lg-0 mb-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Accuracy</p>
                                            <h6 class="font-weight-bolder text-sm">
                                                {{ $stats['average_accuracy'] ? round($stats['average_accuracy'], 1) . 'm' : 'N/A' }}
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                            <i class="fas fa-crosshairs text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 col-sm-6">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <p class="text-sm mb-0 text-uppercase font-weight-bold">Addresses</p>
                                            <h5 class="font-weight-bolder">
                                                {{ $stats['unique_addresses'] }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-dark shadow-dark text-center rounded-circle">
                                            <i class="fas fa-building text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map and Data -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Location Map</h6>
                            </div>
                            <div class="card-body">
                                <div id="map" style="height: 500px; width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Location Timeline</h6>
                            </div>
                            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                                <div id="location-timeline">
                                    @if($locations->count() > 0)
                                        @foreach($locations as $location)
                                        <div class="timeline-item mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs bg-gradient-info me-2">
                                                    <i class="fas fa-map-marker-alt text-xs"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="text-sm mb-0">{{ \Carbon\Carbon::parse($location->location_timestamp)->format('H:i:s') }}</h6>
                                                    <p class="text-xs mb-0">
                                                        {{ $location->address ?? 'Lat: ' . $location->latitude . ', Lng: ' . $location->longitude }}
                                                    </p>
                                                    @if($location->accuracy)
                                                    <span class="badge badge-sm bg-gradient-secondary">{{ round($location->accuracy, 1) }}m accuracy</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-map-marker-alt text-muted" style="font-size: 3rem;"></i>
                                            <h6 class="text-muted mt-2">No location data for this date</h6>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXi6lk-Tey8r9YjTyxBPYgbXx44sqrhgU&callback=initMap"></script>
<script>
let map;
let markers = [];
let directionsService;
let directionsRenderer;

function initMap() {
    // Initialize map
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 0, lng: 0 },
        zoom: 13,
        mapTypeId: 'roadmap'
    });

    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        draggable: false,
        suppressMarkers: false
    });
    directionsRenderer.setMap(map);

    loadLocationData();
}

function loadLocationData() {
    const date = $('#date-picker').val();
    const userId = {{ $user->id }};
    
    // Clear existing markers
    markers.forEach(marker => marker.setMap(null));
    markers = [];
    
    $.ajax({
        url: `{{ route('location.user.data', $user->id) }}`,
        method: 'GET',
        data: { date: date },
        success: function(response) {
            if (response.success && response.locations.length > 0) {
                displayLocationsOnMap(response.locations);
                updateTimeline(response.locations);
                
                // Update URL with new date
                const url = new URL(window.location);
                url.searchParams.set('date', date);
                window.history.pushState({}, '', url);
            } else {
                // No locations found
                $('#location-timeline').html(`
                    <div class="text-center py-4">
                        <i class="fas fa-map-marker-alt text-muted" style="font-size: 3rem;"></i>
                        <h6 class="text-muted mt-2">No location data for this date</h6>
                    </div>
                `);
            }
        },
        error: function(xhr) {
            console.error('Error loading location data:', xhr);
            showToast('Error loading location data', 'error');
        }
    });
}

function displayLocationsOnMap(locations) {
    if (locations.length === 0) return;
    
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
            title: `${location.timestamp} - ${location.address || 'Location'}`,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: index === 0 ? 10 : (index === locations.length - 1 ? 10 : 6),
                fillColor: index === 0 ? '#28a745' : (index === locations.length - 1 ? '#dc3545' : '#007bff'),
                fillOpacity: 1,
                strokeWeight: 2,
                strokeColor: '#ffffff'
            }
        });
        
        // Info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div>
                    <strong>${location.timestamp}</strong><br>
                    ${location.address || `Lat: ${location.lat}, Lng: ${location.lng}`}<br>
                    ${location.accuracy ? `Accuracy: ${location.accuracy}m` : ''}
                    ${location.speed ? `<br>Speed: ${location.speed} m/s` : ''}
                </div>
            `
        });
        
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
        
        markers.push(marker);
    });
    
    // Draw path if more than one location
    if (locations.length > 1) {
        const pathLine = new google.maps.Polyline({
            path: path,
            geodesic: true,
            strokeColor: '#007bff',
            strokeOpacity: 1.0,
            strokeWeight: 3
        });
        pathLine.setMap(map);
    }
    
    // Fit map to show all markers
    map.fitBounds(bounds);
}

function updateTimeline(locations) {
    let timelineHtml = '';
    
    locations.forEach((location, index) => {
        timelineHtml += `
            <div class="timeline-item mb-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xs bg-gradient-${index === 0 ? 'success' : (index === locations.length - 1 ? 'danger' : 'info')} me-2">
                        <i class="fas fa-map-marker-alt text-xs"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="text-sm mb-0">${location.timestamp}</h6>
                        <p class="text-xs mb-0">
                            ${location.address || `Lat: ${location.lat}, Lng: ${location.lng}`}
                        </p>
                        ${location.accuracy ? `<span class="badge badge-sm bg-gradient-secondary">${location.accuracy}m accuracy</span>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#location-timeline').html(timelineHtml);
}

$(document).ready(function() {
    $('#date-picker').on('change', function() {
        loadLocationData();
    });
});

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
