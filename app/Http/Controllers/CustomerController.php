<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerContactPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\PdfExportService;

class CustomerController extends Controller
{
    protected $pdfExportService;

    public function __construct(PdfExportService $pdfExportService)
    {
        $this->pdfExportService = $pdfExportService;
    }

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

    public function exportCsv(Request $request)
    {
        // Get filters from request
        $search = $request->get('search', '');

        // Build query with same logic as Livewire component
        $query = Customer::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->get();

        // Create CSV content
        $csvContent = $this->generateCustomersCsvContent($customers, $request->all());

        // Create filename
        $filename = 'customers_export_' . now()->format('Y-m-d_H-i-s') . '.csv';

        // Return CSV response
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'no-cache, must-revalidate');
    }

    private function generateCustomersCsvContent($customers, $filters)
    {
        $csv = [];
        
        // Add header with filters info
        $filterText = 'Customers Export - Generated on: ' . now()->format('F d, Y \a\t H:i');
        $csv[] = [$filterText];
        
        $appliedFilters = [];
        if (!empty($filters['search'])) {
            $appliedFilters[] = "Search: " . $filters['search'];
        }
        
        if (!empty($appliedFilters)) {
            $csv[] = ['Applied Filters: ' . implode(' | ', $appliedFilters)];
        }
        
        $csv[] = []; // Empty row
        
        // Add table headers
        $csv[] = ['Name', 'Company', 'Email', 'Phone', 'Address', 'Date Created'];
        
        // Add data rows
        foreach ($customers as $customer) {
            $csv[] = [
                $customer->name,
                $customer->company_name,
                $customer->email,
                $customer->phone_number,
                $customer->address,
                $customer->created_at->format('M d, Y')
            ];
        }
        
        $csv[] = []; // Empty row
        $csv[] = ['Total Customers: ' . $customers->count()];
        
        // Convert to CSV string
        $output = '';
        foreach ($csv as $row) {
            $output .= '"' . implode('","', $row) . '"' . "\n";
        }
        
        return $output;
    }

    /**
     * Export customers to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Customer::with(['contactPersons']);

        // Apply filters
        $filters = [];
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            $filters['search'] = $request->search;
        }

        if ($request->filled('status_filter') && $request->status_filter != 'all') {
            $query->where('status', $request->status_filter);
            $filters['status_filter'] = $request->status_filter;
        }

        $customers = $query->get();

        return $this->pdfExportService->generateCustomersPdf($customers, $filters);
    }

    /**
     * Export a single customer to PDF.
     */
    public function exportSinglePdf($uuid)
    {
        $customer = Customer::where('uuid', $uuid)->with(['contactPersons', 'orders.products', 'tickets'])->firstOrFail();
        
        return $this->pdfExportService->generateSingleCustomerPdf($customer);
    }
}
