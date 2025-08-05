<?php

namespace App\Http\Livewire;

use App\Models\Lead;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class Leads extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $leadOwnerFilter = '';
    public $visitStatusFilter = '';
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';
    // Removed queryString to keep clean URLs

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingLeadOwnerFilter()
    {
        $this->resetPage();
    }

    public function updatingVisitStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $leads = Lead::with('leadOwner')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('lead_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('industry', 'like', '%' . $this->search . '%')
                      ->orWhere('lead_source', 'like', '%' . $this->search . '%')
                      ->orWhere('deal_title', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->leadOwnerFilter, function ($query) {
                $query->where('lead_owner_id', $this->leadOwnerFilter);
            })
            ->when($this->visitStatusFilter, function ($query) {
                $query->where('visit_status', $this->visitStatusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $users = User::where('role', '!=', 'customer')->get();
        $statuses = Lead::select('status')->distinct()->whereNotNull('status')->pluck('status');
        $visitStatuses = Lead::select('visit_status')->distinct()->whereNotNull('visit_status')->pluck('visit_status');

        return view('livewire.leads', [
            'leads' => $leads,
            'users' => $users,
            'statuses' => $statuses,
            'visitStatuses' => $visitStatuses,
        ]);
    }

    public function deleteLead($uuid)
    {
        $lead = Lead::where('uuid', $uuid)->first();
        
        if ($lead) {
            // Remove associated file if exists
            if ($lead->file_url) {
                $oldPath = str_replace('/storage/', '', $lead->file_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $lead->delete();
            session()->flash('success', 'Lead deleted successfully.');
        } else {
            session()->flash('error', 'Lead not found.');
        }
    }
}
