<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\UserAttendance;
use Carbon\Carbon;

class Staff extends Component
{
    protected $layout = 'layouts.app';

    public $staffMembers;
    public $selectedUserId;
    public $showingAttendanceDetails = false;
    public $attendanceDetails = [];

    protected $listeners = ['refreshStaffList' => '$refresh'];

    public function mount()
    {
        $this->loadStaffMembers();
    }

    public function loadStaffMembers()
    {
        $this->staffMembers = User::with(['userAttendances' => function($query) {
            $query->whereDate('check_in', Carbon::today())
                  ->orderBy('check_in', 'desc');
        }])->get();
    }

    public function checkOutUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Find the latest attendance record for today that doesn't have check_out
        $attendance = UserAttendance::where('user_id', $userId)
            ->whereDate('check_in', Carbon::today())
            ->whereNull('check_out')
            ->latest('check_in')
            ->first();

        if (!$attendance) {
            session()->flash('error', 'No active check-in found for this user today.');
            return;
        }

        // Update the attendance record with check-out information
        $attendance->update([
            'check_out' => Carbon::now(),
            'check_out_location_name' => 'Admin checkout',
        ]);

        session()->flash('success', 'User checked out successfully.');
        $this->loadStaffMembers();
    }

    public function showAttendanceDetails($userId)
    {
        $this->selectedUserId = $userId;
        $user = User::findOrFail($userId);
        
        $todayAttendance = UserAttendance::where('user_id', $userId)
            ->whereDate('check_in', Carbon::today())
            ->orderBy('check_in', 'desc')
            ->first();

        $this->attendanceDetails = [
            'user' => $user,
            'attendance' => $todayAttendance,
            'status' => $todayAttendance ? 
                ($todayAttendance->check_out ? 'checked_out' : 'checked_in') : 
                'not_checked_in'
        ];
        
        $this->showingAttendanceDetails = true;
    }

    public function closeAttendanceDetails()
    {
        $this->showingAttendanceDetails = false;
        $this->attendanceDetails = [];
        $this->selectedUserId = null;
    }

    public function refreshData()
    {
        $this->loadStaffMembers();
        session()->flash('info', 'Data refreshed successfully.');
    }

    public function render()
    {
        return view('livewire.staff');
    }
}
