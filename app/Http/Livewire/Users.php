<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class Users extends Component
{
    use WithPagination;

    protected $layout = 'layouts.app';

    public $perPage = 10;
    public $search = '';
    public $statusFilter = 'all';
    public $approvalFilter = 'all';
    public $roleFilter = 'all';
    protected $paginationTheme = 'bootstrap';
    public $confirmingUserDeletionId = null;
    public $selectedUsers = [];
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $updatesQueryString = ['search', 'perPage', 'statusFilter','approvalFilter', 'roleFilter'];

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

        if ($this->approvalFilter !== 'all') {
            if ($this->approvalFilter === 'require_approval') {
                $query->where('approval_required', 'yes');
            } elseif ($this->approvalFilter === 'approved') {
                $query->where('approval_required', 'no');
            }
        }

        if ($this->roleFilter !== 'all') {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        $users = $query->orderBy($this->sortField, $this->sortDirection)->paginate($this->perPage);
        $rolesList = \Spatie\Permission\Models\Role::pluck('name', 'name'); // optional for dropdown

        return view('livewire.users', compact('users','rolesList'));
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

    public function approveUser($userId)
    {
        $user = User::findOrFail($userId);

        if (!$user->hasRole(['marketing', 'staff'])) {
            $this->dispatchBrowserEvent('alert', [
                'type' => 'error',
                'message' => 'This user does not require approval.',
            ]);
            return;
        }

        $user->approval_required = 'no';
        $user->save();

        $this->dispatchBrowserEvent('alert', [
            'type' => 'success',
            'message' => 'User approved successfully.',
        ]);

        // Optional: refresh user list
        $this->resetPage(); // if using pagination
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }




}
