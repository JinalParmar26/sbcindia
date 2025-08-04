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

    protected $updatesQueryString = ['search', 'perPage', 'customerFilter', 'monthFilter'];

    public $customerFilter = 'all';
    public $customers = [];

    public $yearFilter = 'all';
    public $availableYears = [];


    public $monthFilter = 'all';

    public $sortField = 'created_at';
    public $sortDirection = 'desc';


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }
    public function updatingMonthFilter() {
        $this->resetPage();
    }

    public function render()
    {
        $query = Order::query()
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select('orders.*') // important to keep only order columns
            ->with(['customer', 'orderProducts.product']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('orders.title', 'like', "%{$this->search}%")
                    ->orWhere('customers.name', 'like', "%{$this->search}%");
            });
        }

        if ($this->customerFilter !== 'all') {
            $query->where('orders.customer_id', $this->customerFilter);
        }

        if ($this->yearFilter !== 'all') {
            $query->whereYear('orders.created_at', $this->yearFilter);
        }

        if ($this->monthFilter !== 'all') {
            $query->whereMonth('orders.created_at', $this->monthFilter);
        }

        $orders = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.orders', [
            'orders' => $orders,
            'customers' => $this->customers,
            'availableYears' => $this->availableYears
        ]);
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

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
            $this->sortField = $field;
        }
    }
}
