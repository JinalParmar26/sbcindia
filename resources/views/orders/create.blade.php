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
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $order->title ?? '') }}">
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
<!-- Modal for adding new product -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="addProductForm" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Product Name -->
          <div class="mb-3">
            <label>Product Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="product_name" class="form-control" required>
            <div class="invalid-feedback"></div>
          </div>

          <!-- Model Number -->
          <div class="mb-3">
            <label>Model Number <span class="text-danger">*</span></label>
            <input type="text" name="model_number" id="model_number" class="form-control" required>
            <div class="invalid-feedback"></div>
          </div>

          <!-- Image Upload -->
          <div class="mb-3">
            <label>Product Image <span class="text-danger">*</span></label>
            <input type="file" name="image" id="image" class="form-control" required>
            <div class="invalid-feedback"></div>
          </div>

          <!-- Description -->
          <div class="mb-3">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
            <div class="invalid-feedback"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Product & Select</button>
        </div>
      </div>
    </form>
  </div>
</div>


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

    document.addEventListener('DOMContentLoaded', function () {
        let currentProductSelectIndex = null;

        // Bootstrap 5 modal instance
        const addProductModalEl = document.getElementById('addProductModal');
        const addProductModal = new bootstrap.Modal(addProductModalEl);

        document.addEventListener('change', function (e) {
            if (e.target && e.target.classList.contains('product-select')) {
                if (e.target.value === 'other') {
                    currentProductSelectIndex = e.target.dataset.index;

                    // Reset modal form
                    const form = document.getElementById('addProductForm');
                    form.reset();
                    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                    
                    addProductModal.show();
                }
            }
        });
        // Handle add product form submit via AJAX
        document.getElementById('addProductForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Clear previous errors
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            this.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Saving... <span class="spinner-border spinner-border-sm ms-1"></span>';


            fetch("{{ route('products.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                if (!response.ok) {
                    let data = await response.json();
                    if (data.errors) {
                        // Show validation errors
                        Object.keys(data.errors).forEach(field => {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                input.nextElementSibling.textContent = data.errors[field][0];
                            }
                        });
                    } else {
                        alert('Failed to save product. Try again.');
                    }
                    throw new Error('Validation failed');
                }
                return response.json();
            })
            .then(data => {
               
                const currentSelect = document.querySelector(`.product-select[data-index="${currentProductSelectIndex}"]`);
                if (currentSelect) {
                    const option = document.createElement('option');
                    option.value = data.id;
                    option.text = data.name;
                    currentSelect.appendChild(option);
                    currentSelect.value = data.id;
                }

               setTimeout(() => {
                   
                    if (currentSelect) {
                        currentSelect.value = data.id;
                    }
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Save Product & Select';
                    // Now hide the modal
                    addProductModal.hide();
                }, 100); // slight delay ensures .value takes effect
             })
            .catch(err => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Save Product & Select';
                 addProductModal.hide();
                console.error(err);
            });
        });
    });

</script>
@endsection
