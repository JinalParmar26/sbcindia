<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAttendance;
use App\Models\Service;
use App\Models\UserSalary;
use Carbon\Carbon;

class SalaryCalculatorService
{
    public function calculate(User $user, Carbon $date): ?UserSalary
    {
        if ($user->calculate_salary != 1) {
            return null;
        }

        // Get attendance for the day
        $attendance = UserAttendance::where('user_id', $user->id)
            ->whereDate('check_in', $date)
            ->first();

        if (!$attendance || !$attendance->check_in || !$attendance->check_out) {
            return null; // No attendance = no salary
        }

        $workingStart = Carbon::parse($date->format('Y-m-d') . ' ' . $user->working_hours_start);
        $workingEnd   = Carbon::parse($date->format('Y-m-d') . ' ' . $user->working_hours_end);

        $checkIn  = Carbon::parse($attendance->check_in);
        $checkOut = Carbon::parse($attendance->check_out);

        $hourlyRate = $user->Salary / 30 / $workingStart->diffInHours($workingEnd);

        $normalHours = 0;
        $extraHours = 0;
        $serviceHours = 0;

        // Services for the day
        $services = Service::where('user_id', $user->id)
            ->whereDate('start_date_time', $date)
            ->get();

        // Base normal hours (inside working hours)
        $workPeriod = $checkIn->copy()->between($workingStart, $workingEnd) ? $checkIn : $workingStart;
        $workPeriodEnd = $checkOut->lt($workingEnd) ? $checkOut : $workingEnd;

        if ($workPeriodEnd->gt($workPeriod)) {
            $normalHours = $workPeriodEnd->diffInHours($workPeriod);
        }

        // Check services
        foreach ($services as $service) {
            $sStart = Carbon::parse($service->start_date_time);
            $sEnd   = Carbon::parse($service->end_date_time);

            if ($sEnd && $sStart) {
                $serviceHours += $sEnd->diffInHours($sStart);
            }
        }

        // Extra hours (after office end until checkout)
        if ($checkOut->gt($workingEnd)) {
            $extraHours = $checkOut->diffInHours($workingEnd) - $serviceHours;
            if ($extraHours < 0) $extraHours = 0;
        }

        // Salary calculation
        $normalSalary  = $normalHours * $hourlyRate;
        $serviceSalary = $serviceHours * $hourlyRate * 2;
        $extraSalary   = $extraHours * $hourlyRate * 1.5;
        $totalSalary   = $normalSalary + $serviceSalary + $extraSalary;

        return UserSalary::updateOrCreate(
            ['user_id' => $user->id, 'salary_date' => $date->toDateString()],
            [
                'check_in' => $checkIn->format('H:i:s'),
                'check_out' => $checkOut->format('H:i:s'),
                'normal_hours' => $normalHours,
                'service_hours' => $serviceHours,
                'extra_hours' => $extraHours,
                'normal_salary' => $normalSalary,
                'service_salary' => $serviceSalary,
                'extra_salary' => $extraSalary,
                'total_salary' => $totalSalary,
            ]
        );
    }
}
