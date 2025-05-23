<div class="border p-3 mb-3 product-item">
    <div class="row">
        <div class="col-md-4">
            <label>Product <span class="text-danger">*</span></label>
            <select name="products[{{ $index }}][product_id]" class="form-control" required>
                <option value="">Select</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ (old("products.{$index}.product_id", $productData->product_id ?? '') == $product->id) ? 'selected' : '' }}>
                {{ $product->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm remove-product-btn">Remove</button>
        </div>
    </div>

    <div class="config-wrapper mt-3">
        <label>Configurations</label>
        <div class="config-items">
            @php
            $configs = json_decode($productData->configurations ?? '{}', true) ?? [];
            @endphp
            @foreach ($configs as $key => $val)
            <div class="row mb-2 config-pair">
                <div class="col-md-5"><input name="products[{{ $index }}][config_keys][]" class="form-control" value="{{ $key }}" placeholder="Key"></div>
                <div class="col-md-5"><input name="products[{{ $index }}][config_values][]" class="form-control" value="{{ $val }}" placeholder="Value"></div>
                <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-config-btn">✕</button></div>
            </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm add-config-btn" data-index="{{ $index }}">+ Add Configuration</button>
    </div>
</div>
