<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class Customers extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    protected $paginationTheme = 'bootstrap';
    public $confirmingCustomerDeletionId = null;
    public $selectedCustomers = [];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $updatesQueryString = ['search', 'perPage'];

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
        $query = Customer::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('company_name', 'like', "%{$this->search}%")
                    ->orWhere('phone_number', 'like', "%{$this->search}%")
                ->orWhere('address', 'like', "%{$this->search}%");
            });
        }

        $customers = $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.customers', compact('customers'));
    }


    public function confirmDelete($userId)
    {
        $this->confirmingCustomerDeletionId = $userId;
        $this->dispatchBrowserEvent('show-delete-modal');
        // This is where you could trigger a modal or a browser confirm dialog,
        // for example emit an event or set a property to show a confirmation modal.
    }

    public function deleteCustomer()
    {
        Customer::findOrFail($this->confirmingCustomerDeletionId)->delete();
        $this->confirmingUserDeletionId = null;
        $this->dispatchBrowserEvent('hide-delete-modal');
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
