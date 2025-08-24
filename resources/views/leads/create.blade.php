@extends('layouts.app')

@section('content')
<div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z"></path>
                    </svg>
                </a>
            </li>
            <li class="breadcrumb-item"><a href="{{ route('leads') }}">Leads</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Lead</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4">Create New Lead</h1>
            <p class="mb-0">Add a new sales lead to your pipeline</p>
        </div>
        <div>
            <a href="{{ route('leads') }}" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                </svg>
                Back to Leads
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow mb-4">
    <div class="card-header">
        <h5 class="mb-0">Lead Information</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('leads.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Basic Info -->
                <div class="col-12 mb-3">
                    <h6 class="mb-3 text-gray-800">Basic Information</h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Company</label>
                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                    @error('company_name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Industry</label>
                    <input type="text" name="industry" class="form-control" value="{{ old('industry') }}">
                    @error('industry') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Source</label>
                    <input type="text" name="source" class="form-control" value="{{ old('source') }}" placeholder="e.g. Website, Referral">
                    @error('source') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Contact Info -->
                <div class="col-12 mt-4 mb-2">
                    <h6 class="text-gray-800">Contact Information</h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                    @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                    @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                    @error('state') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country') }}">
                    @error('country') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}">
                    @error('pincode') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Deal Info -->
                <div class="col-12 mt-4 mb-2">
                    <h6 class="text-gray-800">Deal Information</h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Deal Title</label>
                    <input type="text" name="deal_title" class="form-control" value="{{ old('deal_title') }}">
                    @error('deal_title') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Deal Amount</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="deal_amount" class="form-control" value="{{ old('deal_amount') }}" step="0.01" min="0">
                    </div>
                    @error('deal_amount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- File Upload -->
                <div class="col-12 mt-4 mb-2">
                    <h6 class="text-gray-800">Attachments</h6>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Attachment</label>
                    <input type="file" name="file" class="form-control">
                    <small class="text-muted">Maximum file size: 10MB</small>
                    @error('file') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{ route('leads') }}" class="btn btn-outline-gray-600 me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Lead</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
