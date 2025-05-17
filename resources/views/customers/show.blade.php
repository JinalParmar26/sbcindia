@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Customer Details</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Name -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Name:</label>
                <div>{{ $customer->name }}</div>
            </div>

            <!-- Email -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Email:</label>
                <div>{{ $customer->email }}</div>
            </div>

            <!-- Phone -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Phone Number:</label>
                <div>{{ $customer->phone_number ?? '-' }}</div>
            </div>

            <!-- Address -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Address:</label>
                <div>{{ $customer->address ?? '-' }}</div>
            </div>

            <!-- City -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">City:</label>
                <div>{{ $customer->city ?? '-' }}</div>
            </div>

            <!-- Created At -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Created At:</label>
                <div>{{ optional($customer->created_at)->format('d M Y, h:i A') ?? '-' }}</div>
            </div>

            <!-- Updated At -->
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Last Updated:</label>
                <div>{{ optional($customer->updated_at)->format('d M Y, h:i A') ?? '-' }}</div>
            </div>
        </div>

        <hr>
        <h5>Contact Persons</h5>
        @forelse($customer->contactPersons as $person)
        <div class="border p-3 mb-3">
            <div><strong>Name:</strong> {{ $person->name }}</div>
            <div><strong>Email:</strong> {{ $person->email }}</div>
            <div><strong>Phone:</strong> {{ $person->phone_number }}</div>
            <div><strong>Alternate:</strong> {{ $person->alternate_phone_number }}</div>
        </div>
        @empty
        <p>No contact persons added.</p>
        @endforelse

        <div class="mt-4 d-flex justify-content-end">
            <a href="{{ route('customers') }}" class="btn btn-secondary me-2">Back to List</a>
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">Edit Customer</a>
        </div>
    </div>
</div>
@endsection
