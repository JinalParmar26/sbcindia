<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UserLocation;
use Carbon\Carbon;

class StaffLocations extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    public $selectedUserId = '';
    public $selectedDate = '';
    public $search = '';
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    protected $updatesQueryString = ['selectedUserId', 'selectedDate', 'search'];

    public function mount()
    {
        $this->selectedDate = Carbon::today()->format('Y-m-d');
    }

    public function updatingSelectedUserId() 
    { 
        $this->resetPage(); 
    }
    
    public function updatingSelectedDate() 
    { 
        $this->resetPage(); 
    }
    
    public function updatingSearch() 
    { 
        $this->resetPage(); 
    }

    public function render()
    {
        $staffList = User::orderBy('name')->get();
        
        $locationsQuery = UserLocation::with('user')
            ->join('users', 'user_locations.user_id', '=', 'users.id')
            ->select('user_locations.*', 'users.name as user_name', 'users.email as user_email');

        if ($this->selectedUserId) {
            $locationsQuery->where('user_locations.user_id', $this->selectedUserId);
        }

        if ($this->selectedDate) {
            $locationsQuery->whereDate('user_locations.recorded_at', $this->selectedDate);
        }

        if ($this->search) {
            $searchTerm = $this->search;
            $locationsQuery->where('users.name', 'like', "%{$searchTerm}%")
                          ->orWhere('users.email', 'like', "%{$searchTerm}%")
                          ->orWhere('user_locations.address', 'like', "%{$searchTerm}%");
        }

        $locations = $locationsQuery->orderBy('user_locations.recorded_at', 'desc')
            ->paginate($this->perPage);

        // Get statistics for the selected date
        $stats = [];
        if ($this->selectedDate) {
            $totalUsers = User::count();
            $usersWithLocations = UserLocation::whereDate('recorded_at', $this->selectedDate)
                ->distinct('user_id')
                ->count('user_id');
            
            $stats = [
                'total_users' => $totalUsers,
                'users_with_locations' => $usersWithLocations,
                'total_locations' => UserLocation::whereDate('recorded_at', $this->selectedDate)->count(),
                'date' => Carbon::parse($this->selectedDate)->format('d M Y')
            ];
        }

        // Get map data for selected user and date
        $mapLocations = [];
        if ($this->selectedUserId && $this->selectedDate) {
            $userLocations = UserLocation::where('user_id', $this->selectedUserId)
                ->whereDate('recorded_at', $this->selectedDate)
                ->orderBy('recorded_at', 'asc')
                ->get();
            
            foreach ($userLocations as $loc) {
                $mapLocations[] = [
                    'lat' => (float) $loc->latitude,
                    'lng' => (float) $loc->longitude,
                    'timestamp' => $loc->recorded_at->format('H:i:s'),
                    'address' => $loc->address,
                    'accuracy' => $loc->accuracy,
                ];
            }
        }

        return view('livewire.staff-locations', compact('locations', 'staffList', 'stats', 'mapLocations'));
    }

    public function exportLocationData()
    {
        // This method can be implemented to export location data
        $this->dispatchBrowserEvent('show-alert', [
            'type' => 'info',
            'message' => 'Export functionality will be implemented soon.'
        ]);
    }
}