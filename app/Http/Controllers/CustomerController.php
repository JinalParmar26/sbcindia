<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerContactPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Listing is handled by Livewire
        return redirect()->route('customers.index');
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone_number' => ['required', 'digits:10'],
            'address' => 'required|string|max:1000',
            'contact_persons.*.name' => 'required|string|max:255',
            'contact_persons.*.email' => 'nullable|email',
            'contact_persons.*.phone_number' => 'nullable|string',
            'contact_persons.*.alternate_phone_number' => 'nullable|string',
        ]);

        $validated['uuid'] = Str::uuid()->toString();

        $customer = Customer::create([
            'uuid' => $validated['uuid'],
            'name' => $validated['name'],
            'company_name' => $validated['company_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
        ]);

        foreach ($request->input('contact_persons', []) as $person) {
            $customer->contactPersons()->create([
                'uuid' => Str::uuid(),
                'name' => $person['name'],
                'email' => $person['email'] ?? null,
                'phone_number' => $person['phone_number'] ?? null,
                'alternate_phone_number' => $person['alternate_phone_number'] ?? null,
            ]);
        }

        return redirect()->route('customers.show', $customer->uuid)->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer (by UUID).
     */
    public function show($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->firstOrFail();
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required',
            'address' => 'required',
            'contact_persons.*.name' => 'nullable|string|max:255',
            'contact_persons.*.email' => 'nullable|email|max:255',
            'contact_persons.*.phone_number' => 'nullable|string|max:20',
            'contact_persons.*.alternate_phone_number' => 'nullable|string|max:20',
        ]);

        $customer->update($request->only(['name', 'company_name', 'email', 'phone_number', 'address']));

        // Handle Contact Persons
        $existingIds = $customer->contactPersons()->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($request->contact_persons ?? [] as $person) {
            if (!empty($person['name'])) {
                if (!empty($person['id'])) {
                    $submittedIds[] = $person['id'];
                    $customer->contactPersons()->where('id', $person['id'])->update([
                        'name' => $person['name'],
                        'email' => $person['email'] ?? null,
                        'phone_number' => $person['phone_number'] ?? null,
                        'alternate_phone_number' => $person['alternate_phone_number'] ?? null,
                    ]);
                } else {
                    $customer->contactPersons()->create([
                        'uuid' => Str::uuid(),
                        'name' => $person['name'],
                        'email' => $person['email'] ?? null,
                        'phone_number' => $person['phone_number'] ?? null,
                        'alternate_phone_number' => $person['alternate_phone_number'] ?? null,
                    ]);
                }
            }
        }

        // Delete removed contacts
        $toDelete = array_diff($existingIds, $submittedIds);
        CustomerContactPerson::whereIn('id', $toDelete)->delete();

        return redirect()->route('customers.show', $customer->uuid)->with('success', 'Customer updated successfully.');
    }


    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers')->with('success', 'Customer deleted.');
    }
}
