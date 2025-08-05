<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLocation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LocationManagementController extends Controller
{
    public function index()
    {
        return view('location-management.index');
    }

    public function liveLocationTracking()
    {
        $today = Carbon::today();
        
        // Get all staff with their latest location today using raw SQL with proper parameter binding
        $staffWithLocations = collect(DB::select("
            SELECT 
                users.id,
                users.name,
                users.email,
                users.phone_number,
                users.uuid,
                MAX(user_locations.location_timestamp) as last_location_time,
                COUNT(user_locations.id) as location_count,
                (SELECT latitude FROM user_locations ul1 WHERE ul1.user_id = users.id AND DATE(ul1.location_timestamp) = ? ORDER BY ul1.location_timestamp DESC LIMIT 1) as latest_latitude,
                (SELECT longitude FROM user_locations ul2 WHERE ul2.user_id = users.id AND DATE(ul2.location_timestamp) = ? ORDER BY ul2.location_timestamp DESC LIMIT 1) as latest_longitude,
                (SELECT address FROM user_locations ul3 WHERE ul3.user_id = users.id AND DATE(ul3.location_timestamp) = ? ORDER BY ul3.location_timestamp DESC LIMIT 1) as latest_address
            FROM users 
            LEFT JOIN user_locations ON users.id = user_locations.user_id 
                AND DATE(user_locations.location_timestamp) = ?
            GROUP BY users.id, users.name, users.email, users.phone_number, users.uuid
            ORDER BY users.name
        ", [$today->format('Y-m-d'), $today->format('Y-m-d'), $today->format('Y-m-d'), $today->format('Y-m-d')]));

        return view('location-management.live-tracking', compact('staffWithLocations', 'today'));
    }

    public function getLiveLocationData(Request $request)
    {
        $today = Carbon::today();
        
        // Get all staff with their latest location today
        $staffWithLocations = collect(DB::select("
            SELECT 
                users.id,
                users.name,
                users.email,
                users.phone_number,
                users.uuid,
                MAX(user_locations.location_timestamp) as last_location_time,
                COUNT(user_locations.id) as location_count,
                (SELECT latitude FROM user_locations ul1 WHERE ul1.user_id = users.id AND DATE(ul1.location_timestamp) = ? ORDER BY ul1.location_timestamp DESC LIMIT 1) as latest_latitude,
                (SELECT longitude FROM user_locations ul2 WHERE ul2.user_id = users.id AND DATE(ul2.location_timestamp) = ? ORDER BY ul2.location_timestamp DESC LIMIT 1) as latest_longitude,
                (SELECT address FROM user_locations ul3 WHERE ul3.user_id = users.id AND DATE(ul3.location_timestamp) = ? ORDER BY ul3.location_timestamp DESC LIMIT 1) as latest_address
            FROM users 
            LEFT JOIN user_locations ON users.id = user_locations.user_id 
                AND DATE(user_locations.location_timestamp) = ?
            GROUP BY users.id, users.name, users.email, users.phone_number, users.uuid
            ORDER BY users.name
        ", [$today->format('Y-m-d'), $today->format('Y-m-d'), $today->format('Y-m-d'), $today->format('Y-m-d')]));

        return response()->json([
            'success' => true,
            'data' => $staffWithLocations,
            'date' => $today->format('Y-m-d')
        ]);
    }

    public function showUserLocations($userId)
    {
        $user = User::findOrFail($userId);
        $today = Carbon::today();
        
        $locations = UserLocation::where('user_id', $userId)
            ->whereDate('location_timestamp', $today)
            ->orderBy('location_timestamp', 'desc')
            ->get();

        return view('location-management.user-locations', compact('user', 'locations', 'today'));
    }

    public function getUserLocationTrail(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $locations = UserLocation::where('user_id', $userId)
            ->whereDate('location_timestamp', $date)
            ->orderBy('location_timestamp', 'asc')
            ->get();

        $locationData = $locations->map(function($location) {
            return [
                'id' => $location->id,
                'lat' => (float) $location->latitude,
                'lng' => (float) $location->longitude,
                'timestamp' => $location->location_timestamp->format('H:i:s'),
                'formatted_time' => $location->location_timestamp->format('g:i A'),
                'address' => $location->address,
                'accuracy' => $location->accuracy,
                'speed' => $location->speed,
                'altitude' => $location->altitude,
            ];
        });

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'date' => $date,
            'locations' => $locationData,
            'total_locations' => $locations->count(),
        ]);
    }

    public function getLocationData(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $locations = UserLocation::where('user_id', $userId)
            ->whereDate('location_timestamp', $date)
            ->orderBy('location_timestamp', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'locations' => $locations,
            'user' => $user,
            'date' => $date
        ]);
    }

    public function getUserLocationSummary(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $locations = UserLocation::where('user_id', $userId)
            ->whereDate('location_timestamp', $date)
            ->orderBy('location_timestamp', 'asc')
            ->get();

        $summary = [
            'total_locations' => $locations->count(),
            'first_location' => $locations->first(),
            'last_location' => $locations->last(),
            'duration' => null,
            'distance_covered' => 0,
        ];

        if ($summary['first_location'] && $summary['last_location']) {
            $start = Carbon::parse($summary['first_location']->location_timestamp);
            $end = Carbon::parse($summary['last_location']->location_timestamp);
            $summary['duration'] = $start->diffInMinutes($end);
        }

        return response()->json([
            'success' => true,
            'user' => $user,
            'date' => $date,
            'summary' => $summary,
            'locations' => $locations
        ]);
    }

    public function exportLocationData(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $locations = UserLocation::where('user_id', $userId)
            ->whereDate('location_timestamp', $date)
            ->orderBy('location_timestamp', 'asc')
            ->get();

        // Here you can implement CSV or PDF export
        // For now, return JSON
        return response()->json([
            'success' => true,
            'message' => 'Export functionality to be implemented',
            'data' => $locations
        ]);
    }

    public function getUsersWithLocationData(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        
        $users = User::whereHas('userLocations', function($query) use ($date) {
            $query->whereDate('location_timestamp', $date);
        })
        ->with(['userLocations' => function($query) use ($date) {
            $query->whereDate('location_timestamp', $date)
                  ->orderBy('location_timestamp', 'desc')
                  ->take(1);
        }])
        ->get();

        return response()->json([
            'success' => true,
            'users' => $users,
            'date' => $date
        ]);
    }

    public function cleanupOldLocations(Request $request)
    {
        $days = $request->get('days', 30);
        $cutoffDate = Carbon::now()->subDays($days);
        
        $deletedCount = UserLocation::where('location_timestamp', '<', $cutoffDate)->delete();
        
        return response()->json([
            'success' => true,
            'message' => "Deleted {$deletedCount} old location records older than {$days} days",
            'deleted_count' => $deletedCount
        ]);
    }
}
