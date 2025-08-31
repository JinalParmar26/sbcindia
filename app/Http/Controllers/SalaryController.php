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

    public function monthlySummary(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m')); // default current month
        $start = Carbon::parse($month . '-01')->startOfMonth();
        $end   = Carbon::parse($month . '-01')->endOfMonth();

        $users = User::where('calculate_salary', 1)->get();

        $data = $users->map(function ($user) use ($start, $end) {
            $presentDays = UserAttendance::where('user_id', $user->id)
                ->whereBetween('check_in', [$start, $end])
                ->whereNotNull('check_in')
                ->count();

            $totalDays = $start->daysInMonth;
            $absentDays = $totalDays - $presentDays;

            // Daily base salary
            $dailySalary = $user->Salary ? $user->Salary / 30 : 0;

            // Total main salary (only present days)
            $mainSalary = $dailySalary * $presentDays;

            // Calculate extra salary
            $extraSalary = $this->calculateExtraSalary($user->id, $start, $end, $dailySalary);

            return [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'main_salary' => round($mainSalary),
                'extra_salary' => round($extraSalary),
                'final_salary' => round($mainSalary + $extraSalary),
            ];
        });

        return view('salary.monthly_summary', compact('data', 'month'));
    }

    // ✅ 2. User Detail (day-wise breakdown)
    public function monthlyDetail(User $user, $month)
    {
        $start = Carbon::parse($month . '-01')->startOfMonth();
        $end   = Carbon::parse($month . '-01')->endOfMonth();

        $dailySalary = $user->Salary ? $user->Salary / 30 : 0;
        $workingStart = $user->working_hours_start;
        $workingEnd   = $user->working_hours_end;

        $dates = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $attendance = UserAttendance::where('user_id', $user->id)
                ->whereDate('check_in', $date->toDateString())
                ->first();

            $present = $attendance && $attendance->check_in;

            $mainHours = $extraHours = $serviceHours = 0;
            $mainSalary = $extraSalary = $serviceSalary = 0;

            if ($present) {
                $checkIn = Carbon::parse($attendance->check_in);
                $checkOut = $attendance->check_out ? Carbon::parse($attendance->check_out) : $checkIn;

                $workedHours = $checkOut->diffInMinutes($checkIn) / 60;

                // Main hours = within working hours
                if ($workingStart && $workingEnd) {
                    $scheduledStart = Carbon::parse($workingStart);
                    $scheduledEnd   = Carbon::parse($workingEnd);
                    $scheduledHours = $scheduledEnd->diffInMinutes($scheduledStart) / 60;

                    $mainHours = min($workedHours, $scheduledHours);
                    $extraHours = max(0, $workedHours - $scheduledHours);
                } else {
                    $mainHours = $workedHours;
                }

                // Service hours (double pay)
                $serviceHours = Service::where('user_id', $user->id)
                    ->whereDate('start_date_time', $date->toDateString())
                    ->get()
                    ->sum(function ($service) {
                        if ($service->end_date_time && $service->start_date_time) {
                            return Carbon::parse($service->end_date_time)
                                ->diffInMinutes(Carbon::parse($service->start_date_time)) / 60;
                        }
                        return 0;
                    });

                // Salary calculations
                $hourlyRate = $dailySalary / ($mainHours ?: 1); // crude calc, prevents div0
                $mainSalary = $dailySalary; // base daily salary
                $extraSalary = $extraHours * $hourlyRate * 1.5;
                $serviceSalary = $serviceHours * $hourlyRate * 2;
            }

            $dates->push([
                'date' => $date->toDateString(),
                'present' => $present ? 'Present' : 'Absent',
                'main_hours' => round($mainHours, 2),
                'extra_hours' => round($extraHours, 2),
                'service_hours' => round($serviceHours, 2),
                'main_salary' => round($mainSalary),
                'extra_salary' => round($extraSalary),
                'service_salary' => round($serviceSalary),
                'final_salary' => round($mainSalary + $extraSalary + $serviceSalary),
            ]);
        }

        return view('salary.monthly_detail', compact('user', 'dates', 'month'));
    }

    // ✅ Helper to compute extra salary for summary
    private function calculateExtraSalary($userId, $start, $end, $dailySalary)
    {
        $user = User::find($userId);
        $workingStart = $user->working_hours_start;
        $workingEnd   = $user->working_hours_end;

        $attendances = UserAttendance::where('user_id', $userId)
            ->whereBetween('check_in', [$start, $end])
            ->get();

        $total = 0;
        foreach ($attendances as $attendance) {
            if (!$attendance->check_in || !$attendance->check_out) continue;

            $checkIn = Carbon::parse($attendance->check_in);
            $checkOut = Carbon::parse($attendance->check_out);
            $workedHours = $checkOut->diffInMinutes($checkIn) / 60;

            $scheduledHours = 0;
            if ($workingStart && $workingEnd) {
                $scheduledStart = Carbon::parse($workingStart);
                $scheduledEnd   = Carbon::parse($workingEnd);
                $scheduledHours = $scheduledEnd->diffInMinutes($scheduledStart) / 60;
            }

            $extraHours = max(0, $workedHours - $scheduledHours);

            $hourlyRate = $scheduledHours > 0 ? $dailySalary / $scheduledHours : 0;
            $extraSalary = $extraHours * $hourlyRate * 1.5;

            // Add service hours (double pay)
            $serviceHours = Service::where('user_id', $userId)
                ->whereDate('start_date_time', Carbon::parse($attendance->date)->toDateString())
                ->get()
                ->sum(function ($service) {
                    if ($service->end_date_time && $service->start_date_time) {
                        return Carbon::parse($service->end_date_time)
                            ->diffInMinutes(Carbon::parse($service->start_date_time)) / 60;
                    }
                    return 0;
                });

            $serviceSalary = $serviceHours * $hourlyRate * 2;

            $total += ($extraSalary + $serviceSalary);
        }
        return $total;
    }
}
