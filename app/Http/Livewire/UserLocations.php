<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UserLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserLocations extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    // Reset pagination when search changes
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Reset pagination when perPage changes
    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Step 1: Fetch paginated users
        $usersQuery = User::select('id', 'name', 'email', 'role')
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            })
            ->paginate($this->perPage);
        $userIds = $usersQuery->pluck('id')->toArray();

        // Step 2: Latest location per user
        $latestLocations = UserLocation::select('user_id', 'latitude', 'longitude', 'address', 'location_timestamp')
            ->whereIn('user_id', $userIds)
            ->whereIn('id', function($query) use ($userIds) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('user_locations')
                    ->whereIn('user_id', $userIds)
                    ->groupBy('user_id');
            })
            ->get()
            ->keyBy('user_id');

        // Step 3: Today's latest locations for map
        $today = Carbon::today();
        $todayLocations = UserLocation::select('user_id', 'latitude', 'longitude', 'address')
            ->whereIn('user_id', $userIds)
            ->whereDate('location_timestamp', $today)
            ->whereIn('id', function($query) use ($userIds, $today) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('user_locations')
                    ->whereIn('user_id', $userIds)
                    ->whereDate('location_timestamp', $today)
                    ->groupBy('user_id');
            })
            ->get()
            ->mapWithKeys(function($loc) {
                return [$loc->user_id => [
                    'latitude' => $loc->latitude,
                    'longitude' => $loc->longitude,
                    'address' => $loc->address ?? '-',
                    'name' => $loc->user ? $loc->user->name : 'Unknown',
                ]];
            });

        return view('livewire.user-locations', [
            'users' => $usersQuery,
            'lastLocations' => $latestLocations,
            'todayLocations' => $todayLocations,
        ]);
    }
}
