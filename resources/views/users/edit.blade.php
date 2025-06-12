@extends('layouts.main')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit User</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Name -->
                    <div class="col-md-6 mb-3">
                        <label>Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label>Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep unchanged">
                        @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Leave blank to keep unchanged">
                    </div>
                    <!-- Profile Photo -->
                    <div class="col-md-6 mb-3">
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-3 mb-3">
                                <img
                                    src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                    class="rounded-circle mb-2"
                                    alt="User Photo"
                                    width="100" height="100">
                            </div>
                            <div class="col-md-9 mb-3">
                                <label for="profile_photo">Upload New Profile Photo</label>
                                <input type="file" name="profile_photo" class="form-control mt-2">
                                @error('profile_photo') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Phone Number -->
                    <div class="col-md-6 mb-3">
                        <label>Phone Number</label>
                        <input type="text" name="phone_number" class="form-control"
                               value="{{ old('phone_number', $user->phone_number) }}">
                        @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <!-- Role -->
                    <div class="col-md-6 mb-3">
                        <label>Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="" disabled>Select a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ collect(old('role', $user->roles->pluck('name')->toArray()))->contains($role->name) ? 'selected' : '' }}
                                >
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="col-md-6 mb-3 d-flex align-items-center">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="isActive" value="1" id="isActive"
                                {{ old('isActive', $user->isActive) ? 'checked' : '' }}>
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
                                        {{ (is_array(old('working_days')) && in_array($day, old('working_days')))
                                             || (!old('working_days') && isset($user) && in_array($day, $user->working_days)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day-{{ $day }}">{{ ucfirst($day) }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('working_days') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <!-- Working Hours -->
                    <div class="col-md-6 mb-3">
                        <label>Working Hours Start</label>
                        <input type="time" name="working_hours_start" class="form-control"
                               value="{{ old('working_hours_start', isset($user) ? substr($user->working_hours_start, 0, 5) : '') }}">
                        @error('working_hours_start') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Working Hours End</label>
                        <input type="time" name="working_hours_end" class="form-control"
                               value="{{ old('working_hours_end', isset($user) ? substr($user->working_hours_end, 0, 5) : '') }}">
                        @error('working_hours_end') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ route('users') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
@endsection
