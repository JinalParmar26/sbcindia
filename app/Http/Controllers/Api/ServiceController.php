<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Get all services assigned to the authenticated user
     */
    public function userServices(Request $request)
    {
        try {
            $user = Auth::user();
            return response()->json([
                'user' => $user
            ], 200);

            // Fetch services for this user with required relations
            $services = Service::with([
                'ticket.customer',
                'ticket.orderProduct',
            ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

            // Format response
            $data = $services->map(function ($service) {
                return [
                    'service_uuid'        => $service->uuid,
                    'customer_name'       => $service->ticket->customer->name ?? null,
                    'product_serial_no'   => $service->ticket->orderProduct->serial_number ?? null,
                    'product_model_no'    => $service->ticket->orderProduct->model_number ?? null,
                    'ticket_subject'      => $service->ticket->subject ?? null,
                    'ticket_created_at'   => optional($service->ticket->created_at)->format('Y-m-d H:i:s'),
                    'service_start_time'  => $service->start_time ? $service->start_time->format('Y-m-d H:i:s') : null,
                    'service_end_time'    => $service->end_time ? $service->end_time->format('Y-m-d H:i:s') : null,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Services fetched successfully',
                'data' => $data,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch services',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
