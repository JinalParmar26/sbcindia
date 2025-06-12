<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAttendance;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $attendances = UserAttendance::where('user_id', $request->user()->id)->get();
        return response()->json($attendances);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'check_in' => 'nullable|date_format:Y-m-d\TH:i:s',
            'check_in_latitude' => 'nullable|numeric',
            'check_in_longitude' => 'nullable|numeric',
            'check_in_location_name' => 'nullable|string',
            'check_out' => 'nullable|date_format:Y-m-d\TH:i:s',
            'check_out_latitude' => 'nullable|numeric',
            'check_out_longitude' => 'nullable|numeric',
            'check_out_location_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $attendance = UserAttendance::create([
            'user_id' => $request->user()->id,
            ...$request->all()
        ]);

        return response()->json(['message' => 'Attendance added', 'data' => $attendance]);
    }


    public function checkin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'check_in' => 'nullable|date_format:Y-m-d\TH:i:s',
            'check_in_latitude' => 'nullable|numeric',
            'check_in_longitude' => 'nullable|numeric',
            'check_in_location_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $checkIn =now();

        $attendance = UserAttendance::create([
            'user_id' => $request->user()->id,
            'check_in' => $checkIn,
            'check_in_latitude' => $request->input('check_in_latitude'),
            'check_in_longitude' => $request->input('check_in_longitude'),
            'check_in_location_name' => $request->input('check_in_location_name'),
        ]);

        return response()->json(['message' => 'Check-in recorded', 'data' => $attendance]);
    }

    public function show(Request $request, UserAttendance $attendance)
    {
        if (!$attendance) {
            return response()->json(['message' => 'Attendance record not found'], 404);
        }

        if ($attendance->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($attendance);
    }

    public function update(Request $request, UserAttendance $attendance)
    {
        //dd($request->all());

        $validator = Validator::make($request->all(), [
            'check_in' => 'nullable|date_format:Y-m-d\TH:i:s',
            'check_in_latitude' => 'nullable|numeric',
            'check_in_longitude' => 'nullable|numeric',
            'check_in_location_name' => 'nullable|string',
            'check_out' => 'nullable|date_format:Y-m-d\TH:i:s',
            'check_out_latitude' => 'nullable|numeric',
            'check_out_longitude' => 'nullable|numeric',
            'check_out_location_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!$attendance) {
            return response()->json(['message' => 'Attendance record not found'], 404);
        }

        if ($attendance->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $fieldsToUpdate = $request->only([
            'check_in',
            'check_in_latitude',
            'check_in_longitude',
            'check_in_location_name',
            'check_out',
            'check_out_latitude',
            'check_out_longitude',
            'check_out_location_name',
        ]);


        $attendance->update($fieldsToUpdate);

        return response()->json(['message' => 'Attendance updated', 'data' => $attendance]);
    }

    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'check_out' => 'nullable|date_format:Y-m-d\TH:i:s',
            'check_out_latitude' => 'nullable|numeric',
            'check_out_longitude' => 'nullable|numeric',
            'check_out_location_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $userId = $request->user()->id;

        // Use provided check_out or current timestamp
        $checkOut = now();

        // Find today's latest attendance with no check_out
        $attendance = UserAttendance::where('user_id', $userId)
            ->whereNull('check_out')
            ->latest('check_in')
            ->first();

        if (!$attendance) {
            return response()->json(['error' => 'No open check-in found for today.'], 404);
        }

        // Update check-out details
        $attendance->update([
            'check_out' => $checkOut,
            'check_out_latitude' => $request->input('check_out_latitude'),
            'check_out_longitude' => $request->input('check_out_longitude'),
            'check_out_location_name' => $request->input('check_out_location_name'),
        ]);

        return response()->json(['message' => 'Check-out updated successfully.', 'data' => $attendance]);
    }


    public function destroy(Request $request, UserAttendance $attendance)
    {
        if (!$attendance) {
            return response()->json(['message' => 'Attendance record not found'], 404);
        }

        if ($attendance->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $attendance->delete();

        return response()->json(['message' => 'Attendance deleted']);
    }
}
