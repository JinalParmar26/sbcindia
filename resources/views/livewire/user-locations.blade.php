<div>
    {{-- Breadcrumb and Title --}}
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Locations</li>
                </ol>
            </nav>
            <h2 class="h4">User Locations</h2>
            <p class="mb-0">View last known location for all staff and today's location on map.</p>
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

    {{-- Search --}}
    <div class="table-settings mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-12 col-lg-6 d-md-flex mb-2 mb-md-0">
                <div class="input-group fmxw-300">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search staff">
                </div>
            </div>
            <div class="col-12 col-lg-6 d-flex justify-content-lg-end">
                <div class="btn-group">
                    <button class="btn btn-outline-gray-600 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Show: {{ $perPage }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" wire:click.prevent="$set('perPage', 10)">10</a></li>
                        <li><a class="dropdown-item" href="#" wire:click.prevent="$set('perPage', 20)">20</a></li>
                        <li><a class="dropdown-item" href="#" wire:click.prevent="$set('perPage', 30)">30</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Left Section: User List --}}
        <div class="col-lg-6 mb-4">
            <div class="card card-body shadow border-0 table-wrapper table-responsive">
                <table class="table user-table table-hover align-items-center">
                    <thead>
                        <tr>
                            <th>Staff Name & Email</th>
                            <th>Last Location</th>
                            <th>Today</th>
                            <th>More</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            @php
                                $lastLocation = $lastLocations[$user->id] ?? null;
                                $todayLocation = $todayLocations[$user->id] ?? null;
                            @endphp
                            <tr>
                                <td>{{ $user->name }}<br><small>{{ $user->email }} - {{ $user->role }}</small></td>
                                <td>
                                    @if($lastLocation)
                                        {{ $lastLocation->address ?? '-' }}<br>
                                        <small>{{ \Carbon\Carbon::parse($lastLocation->location_timestamp)->format('d M Y, H:i') }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($todayLocation)
                                        <span class="text-success">✔</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('user-location.show', $user->uuid) }}" class="dropdown-item text-danger">
                                        View 
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $users->links() }}
            </div>
        </div>

        {{-- Right Section: Map --}}
        <div class="col-lg-6 mb-4">
            <div id="map" style="height: 500px; width: 100%; border:1px solid #ccc;"></div>
        </div>
    </div>
</div>

 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function () {
            // Make sure map container exists
            if(!document.getElementById('map')) return;

            // Initialize Leaflet map (center on India)
            var map = L.map('map').setView([20.5937, 78.9629], 5);

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Today locations from Livewire
            const todayLocations = @json($todayLocations);

            const bounds = [];

            Object.values(todayLocations).forEach(loc => {
                if(loc.latitude && loc.longitude){
                    const marker = L.marker([parseFloat(loc.latitude), parseFloat(loc.longitude)]).addTo(map);
                    
                    // Popup with name, address, timestamp
                    marker.bindPopup(`
                        <div style="font-size:14px;">
                            <b>${loc.name || 'Unknown'}</b><br>
                            ${loc.address || '-'}<br>
                            <small>${loc.location_timestamp || ''}</small>
                        </div>
                    `);

                    bounds.push([parseFloat(loc.latitude), parseFloat(loc.longitude)]);
                }
            });

            // Adjust map to show all markers
            if(bounds.length) {
                map.fitBounds(bounds, {padding: [50, 50]});
            }
        });
    </script>
    @endpush