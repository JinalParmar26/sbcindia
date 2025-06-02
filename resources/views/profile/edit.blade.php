@extends('layouts.main')

@section('content')
<div>
    
    <div class="row">
        <div class="col-12 col-xl-8">
            
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">General information</h2>
                   <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="first_name">Name</label>
                                <input wire:model="user.name" name="name" class="form-control" id="name" type="text"
                                    placeholder="Enter your  name" value="{{ $user->name }}" required>
                            </div>
                        </div>                 
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input wire:model="user.email" name="email" class="form-control" id="email" type="email"
                                    placeholder="name@company.com" value="{{ $user->email }}">
                            </div>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>                       
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-3 mb-3">
                            <div class="form-group">
                                <label for="number">Number</label>
                                <input wire:model="user.phone_number" name="phone_number" class="form-control" id="phone_number" type="number"
                                    placeholder="No." value="{{ $user->phone_number }}">
                            </div>
                            @error('user.phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Working Days</label>
                            <div class="d-flex flex-wrap gap-3 mt-2">
                                @foreach ($days as $day)
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" name="working_days[]" value="{{ $day }}" id="day-{{ $day }}"
                                           {{ (is_array($user->working_days) && in_array($day, $user->working_days))
                                    || (!$user->working_days) && isset($user) && in_array($day, $user->working_days) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="day-{{ $day }}">{{ ucfirst($day) }}</label>
                                </div>
                                @endforeach
                            </div>
                            @error('working_days') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                         <div class="col-md-6 mb-3">
                            <label>Working Hours Start</label>

                            <input type="time" name="working_hours_start" class="form-control" value="{{ substr($user->working_hours_start, 0, 5) }}">

                            @error('working_hours_start') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Working Hours End</label>
                            <input type="time" name="working_hours_end" class="form-control" value="{{ substr($user->working_hours_end, 0, 5) }}">
                            @error('working_hours_end') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('profile') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 col-xl-4">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow border-0 text-center p-0">
                        <div wire:ignore.self class="profile-cover rounded-top"
                            data-background="../assets/img/profile-cover.jpg"></div>
                        <div class="card-body pb-5">
                            <img  src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                class="avatar-xl rounded-circle mx-auto mt-n7 mb-4" alt="Neil Portrait">
                            <h4 class="h3">
                                {{  auth()->user()->name ? auth()->user()->name . ' ' . auth()->user()->last_name : 'User Name'}}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection