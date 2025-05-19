<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Service;
use App\Models\Delivery;
use App\Models\ServiceItem;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function assignedTickets(Request $request)
    {

        $tickets = Ticket::with(['customer', 'orderProduct.product'])
            ->where('assigned_to', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['tickets' => $tickets]);
    }

    public function show($uuid)
    {
        $ticket = Ticket::with([
            'customer',
            'contactPerson',
            'assignedTo',
            'orderProduct.product',
        ])->where('uuid', $uuid)->firstOrFail();

        return response()->json([
            'ticket' => $ticket
        ]);
    }

    public function storeTicketEntry(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:delivery,service',
            'start' => 'required',
            'end' => 'nullable',
            'items' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = $request->type;
        $data = $request->data;
        $items = $request->items ?? [];

        if ($type === 'service') {
            $service = Service::create([
                'ticket_id' => $ticket->id,
                ...$data
            ]);
            foreach ($items as $item) {
                ServiceItem::create([
                    'service_type' => 'service',
                    'service_id' => $service->id,
                    ...$item
                ]);
            }
        } elseif ($type === 'delivery') {
            $delivery = Delivery::create([
                'ticket_id' => $ticket->id,
                ...$data
            ]);
            foreach ($items as $item) {
                ServiceItem::create([
                    'service_type' => 'delivery',
                    'service_id' => $delivery->id,
                    ...$item
                ]);
            }
        }

        return response()->json(['message' => ucfirst($type) . ' entry saved.']);
    }
}
