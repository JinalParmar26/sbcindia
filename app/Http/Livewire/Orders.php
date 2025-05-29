<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\Customer;

class Orders extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $statusFilter = 'all';
    protected $paginationTheme = 'bootstrap';
    public $confirmingOrderDeletionId = null;

    protected $updatesQueryString = ['search', 'perPage', 'customerFilter'];

    public $customerFilter = 'all';
    public $customers = [];

    public $yearFilter = 'all';
    public $availableYears = [];


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

        if ($this->customerFilter !== 'all') {
            $query->where('customer_id', $this->customerFilter);
        }

        if ($this->yearFilter !== 'all') {
            $query->whereYear('created_at', $this->yearFilter);
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

    public function mount()
    {
         $this->customers = Customer::orderBy('name')->get();
         $this->availableYears = Order::selectRaw('YEAR(created_at) as year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year')
        ->toArray();
    }
}
