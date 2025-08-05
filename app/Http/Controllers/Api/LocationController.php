<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LocationController extends Controller
{
    /**
     * Store location data from mobile app
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'address' => 'nullable|string|max:500',
                'accuracy' => 'nullable|numeric|min:0',
                'altitude' => 'nullable|numeric',
                'speed' => 'nullable|numeric|min:0',
                'provider' => 'nullable|string|max:50',
                'location_timestamp' => 'required|date',
                'additional_data' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create location record
            $location = UserLocation::create([
                'user_id' => $request->user_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address,
                'accuracy' => $request->accuracy,
                'altitude' => $request->altitude,
                'speed' => $request->speed,
                'provider' => $request->provider,
                'location_timestamp' => $request->location_timestamp,
                'additional_data' => $request->additional_data
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Location stored successfully',
                'data' => $location
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store multiple location data (bulk insert)
     */
    public function storeBulk(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'locations' => 'required|array|min:1|max:100',
                'locations.*.user_id' => 'required|exists:users,id',
                'locations.*.latitude' => 'required|numeric|between:-90,90',
                'locations.*.longitude' => 'required|numeric|between:-180,180',
                'locations.*.address' => 'nullable|string|max:500',
                'locations.*.accuracy' => 'nullable|numeric|min:0',
                'locations.*.altitude' => 'nullable|numeric',
                'locations.*.speed' => 'nullable|numeric|min:0',
                'locations.*.provider' => 'nullable|string|max:50',
                'locations.*.location_timestamp' => 'required|date',
                'locations.*.additional_data' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare data for bulk insert
            $locationData = collect($request->locations)->map(function ($location) {
                return [
                    'user_id' => $location['user_id'],
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                    'address' => $location['address'] ?? null,
                    'accuracy' => $location['accuracy'] ?? null,
                    'altitude' => $location['altitude'] ?? null,
                    'speed' => $location['speed'] ?? null,
                    'provider' => $location['provider'] ?? null,
                    'location_timestamp' => $location['location_timestamp'],
                    'additional_data' => isset($location['additional_data']) ? json_encode($location['additional_data']) : null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->toArray();

            // Bulk insert
            UserLocation::insert($locationData);

            return response()->json([
                'success' => true,
                'message' => 'Locations stored successfully',
                'count' => count($locationData)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to store locations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user locations for a specific date
     */
    public function getUserLocations(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'date' => 'required|date',
                'limit' => 'nullable|integer|min:1|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $locations = UserLocation::forUser($request->user_id)
                ->forDate($request->date)
                ->orderBy('location_timestamp', 'asc')
                ->limit($request->limit ?? 500)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $locations,
                'count' => $locations->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch locations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user locations for a date range
     */
    public function getUserLocationRange(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'limit' => 'nullable|integer|min:1|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $locations = UserLocation::forUser($request->user_id)
                ->forDateRange($request->start_date, $request->end_date)
                ->orderBy('location_timestamp', 'asc')
                ->limit($request->limit ?? 1000)
                ->get();

            // Group by date
            $groupedLocations = $locations->groupBy(function($location) {
                return Carbon::parse($location->location_timestamp)->format('Y-m-d');
            });

            return response()->json([
                'success' => true,
                'data' => $groupedLocations,
                'total_count' => $locations->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch location range',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get location statistics for a user
     */
    public function getLocationStats(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'date' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $query = UserLocation::forUser($request->user_id);
            
            if ($request->date) {
                $query->forDate($request->date);
            }

            $stats = [
                'total_locations' => $query->count(),
                'first_location' => $query->orderBy('location_timestamp', 'asc')->first(),
                'last_location' => $query->orderBy('location_timestamp', 'desc')->first(),
                'date_range' => [
                    'start' => $query->min('location_timestamp'),
                    'end' => $query->max('location_timestamp')
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch location statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
