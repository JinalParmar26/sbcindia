@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Edit Order</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('orders.update', $order) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $order->title) }}">
                </div>

                <div class="col-md-6">
                    <label>Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-control searchable-dropdown" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }} ({{ $customer->company_name }})
                        </option>
                        @endforeach
                    </select>
                    @error('customer_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <hr>
            <h5>Product</h5>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Product</label>
                    <select name="product_id" class="form-control product-select searchable-dropdown" data-index="0">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            @php
                                $selectedProductId = isset($productsData) && count($productsData) > 0 ? $productsData[0]['product_id'] : '';
                            @endphp
                            <option value="{{ $product->id }}" {{ $selectedProductId == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="config-wrapper">
                <!-- Dynamic config inputs will be injected here -->
            </div>

            <!-- Existing Images Section -->
            @if($order->images && $order->images->count() > 0)
            <hr>
            <h5>Current Images</h5>
            <div class="row mb-3">
                @foreach($order->images as $image)
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" alt="{{ $image->image_name }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted">{{ $image->image_name }}</small>
                            <button type="button" class="btn btn-sm btn-danger mt-1 delete-image" data-image-id="{{ $image->id }}">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Add New Images Section -->
            <hr>
            <h5>Add New Images</h5>
            <div class="row mb-3">
                <div class="col-12">
                    <label class="form-label">Order Images</label>
                    <div class="image-upload-container">
                        <input type="file" name="order_images[]" id="order_images" class="form-control" multiple accept="image/*,.avif" style="display: none;">
                        <div class="upload-area" id="upload-area">
                            <div class="upload-content">
                                <i class="fa fa-cloud-upload fa-3x mb-3"></i>
                                <p>Drag & drop images here or click to select</p>
                                <p class="text-muted">Supports: JPG, PNG, GIF, SVG, WEBP, AVIF (Max 10MB each)</p>
                            </div>
                        </div>
                        <div class="image-preview" id="image-preview"></div>
                    </div>
                    @error('order_images') <small class="text-danger">{{ $message }}</small> @enderror
                    @error('order_images.*') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('orders.show', $order->uuid) }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </div>
        </form>
    </div>
</div>

<style>
.category-radio-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

/* Searchable dropdown styles */
.searchable-dropdown-wrapper {
    position: relative;
}

.searchable-dropdown-input {
    width: 100%;
    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    cursor: pointer;
}

.searchable-dropdown-input:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.searchable-dropdown-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ced4da;
    border-top: none;
    border-radius: 0 0 0.375rem 0.375rem;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.searchable-dropdown-item {
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.searchable-dropdown-item:hover {
    background-color: #f8f9fa;
}

.searchable-dropdown-item:last-child {
    border-bottom: none;
}

.searchable-dropdown-item.selected {
    background-color: #e9ecef;
}

.searchable-dropdown-no-results {
    padding: 0.5rem 0.75rem;
    color: #6c757d;
    font-style: italic;
}

/* Image Upload Styles */
.image-upload-container {
    margin-bottom: 20px;
}

.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.upload-area:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.upload-area.dragover {
    border-color: #007bff;
    background-color: #e3f2fd;
}

.upload-content i {
    color: #6c757d;
}

.image-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 15px;
}

.image-preview-item {
    position: relative;
    width: 150px;
    height: 150px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: #f8f9fa;
}

.image-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.image-preview-item .remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    cursor: pointer;
    font-size: 12px;
}

.image-preview-item .remove-btn:hover {
    background: rgba(220, 53, 69, 1);
}
</style>

<script>
// Initialize searchable dropdowns on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all searchable dropdowns
    initializeSearchableDropdowns();
    
    @if(isset($productsData) && count($productsData) > 0 && !empty($productsData[0]['product_id']))
        const productId = {{ $productsData[0]['product_id'] }};
        const existingConfig = @json($productsData[0]['configurations']);
        console.log('Loading existing product configuration for product:', productId);
        setTimeout(() => {
            loadProductConfig(productId, existingConfig);
        }, 100);
    @endif
});

// Function to initialize searchable dropdowns
function initializeSearchableDropdowns() {
    document.querySelectorAll('.searchable-dropdown').forEach(function(select) {
        createSearchableDropdown(select);
    });
}

