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
    public $attendanceIdToDelete;

    protected $listeners = ['deleteConfirmed' => 'deleteAttendance'];

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

    public function confirmDelete($id)
    {
        $this->attendanceIdToDelete = $id;
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function deleteAttendance()
    {
        if ($this->attendanceIdToDelete) {
            \App\Models\UserAttendance::find($this->attendanceIdToDelete)->delete();
            $this->dispatchBrowserEvent('hide-delete-modal');
            session()->flash('message', 'Attendance deleted successfully.');
        }
    }

    public function render()
    {
        $today = Carbon::today()->toDateString();

        $usersQuery = User::where('role', 'staff')
            ->with(['userAttendances' => function ($q) use ($today) {
                $q->whereDate('check_in', $today);
            }])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $users = $usersQuery->paginate($this->perPage);

        return view('livewire.todays-attendance', [
            'attendances' => $users
        ]);
    }
}
