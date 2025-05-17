<template id="product-template">
    <div class="border p-3 mb-3 product-item">
        <div class="row">
            <div class="col-md-4">
                <label>Product</label>
                <select name="products[__INDEX__][product_id]" class="form-control">
                    <option value="">Select</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Serial Number</label>
                <input type="text" name="products[__INDEX__][serial_number]" class="form-control">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-sm remove-product-btn">Remove</button>
            </div>
        </div>

        <div class="config-wrapper mt-3">
            <label>Configurations</label>
            <div class="config-items"></div>
            <button type="button" class="btn btn-outline-secondary btn-sm add-config-btn" data-index="__INDEX__">+ Add Configuration</button>
        </div>
    </div>
</template>
