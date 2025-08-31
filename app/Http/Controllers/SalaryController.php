<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\SalaryCalculatorService;
use Carbon\Carbon;

use App\Models\UserAttendance; 
use App\Models\Service;



class SalaryController extends Controller
{
    public function calculateForDate(Request $request)
    {
        $userId = $request->input('user_id');
        $date   = $request->input('date');

        $user = User::findOrFail($userId);

        // 🛑 Ensure salary + working hours are defined
        if (!$user->Salary || !$user->working_hours_start || !$user->working_hours_end) {
            return response()->json([
                'error' => 'Salary or working hours not defined for this user'
            ], 400);
        }

        // Step 1: Daily working hours
        $start = \Carbon\Carbon::parse($user->working_hours_start);
        $end   = \Carbon\Carbon::parse($user->working_hours_end);
        $dailyHours = $end->diffInHours($start);

        if ($dailyHours <= 0) {
            return response()->json([
                'error' => 'Invalid working hours for this user'
            ], 400);
        }

        // Step 2: Hourly rate
        $hourlyRate = $user->Salary / (30 * $dailyHours);

        // Step 3: Attendance
        $attendance = UserAttendance::where('user_id', $userId)
            ->whereDate('check_in', $date)
            ->first();

        if (!$attendance || !$attendance->check_out) {
            return response()->json(['error' => 'Incomplete attendance for this date'], 400);
        }

        $workedMinutes = \Carbon\Carbon::parse($attendance->check_in)
            ->diffInMinutes(\Carbon\Carbon::parse($attendance->check_out));
        $workedHours = $workedMinutes / 60;

        // Step 4: Service hours (optional)
        $serviceHours = Service::where('user_id', $userId)
            ->whereDate('start_date_time', $date)
            ->get()
            ->sum(function ($service) {
                if ($service->end_date_time && $service->start_date_time) {
                    return Carbon::parse($service->end_date_time)
                        ->diffInMinutes(Carbon::parse($service->start_date_time)) / 60;
                }
                return 0;
            });

        // Step 5: Hours split
        $mainHours   = min($workedHours, $dailyHours);
        $extraHours  = max(0, $workedHours - $dailyHours);

        // Step 6: Salary split
        $mainSalary    = $mainHours * $hourlyRate;
        $serviceSalary = $serviceHours * ($hourlyRate * 2);
        $extraSalary   = $extraHours * ($hourlyRate * 1.5);

        $totalHours  = $mainHours + $serviceHours + $extraHours;
        $totalSalary = $mainSalary + $serviceSalary + $extraSalary;

        return response()->json([
            'date'           => $date,
            'main_hours'     => number_format($mainHours, 2) . 'h',
            'service_hours'  => number_format($serviceHours, 2) . 'h',
            'extra_hours'    => number_format($extraHours, 2) . 'h',
            'total_hours'    => number_format($totalHours, 2) . 'h',

            'main_salary'    => '₹' . number_format($mainSalary, 2),
            'service_salary' => '₹' . number_format($serviceSalary, 2),
            'extra_salary'   => '₹' . number_format($extraSalary, 2),
            'total_salary'   => '₹' . number_format($totalSalary, 2),
        ]);
    }

}
