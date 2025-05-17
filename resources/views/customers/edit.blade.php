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
                <!-- Basic Customer Fields -->
                <div class="col-md-6 mb-3">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $customer->company_name) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $customer->phone_number) }}" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Address <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="3" required>{{ old('address', $customer->address) }}</textarea>
                </div>
            </div>

            <!-- Contact Persons -->
            <hr>
            <h5>Contact Persons</h5>
            <div id="contact-persons">
                @foreach(old('contact_persons', $customer->contactPersons->toArray()) as $index => $person)
                <div class="row border p-3 mb-3 contact-person-entry">
                    <input type="hidden" name="contact_persons[{{ $index }}][id]" value="{{ $person['id'] ?? '' }}">
                    <div class="col-md-4">
                        <label>Name</label>
                        <input type="text" name="contact_persons[{{ $index }}][name]" class="form-control" value="{{ $person['name'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label>Email</label>
                        <input type="email" name="contact_persons[{{ $index }}][email]" class="form-control" value="{{ $person['email'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label>Phone</label>
                        <input type="text" name="contact_persons[{{ $index }}][phone_number]" class="form-control" value="{{ $person['phone_number'] ?? '' }}">
                    </div>
                    <div class="col-md-4 mt-2">
                        <label>Alternate Phone</label>
                        <input type="text" name="contact_persons[{{ $index }}][alternate_phone_number]" class="form-control" value="{{ $person['alternate_phone_number'] ?? '' }}">
                    </div>
                    <div class="col-md-2 mt-4">
                        <button type="button" class="btn btn-danger btn-sm mt-2 remove-contact">Remove</button>
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-secondary btn-sm" id="add-contact">Add Contact Person</button>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('customers') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Customer</button>
            </div>
        </form>
    </div>
</div>

<script>
    let contactIndex = {{ count(old('contact_persons', $customer->contactPersons)) }};
    document.getElementById('add-contact').addEventListener('click', function () {
        const wrapper = document.getElementById('contact-persons');
        const html = `
            <div class="row border p-3 mb-3 contact-person-entry">
                <div class="col-md-4">
                    <label>Name</label>
                    <input type="text" name="contact_persons[${contactIndex}][name]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Email</label>
                    <input type="email" name="contact_persons[${contactIndex}][email]" class="form-control">
                </div>
                <div class="col-md-4">
                    <label>Phone</label>
                    <input type="text" name="contact_persons[${contactIndex}][phone_number]" class="form-control">
                </div>
                <div class="col-md-4 mt-2">
                    <label>Alternate Phone</label>
                    <input type="text" name="contact_persons[${contactIndex}][alternate_phone_number]" class="form-control">
                </div>
                <div class="col-md-2 mt-4">
                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-contact">Remove</button>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', html);
        contactIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-contact')) {
            e.target.closest('.contact-person-entry').remove();
        }
    });
</script>
@endsection
