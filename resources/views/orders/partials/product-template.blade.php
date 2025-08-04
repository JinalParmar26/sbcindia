<template id="product-template">
    <div class="border p-3 mb-3 product-item">
        <div class="row">
            <div class="col-md-4">
                <label>Product</label>
                <select name="products[__INDEX__][product_id]" class="form-control product-select" data-index="__INDEX__">
                    <option value="">Select</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                    @if(!request()->routeIs('orders.edit'))
                        <option value="other">Other (Add New Product)</option>
                    @endif
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-product-btn">Remove</button>
            </div>
        </div>

        <div class="config-wrapper mt-3">
            <!-- Dynamic config content loaded here -->
        </div>
    </div>
</template>
