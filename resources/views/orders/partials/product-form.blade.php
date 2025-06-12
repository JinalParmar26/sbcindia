@php
    $configs = $productData->configurations ? json_decode($productData->configurations, true) : null;
//    print_r($configs);exit;
@endphp

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
                <option value="other">+ Add New Product</option>
            </select>
        </div>s
    </div>

    <div class="config-wrapper">
        <label>Configurations</label>
        <div class="config-items">
            @if (!empty($configs))
                @foreach ($configs as $categoryName => $optionValue)
                    <div class="row mb-2 config-pair">
                        <div class="col-md-5">
                            <select name="products[{{ $index }}][config_keys][]" class="form-control category-select">
                                <option value="">Select Key</option>
                                @foreach (\App\Models\ProductSpecCategory::all() as $cat)
                                    <option value="{{ $cat->id }}" {{ $cat->category == $categoryName ? 'selected' : '' }}>
                                        {{ $cat->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <select name="products[{{ $index }}][config_values][]" class="form-control option-select">
                                @if($category = \App\Models\ProductSpecCategory::where('category', $categoryName)->first())
                                    <option value="">Select Option</option>
                                    @foreach (\App\Models\ProductSpecOption::where('spec_category', $category->id)->get() as $opt)
                                        <option value="{{ $opt->cat_option }}" {{ $opt->cat_option == $optionValue ? 'selected' : '' }}>
                                            {{ $opt->cat_option }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="">No options available</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-config-btn">✕</button>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" class="btn btn-outline-secondary btn-sm add-config-btn" data-index="{{ $index }}">+ Add Configuration</button>
    </div>
    <button type="button" class="btn btn-sm btn-outline-danger mt-3 remove-product-btn">Remove Product</button>
</div>
