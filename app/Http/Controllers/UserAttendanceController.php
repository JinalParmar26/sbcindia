<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAttendance;
use Carbon\Carbon;

class UserAttendanceController extends Controller
{
    public function detail($uuid, Request $request)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();

        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Attendance records keyed by date
        $attendancesRaw = UserAttendance::where('user_id', $user->id)
            ->whereBetween('check_in', [$start, $end])
            ->get()
            ->keyBy(function($item){
                return Carbon::parse($item->check_in)->format('Y-m-d');
            });

        // Build calendar grid (7x6)
        $daysInMonth = $start->daysInMonth;
        $firstDayOfWeek = $start->dayOfWeekIso; // 1=Mon ... 7=Sun
        $calendar = [];
        $week = [];

        // Fill empty cells before first day
        for($i=1; $i<$firstDayOfWeek; $i++){
            $week[] = null;
        }

        for($day=1; $day<=$daysInMonth; $day++){
            $currentDate = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
            $attendance = $attendancesRaw[$currentDate] ?? null;
            $week[] = [
                'date' => Carbon::createFromDate($year, $month, $day),
                'attendance' => $attendance
            ];

            if(count($week) == 7){
                $calendar[] = $week;
                $week = [];
            }
        }

        // Fill remaining cells
        if(count($week) > 0){
            while(count($week) < 7) $week[] = null;
            $calendar[] = $week;
        }

        return view('attendance.detail', compact('user', 'calendar', 'month', 'year'));
    }
}
