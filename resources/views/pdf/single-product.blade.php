@extends('pdf.layout')

@section('title', $title)

@section('content')
<div class="header">
    <h1>{{ $title }}</h1>
    <p>Generated on {{ $generated_at }}</p>
</div>

<div class="product-details">
    <h2>Product Information</h2>
    <div class="info-section">
        <div class="info-row">
            <span class="label">Name:</span>
            <span class="value">{{ $product->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Model Number:</span>
            <span class="value">{{ $product->model_number ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Description:</span>
            <span class="value">{{ $product->description ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Image:</span>
            <span class="value">
                @if($product->image)
                    Image Available
                @else
                    No Image
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="label">Status:</span>
            <span class="value">{{ ucfirst($product->status ?? 'Active') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Created:</span>
            <span class="value">{{ $product->created_at->format('Y-m-d H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="label">Updated:</span>
            <span class="value">{{ $product->updated_at->format('Y-m-d H:i') }}</span>
        </div>
    </div>
</div>

@if($product->orders && $product->orders->count() > 0)
<div class="orders">
    <h2>Orders</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Quantity</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->pivot->quantity ?? 'N/A' }}</td>
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

@if($product->tickets && $product->tickets->count() > 0)
<div class="tickets">
    <h2>Related Tickets</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ $ticket->subject }}</td>
                    <td>{{ $ticket->customer->name ?? 'N/A' }}</td>
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
