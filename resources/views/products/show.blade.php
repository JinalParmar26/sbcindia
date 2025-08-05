@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Product Details</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Name -->
            <div class="col-md-6 mb-3">
                <strong>Product Name:</strong>
                <p class="mb-0">{{ $product->name }}</p>
            </div>

            <!-- Model Number -->
            <div class="col-md-6 mb-3">
                <strong>Model Number:</strong>
                <p class="mb-0">{{ $product->model_number ?? '-' }}</p>
            </div>

            <!-- Image -->
            <div class="col-md-6 mb-3">
                <strong>Product Image:</strong><br>
                @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-thumbnail mt-2" width="150">
                @else
                <p class="mb-0">No image uploaded.</p>
                @endif
            </div>

            <!-- Description -->
            <div class="col-md-12 mb-3">
                <strong>Description:</strong>
                <p class="mb-0">{{ $product->description ?? '-' }}</p>
            </div>

            <!-- Created At -->
            <div class="col-md-6 mb-3">
                <strong>Created At:</strong>
                <p class="mb-0">{{ $product->created_at->format('M d, Y h:i A') }}</p>
            </div>

            <!-- Updated At -->
            <div class="col-md-6 mb-3">
                <strong>Last Updated:</strong>
                <p class="mb-0">{{ $product->updated_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>


        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('products') }}" class="btn btn-secondary">Back to List</a>
            <div>
                <a href="{{ route('products.single.pdf', $product->uuid) }}" class="btn btn-outline-primary me-2" target="_blank">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit Product</a>
            </div>
        </div>
    </div>
</div>
@endsection

