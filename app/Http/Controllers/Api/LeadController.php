<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Lead;
use App\Models\LeadVisitLog;
use App\Models\LeadVisitLogImage;

class LeadController extends Controller
{
    /**
     * Store a new lead
     */
    public function storeLead(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'source' => 'required|string|max:100',
            'industry' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contact' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
        ]);

        $lead = Lead::create([
            'uuid' => Str::uuid(),
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'source' => $request->source,
            'industry' => $request->industry,
            'company_name' => $request->company_name,
            'address' => $request->address,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'area' => $request->area,
            'pincode' => $request->pincode,
            'email' => $request->email,
            'contact' => $request->contact,
            'whatsapp_number' => $request->whatsapp_number,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Lead created successfully',
            'data' => $lead
        ]);
    }

    /**
     * Store a new lead visit log
     */
    public function storeLeadLog(Request $request)
    {
        $request->validate([
            'lead_id' => 'nullable|integer|exists:leads,lead_id',
            'lead_uuid' => 'nullable|uuid|exists:leads,uuid',
            'lead_type' => 'required|string|max:100',
            'rating' => 'required|string|max:100',
            'visit_date' => 'required|date',
            'visit_start_time' => 'nullable|date_format:H:i:s',
            'visit_start_latitude' => 'nullable|numeric',
            'visit_start_longitude' => 'nullable|numeric',
            'visit_start_location_name' => 'nullable|string|max:255',
            'visit_end_time' => 'nullable|date_format:H:i:s',
            'visit_end_latitude' => 'nullable|numeric',
            'visit_end_longitude' => 'nullable|numeric',
            'visit_end_location_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'presented_products' => 'nullable|string',
        ]);

        // 🔎 Find lead either by id or uuid
        $lead = null;
        if ($request->lead_id) {
            $lead = Lead::where('lead_id', $request->lead_id)->first();
        } elseif ($request->lead_uuid) {
            $lead = Lead::where('uuid', $request->lead_uuid)->first();
        }

        if (!$lead) {
            return response()->json([
                'status' => false,
                'message' => 'Lead not found',
            ], 404);
        }

        $log = LeadVisitLog::create([
            'uuid' => Str::uuid(),
            'lead_id' => $lead->lead_id,
            'user_id' => $request->user()->id,
            'lead_type' => $request->lead_type,
            'rating' => $request->rating,
            'visit_date' => $request->visit_date,
            'visit_start_time' => $request->visit_start_time,
            'visit_start_latitude' => $request->visit_start_latitude,
            'visit_start_longitude' => $request->visit_start_longitude,
            'visit_start_location_name' => $request->visit_start_location_name,
            'visit_end_time' => $request->visit_end_time,
            'visit_end_latitude' => $request->visit_end_latitude,
            'visit_end_longitude' => $request->visit_end_longitude,
            'visit_end_location_name' => $request->visit_end_location_name,
            'notes' => $request->notes,
            'presented_products' => $request->presented_products,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Lead visit log created successfully',
            'data' => $log
        ]);
    }

    /**
     * Store a new lead visit log image
     */
    public function storeLeadLogImage(Request $request)
    {
        $request->validate([
            'lead_visit_log_id' => 'nullable|integer|exists:lead_visit_logs,id',
            'lead_visit_log_uuid' => 'nullable|uuid|exists:lead_visit_logs,uuid',
            'image' => 'required|string|max:255', // later can replace with file upload
        ]);

        // 🔎 Find log either by id or uuid
        $log = null;
        if ($request->lead_visit_log_id) {
            $log = LeadVisitLog::find($request->lead_visit_log_id);
        } elseif ($request->lead_visit_log_uuid) {
            $log = LeadVisitLog::where('uuid', $request->lead_visit_log_uuid)->first();
        }

        if (!$log) {
            return response()->json([
                'status' => false,
                'message' => 'Lead visit log not found',
            ], 404);
        }

        $image = LeadVisitLogImage::create([
            'uuid' => Str::uuid(),
            'lead_visit_log_id' => $log->id,
            'user_id' => $request->user()->id,
            'image' => $request->image,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Lead visit log image created successfully',
            'data' => $image
        ]);
    }


    public function getLeads(Request $request)
    {
        $user = $request->user();

        $leads = Lead::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Leads fetched successfully',
            'data' => $leads
        ]);
    }
}