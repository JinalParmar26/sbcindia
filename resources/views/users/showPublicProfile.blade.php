<x-layouts.base>
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">{{ $user->name }}</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-xl-8">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">General information</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Name:</label>
                            <div>{{ $user->name }}</div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <div>{{ $user->email }}</div>
                        </div>

                        <!-- Phone Number -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone Number:</label>
                            <div>{{ $user->phone_number ?? '-' }}</div>
                        </div>

                        <!-- Working Days -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Working Days:</label>
                            <div>
                                @if($user->working_days)
                                @foreach (is_array($user->working_days) ? $user->working_days : json_decode($user->working_days) as $day)
                                <span class="badge bg-primary me-1">{{ ucfirst($day) }}</span>
                                @endforeach
                                @else
                                <span>-</span>
                                @endif
                            </div>
                        </div>

                        <!-- Working Hours -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Working Hours Start:</label>
                            <div>{{ $user->working_hours_start ? substr($user->working_hours_start, 0, 5) : '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Working Hours End:</label>
                            <div>{{ $user->working_hours_end ? substr($user->working_hours_end, 0, 5) : '-' }}</div>
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
                                <img  src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                    class="avatar-xl rounded-circle mx-auto mt-n7 mb-4" alt="Neil Portrait">
                                <h4 class="h3">
                                    {{ $user->name ? $user->name . ' ' .$user->last_name : 'User Name'}}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
        </div>
        
    </div>
</div>

</x-layouts.base>
