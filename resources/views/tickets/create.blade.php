@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Ticket</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('tickets.store') }}" method="POST">
                @csrf

                <div class="row g-3">
                    <!-- Subject -->
                    <div class="col-md-6">
                        <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                        <input type="text" id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror"
                               value="{{ old('subject') }}" required>
                        @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ticket Type -->
{{--                    <div class="col-md-6">--}}
{{--                        <label for="type" class="form-label">Ticket Type <span class="text-danger">*</span></label>--}}
{{--                        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>--}}
{{--                            <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select ticket type</option>--}}
{{--                            <option value="delivery" {{ old('type') == 'delivery' ? 'selected' : '' }}>Delivery</option>--}}
{{--                            <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>Service</option>--}}
{{--                        </select>--}}
{{--                        @error('type')--}}
{{--                        <div class="invalid-feedback">{{ $message }}</div>--}}
{{--                        @enderror--}}
{{--                    </div>--}}

                    <!-- Customer -->
                    <div class="col-md-6">
                        <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                        <select id="customer_id" name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                            <option value="" disabled {{ old('customer_id') ? '' : 'selected' }}>Select customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Customer Contact (dynamic) -->
                    <div class="col-md-6">
                        <label for="customer_contact_person_id" class="form-label">Customer Contact</label>
                        <select id="customer_contact_person_id" name="customer_contact_person_id" class="form-select @error('customer_contact_person_id') is-invalid @enderror">
                            <option value="" selected disabled>Select contact</option>
                            {{-- Will be filled via JS --}}
                        </select>
                        @error('customer_contact_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Order Product -->
                    <div class="col-md-6">
                        <label for="order_product_id" class="form-label">Order Product <span class="text-danger">*</span></label>
                        <select id="order_product_id" name="order_product_id" class="form-select @error('order_product_id') is-invalid @enderror" required>
                            <option value="" disabled {{ old('order_product_id') ? '' : 'selected' }}>Select product</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('order_product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->product->name ?? 'N/A' }} (Order #{{ $product->order->id ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                        @error('order_product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Assigned To -->
                    <div class="col-md-6">
                        <label for="assigned_to" class="form-label">Assigned To <span class="text-danger">*</span></label>
                        <select id="assigned_to" name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror" required>
                            <option value="" disabled {{ old('assigned_to') ? '' : 'selected' }}>Select staff</option>
                            @foreach($staff as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Additional Staff -->
                    <div class="col-md-6">
                        <label for="additional_staff" class="form-label">Additional Staff</label>
                        <select id="additional_staff" name="additional_staff[]" class="form-select @error('additional_staff') is-invalid @enderror" multiple>
                            @foreach($staff as $user)
                            <option value="{{ $user->id }}" {{ collect(old('additional_staff'))->contains($user->id) ? 'selected' : '' }}>
                            {{ $user->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('additional_staff')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('tickets') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerSelect = document.getElementById('customer_id');
        const contactSelect = document.getElementById('customer_contact_person_id');

        customerSelect.addEventListener('change', function () {
            const customerId = this.value;
            contactSelect.innerHTML = '<option disabled selected>Loading...</option>';

            fetch(`/api/customers/${customerId}/contacts`)
                .then(res => res.json())
                .then(data => {
                    contactSelect.innerHTML = '<option disabled selected>Select contact</option>';
                    data.forEach(contact => {
                        const option = document.createElement('option');
                        option.value = contact.id;
                        option.textContent = contact.name + ' (' + contact.email + ')';
                        contactSelect.appendChild(option);
                    });
                })
                .catch(() => {
                    contactSelect.innerHTML = '<option disabled selected>Error loading contacts</option>';
                });
        });
    });
</script>
@endsection
