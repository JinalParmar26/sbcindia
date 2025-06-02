<title>Volt Laravel Dashboard - Profile</title>
<div>
    
    <div class="row">
        <div class="col-12 col-xl-8">
            @if($showSavedAlert)
            <div class="alert alert-success" role="alert">
                Saved!
            </div>
            @endif
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">General information</h2>
                   

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div>
                                <label for="first_name">Name</label>
                                <div>{{ $user->name }}</div>
                            </div>
                        </div>                 
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div>{{ $user->email }}</div>                               
                            </div>
                        </div>                       
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="number">Number</label>
                                <div>{{ $user->phone_number }}</div>                                
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="number">Role</label>
                                <div>{{ $user->roles->pluck('name')->join(', ') }}</div>                       
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Working Days</label>
                            <div class="d-flex flex-wrap gap-3 mt-2">
                                @if($user->working_days)
                                    @foreach (is_array($user->working_days) ? $user->working_days : json_decode($user->working_days) as $day)
                                    <span class="badge bg-primary me-1">{{ ucfirst($day) }}</span>
                                    @endforeach
                                @else
                                    <span>-</span>
                                @endif
                            </div>
                        </div>                        
                       
                         <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Working Hours Start:</label>
                            <div>{{ $user->working_hours_start ? substr($user->working_hours_start, 0, 5) : '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                             <label class="form-label fw-bold">Working Hours End:</label>
                            <div>{{ $user->working_hours_end ? substr($user->working_hours_end, 0, 5) : '-' }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="mt-4 d-flex justify-content-end">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                   
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
