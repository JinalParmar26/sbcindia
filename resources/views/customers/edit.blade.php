@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Edit Customer</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label>Name <span class="text-danger">*</span></label>
                    <input required type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Company Name -->
                <div class="col-md-6 mb-3">
                    <label>Company Name</label>
                    <input required type="text" name="company_name" class="form-control" value="{{ old('company_name', $customer->company_name) }}">
                    @error('company_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input required type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Phone Number -->
                <div class="col-md-6 mb-3">
                    <label>Phone Number</label>
                    <input required type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $customer->phone_number) }}">
                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Address -->
                <div class="col-md-12 mb-3">
                    <label>Address</label>
                    <textarea required name="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('customers') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Customer</button>
            </div>
        </form>
    </div>
</div>
@endsection
