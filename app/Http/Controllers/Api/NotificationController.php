<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Register FCM token for the authenticated user
     */
    public function registerFCMToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = $request->user();
            $user->fcm_token = $request->fcm_token;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'FCM token registered successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to register FCM token', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to register FCM token'
            ], 500);
        }
    }

    /**
     * Send test notification to user
     */
    public function sendTestNotification(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user->fcm_token) {
                return response()->json([
                    'success' => false,
                    'message' => 'No FCM token found for user'
                ], 400);
            }

            // Create a dummy ticket for testing
            $testNotificationData = [
                'title' => 'Test Notification',
                'body' => 'This is a test notification from your ERP system',
                'data' => [
                    'type' => 'test',
                    'message' => 'Test notification sent successfully',
                    'sent_at' => now()->toISOString(),
                ]
            ];

            $serverKey = config('services.firebase.server_key');
            
            if (!$serverKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'Firebase server key not configured'
                ], 500);
            }

            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
            
            $payload = [
                'to' => $user->fcm_token,
                'notification' => [
                    'title' => $testNotificationData['title'],
                    'body' => $testNotificationData['body'],
                    'sound' => 'default',
                    'badge' => 1,
                ],
                'data' => $testNotificationData['data'],
                'priority' => 'high',
                'content_available' => true,
            ];

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ])->post($fcmUrl, $payload);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test notification sent successfully',
                    'response' => $response->json()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send test notification',
                    'error' => $response->json()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Failed to send test notification', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send test notification'
            ], 500);
        }
    }

    /**
     * Send notification for specific ticket
     */
    public function sendTicketNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_uuid' => 'required|string',
            'type' => 'required|string|in:ticket_created,ticket_updated,ticket_completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $ticket = Ticket::where('uuid', $request->ticket_uuid)->firstOrFail();
            
            $result = $this->notificationService->sendTicketNotification($ticket, $request->type);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification sent successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send notification'
                ], 500);
            }

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to send ticket notification', [
                'ticket_uuid' => $request->ticket_uuid,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification'
            ], 500);
        }
    }

    /**
     * Get notification settings for user
     */
    public function getNotificationSettings(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'fcm_token_registered' => !empty($user->fcm_token),
                'notifications_enabled' => $user->notifications_enabled ?? true,
                'ticket_notifications' => $user->ticket_notifications ?? true,
                'attendance_notifications' => $user->attendance_notifications ?? true,
            ]
        ]);
    }

    /**
     * Update notification settings
     */
    public function updateNotificationSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notifications_enabled' => 'boolean',
            'ticket_notifications' => 'boolean',
            'attendance_notifications' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $user = $request->user();
            
            if ($request->has('notifications_enabled')) {
                $user->notifications_enabled = $request->notifications_enabled;
            }
            
            if ($request->has('ticket_notifications')) {
                $user->ticket_notifications = $request->ticket_notifications;
            }
            
            if ($request->has('attendance_notifications')) {
                $user->attendance_notifications = $request->attendance_notifications;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Notification settings updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update notification settings', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update notification settings'
            ], 500);
        }
    }
}
