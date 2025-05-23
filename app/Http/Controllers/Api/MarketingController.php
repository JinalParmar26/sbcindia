<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Marketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MarketingController extends Controller
{
    public function index(Request $request)
    {
        $logs = Marketing::where('user_id', $request->user()->id)->get();
        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string',
            'company_address' => 'required|string',
            'company_phone_number' => 'required|string',
            'contact_person_name' => 'required|string',
            'contact_person_phone_number' => 'required|string',
            'visit_date' => 'required|date',
            'visit_start_time' => 'nullable|date_format:H:i:s',
            'visit_start_latitude' => 'nullable|numeric',
            'visit_start_longitude' => 'nullable|numeric',
            'visit_start_location_name' => 'nullable|string',
            'visit_end_time' => 'nullable|date_format:H:i:s',
            'visit_end_latitude' => 'nullable|numeric',
            'visit_end_longitude' => 'nullable|numeric',
            'visit_end_location_name' => 'nullable|string',
            'notes' => 'nullable|string',
            'presented_products' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $log = Marketing::create(array_merge(
            $validator->validated(),
            [
                'uuid' => (string) Str::uuid(),
                'user_id' => $request->user()->id,
            ]
        ));

        return response()->json(['message' => 'Marketing log created.', 'log' => $log]);
    }

    public function show(Request $request, Marketing $marketing)
    {
        if (!$marketing) {
            return response()->json(['message' => 'Marketing Record not found'], 404);
        }

        if ($marketing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($marketing);
    }


    public function update(Request $request, Marketing $marketing)
    {
        if (!$marketing) {
            return response()->json(['message' => 'Marketing Record not found'], 404);
        }

        if ($marketing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        $validator = Validator::make($request->all(), [
            'company_name' => 'sometimes|string',
            'company_address' => 'sometimes|string',
            'company_phone_number' => 'sometimes|string',
            'contact_person_name' => 'sometimes|string',
            'contact_person_phone_number' => 'sometimes|string',
            'visit_date' => 'sometimes|date',
            'visit_start_time' => 'nullable|date_format:H:i:s',
            'visit_start_latitude' => 'nullable|numeric',
            'visit_start_longitude' => 'nullable|numeric',
            'visit_start_location_name' => 'nullable|string',
            'visit_end_time' => 'nullable|date_format:H:i:s',
            'visit_end_latitude' => 'nullable|numeric',
            'visit_end_longitude' => 'nullable|numeric',
            'visit_end_location_name' => 'nullable|string',
            'notes' => 'nullable|string',
            'presented_products' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $marketing->update($validator->validated());

        return response()->json(['message' => 'Marketing log updated.', 'log' => $marketing]);
    }

    public function destroy(Request $request, Marketing $marketing)
    {
        if (!$marketing) {
            return response()->json(['message' => 'Marketing Record not found'], 404);
        }

        
        if ($marketing->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $marketing->delete();
        return response()->json(['message' => 'Marketing log deleted.']);
    }
}
