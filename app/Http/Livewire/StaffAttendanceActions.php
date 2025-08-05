<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\UserAttendance;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StaffAttendanceActions extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    public $selectedMonth;
    public $selectedYear;
    public $selectedDay;
    public $selectedUser;
    public $dateRange = 'today';
    public $sortBy = 'date';
    public $sortDirection = 'desc';
    public $perPage = 15;
    public $search = '';
    public $activeTab = 'all'; // New property for tab management
    public $selectedPersonId; // For person-wise view
    
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        $this->selectedDay = Carbon::now()->day;
    }

    public function updated($property)
    {
        if (in_array($property, ['selectedMonth', 'selectedYear', 'selectedDay', 'selectedUser', 'dateRange', 'search', 'activeTab', 'selectedPersonId'])) {
            $this->resetPage();
        }
    }

    public function resetFilters()
    {
        $this->selectedMonth = Carbon::now()->month;
        $this->selectedYear = Carbon::now()->year;
        $this->selectedDay = Carbon::now()->day;
        $this->selectedUser = null;
        $this->dateRange = 'today';
        $this->search = '';
        $this->resetPage();
    }

    public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        
        // Reset filters when switching tabs
        if ($tab === 'all') {
            $this->selectedPersonId = null;
        }
    }

    public function selectPerson($personId)
    {
        $this->selectedPersonId = $personId;
        $this->activeTab = 'person';
        $this->resetPage();
    }

    public function getAttendanceData()
    {
        $query = UserAttendance::with('user')
            ->whereHas('user', function($q) {
                if ($this->search) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                }
            });

        // Apply date filters
        switch ($this->dateRange) {
            case 'today':
                $query->whereDate('check_in', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('check_in', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('check_in', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('check_in', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('check_in', Carbon::now()->month)
                      ->whereYear('check_in', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('check_in', Carbon::now()->subMonth()->month)
                      ->whereYear('check_in', Carbon::now()->subMonth()->year);
                break;
            case 'custom_day':
                $query->whereDate('check_in', Carbon::create($this->selectedYear, $this->selectedMonth, $this->selectedDay));
                break;
            case 'custom_month':
                $query->whereMonth('check_in', $this->selectedMonth)
                      ->whereYear('check_in', $this->selectedYear);
                break;
            case 'custom_year':
                $query->whereYear('check_in', $this->selectedYear);
                break;
        }

        // Apply user filter
        if ($this->selectedUser) {
            $query->where('user_id', $this->selectedUser);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'date':
                $query->orderBy('check_in', $this->sortDirection);
                break;
            case 'user':
                $query->join('users', 'user_attendance.user_id', '=', 'users.id')
                      ->orderBy('users.name', $this->sortDirection)
                      ->select('user_attendance.*');
                break;
            case 'check_in':
                $query->orderBy('check_in', $this->sortDirection);
                break;
            case 'check_out':
                $query->orderBy('check_out', $this->sortDirection);
                break;
            case 'working_hours':
                $query->orderByRaw('CASE WHEN check_out IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, check_in, check_out) ELSE 0 END ' . $this->sortDirection);
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getUsers()
    {
        return User::orderBy('name')->get();
    }

    public function getStatistics()
    {
        $baseQuery = UserAttendance::with('user');

        // Apply same date filters as main query
        switch ($this->dateRange) {
            case 'today':
                $baseQuery->whereDate('check_in', Carbon::today());
                break;
            case 'yesterday':
                $baseQuery->whereDate('check_in', Carbon::yesterday());
                break;
            case 'this_week':
                $baseQuery->whereBetween('check_in', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $baseQuery->whereBetween('check_in', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                break;
            case 'this_month':
                $baseQuery->whereMonth('check_in', Carbon::now()->month)
                          ->whereYear('check_in', Carbon::now()->year);
                break;
            case 'last_month':
                $baseQuery->whereMonth('check_in', Carbon::now()->subMonth()->month)
                          ->whereYear('check_in', Carbon::now()->subMonth()->year);
                break;
            case 'custom_day':
                $baseQuery->whereDate('check_in', Carbon::create($this->selectedYear, $this->selectedMonth, $this->selectedDay));
                break;
            case 'custom_month':
                $baseQuery->whereMonth('check_in', $this->selectedMonth)
                          ->whereYear('check_in', $this->selectedYear);
                break;
            case 'custom_year':
                $baseQuery->whereYear('check_in', $this->selectedYear);
                break;
        }

        $attendanceRecords = $baseQuery->get();

        return [
            'total_records' => $attendanceRecords->count(),
            'completed_shifts' => $attendanceRecords->whereNotNull('check_out')->count(),
            'active_shifts' => $attendanceRecords->whereNull('check_out')->count(),
            'total_working_hours' => $attendanceRecords->whereNotNull('check_out')->sum(function($record) {
                return Carbon::parse($record->check_in)->diffInMinutes(Carbon::parse($record->check_out));
            }),
            'average_working_hours' => $attendanceRecords->whereNotNull('check_out')->count() > 0 ? 
                $attendanceRecords->whereNotNull('check_out')->avg(function($record) {
                    return Carbon::parse($record->check_in)->diffInMinutes(Carbon::parse($record->check_out));
                }) : 0,
            'unique_users' => $attendanceRecords->pluck('user_id')->unique()->count(),
        ];
    }

    public function getPersonWiseData()
    {
        if (!$this->selectedPersonId) {
            return collect();
        }

        $query = UserAttendance::with('user')
            ->where('user_id', $this->selectedPersonId);

        // Apply date filters for person-wise view
        switch ($this->dateRange) {
            case 'today':
                $query->whereDate('check_in', Carbon::today());
                break;
            case 'yesterday':
                $query->whereDate('check_in', Carbon::yesterday());
                break;
            case 'this_week':
                $query->whereBetween('check_in', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $query->whereBetween('check_in', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                break;
            case 'this_month':
                $query->whereMonth('check_in', Carbon::now()->month)
                      ->whereYear('check_in', Carbon::now()->year);
                break;
            case 'last_month':
                $query->whereMonth('check_in', Carbon::now()->subMonth()->month)
                      ->whereYear('check_in', Carbon::now()->subMonth()->year);
                break;
            case 'custom_day':
                $query->whereDate('check_in', Carbon::create($this->selectedYear, $this->selectedMonth, $this->selectedDay));
                break;
            case 'custom_month':
                $query->whereMonth('check_in', $this->selectedMonth)
                      ->whereYear('check_in', $this->selectedYear);
                break;
            case 'custom_year':
                $query->whereYear('check_in', $this->selectedYear);
                break;
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'date':
                $query->orderBy('check_in', $this->sortDirection);
                break;
            case 'check_in':
                $query->orderBy('check_in', $this->sortDirection);
                break;
            case 'check_out':
                $query->orderBy('check_out', $this->sortDirection);
                break;
            case 'working_hours':
                $query->orderByRaw('CASE WHEN check_out IS NOT NULL THEN TIMESTAMPDIFF(MINUTE, check_in, check_out) ELSE 0 END ' . $this->sortDirection);
                break;
        }

        return $query->paginate($this->perPage);
    }

    public function getPersonWiseStatistics()
    {
        if (!$this->selectedPersonId) {
            return [
                'total_records' => 0,
                'completed_shifts' => 0,
                'active_shifts' => 0,
                'total_working_hours' => 0,
                'average_working_hours' => 0,
                'total_days_worked' => 0,
            ];
        }

        $baseQuery = UserAttendance::where('user_id', $this->selectedPersonId);

        // Apply same date filters as person-wise query
        switch ($this->dateRange) {
            case 'today':
                $baseQuery->whereDate('check_in', Carbon::today());
                break;
            case 'yesterday':
                $baseQuery->whereDate('check_in', Carbon::yesterday());
                break;
            case 'this_week':
                $baseQuery->whereBetween('check_in', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'last_week':
                $baseQuery->whereBetween('check_in', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                break;
            case 'this_month':
                $baseQuery->whereMonth('check_in', Carbon::now()->month)
                          ->whereYear('check_in', Carbon::now()->year);
                break;
            case 'last_month':
                $baseQuery->whereMonth('check_in', Carbon::now()->subMonth()->month)
                          ->whereYear('check_in', Carbon::now()->subMonth()->year);
                break;
            case 'custom_day':
                $baseQuery->whereDate('check_in', Carbon::create($this->selectedYear, $this->selectedMonth, $this->selectedDay));
                break;
            case 'custom_month':
                $baseQuery->whereMonth('check_in', $this->selectedMonth)
                          ->whereYear('check_in', $this->selectedYear);
                break;
            case 'custom_year':
                $baseQuery->whereYear('check_in', $this->selectedYear);
                break;
        }

        $attendanceRecords = $baseQuery->get();

        return [
            'total_records' => $attendanceRecords->count(),
            'completed_shifts' => $attendanceRecords->whereNotNull('check_out')->count(),
            'active_shifts' => $attendanceRecords->whereNull('check_out')->count(),
            'total_working_hours' => $attendanceRecords->whereNotNull('check_out')->sum(function($record) {
                return Carbon::parse($record->check_in)->diffInMinutes(Carbon::parse($record->check_out));
            }),
            'average_working_hours' => $attendanceRecords->whereNotNull('check_out')->count() > 0 ? 
                $attendanceRecords->whereNotNull('check_out')->avg(function($record) {
                    return Carbon::parse($record->check_in)->diffInMinutes(Carbon::parse($record->check_out));
                }) : 0,
            'total_days_worked' => $attendanceRecords->pluck('check_in')->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })->unique()->count(),
        ];
    }

    public function render()
    {
        $attendanceData = $this->getAttendanceData();
        $users = $this->getUsers();
        $statistics = $this->getStatistics();
        $personWiseData = $this->activeTab === 'person' ? $this->getPersonWiseData() : collect();
        $personWiseStatistics = $this->activeTab === 'person' ? $this->getPersonWiseStatistics() : [];
        $selectedPerson = $this->selectedPersonId ? User::find($this->selectedPersonId) : null;

        return view('livewire.staff-attendance-actions', [
            'attendanceData' => $attendanceData,
            'users' => $users,
            'statistics' => $statistics,
            'personWiseData' => $personWiseData,
            'personWiseStatistics' => $personWiseStatistics,
            'selectedPerson' => $selectedPerson,
        ]);
    }
}
