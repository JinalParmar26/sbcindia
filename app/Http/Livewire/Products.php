<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class Products extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    protected $paginationTheme = 'bootstrap';
    public $confirmingProductDeletionId = null;
    public $selectedUsers = [];

    protected $updatesQueryString = ['search', 'perPage'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('model_number', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.products', compact('products'));
    }

    public function confirmDelete($productId)
    {
        $this->confirmingProductDeletionId = $productId;
        $this->dispatchBrowserEvent('show-delete-modal');
        // This is where you could trigger a modal or a browser confirm dialog,
        // for example emit an event or set a property to show a confirmation modal.
    }

    public function deleteProduct()
    {
        Product::findOrFail($this->confirmingProductDeletionId)->delete();
        $this->confirmingProductDeletionId = null;
        $this->dispatchBrowserEvent('hide-delete-modal');
    }

}
