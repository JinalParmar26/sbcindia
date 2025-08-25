<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Carbon\Carbon;

class TodaysAttendance extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $listeners = ['refreshData' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function refreshData()
    {
        $this->resetPage();
    }

    public function render()
    {
        $today = Carbon::today()->toDateString();

        // Query for stats (all staff)
        $staffMembers = User::where('role', 'staff')
            ->with(['userAttendances' => function ($q) use ($today) {
                $q->whereDate('check_in', $today);
            }])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        // Calculate stats
        $checkedIn = $staffMembers->filter(fn($s) => $s->userAttendances->first() && !$s->userAttendances->first()->check_out)->count();
        $checkedOut = $staffMembers->filter(fn($s) => $s->userAttendances->first() && $s->userAttendances->first()->check_out)->count();
        $notCheckedIn = $staffMembers->filter(fn($s) => $s->userAttendances->isEmpty())->count();

        // Paginated table
        $attendancesQuery = User::where('role', 'staff')
            ->with(['userAttendances' => function ($q) use ($today) {
                $q->whereDate('check_in', $today);
            }])
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortField, $this->sortDirection);

        $attendances = $attendancesQuery->paginate($this->perPage);

        return view('livewire.todays-attendance', [
            'staffMembers' => $staffMembers, // for stats cards
            'attendances' => $attendances,   // for table with pagination
            'checkedIn' => $checkedIn,
            'checkedOut' => $checkedOut,
            'notCheckedIn' => $notCheckedIn,
        ]);
    }
}
