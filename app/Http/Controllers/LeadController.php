<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeadController extends Controller
{
    /**
     * Display a listing of leads
     */
    public function index()
    {
        // This will be handled by Livewire component
        return redirect()->route('leads.index');
    }

    /**
     * Show the form for creating a new lead
     */
    public function create()
    {
        $users = User::where('role', '!=', 'customer')->get();
        $statuses = ['New', 'Qualified', 'Contacted', 'Converted', 'Lost'];
        $visitStatuses = ['Not Started', 'Started', 'Ended'];
        $dealStatuses = ['Open', 'Won', 'Lost', 'Postponed'];
        
        return view('leads.create', compact('users', 'statuses', 'visitStatuses', 'dealStatuses'));
    }

    /**
     * Store a newly created lead
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_name' => 'required|string|max:150',
            'lead_owner_id' => 'nullable|exists:users,id',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
            'status' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:100',
            'lead_source' => 'nullable|string|max:100',
            'price_group' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'visit_started_at' => 'nullable|date',
            'visit_ended_at' => 'nullable|date|after_or_equal:visit_started_at',
            'visit_status' => 'nullable|string|max:50',
            'file' => 'nullable|file|max:10240', // 10MB max
            'deal_title' => 'nullable|string|max:150',
            'deal_amount' => 'nullable|numeric|min:0',
            'deal_status' => 'nullable|string|max:50',
        ]);

        // Set default values
        $validated['uuid'] = Str::uuid();
        $validated['lead_owner_id'] = $validated['lead_owner_id'] ?? Auth::id();
        $validated['status'] = $validated['status'] ?? 'New';
        $validated['visit_status'] = $validated['visit_status'] ?? 'Not Started';

        // Handle collaborators
        if (isset($validated['collaborators'])) {
            $validated['collaborators'] = implode(',', $validated['collaborators']);
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('leads/attachments', $filename, 'public');
            $validated['file_url'] = Storage::url($path);
        }

        $lead = Lead::create($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead created successfully.',
                'lead' => $lead,
            ]);
        }

        return redirect()->route('leads.show', $lead->uuid)->with('success', 'Lead created successfully.');
    }

    /**
     * Display the specified lead
     */
    public function show($uuid)
    {
        $lead = Lead::with('leadOwner')->where('uuid', $uuid)->firstOrFail();
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified lead
     */
    public function edit($uuid)
    {
        $lead = Lead::where('uuid', $uuid)->firstOrFail();
        $users = User::where('role', '!=', 'customer')->get();
        $statuses = ['New', 'Qualified', 'Contacted', 'Converted', 'Lost'];
        $visitStatuses = ['Not Started', 'Started', 'Ended'];
        $dealStatuses = ['Open', 'Won', 'Lost', 'Postponed'];
        
        // Convert collaborators string to array for form
        $selectedCollaborators = $lead->collaborators ? explode(',', $lead->collaborators) : [];
        
        return view('leads.edit', compact('lead', 'users', 'statuses', 'visitStatuses', 'dealStatuses', 'selectedCollaborators'));
    }

    /**
     * Update the specified lead
     */
    public function update(Request $request, $uuid)
    {
        $lead = Lead::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'lead_name' => 'required|string|max:150',
            'lead_owner_id' => 'nullable|exists:users,id',
            'collaborators' => 'nullable|array',
            'collaborators.*' => 'exists:users,id',
            'status' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:100',
            'lead_source' => 'nullable|string|max:100',
            'price_group' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'visit_started_at' => 'nullable|date',
            'visit_ended_at' => 'nullable|date|after_or_equal:visit_started_at',
            'visit_status' => 'nullable|string|max:50',
            'file' => 'nullable|file|max:10240', // 10MB max
            'deal_title' => 'nullable|string|max:150',
            'deal_amount' => 'nullable|numeric|min:0',
            'deal_status' => 'nullable|string|max:50',
            'remove_file' => 'nullable|boolean',
        ]);

        // Handle collaborators
        if (isset($validated['collaborators'])) {
            $validated['collaborators'] = implode(',', $validated['collaborators']);
        } else {
            $validated['collaborators'] = null;
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            // Remove old file if exists
            if ($lead->file_url) {
                $oldPath = str_replace('/storage/', '', $lead->file_url);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('leads/attachments', $filename, 'public');
            $validated['file_url'] = Storage::url($path);
        } elseif ($request->boolean('remove_file')) {
            // Remove file if requested
            if ($lead->file_url) {
                $oldPath = str_replace('/storage/', '', $lead->file_url);
                Storage::disk('public')->delete($oldPath);
            }
            $validated['file_url'] = null;
        }

        $lead->update($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lead updated successfully.',
                'lead' => $lead->fresh(),
            ]);
        }

        return redirect()->route('leads.show', $lead->uuid)->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified lead
     */
    public function destroy($uuid)
    {
        $lead = Lead::where('uuid', $uuid)->firstOrFail();

        // Remove associated file if exists
        if ($lead->file_url) {
            $oldPath = str_replace('/storage/', '', $lead->file_url);
            Storage::disk('public')->delete($oldPath);
        }

        $lead->delete();

        return redirect()->route('leads')->with('success', 'Lead deleted successfully.');
    }

    /**
     * Start a visit for the lead
     */
    public function startVisit($uuid)
    {
        $lead = Lead::where('uuid', $uuid)->firstOrFail();

        $lead->update([
            'visit_started_at' => now(),
            'visit_status' => 'Started',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visit started successfully.',
            'visit_started_at' => $lead->visit_started_at->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * End a visit for the lead
     */
    public function endVisit($uuid)
    {
        $lead = Lead::where('uuid', $uuid)->firstOrFail();

        if (!$lead->visit_started_at) {
            return response()->json([
                'success' => false,
                'message' => 'Visit has not been started yet.',
            ], 400);
        }

        $lead->update([
            'visit_ended_at' => now(),
            'visit_status' => 'Ended',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Visit ended successfully.',
            'visit_ended_at' => $lead->visit_ended_at->format('Y-m-d H:i:s'),
            'duration' => $lead->visit_duration . ' minutes',
        ]);
    }

    /**
     * Export leads to CSV
     */
    public function exportCsv()
    {
        $leads = Lead::with('leadOwner')->get();

        $filename = 'leads_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($leads) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, [
                'Lead Name',
                'Lead Owner',
                'Status',
                'Industry',
                'Lead Source',
                'Price Group',
                'Title',
                'Email',
                'Address',
                'Country',
                'Pincode',
                'Visit Status',
                'Visit Started',
                'Visit Ended',
                'Deal Title',
                'Deal Amount',
                'Deal Status',
                'Created At',
                'Updated At',
            ]);

            // CSV data
            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->lead_name,
                    $lead->leadOwner ? $lead->leadOwner->first_name . ' ' . $lead->leadOwner->last_name : '',
                    $lead->status,
                    $lead->industry,
                    $lead->lead_source,
                    $lead->price_group,
                    $lead->title,
                    $lead->email,
                    $lead->address,
                    $lead->country,
                    $lead->pincode,
                    $lead->visit_status,
                    $lead->visit_started_at ? $lead->visit_started_at->format('Y-m-d H:i:s') : '',
                    $lead->visit_ended_at ? $lead->visit_ended_at->format('Y-m-d H:i:s') : '',
                    $lead->deal_title,
                    $lead->deal_amount,
                    $lead->deal_status,
                    $lead->created_at->format('Y-m-d H:i:s'),
                    $lead->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
