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
use Exception;

class AuthController extends Controller
{
    // Login API
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'uid' => ['required', 'string'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials.'
            ], 401); // 401 = Unauthorized
        }

        $user = Auth::user();

        $token = $user->createToken('api-token')->plainTextToken;

        // Store/update the UID and check for approval bypass
        $requestUid = $request->input('uid');
        
        // Check if the provided UID matches the stored UID
        $user->approval_required = 'no';
        if ($user->uid != $requestUid) {
            $user->approval_required = 'yes';
        }
        // If UID doesn't match, keep current approval_required value
        
        // Always update the UID
        $user->uid = $requestUid;
        $user->save();

        // Prepare response based on current approval_required status
        $response = [
            'token' => $token,
            'token_type' => 'Bearer',
        ];

        // Only include approval_required in response if it's "yes"
        if ($user->approval_required === 'yes') {
            $response['message'] = 'Login successful, waiting for admin approval';
            $response['approval_required'] = 'yes';
        } else {
            $response['approval_required'] = 'no';
            $response['message'] = 'Login successful';
        }

        return response()->json($response);
    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        
        // Create user data array with full URLs for photos
        $userData = $user->toArray();
        
        // Add full URLs for profile photo and signature photo if they exist
        if ($user->profile_photo) {
            $userData['profile_photo_url'] = url('storage/' . $user->profile_photo);
        }
        
        if ($user->sign_photo) {
            $userData['sign_photo_url'] = url('storage/' . $user->sign_photo);
        }
        
        return response()->json($userData);
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
        $user = auth()->user();
        $profileUrl = url("/user-profile/{$user->uuid}");
        $qrCode = QrCode::format('png')->size(150)->generate($profileUrl);
        $base64 = base64_encode($qrCode);

        return response()->json([
            'qr_code' => 'data:image/png;base64,' . $base64
        ]);
        /*
        $qrCode = QrCode::format('png')->size(150)->generate(route('showPublicProfile', $user->uuid));
        return response($qrCode)->header('Content-Type', 'image/png');
        */
    }

    public function checkApprovalStatus(Request $request)
    {
        $user = auth()->user(); // get currently authenticated user

        $isApproved = !($user->approval_required === 'yes');

        return response()->json([
            'approved' => $isApproved,
            'status' => $isApproved ? 'approved' : 'Waiting for approval',
        ]);
    }

    public function uploadPhotos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'profile_photo' => 'nullable', // can be file or base64 string
            'sign_photo' => 'nullable',    // can be file or base64 string
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // At least one photo must be provided
        if (!$request->hasFile('profile_photo') && !$request->hasFile('sign_photo') &&
            !$request->filled('profile_photo') && !$request->filled('sign_photo')) {
            return response()->json([
                'error' => 'At least one photo (profile_photo or sign_photo) must be provided'
            ], 400);
        }

        $user = $request->user();
        $response = ['message' => 'Photos uploaded successfully'];

        // Profile photo
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
            $response['profile_photo_url'] = url('storage/' . $path);
        } elseif ($request->filled('profile_photo')) {
            // base64 string support
            $path = $this->uploadBase64Image($request->input('profile_photo'), 'profile_photos');
            if ($path) {
                $user->profile_photo = $path;
                $response['profile_photo_url'] = url('storage/' . $path);
            }
        }

        // Signature photo
        if ($request->hasFile('sign_photo')) {
            $path = $request->file('sign_photo')->store('sign_photos', 'public');
            $user->sign_photo = $path;
            $response['sign_photo_url'] = url('storage/' . $path);
        } elseif ($request->filled('sign_photo')) {
            // base64 string support
            $path = $this->uploadBase64Image($request->input('sign_photo'), 'sign_photos');
            if ($path) {
                $user->sign_photo = $path;
                $response['sign_photo_url'] = url('storage/' . $path);
            }
        }

        $user->save();

        $response['user_id'] = $user->id;
        $response['user_name'] = $user->name;

        return response()->json($response);
    }

    private function downloadAndSaveImage($url, $directory)
    {
        try {
            $imageContents = @file_get_contents($url);
            if ($imageContents === false) {
                return false;
            }

            $info = pathinfo(parse_url($url, PHP_URL_PATH));
            $extension = strtolower($info['extension'] ?? 'jpg');

            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                $extension = 'jpg'; // fallback
            }

            $prefix = str_replace('_photos', '', $directory);
            $filename = $prefix . '_' . uniqid('', true) . '.' . $extension;
            $directoryPath = storage_path("app/public/{$directory}");

            if (!file_exists($directoryPath)) {
                mkdir($directoryPath, 0755, true);
            }

            $path = $directoryPath . '/' . $filename;
            $result = file_put_contents($path, $imageContents);

            return $result ? $directory . '/' . $filename : false;
        } catch (\Exception $e) {
            \Log::error("Download error: " . $e->getMessage());
            return false;
        }
    }

    public function updateUserProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'department' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $updated = false;
        $updatedFields = [];

        // Update name if provided
        if ($request->filled('name')) {
            $user->name = $request->input('name');
            $updatedFields[] = 'name';
            $updated = true;
        }

        // Update phone_number if provided
        if ($request->filled('phone_number')) {
            $user->phone_number = $request->input('phone_number');
            $updatedFields[] = 'phone_number';
            $updated = true;
        }

        // Update department if provided
        if ($request->filled('department')) {
            $user->department = $request->input('department');
            $updatedFields[] = 'department';
            $updated = true;
        }

        if (!$updated) {
            return response()->json([
                'message' => 'No fields provided for update'
            ], 400);
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'updated_fields' => $updatedFields,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'department' => $user->department,
                'updated_at' => $user->updated_at
            ]
        ]);
    }

}
