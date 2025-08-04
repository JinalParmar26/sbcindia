@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Customer Details</h4>
        </div>
        <div class="card-body">
            <!-- Customer Info -->
            <div class="row mb-4">
                @foreach([
                    'Name' => $customer->name,
                    'Email' => $customer->email,
                    'Phone Number' => $customer->phone_number ?? '-',
                    'Address' => $customer->address ?? '-'
                ] as $label => $value)
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">{{ $label }}:</label>
                        <div>{{ $value }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs" id="customerTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab">Contact Persons</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">Orders</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tickets-tab" data-bs-toggle="tab" data-bs-target="#tickets" type="button" role="tab">Maintenance Tickets</button>
                </li>
            </ul>

            <div class="tab-content p-3 border border-top-0 bg-light" id="customerTabsContent">
                <!-- Contact Persons -->
                <div class="tab-pane fade show active" id="contacts" role="tabpanel">
                    @forelse($customer->contactPersons as $person)
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-2">{{ $person->name }}</h6>
                                <p class="mb-1"><strong>Email:</strong> {{ $person->email }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $person->phone_number }}</p>
                                <p class="mb-0"><strong>Alternate:</strong> {{ $person->alternate_phone_number }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No contact persons added.</p>
                    @endforelse
                </div>

                <!-- Orders -->
                <div class="tab-pane fade" id="orders" role="tabpanel">
                    @forelse($customer->orders as $order)
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-2">{{ $order->title }}</h6>
                                <p class="mb-2"><strong>Created At:</strong> {{ optional($order->created_at)->format('d M Y') }}</p>
                                @if($order->orderProducts->count())
                                    <div class="mt-3">
                                        <strong>Products:</strong>
                                        <ul class="list-group list-group-flush mt-2">
                                            @foreach($order->orderProducts as $product)
                                                <li class="list-group-item">
                                                    <strong>Name:</strong> {{ $product->product->name }}
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Serial:</strong> {{ $product->serial_number }}
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>Model:</strong> {{ $product->model_number }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-sm btn-secondary" target="_blank">View Order</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No orders found.</p>
                    @endforelse
                </div>

                <!-- Maintenance Tickets -->
                <div class="tab-pane fade" id="tickets" role="tabpanel">
                    @forelse($customer->tickets as $ticket)
                        @if($ticket->type == 'service')
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-2">{{ $ticket->subject }}</h6>
                                <p class="mb-1"><strong>Start:</strong> {{ optional($ticket->start)->format('d M Y h:i A') }}</p>
                                <p class="mb-1"><strong>End:</strong> {{ optional($ticket->end)->format('d M Y h:i A') }}</p>
                                <div class="mb-2">
                                    <strong>Order Product:</strong>
                                    <ul class="list-group list-group-flush mt-2">
                                        @if($ticket->orderProduct)
                                            <li class="list-group-item">
                                                <strong>Name:</strong> {{  $ticket->orderProduct->product->name }}
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Serial:</strong> {{  $ticket->orderProduct->serial_number }}
                                            </li>
                                            <li class="list-group-item">
                                                <strong>Model:</strong> {{  $ticket->orderProduct->model_number }}
                                            </li>
                                        @else
                                            N/A
                                        @endif
                                    </ul>
                                </div>
                                <a href="{{ route('tickets.show', $ticket->uuid) }}" class="btn btn-sm btn-secondary" target="_blank">View Ticket</a>
                            </div>
                        </div>
                        @endif
                    @empty
                        <p class="text-muted">No maintenance tickets found.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('customers') }}" class="btn btn-secondary">Back to List</a>
                <div>
                    <a href="{{ route('customers.single.pdf', $customer->uuid) }}" class="btn btn-outline-primary me-2" target="_blank">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download PDF
                    </a>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">Edit Customer</a>
                </div>
            </div>
        </div>
    </div>
@endsection
