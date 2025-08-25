<div>
    {{-- Breadcrumb and Title --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ url()->previous() }}">User Locations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                </ol>
            </nav>
            <h2 class="h4">{{ $user->name }} - Location Details</h2>
            <p class="mb-0">View day-wise locations and map pins.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ url()->previous() }}" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    {{-- Date Filter --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="date" wire:model="selectedDate" class="form-control" max="{{ now()->format('Y-m-d') }}">
        </div>
    </div>

    <div class="row">
        {{-- Table --}}
        <div class="col-lg-6 mb-4">
            <div class="card card-body shadow border-0 table-wrapper table-responsive">
                <table class="table table-hover align-items-center">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($locations as $loc)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($loc->location_timestamp)->format('h:i A') }}</td>
                                <td>{{ $loc->address ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No locations recorded for this date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Map --}}
        <div class="col-lg-6 mb-4">
            <div id="map" style="width:100%; height:500px;" class="shadow border rounded" 
                 data-pins='@json($mapPins)'>
            </div>
        </div>
    </div>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@push('scripts')
<script>
let map;

function initUserLocationMap(pins = null){
    const mapDiv = document.getElementById('map');
    if(!mapDiv) return;

    // Get latest pins
    pins = pins || JSON.parse(mapDiv.dataset.pins || '[]');

    // Destroy existing map if any
    if(map) map.remove();

    map = L.map('map').setView([20.5937, 78.9629], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const bounds = [];

    pins.forEach(loc => {
        if(loc.latitude && loc.longitude){
            const marker = L.marker([parseFloat(loc.latitude), parseFloat(loc.longitude)]).addTo(map);
            marker.bindPopup(`
                <div style="font-size:14px;">
                    <b>${loc.address || '-'}</b><br>
                    <small>${loc.time || ''}</small>
                </div>
            `);
            bounds.push([parseFloat(loc.latitude), parseFloat(loc.longitude)]);
        }
    });

    if(bounds.length){
        map.fitBounds(bounds, {padding: [50,50]});
    }
}

document.addEventListener('livewire:load', function () {
    initUserLocationMap();

    // After every Livewire update
    Livewire.hook('message.processed', () => {
        const pins = @this.mapPins; // fetch latest pins from Livewire
        initUserLocationMap(pins);
    });
});
</script>
@endpush
