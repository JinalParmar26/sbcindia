<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PublicStaffController extends Controller
{
    /**
     * Display staff visiting card - public access
     */
    public function visitingCard($uuid)
    {
        try {
            $staff = User::where('uuid', $uuid)
                ->where('isActive', true)
                ->firstOrFail();
            
            return view('public.staff-visiting-card', compact('staff'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->view('errors.404', [
                'message' => 'Staff member not found or inactive.',
                'uuid' => $uuid
            ], 404);
        }
    }
    
    /**
     * Display staff profile - public access with more details
     */
    public function profile($uuid)
    {
        try {
            $staff = User::where('uuid', $uuid)
                ->where('isActive', true)
                ->firstOrFail();
            
            return view('public.staff-profile', compact('staff'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->view('errors.404', [
                'message' => 'Staff member not found or inactive.',
                'uuid' => $uuid
            ], 404);
        }
    }
}
