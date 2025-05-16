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


        <div class="mt-4 d-flex justify-content-end">
            <a href="{{ route('products') }}" class="btn btn-secondary me-2">Back to List</a>
            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit Product</a>
        </div>
    </div>
</div>
@endsection

