<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $customers = Customer::all();
        $products = OrderProduct::with('order', 'product')->get();
        $staff = User::role('staff')->get(); // Assuming roles are used
        return view('tickets.create', compact('customers', 'products', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'customer_contact_person_id' => 'required|exists:customer_contact_person,id',
            'order_product_id' => 'required|exists:order_products,id',
            'assigned_to' => 'required|exists:users,id',
            'additional_staff' => 'nullable|array',
            'additional_staff.*' => 'exists:users,id'
        ]);

        $validated['attended_by'] = auth()->id();
        $validated['uuid'] = Str::uuid()->toString();

        $ticket = Ticket::create($validated);
        $ticket->additionalStaff()->sync($validated['additional_staff'] ?? []);

        return redirect()->route('tickets.show', $ticket->uuid)->with('success', 'Ticket created successfully.');
    }

    public function show($uuid)
    {
        $ticket = Ticket::with(['customer', 'orderProduct.product', 'assignedTo', 'additionalStaff'])->where('uuid', $uuid)->firstOrFail();
        return view('tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = Ticket::findOrFail($id);
        $customers = Customer::all();
        $products = OrderProduct::with('order', 'product')->get();
        $staff = User::role('staff')->get();
        $customerContacts =  $ticket->customer->contactPersons;
        return view('tickets.edit', compact('ticket', 'customers', 'products', 'staff','customerContacts'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'customer_id' => 'required|exists:customers,id',
            'customer_contact_person_id' => 'required|exists:customer_contact_person,id',
            'order_product_id' => 'required|exists:order_products,id',
            'attended_by' => auth()->id(),
            'assigned_to' => 'required|exists:users,id',
            'additional_staff' => 'nullable|array',
            'additional_staff.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $ticket->update($validated);
        $ticket->additionalStaff()->sync($validated['additional_staff'] ?? []);

        return redirect()->route('tickets.show', $ticket->uuid)->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets')->with('success', 'Ticket deleted.');
    }
}
