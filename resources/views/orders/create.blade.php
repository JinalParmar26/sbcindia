@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">{{ isset($order) ? 'Edit Order' : 'Add Order' }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}" method="POST">
            @csrf
            @if(isset($order)) @method('PUT') @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $order->title ?? '') }}" required>
                    @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6">
                    <label>Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-control" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id ?? '') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }} ({{ $customer->company_name }})
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <hr>
            <h5>Products</h5>
            <div id="products-wrapper">
                @php $productsData = old('products', $order->orderProducts ?? [ [] ]); @endphp
                @foreach ($productsData as $index => $productData)
                @include('orders.partials.product-form', [
                'index' => $index,
                'productData' => $productData,
                'products' => $products
                ])
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm" id="add-product-btn">+ Add Product</button>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('orders') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">{{ isset($order) ? 'Update' : 'Save' }} Order</button>
            </div>
        </form>
    </div>
</div>

@include('orders.partials.product-template', ['products' => $products])

<script>
    let productIndex = {{ count($productsData) }};

    document.getElementById('add-product-btn').addEventListener('click', function () {
        const template = document.getElementById('product-template').innerHTML;
        const rendered = template.replace(/__INDEX__/g, productIndex);
        document.getElementById('products-wrapper').insertAdjacentHTML('beforeend', rendered);
        productIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product-btn')) {
            e.target.closest('.product-item').remove();
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-config-btn')) {
            const wrapper = e.target.closest('.config-wrapper').querySelector('.config-items');
            const configIndex = wrapper.children.length;
            const index = e.target.dataset.index;
            const configHTML = `
                <div class="row mb-2 config-pair">
                    <div class="col-md-5"><input name="products[${index}][config_keys][]" class="form-control" placeholder="Key"></div>
                    <div class="col-md-5"><input name="products[${index}][config_values][]" class="form-control" placeholder="Value"></div>
                    <div class="col-md-2"><button type="button" class="btn btn-danger btn-sm remove-config-btn">✕</button></div>
                </div>
            `;
            wrapper.insertAdjacentHTML('beforeend', configHTML);
        }

        if (e.target.classList.contains('remove-config-btn')) {
            e.target.closest('.config-pair').remove();
        }
    });
</script>
@endsection
