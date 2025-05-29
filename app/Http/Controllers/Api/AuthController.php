<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
}
