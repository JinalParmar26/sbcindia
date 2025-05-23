<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OvertimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OvertimeLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = OvertimeLog::where('user_id', $request->user()->id)->get();
        return response()->json($logs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'working_hours' => 'required|numeric|min:0',
            'traving_hours' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $log = OvertimeLog::create([
            'user_id' => $request->user()->id,
            'date' => $request->date,
            'working_hours' => $request->working_hours,
            'traving_hours' => $request->traving_hours,
        ]);

        return response()->json(['message' => 'Overtime log created.', 'log' => $log]);
    }

    public function show(Request $request, OvertimeLog $overtimeLog)
    {

        if (!$overtimeLog) {
            return response()->json(['message' => 'Overtime log not found'], 404);
        }

        if ($overtimeLog->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($overtimeLog);
    }

    public function update(Request $request, OvertimeLog $overtimeLog)
    {
        if (!$overtimeLog) {
            return response()->json(['message' => 'Overtime log not found'], 404);
        }

        if ($overtimeLog->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'date' => 'sometimes|required|date',
            'working_hours' => 'sometimes|required|numeric|min:0',
            'traving_hours' => 'sometimes|required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $overtimeLog->update($request->only(['date', 'working_hours', 'traving_hours']));

        return response()->json(['message' => 'Overtime log updated.', 'log' => $overtimeLog]);
    }

    public function destroy(Request $request, OvertimeLog $overtimeLog)
    {
        if (!$overtimeLog) {
            return response()->json(['message' => 'Overtime log not found'], 404);
        }

        if ($overtimeLog->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $overtimeLog->delete();
        return response()->json(['message' => 'Overtime log deleted.']);
    }
}
