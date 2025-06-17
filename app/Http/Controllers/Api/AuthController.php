<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends Controller
{
    // Login API
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 401); // 401 = Unauthorized
        }

        $user = Auth::user();

        // Allow only staff roles
        if (!$user->hasRole(['marketing', 'staff'])) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        $user->approval_required = 'yes';
        $user->save();

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'message' => 'Login successful, waiting for admin approval'
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }


    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user()->id,
            'phone_number' => 'nullable|string|max:15',
            'working_days' => 'nullable|string', // e.g., "monday,tuesday"
            'working_hours_start' => 'nullable|date_format:H:i:s',
            'working_hours_end' => 'nullable|date_format:H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $profile_photo_url = $user->profile_photo;
        if ($request->filled('profile_photo')) {
            $imageData = $request->input('profile_photo');

            // Match base64 with data URI scheme (optional but safer)
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $image = substr($imageData, strpos($imageData, ',') + 1);
                $image = base64_decode($image);
                $extension = strtolower($type[1]); // jpg, png, etc.

                $filename = uniqid('profile_', true) . '.' . $extension;
                $path = storage_path("app/public/profile_photos/{$filename}");

                file_put_contents($path, $image);

                $profile_photo_url = "profile_photos/{$filename}";
            }
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'working_days' => $request->input('working_days'),
            'working_hours_start' => $request->input('working_hours_start'),
            'working_hours_end' => $request->input('working_hours_end'),
            'profile_photo' => $profile_photo_url
        ]);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => $user,
        ]);
    }

    public function getQR(Request $request)
    {
        $user = $request->user();
        $imageUrl = route('showQr', $user->uuid);
        return response()->json([
            'qr_image_url' => $imageUrl,
        ]);
    }

    public function checkApprovalStatus(Request $request)
    {
        $user = $request->user(); // get currently authenticated user

        $isApproved = !($user->approval_required === 'yes');

        return response()->json([
            'approved' => $isApproved,
            'status' => $isApproved ? 'approved' : 'Waiting for approval',
        ]);
    }

}
