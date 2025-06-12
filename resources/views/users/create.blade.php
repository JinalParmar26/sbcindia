@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Add User</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label>Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6 mb-3">
                    <label>Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="profile_photo">Profile Photo</label>
                    <input type="file" name="profile_photo" class="form-control">
                    @error('profile_photo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <!-- Phone Number -->
                <div class="col-md-6 mb-3">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="" disabled selected>Select a role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Is Active -->
                <div class="col-md-6 mb-3 d-flex align-items-center">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="isActive" value="1" id="isActive" {{ old('isActive') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>



                <!-- Working Days -->
                <div class="col-md-12 mb-3">
                    <label>Working Days</label>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        @foreach ($days as $day)
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" name="working_days[]" value="{{ $day }}" id="day-{{ $day }}"
                                   {{ is_array(old('working_days')) && in_array($day, old('working_days')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="day-{{ $day }}">{{ ucfirst($day) }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('working_days') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Working Hours -->
                <div class="col-md-6 mb-3">
                    <label>Working Hours Start</label>
                    <input type="time" name="working_hours_start" class="form-control" value="{{ old('working_hours_start') }}">
                    @error('working_hours_start') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Working Hours End</label>
                    <input type="time" name="working_hours_end" class="form-control" value="{{ old('working_hours_end') }}">
                    @error('working_hours_end') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('users') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save User</button>
            </div>
        </form>
    </div>
</div>
@endsection