function createSearchableDropdown(selectElement) {
    const wrapper = document.createElement('div');
    wrapper.className = 'searchable-dropdown-wrapper';
    
    const input = document.createElement('input');
    input.type = 'text';
    input.className = 'form-control searchable-dropdown-input';
    input.placeholder = selectElement.options[0] ? selectElement.options[0].text : 'Select an option';
    input.readOnly = false;
    
    const list = document.createElement('div');
    list.className = 'searchable-dropdown-list';
    
    // Store original options
    const options = Array.from(selectElement.options);
    
    // Set initial value if option is selected
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    if (selectedOption && selectedOption.value) {
        input.value = selectedOption.text;
    }
    
    // Replace select with wrapper
    selectElement.style.display = 'none';
    selectElement.parentNode.insertBefore(wrapper, selectElement);
    wrapper.appendChild(input);
    wrapper.appendChild(list);
    wrapper.appendChild(selectElement);
    
    // Show/hide dropdown
    input.addEventListener('click', function() {
        toggleDropdown(list, options, input, selectElement);
    });
    
    input.addEventListener('focus', function() {
        showDropdown(list, options, input, selectElement);
    });
    
    // Filter options as user types
    input.addEventListener('input', function() {
        filterOptions(list, options, input.value, input, selectElement);
    });
    
    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!wrapper.contains(e.target)) {
            list.style.display = 'none';
        }
    });
}

function toggleDropdown(list, options, input, selectElement) {
    if (list.style.display === 'block') {
        list.style.display = 'none';
    } else {
        showDropdown(list, options, input, selectElement);
    }
}

function showDropdown(list, options, input, selectElement) {
    filterOptions(list, options, input.value, input, selectElement);
    list.style.display = 'block';
}

function filterOptions(list, options, searchTerm, input, selectElement) {
    list.innerHTML = '';
    
    const filteredOptions = options.filter(option => {
        if (!option.value) return true; // Keep placeholder option
        return option.text.toLowerCase().includes(searchTerm.toLowerCase());
    });
    
    if (filteredOptions.length === 0 || (filteredOptions.length === 1 && !filteredOptions[0].value)) {
        const noResults = document.createElement('div');
        noResults.className = 'searchable-dropdown-no-results';
        noResults.textContent = 'No results found';
        list.appendChild(noResults);
    } else {
        filteredOptions.forEach(option => {
            if (!option.value && searchTerm) return; // Hide placeholder when searching
            
            const item = document.createElement('div');
            item.className = 'searchable-dropdown-item';
            item.textContent = option.text;
            item.dataset.value = option.value;
            
            if (option.selected) {
                item.classList.add('selected');
            }
            
            item.addEventListener('click', function() {
                selectOption(option, input, selectElement, list);
            });
            
            list.appendChild(item);
        });
    }
}

function selectOption(option, input, selectElement, list) {
    input.value = option.text;
    selectElement.value = option.value;
    
    // Remove selected class from all items
    list.querySelectorAll('.searchable-dropdown-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selected class to current item
    const currentItem = list.querySelector(`[data-value="${option.value}"]`);
    if (currentItem) {
        currentItem.classList.add('selected');
    }
    
    list.style.display = 'none';
    
    // Trigger change event
    selectElement.dispatchEvent(new Event('change', { bubbles: true }));
}

// Handle product selection change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        const productId = e.target.value;
        
        if (productId) {
            loadProductConfig(productId);
        } else {
            // Clear the config wrapper if no product selected
            const container = document.querySelector('.config-wrapper');
            if (container) {
                container.innerHTML = '';
            }
        }
    }
});

