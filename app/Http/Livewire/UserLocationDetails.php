<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserLocation;
use Carbon\Carbon;

class UserLocationDetails extends Component
{
    public $userUuid;
    public $selectedDate;
    public $user;
    public $mapPins = [];

    public function mount($uuid)
    {
        $this->userUuid = $uuid;
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->user = User::where('uuid', $uuid)->firstOrFail();
        $this->loadLocations();
    }

    public function updatedSelectedDate()
    {
        $this->loadLocations();
    }

    private function loadLocations()
    {
        $date = Carbon::parse($this->selectedDate);

        $locations = UserLocation::where('user_id', $this->user->id)
            ->whereDate('location_timestamp', $date)
            ->orderBy('location_timestamp')
            ->get();

        $this->mapPins = $locations->map(function($loc) {
            return [
                'latitude' => $loc->latitude,
                'longitude' => $loc->longitude,
                'address' => $loc->address ?? '-',
                'time' => $loc->location_timestamp ? Carbon::parse($loc->location_timestamp)->format('h:i A') : '',
            ];
        })->values();

        $this->locations = $locations; // Keep for table display
    }

    public function render()
    {
        return view('livewire.user-location-details', [
            'locations' => $this->locations,
        ]);
    }
}
