<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ticket;

class Tickets extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $confirmingTicketDeletionId = null;

    protected $updatesQueryString = ['search', 'perPage'];

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
            ->with(['customer', 'orderProduct', 'assignedTo']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', "%{$this->search}%")
                    ->orwhere('type', 'like', "%{$this->search}%")
                    ->orWhereHas('customer', function($c) {
                        $c->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('orderProduct.product', function($p) {
                        $p->where('name', 'like', "%{$this->search}%");
                   });
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

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
}