function loadProductConfig(productId, existingConfig = null) {
    const container = document.querySelector('.config-wrapper');
    
    if (!productId) return;

    console.log('Loading config for product:', productId, 'existing:', existingConfig);

    fetch(`/products/${productId}/specs`)
        .then(res => {
            if (!res.ok) {
                throw new Error('Failed to load specs');
            }
            return res.json();
        })
        .then(categories => {
            console.log('Categories loaded:', categories);
            let html = '';
            let subIndex = 0;

            Object.values(categories).forEach(cat => {
                const label = `<label class="form-label">${cat.value} <span class='text-danger'>*</span></label>`;
                const inputName = `product_configs[${subIndex}][${cat.key_slug}]`;

                if (cat.dynamic === 0) {
                    html += `<div class="mb-3">${label}
                        <div class="category-radio-group"
                            data-cat-id="${cat.key}"
                            data-key-slug="${cat.key_slug}"
                            data-subindex="${subIndex}"
                            data-category-name="${cat.value}">
                            Loading options...
                        </div>
                    </div>`;
                } else if (cat.dynamic === 1) {
                    const existingValue = existingConfig && existingConfig[cat.value] ? existingConfig[cat.value] : '';
                    html += `<div class="mb-3">${label}
                        <input type="text" class="form-control config-input"
                            name="${inputName}" value="${existingValue}" required pattern="\\d+"
                            inputmode="numeric" placeholder="Enter number only">
                    </div>`;
                }

                subIndex++;
            });

            container.innerHTML = html;

            // Load options for dynamic=0 categories
            Object.values(categories).forEach(cat => {
                if (cat.dynamic === 0) {
                    fetch(`/products/${cat.key}/options`)
                        .then(res => {
                            if (!res.ok) {
                                throw new Error('Failed to load options');
                            }
                            return res.json();
                        })
                        .then(options => {
                            console.log('Options loaded for category:', cat.value, options);
                            const group = container.querySelector(`[data-cat-id="${cat.key}"]`);
                            if (!group) {
                                console.error('Group not found for category:', cat.key);
                                return;
                            }
                            
                            const subindex = group.dataset.subindex;
                            const categoryName = group.dataset.categoryName;
                            let radios = '';

                            // Get existing value for this category
                            const existingValue = existingConfig && existingConfig[categoryName] ? 
                                existingConfig[categoryName].toLowerCase().replace(/\s+/g, '_') : '';

                            console.log('Existing value for', categoryName, ':', existingValue);

                            options.forEach((opt, i) => {
                                const normalizedValue = opt.cat_option.toLowerCase().replace(/\s+/g, '_');
                                const id = `opt_${cat.key}_${i}_${Date.now()}`;
                                const isChecked = existingValue === normalizedValue ? 'checked' : '';
                                
                                radios += `
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="product_configs[${subindex}][${cat.key_slug}]"
                                            id="${id}" value="${normalizedValue}" ${isChecked} required>
                                        <label class="form-check-label" for="${id}">${opt.cat_option}</label>
                                    </div>`;
                            });

                            group.innerHTML = radios;
                        })
                        .catch(error => {
                            console.error('Error loading options:', error);
                            const group = container.querySelector(`[data-cat-id="${cat.key}"]`);
                            if (group) {
                                group.innerHTML = 'Error loading options';
                            }
                        });
                }
            });
        })
        .catch(error => {
            console.error('Error loading specs:', error);
            container.innerHTML = '<div class="alert alert-danger">Error loading product configuration</div>';
        });
}

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('config-input')) {
        e.target.value = e.target.value.replace(/[^\d]/g, '');
    }
});

document.querySelector('form').addEventListener('submit', function (e) {
    const numberInputs = document.querySelectorAll('.config-input');
    for (let input of numberInputs) {
        const val = input.value.trim();
        if (val && !/^\d+$/.test(val)) {
            alert("Please enter only positive integers.");
            input.focus();
            e.preventDefault();
            return false;
        }
    }
});

// Image Upload Functionality
document.addEventListener('DOMContentLoaded', function() {
    const uploadArea = document.getElementById('upload-area');
    const fileInput = document.getElementById('order_images');
    const imagePreview = document.getElementById('image-preview');
    let selectedFiles = [];

    // Click to select files
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = Array.from(e.dataTransfer.files);
        handleFiles(files);
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        const files = Array.from(e.target.files);
        handleFiles(files);
    });

    function handleFiles(files) {
        console.log('Files to handle:', files);
        files.forEach(file => {
            if (file.type.startsWith('image/')) {
                selectedFiles.push(file);
                displayPreview(file);
                console.log('Added file:', file.name);
            } else {
                console.log('Skipped non-image file:', file.name);
            }
        });
        updateFileInput();
        console.log('Total selected files:', selectedFiles.length);
    }

    function displayPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const previewItem = document.createElement('div');
            previewItem.className = 'image-preview-item';
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="remove-btn" onclick="removeImage(${selectedFiles.length - 1})">×</button>
            `;
            imagePreview.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        console.log('Updated file input with', fileInput.files.length, 'files');
    }

    window.removeImage = function(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        refreshPreviews();
    };

    function refreshPreviews() {
        imagePreview.innerHTML = '';
        selectedFiles.forEach(file => displayPreview(file));
    }

    // Delete existing images
    document.querySelectorAll('.delete-image').forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            if (confirm('Are you sure you want to delete this image?')) {
                fetch(`/orders/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.col-md-3').remove();
                    } else {
                        alert('Failed to delete image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete image');
                });
            }
        });
    });

    // Add form submission handler for debugging
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('Form submitting...');
        const formData = new FormData(this);
        
        console.log('Form data contents:');
        for (let [key, value] of formData.entries()) {
            if (key === 'order_images[]') {
                console.log(`${key}:`, value.name, 'Size:', value.size);
            } else {
                console.log(`${key}:`, value);
            }
        }
        
        const fileInput = document.getElementById('order_images');
        console.log('File input files:', fileInput.files.length);
        
        // Don't prevent default, let form submit normally
    });
});
</script>
@endsection
