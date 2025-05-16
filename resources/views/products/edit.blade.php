@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Edit Product</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Model Number -->
                <div class="col-md-6 mb-3">
                    <label>Model Number <span class="text-danger">*</span></label>
                    <input required type="text" name="model_number" class="form-control" value="{{ old('model_number', $product->model_number) }}">
                    @error('model_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Image Upload -->
                <div class="col-md-6 mb-3">
                    <label>Product Image <span class="text-danger">*</span></label>
                    <input  type="file" name="image" class="form-control">
                    @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="img-thumbnail mt-2" width="120">
                    @endif
                    @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Description -->
                <div class="col-md-12 mb-3">
                    <label>Description <span class="text-danger">*</span></label>
                    <textarea required name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('products') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>
@endsection
