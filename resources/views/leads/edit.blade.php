@extends('layouts.app')

@section('content')
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('leads') }}">Leads</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit {{ $lead->lead_name }}</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Edit Lead</h1>
            <p class="mb-0">Update lead information</p>
        </div>
        <div>
            <a href="{{ route('leads.show', $lead->uuid) }}" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                </svg>
                Back to Lead
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-header">
        <h5 class="mb-0">Lead Information</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('leads.update', $lead->uuid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Basic Information -->
                <div class="col-12">
                    <h6 class="mb-3 text-gray-800">Basic Information</h6>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lead Name <span class="text-danger">*</span></label>
                    <input type="text" name="lead_name" class="form-control" value="{{ old('lead_name', $lead->lead_name) }}" required>
                    @error('lead_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Lead Owner</label>
                    <select name="lead_owner_id" class="form-select">
                        <option value="">Select Lead Owner</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('lead_owner_id', $lead->lead_owner_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('lead_owner_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Select Status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', $lead->status) == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Industry</label>
                    <input type="text" name="industry" class="form-control" value="{{ old('industry', $lead->industry) }}" placeholder="e.g., Technology, Healthcare">
                    @error('industry') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Lead Source</label>
                    <input type="text" name="lead_source" class="form-control" value="{{ old('lead_source', $lead->lead_source) }}" placeholder="e.g., Website, Referral, Cold Call">
                    @error('lead_source') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Price Group</label>
                    <input type="text" name="price_group" class="form-control" value="{{ old('price_group', $lead->price_group) }}" placeholder="e.g., Premium, Standard, Basic">
                    @error('price_group') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Collaborators</label>
                    <select name="collaborators[]" class="form-select" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, old('collaborators', $selectedCollaborators)) ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl (Cmd on Mac) to select multiple collaborators</small>
                    @error('collaborators') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Contact Information -->
                <div class="col-12 mt-4">
                    <h6 class="mb-3 text-gray-800">Contact Information</h6>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Title</label>
                    <select name="title" class="form-select">
                        <option value="">Select Title</option>
                        <option value="Mr." {{ old('title', $lead->title) == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                        <option value="Mrs." {{ old('title', $lead->title) == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                        <option value="Ms." {{ old('title', $lead->title) == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                        <option value="Dr." {{ old('title', $lead->title) == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        <option value="Prof." {{ old('title', $lead->title) == 'Prof.' ? 'selected' : '' }}>Prof.</option>
                    </select>
                    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-9 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $lead->email) }}">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $lead->address) }}</textarea>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', $lead->country) }}">
                    @error('country') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $lead->pincode) }}">
                    @error('pincode') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Visit Information -->
                <div class="col-12 mt-4">
                    <h6 class="mb-3 text-gray-800">Visit Information</h6>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Visit Status</label>
                    <select name="visit_status" class="form-select">
                        @foreach($visitStatuses as $visitStatus)
                            <option value="{{ $visitStatus }}" {{ old('visit_status', $lead->visit_status) == $visitStatus ? 'selected' : '' }}>
                                {{ $visitStatus }}
                            </option>
                        @endforeach
                    </select>
                    @error('visit_status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Visit Started At</label>
                    <input type="datetime-local" 
                           name="visit_started_at" 
                           class="form-control" 
                           value="{{ old('visit_started_at', $lead->visit_started_at ? $lead->visit_started_at->format('Y-m-d\TH:i') : '') }}">
                    @error('visit_started_at') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Visit Ended At</label>
                    <input type="datetime-local" 
                           name="visit_ended_at" 
                           class="form-control" 
                           value="{{ old('visit_ended_at', $lead->visit_ended_at ? $lead->visit_ended_at->format('Y-m-d\TH:i') : '') }}">
                    @error('visit_ended_at') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Deal Information -->
                <div class="col-12 mt-4">
                    <h6 class="mb-3 text-gray-800">Deal Information</h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Deal Title</label>
                    <input type="text" name="deal_title" class="form-control" value="{{ old('deal_title', $lead->deal_title) }}">
                    @error('deal_title') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Deal Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="deal_amount" class="form-control" value="{{ old('deal_amount', $lead->deal_amount) }}" step="0.01" min="0">
                    </div>
                    @error('deal_amount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Deal Status</label>
                    <select name="deal_status" class="form-select">
                        <option value="">Select Deal Status</option>
                        @foreach($dealStatuses as $dealStatus)
                            <option value="{{ $dealStatus }}" {{ old('deal_status', $lead->deal_status) == $dealStatus ? 'selected' : '' }}>
                                {{ $dealStatus }}
                            </option>
                        @endforeach
                    </select>
                    @error('deal_status') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- File Upload -->
                <div class="col-12 mt-4">
                    <h6 class="mb-3 text-gray-800">Attachments</h6>
                </div>

                @if($lead->file_url)
                <div class="col-md-12 mb-3">
                    <div class="alert alert-info d-flex align-items-center">
                        <svg class="icon icon-sm me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        <div class="flex-grow-1">
                            <strong>Current Attachment:</strong>
                            <a href="{{ $lead->file_url }}" target="_blank" class="ms-2">View File</a>
                        </div>
                        <div class="form-check ms-3">
                            <input class="form-check-input" type="checkbox" name="remove_file" id="remove_file" value="1">
                            <label class="form-check-label" for="remove_file">
                                Remove
                            </label>
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-md-12 mb-3">
                    <label class="form-label">
                        @if($lead->file_url)
                            Replace Attachment
                        @else
                            Attachment
                        @endif
                    </label>
                    <input type="file" name="file" class="form-control">
                    <small class="text-muted">Maximum file size: 10MB</small>
                    @error('file') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('leads.show', $lead->uuid) }}" class="btn btn-outline-gray-600 me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Lead</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
