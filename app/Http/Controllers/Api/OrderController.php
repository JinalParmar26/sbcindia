<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function show($uuid)
    {
        $order = Order::with([
            'customer',
            'images',
            'orderProducts.product',
            'orderProducts.tickets' => function ($q) {
                $q->with([
                    'customer',
                    'contactPerson',
                    'assignedTo',
                    'attendedBy',
                    'additionalStaff',
                    'images',
                    'services' => function ($s) {
                        $s->with(['serviceItems', 'user']);
                    }
                ]);
            }
        ])->where('uuid', $uuid)->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $order
        ]);
    }
}