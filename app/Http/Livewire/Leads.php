<?php

namespace App\Http\Livewire;

use App\Models\Lead;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Leads extends Component
{
    use WithPagination;

    public $search = '';
    public $leadOwnerFilter = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLeadOwnerFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $leads = Lead::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('industry', 'like', '%' . $this->search . '%')
                        ->orWhere('source', 'like', '%' . $this->search . '%')
                        ->orWhere('company_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->leadOwnerFilter, function ($query) {
                $query->where('user_id', $this->leadOwnerFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $users = User::where('role', '!=', 'customer')->get();

        return view('livewire.leads', [
            'leads' => $leads,
            'users' => $users,
        ]);
    }

    public function deleteLead($uuid)
    {
        $lead = Lead::where('uuid', $uuid)->first();
        if ($lead) {
            $lead->delete();
            session()->flash('success', 'Lead deleted successfully.');
        } else {
            session()->flash('error', 'Lead not found.');
        }
    }
}
