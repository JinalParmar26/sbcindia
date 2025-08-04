@extends('pdf.layout')

@section('title', $title)

@section('content')
<div class="header">
    <h1>{{ $title }}</h1>
    <p>Generated on {{ $generated_at }}</p>
</div>

<div class="customer-details">
    <h2>Customer Information</h2>
    <div class="info-section">
        <div class="info-row">
            <span class="label">Name:</span>
            <span class="value">{{ $customer->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Company:</span>
            <span class="value">{{ $customer->company_name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">{{ $customer->email }}</span>
        </div>
        <div class="info-row">
            <span class="label">Phone:</span>
            <span class="value">{{ $customer->phone_number ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Address:</span>
            <span class="value">{{ $customer->address ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Created:</span>
            <span class="value">{{ $customer->created_at->format('Y-m-d H:i') }}</span>
        </div>
    </div>
</div>

@if($customer->contactPersons && $customer->contactPersons->count() > 0)
<div class="contact-persons">
    <h2>Contact Persons</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Alternate Phone</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer->contactPersons as $person)
                <tr>
                    <td>{{ $person->name }}</td>
                    <td>{{ $person->email ?? 'N/A' }}</td>
                    <td>{{ $person->phone_number ?? 'N/A' }}</td>
                    <td>{{ $person->alternate_phone_number ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($customer->orders && $customer->orders->count() > 0)
<div class="orders">
    <h2>Orders</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Products</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer->orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>
                        @if($order->products)
                            @foreach($order->products as $product)
                                {{ $product->name }}@if(!$loop->last), @endif
                            @endforeach
                        @else
                            N/A
                        @endif
                    </td>
                    <td>₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                    <td>{{ ucfirst($order->status ?? 'N/A') }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($customer->tickets && $customer->tickets->count() > 0)
<div class="tickets">
    <h2>Tickets</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customer->tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ ucfirst($ticket->status ?? 'N/A') }}</td>
                    <td>{{ $ticket->assignedTo->name ?? 'N/A' }}</td>
                    <td>{{ $ticket->created_at->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
