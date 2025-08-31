<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\SalaryCalculatorService;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function calculateForDate(Request $request)
    {
        $userId = $request->input('user_id');
        $date = $request->input('date');

        $attendance = UserAttendance::where('user_id', $userId)
            ->whereDate('check_in', $date)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return response()->json([
                'hours' => '0h 0m',
                'salary' => '₹0',
            ]);
        }

        $checkIn = Carbon::parse($attendance->check_in);
        $checkOut = $attendance->check_out ? Carbon::parse($attendance->check_out) : now();
        $diffMinutes = $checkOut->diffInMinutes($checkIn);

        $hours = floor($diffMinutes / 60);
        $minutes = $diffMinutes % 60;

        // Example: ₹50 per hour
        $hourlyRate = 50;
        $salary = round(($diffMinutes / 60) * $hourlyRate, 2);

        return response()->json([
            'hours' => "{$hours}h {$minutes}m",
            'salary' => "₹{$salary}",
        ]);
    }
}
