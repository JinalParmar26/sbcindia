<div class="product-item border p-3 mb-3">
    <div class="row mb-3">
        <div class="col-md-6">
            <label>Product</label>
            <select name="products[{{ $index }}][product_id]" class="form-control product-select" data-index="{{ $index }}">
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ (old("products.{$index}.product_id", $productData['product_id'] ?? $productData->product_id ?? '') == $product->id) ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="config-wrapper">
        <!-- JS will render config inputs here -->
    </div>
</div>
