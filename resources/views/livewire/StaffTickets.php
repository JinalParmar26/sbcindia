<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;

class StaffTickets extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    public $perPage = 10;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    
    public $assignedStaffFilter = 'all';
    public $statusFilter = 'all';
    public $priorityFilter = 'all';
    
    public $staffList = [];
    
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $updatesQueryString = ['search', 'perPage', 'assignedStaffFilter', 'statusFilter', 'priorityFilter'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingAssignedStaffFilter() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }
    public function updatingPriorityFilter() { $this->resetPage(); }

    public function render()
    {
        $query = Ticket::query()
            ->join('customers', 'tickets.customer_id', '=', 'customers.id')
            ->leftJoin('users', 'tickets.assigned_to', '=', 'users.id')
            ->leftJoin('user_attendance', function($join) {
                $join->on('users.id', '=', 'user_attendance.user_id')
                     ->whereDate('user_attendance.created_at', Carbon::today());
            })
            ->select('tickets.*', 'users.name as staff_name', 'users.email as staff_email', 
                    'user_attendance.check_in', 'user_attendance.check_out')
            ->with(['customer', 'assignedTo', 'contactPerson', 'orderProduct.product'])
            ->where('tickets.type', 'service')
            ->whereNotNull('tickets.assigned_to'); // Only show tickets assigned to staff

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('tickets.subject', 'like', "%{$this->search}%")
                    ->orWhere('customers.name', 'like', "%{$this->search}%")
                    ->orWhere('users.name', 'like', "%{$this->search}%");
            });
        }

        if ($this->assignedStaffFilter !== 'all') {
            $query->where('tickets.assigned_to', $this->assignedStaffFilter);
        }

        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'open') {
                $query->whereNull('tickets.start');
            } elseif ($this->statusFilter === 'in_progress') {
                $query->whereNotNull('tickets.start')->whereNull('tickets.end');
            } elseif ($this->statusFilter === 'resolved') {
                $query->whereNotNull('tickets.start')->whereNotNull('tickets.end');
            }
        }

        if ($this->priorityFilter !== 'all') {
            $query->where('tickets.priority', $this->priorityFilter);
        }

        $tickets = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Get ticket statistics
        $stats = [
            'total' => Ticket::where('type', 'service')->whereNotNull('assigned_to')->count(),
            'open' => Ticket::where('type', 'service')->whereNotNull('assigned_to')->whereNull('start')->count(),
            'in_progress' => Ticket::where('type', 'service')->whereNotNull('assigned_to')->whereNotNull('start')->whereNull('end')->count(),
            'resolved' => Ticket::where('type', 'service')->whereNotNull('assigned_to')->whereNotNull('start')->whereNotNull('end')->count(),
        ];

        return view('livewire.staff-tickets', compact('tickets', 'stats'));
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

    public function mount()
    {
        // Get all users (assuming staff is identified by role or another field)
        $this->staffList = User::orderBy('name')->get();
    }
}
