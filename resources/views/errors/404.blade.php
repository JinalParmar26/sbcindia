@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Staff Member Not Found</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <svg class="text-muted" width="100" height="100" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h5 class="card-title">404 - Not Found</h5>
                    <p class="card-text">{{ $message ?? 'The requested staff member could not be found.' }}</p>
                    
                    @if(isset($uuid))
                    <div class="alert alert-info">
                        <small>
                            <strong>UUID:</strong> {{ $uuid }}<br>
                            This staff member may be inactive or the UUID may not exist in our system.
                        </small>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
                        <a href="{{ url('/debug-users') }}" class="btn btn-secondary">View Available Staff</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
