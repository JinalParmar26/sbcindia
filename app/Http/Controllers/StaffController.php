<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAttendance;
use Illuminate\Http\Request;
use App\Services\PdfExportService;

class StaffController extends Controller
{
    protected $pdfExportService;

    public function __construct(PdfExportService $pdfExportService)
    {
        $this->pdfExportService = $pdfExportService;
    }

    /**
     * Display a listing of staff members.
     */
    public function index()
    {
        $staffMembers = User::with(['userAttendances' => function($query) {
            $query->whereDate('check_in', date('Y-m-d'))
                  ->orderBy('check_in', 'desc');
        }])->get();

        return view('staff.index', compact('staffMembers'));
    }

    /**
     * Handle check-out for a specific user.
     */
    public function checkOut(Request $request, User $user)
    {
        // Find the latest attendance record for today that doesn't have check_out
        $attendance = UserAttendance::where('user_id', $user->id)
            ->whereDate('check_in', date('Y-m-d'))
            ->whereNull('check_out')
            ->latest('check_in')
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'No active check-in found for this user today.');
        }

        // Update the attendance record with check-out information
        $attendance->update([
            'check_out' => date('Y-m-d H:i:s'),
            'check_out_latitude' => $request->input('latitude'),
            'check_out_longitude' => $request->input('longitude'),
            'check_out_location_name' => $request->input('location_name', 'Admin checkout'),
        ]);

        return redirect()->back()->with('success', 'User checked out successfully.');
    }

    /**
     * Get attendance details for a specific user.
     */
    public function getAttendanceDetails(User $user)
    {
        $todayAttendance = UserAttendance::where('user_id', $user->id)
            ->whereDate('check_in', date('Y-m-d'))
            ->orderBy('check_in', 'desc')
            ->first();

        return response()->json([
            'user' => $user,
            'attendance' => $todayAttendance,
            'status' => $todayAttendance ? 
                ($todayAttendance->check_out ? 'checked_out' : 'checked_in') : 
                'not_checked_in'
        ]);
    }

    /**
     * Export staff to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = User::with(['userAttendances' => function($query) {
            $query->whereDate('check_in', date('Y-m-d'))
                  ->orderBy('check_in', 'desc');
        }]);

        // Apply filters
        $filters = [];
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            $filters['search'] = $request->search;
        }

        if ($request->filled('status_filter') && $request->status_filter != 'all') {
            $query->where('status', $request->status_filter);
            $filters['status_filter'] = $request->status_filter;
        }

        $staff = $query->get();

        return $this->pdfExportService->generateStaffPdf($staff, $filters);
    }

    /**
     * Export staff attendance to PDF
     */
    public function exportAttendancePdf(Request $request)
    {
        $query = UserAttendance::with(['user']);

        // Apply filters
        $filters = [];
        
        if ($request->filled('date_from')) {
            $query->whereDate('check_in', '>=', $request->date_from);
            $filters['date_from'] = $request->date_from;
        }

        if ($request->filled('date_to')) {
            $query->whereDate('check_in', '<=', $request->date_to);
            $filters['date_to'] = $request->date_to;
        }

        if ($request->filled('user_id') && $request->user_id != 'all') {
            $query->where('user_id', $request->user_id);
            $filters['user_id'] = $request->user_id;
        }

        $attendanceData = $query->orderBy('check_in', 'desc')->get();

        return $this->pdfExportService->generateStaffAttendancePdf($attendanceData, $filters);
    }
}
