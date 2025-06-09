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

        try {
            $ticket = Ticket::where('uuid', $uuid)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No ticket found for the provided UUID.',
            ], 404);
        }
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
        try{
            $data = json_decode($request->input('data'), true);

            $rules = [];

            if ($request->has('type')) {
                $rules['type'] = 'required|in:delivery,service';
            }
            if ($request->has('start') && $request->input('start') != '') {
                $rules['start'] = 'required|date';
            }
            if ($request->has('end') && $request->input('end') != '') {
                $rules['end'] = 'nullable|date';
            }
            if ($request->has('log') && $request->input('log') != '') {
                $rules['log'] = 'nullable|string';
            }
            if ($request->has('data')) {
                //$rules['data'] = 'required|array';

                if (isset($request->data['items'])) {
                    //$rules['data.items'] = 'array';
                    $rules['data.items.*.item'] = 'required_with:data.items|string';
                    $rules['data.items.*.qty'] = 'required_with:data.items|numeric';
                    $rules['data.items.*.rate'] = 'required_with:data.items|numeric';
                    $rules['data.items.*.amount'] = 'required_with:data.items|numeric';
                }
            }

            $validator = $request->validate($rules);


            // if ($validator->fails()) {
            //     return response()->json(['errors' => $validator->errors()], 422);
            // }

            $type = $request->input('type');
            $items = $data['items'] ?? [];

            $updateData = [];

            if ($request->has('start')) {
                $updateData['start'] = $request->input('start');
            }

            if ($request->has('end')) {
                $updateData['end'] = $request->input('end');
            }

            if (isset($data['log'])) {
                $updateData['log'] = $data['log'];
            }


            $ticket->update($updateData);

            if ($type === 'service') {
                // Remove items before inserting service record
                unset($data['items']);

                $service = Service::updateOrCreate(
                    ['ticket_id' => $ticket->id],
                    array_merge($data, ['ticket_id' => $ticket->id])
                );

                if($items) {
                    $service->serviceItems()->delete();


                    foreach ($items as $item) {
                        $service->serviceItems()->create($item);
                    }
                }

            } elseif ($type === 'delivery') {
                unset($data['items']);

                $delivery = Delivery::updateOrCreate(['ticket_id' => $ticket->id],array_merge($data, [
                    'ticket_id' => $ticket->id,
                ]));


                if($items) {
                    $delivery->serviceItems()->delete();


                    foreach ($items as $item) {
                        $delivery->serviceItems()->create($item);
                    }
                }
            }

            return response()->json([
                'message' => ucfirst($type) . ' entry saved successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([

                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
