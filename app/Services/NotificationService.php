<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Ticket;
use App\Models\User;

class NotificationService
{
    /**
     * Send notification to Android app via FCM (Firebase Cloud Messaging)
     */
    public function sendTicketNotification(Ticket $ticket, $type = 'ticket_created')
    {
        try {
            // Get the assigned user's FCM token
            $assignedUser = $ticket->assignedTo;
            
            if (!$assignedUser || !$assignedUser->fcm_token) {
                Log::warning('No FCM token found for assigned user', ['ticket_id' => $ticket->id]);
                return false;
            }

            $notificationData = [
                'title' => $this->getNotificationTitle($type),
                'body' => $this->getNotificationBody($ticket, $type),
                'data' => [
                    'type' => $type,
                    'ticket_id' => $ticket->id,
                    'ticket_uuid' => $ticket->uuid,
                    'subject' => $ticket->subject,
                    'customer_name' => $ticket->customer->name ?? 'N/A',
                    'assigned_to' => $assignedUser->name,
                    'created_at' => $ticket->created_at->toISOString(),
                ]
            ];

            return $this->sendFCMNotification($assignedUser->fcm_token, $notificationData);
            
        } catch (\Exception $e) {
            Log::error('Failed to send ticket notification', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send FCM notification using Firebase Cloud Messaging
     */
    private function sendFCMNotification($fcmToken, $notificationData)
    {
        $serverKey = config('services.firebase.server_key');
        
        if (!$serverKey) {
            Log::error('Firebase server key not configured');
            return false;
        }

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        
        $payload = [
            'to' => $fcmToken,
            'notification' => [
                'title' => $notificationData['title'],
                'body' => $notificationData['body'],
                'sound' => 'default',
                'badge' => 1,
            ],
            'data' => $notificationData['data'],
            'priority' => 'high',
            'content_available' => true,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post($fcmUrl, $payload);

        if ($response->successful()) {
            Log::info('FCM notification sent successfully', [
                'fcm_token' => $fcmToken,
                'response' => $response->json()
            ]);
            return true;
        } else {
            Log::error('FCM notification failed', [
                'fcm_token' => $fcmToken,
                'response' => $response->json(),
                'status' => $response->status()
            ]);
            return false;
        }
    }

    /**
     * Get notification title based on type
     */
    private function getNotificationTitle($type)
    {
        switch ($type) {
            case 'ticket_created':
                return 'New Ticket Assigned';
            case 'ticket_updated':
                return 'Ticket Updated';
            case 'ticket_completed':
                return 'Ticket Completed';
            default:
                return 'Ticket Notification';
        }
    }

    /**
     * Get notification body based on ticket and type
     */
    private function getNotificationBody(Ticket $ticket, $type)
    {
        switch ($type) {
            case 'ticket_created':
                return "New ticket #{$ticket->id} has been assigned to you: {$ticket->subject}";
            case 'ticket_updated':
                return "Ticket #{$ticket->id} has been updated: {$ticket->subject}";
            case 'ticket_completed':
                return "Ticket #{$ticket->id} has been completed: {$ticket->subject}";
            default:
                return "Ticket #{$ticket->id}: {$ticket->subject}";
        }
    }

    /**
     * Send notification to multiple users
     */
    public function sendMultipleNotifications(array $fcmTokens, $notificationData)
    {
        $serverKey = config('services.firebase.server_key');
        
        if (!$serverKey) {
            Log::error('Firebase server key not configured');
            return false;
        }

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        
        $payload = [
            'registration_ids' => $fcmTokens,
            'notification' => [
                'title' => $notificationData['title'],
                'body' => $notificationData['body'],
                'sound' => 'default',
                'badge' => 1,
            ],
            'data' => $notificationData['data'],
            'priority' => 'high',
            'content_available' => true,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post($fcmUrl, $payload);

        return $response->successful();
    }
}
