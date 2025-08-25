<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;


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

    public function exportCsv()
    {
        $fileName = 'leads-' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $leads = Lead::with('user')->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Name',
            'Company Name',
            'Source',
            'Industry',
            'Email',
            'Contact',
            'Whatsapp',
            'Lead Owner',
            'Address',
            'Area',
            'City',
            'State',
            'Country',
            'Pincode',
            'Created At'
        ];

        $callback = function() use ($leads, $columns) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write header
            fputcsv($file, $columns);

            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->name,
                    $lead->company_name,
                    $lead->source,
                    $lead->industry,
                    $lead->email,
                    $lead->contact,
                    $lead->whatsapp_number,
                    $lead->user->name ?? '-',
                    $lead->address,
                    $lead->area,
                    $lead->city,
                    $lead->state,
                    $lead->country,
                    $lead->pincode,
                    $lead->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
   

    public function show($uuid)
    {
        $lead = Lead::with(['user', 'visitLogs.images'])->where('uuid', $uuid)->firstOrFail();

        return view('leads.show', compact('lead'));
    }
}
