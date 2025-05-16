<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Users extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $statusFilter = 'all';
    protected $paginationTheme = 'bootstrap';
    public $confirmingUserDeletionId = null;
    public $selectedUsers = [];

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
        $query = User::query()->with('roles');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where(function ($q) {
                if ($this->statusFilter === 'active') {
                    $q->where('isActive', 1);
                } elseif ($this->statusFilter === 'inactive') {
                    $q->where('isActive', 0);
                }
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.users', compact('users'));
    }


    public function confirmDelete($userId)
    {
        $this->confirmingUserDeletionId = $userId;
        $this->dispatchBrowserEvent('show-delete-modal');
        // This is where you could trigger a modal or a browser confirm dialog,
        // for example emit an event or set a property to show a confirmation modal.
    }

    public function deleteUser()
    {
        User::findOrFail($this->confirmingUserDeletionId)->delete();
        $this->confirmingUserDeletionId = null;
        $this->dispatchBrowserEvent('hide-delete-modal');
    }



}
