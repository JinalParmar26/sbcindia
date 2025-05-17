@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Ticket Details</h4>
        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-primary">Edit Ticket</a>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="col-md-3">Subject</dt>
            <dd class="col-md-9">{{ $ticket->subject }}</dd>

            <dt class="col-md-3">Customer</dt>
            <dd class="col-md-9">{{ $ticket->customer->name ?? '-' }}</dd>

            <dt class="col-md-3">Order Product</dt>
            <dd class="col-md-9">
                {{ $ticket->orderProduct->product->name ?? 'N/A' }}
                (Order #{{ $ticket->orderProduct->order->id ?? 'N/A' }})
            </dd>

            <dt class="col-md-3">Assigned To</dt>
            <dd class="col-md-9">{{ $ticket->assignedTo->name ?? '-' }}</dd>

            <dt class="col-md-3">Additional Staff</dt>
            <dd class="col-md-9">
                @if($ticket->additionalStaff->isNotEmpty())
                <ul class="mb-0">
                    @foreach($ticket->additionalStaff as $staff)
                    <li>{{ $staff->name }}</li>
                    @endforeach
                </ul>
                @else
                <span class="text-muted">None</span>
                @endif
            </dd>

            <dt class="col-md-3">Description</dt>
            <dd class="col-md-9">{{ $ticket->description ?? '-' }}</dd>

            <dt class="col-md-3">Created At</dt>
            <dd class="col-md-9">{{ $ticket->created_at->format('Y-m-d H:i') }}</dd>

            <dt class="col-md-3">Updated At</dt>
            <dd class="col-md-9">{{ $ticket->updated_at->format('Y-m-d H:i') }}</dd>
        </dl>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <a href="{{ route('tickets') }}" class="btn btn-secondary">Back to List</a>
        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-primary">Edit Ticket</a>
    </div>
</div>
@endsection
