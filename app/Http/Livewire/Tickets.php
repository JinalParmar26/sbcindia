<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;

class Tickets extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    public $perPage = 10;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $confirmingTicketDeletionId = null;

    public $customerFilter = 'all';
    public $assignedStaffFilter = 'all';
    public $yearFilter = 'all';
    public $monthFilter = 'all';

    public $customers = [];
    public $staffList = [];
    public $availableYears = [];

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

// Add to query string
    protected $updatesQueryString = ['search', 'perPage', 'customerFilter', 'assignedStaffFilter', 'yearFilter', 'monthFilter'];

// Reset page on filter change
    public function updatingCustomerFilter() { $this->resetPage(); }
    public function updatingAssignedStaffFilter() { $this->resetPage(); }
    public function updatingYearFilter() { $this->resetPage(); }
    public function updatingMonthFilter() { $this->resetPage(); }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Ticket::query()
            ->join('customers', 'tickets.customer_id', '=', 'customers.id')
            ->leftJoin('users', 'tickets.assigned_to', '=', 'users.id')
            ->select('tickets.*')
            ->with(['customer', 'assignedTo'])
            ->where('tickets.type', 'service');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('tickets.subject', 'like', "%{$this->search}%")
                    ->orWhere('customers.name', 'like', "%{$this->search}%")
                    ->orWhere('users.name', 'like', "%{$this->search}%");
            });
        }

        if ($this->customerFilter !== 'all') {
            $query->where('tickets.customer_id', $this->customerFilter);
        }

        if ($this->assignedStaffFilter !== 'all') {
            $query->where('tickets.assigned_to', $this->assignedStaffFilter);
        }

        if ($this->yearFilter !== 'all') {
            $query->whereYear('tickets.created_at', $this->yearFilter);
        }

        if ($this->monthFilter !== 'all') {
            $query->whereMonth('tickets.created_at', $this->monthFilter);
        }

        $tickets = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.tickets', compact('tickets'));
    }


    public function confirmDelete($ticketId)
    {
        $this->confirmingTicketDeletionId = $ticketId;
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function deleteTicket()
    {
        Ticket::findOrFail($this->confirmingTicketDeletionId)->delete();
        $this->confirmingTicketDeletionId = null;
        $this->dispatchBrowserEvent('hide-delete-modal');
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
        $this->customers = \App\Models\Customer::orderBy('name')->get();
        $this->staffList = \App\Models\User::orderBy('name')->get();
        $this->availableYears = Ticket::selectRaw('YEAR(created_at) as year')->distinct()->orderByDesc('year')->pluck('year')->toArray();
    }
}
