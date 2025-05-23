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

    public function storeTicketEntry(Request $request, $uuid)
    {
        try {
            $ticket = Ticket::where('uuid', $uuid)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No ticket found for the provided UUID.',
            ], 404);
        }

        $data = json_decode($request->input('data'), true);


        $validator = Validator::make([
            'type' => $request->type,
            'start' => $request->start,
            'end' => $request->end,
            'log' => $request->log,
            'data' => $data,
        ], [
            'type' => 'required|in:delivery,service',
            'start' => 'required|date',
            'end' => 'nullable|date',
            'log' => 'nullable|string',
            'data' => 'required|array',
            'data.items' => 'nullable|array',
            'data.items.*.item' => 'required_with:data.items|string',
            'data.items.*.qty' => 'required_with:data.items|numeric',
            'data.items.*.rate' => 'required_with:data.items|numeric',
            'data.items.*.amount' => 'required_with:data.items|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = $request->input('type');
        $items = $data['items'] ?? [];

        $ticket->update([
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'log' => $data['log'] ?? null,
        ]);

        if ($type === 'service') {
            // Remove items before inserting service record
            unset($data['items']);

            $service = Service::create(array_merge($data, [
                'ticket_id' => $ticket->id,
            ]));

            foreach ($items as $item) {
                $service->serviceItems()->create($item);
            }

        } elseif ($type === 'delivery') {
            unset($data['items']);

            $delivery = Delivery::create(array_merge($data, [
                'ticket_id' => $ticket->id,
            ]));



            foreach ($items as $item) {
                $delivery->serviceItems()->create($item);
            }
        }

        return response()->json([
            'message' => ucfirst($type) . ' entry saved successfully.'
        ]);
    }
}
