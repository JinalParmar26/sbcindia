<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadController extends Controller
{
    public function create()
    {
        return view('leads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'user_id'         => 'required|exists:users,id',
            'source'          => 'nullable|string|max:255',
            'industry'        => 'nullable|string|max:255',
            'company_name'    => 'nullable|string|max:255',
            'address'         => 'nullable|string|max:500',
            'country'         => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'city'            => 'nullable|string|max:100',
            'area'            => 'nullable|string|max:100',
            'pincode'         => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'contact'         => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        $validated['uuid'] = (string) Str::uuid();

        Lead::create($validated);

        return redirect()->route('leads')->with('success', 'Lead created successfully.');
    }

    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        return view('leads.edit', compact('lead'));
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'user_id'         => 'required|exists:users,id',
            'source'          => 'nullable|string|max:255',
            'industry'        => 'nullable|string|max:255',
            'company_name'    => 'nullable|string|max:255',
            'address'         => 'nullable|string|max:500',
            'country'         => 'nullable|string|max:100',
            'state'           => 'nullable|string|max:100',
            'city'            => 'nullable|string|max:100',
            'area'            => 'nullable|string|max:100',
            'pincode'         => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:255',
            'contact'         => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        $lead->update($validated);

        return redirect()->route('leads')->with('success', 'Lead updated successfully.');
    }

   

    public function show($uuid)
    {
        $lead = Lead::with(['user', 'visitLogs.images'])->where('uuid', $uuid)->firstOrFail();

        return view('leads.show', compact('lead'));
    }
}
