@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Add Customer</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('customers.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Company Name -->
                <div class="col-md-6 mb-3">
                    <label>Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                    @error('company_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Phone Number -->
                <div class="col-md-6 mb-3">
                    <label>Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Address -->
                <div class="col-md-12 mb-3">
                    <label>Address <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <!-- Contact Persons -->
            <div class="col-md-12 mb-3">
                <label>Contact Persons</label>
                <div id="contact-persons-wrapper">
                    <div class="contact-person-item row mb-3">
                        <div class="col-md-3">
                            <input type="text" name="contact_persons[0][name]" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="email" name="contact_persons[0][email]" class="form-control" placeholder="Email">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="contact_persons[0][phone_number]" class="form-control" placeholder="Phone">
                        </div>
                        <div class="col-md-3 d-flex">
                            <input type="text" name="contact_persons[0][alternate_phone_number]" class="form-control me-2" placeholder="Alternate Phone">
                            <button type="button" class="btn btn-danger btn-sm remove-contact d-none">✕</button>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-contact-person" class="btn btn-outline-primary btn-sm mt-2">+ Add Contact Person</button>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('customers') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Customer</button>
            </div>
        </form>
    </div>
</div>

<script>
    let contactIndex = 1;

    document.getElementById('add-contact-person').addEventListener('click', function () {
        const wrapper = document.getElementById('contact-persons-wrapper');
        const item = document.querySelector('.contact-person-item').cloneNode(true);

        // Update input names
        item.querySelectorAll('input').forEach((input) => {
            const nameAttr = input.getAttribute('name');
            const newNameAttr = nameAttr.replace(/\d+/, contactIndex);
            input.setAttribute('name', newNameAttr);
            input.value = '';
        });

        item.querySelector('.remove-contact').classList.remove('d-none');
        item.querySelector('.remove-contact').addEventListener('click', function () {
            item.remove();
        });

        wrapper.appendChild(item);
        contactIndex++;
    });

    document.querySelector('.remove-contact')?.addEventListener('click', function () {
        this.closest('.contact-person-item').remove();
    });
</script>
@endsection
