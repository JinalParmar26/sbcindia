<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;

class Orders extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $statusFilter = 'all';
    protected $paginationTheme = 'bootstrap';
    public $confirmingOrderDeletionId = null;

    protected $updatesQueryString = ['search', 'perPage', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::with('customer');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhereHas('customer', function ($q2) {
                        $q2->where('name', 'like', "%{$this->search}%");
                    });
            });
        }


        $orders = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.orders', compact('orders'));
    }

    public function confirmDelete($orderId)
    {
        $this->confirmingOrderDeletionId = $orderId;
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function deleteOrder()
    {
        Order::findOrFail($this->confirmingOrderDeletionId)->delete();
        $this->confirmingOrderDeletionId = null;
        $this->dispatchBrowserEvent('hide-delete-modal');
    }
}
