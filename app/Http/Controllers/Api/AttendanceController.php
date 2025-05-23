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
            'check_in' => 'required|date_format:Y-m-d\TH:i:s',
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
        if (!$attendance) {
            return response()->json(['message' => 'Attendance record not found'], 404);
        }

        if ($attendance->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $attendance->update($request->all());

        return response()->json(['message' => 'Attendance updated', 'data' => $attendance]);
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
