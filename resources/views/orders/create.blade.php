@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Add Order</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                </div>

                <div class="col-md-6">
                    <label>Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-control searchable-dropdown" required>
                        <option value="">Select Customer</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
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
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="config-wrapper">
                <!-- Dynamic config inputs will be injected here -->
            </div>

            <hr>
            <h5>Order Images</h5>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Upload Images <small class="text-muted">(Max 5MB per image, jpg, jpeg, png, gif, svg)</small></label>
                    <div class="image-upload-container">
                        <div class="image-upload-wrapper">
                            <input type="file" name="order_images[]" class="form-control image-input" multiple accept="image/*" id="imageInput">
                            <div class="image-preview-container mt-3" id="imagePreviewContainer"></div>
                        </div>
                        <small class="text-muted">You can select multiple images at once or add them one by one.</small>
                    </div>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('orders') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Order</button>
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
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.image-upload-container:hover {
    border-color: #0d6efd;
    background-color: #e7f3ff;
}

.image-upload-container.drag-over {
    border-color: #0d6efd;
    background-color: #e7f3ff;
}

.image-preview-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.image-preview-item {
    position: relative;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow: hidden;
    background: white;
}

.image-preview-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.image-preview-item .image-info {
    padding: 0.5rem;
    font-size: 0.875rem;
    color: #6c757d;
}

.image-preview-item .image-name {
    font-weight: 500;
    margin-bottom: 0.25rem;
    word-break: break-word;
}

.image-preview-item .image-size {
    font-size: 0.75rem;
}

.image-preview-item .remove-image {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: rgba(220, 53, 69, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: background-color 0.2s;
}

.image-preview-item .remove-image:hover {
    background: rgba(220, 53, 69, 1);
}

.image-description {
    margin-top: 0.5rem;
}

.image-description input {
    width: 100%;
    padding: 0.25rem 0.5rem;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.upload-progress {
    margin-top: 1rem;
    display: none;
}

.upload-progress .progress {
    height: 20px;
}
</style>

<script>
// Initialize searchable dropdowns on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all searchable dropdowns
    initializeSearchableDropdowns();
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

document.addEventListener('change', function (e) {
    if (e.target.classList.contains('product-select')) {
        const productId = e.target.value;
        const container = document.querySelector('.config-wrapper');

        if (!productId || productId === 'other') return;

        fetch(`/products/${productId}/specs`)
            .then(res => res.json())
            .then(categories => {
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
                                data-subindex="${subIndex}">
                                Loading options...
                            </div>
                        </div>`;
                    } else if (cat.dynamic === 1) {
                        html += `<div class="mb-3">${label}
                            <input type="text" class="form-control config-input"
                                name="${inputName}" required pattern="\\d+"
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
                            .then(res => res.json())
                            .then(options => {
                                const group = container.querySelector(`[data-cat-id="${cat.key}"]`);
                                const subindex = group.dataset.subindex;
                                let radios = '';

                                options.forEach((opt, i) => {
                                    const normalizedValue = opt.cat_option.toLowerCase().replace(/\s+/g, '_');
                                    const id = `opt_${cat.key}_${i}_${Date.now()}`;
                                    radios += `
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="product_configs[${subindex}][${cat.key_slug}]"
                                                id="${id}" value="${normalizedValue}" required>
                                            <label class="form-check-label" for="${id}">${opt.cat_option}</label>
                                        </div>`;
                                });

                                group.innerHTML = radios;
                            });
                    }
                });
            });
    }
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('config-input')) {
        e.target.value = e.target.value.replace(/[^\d]/g, '');
    }
});

document.querySelector('form').addEventListener('submit', function (e) {
    const numberInputs = document.querySelectorAll('.config-input');
    for (let input of numberInputs) {
        const val = input.value.trim();
        if (!/^\d+$/.test(val)) {
            alert("Please enter only positive integers.");
            input.focus();
            e.preventDefault();
            return false;
        }
    }
});

// Image Upload Functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeImageUpload();
});

function initializeImageUpload() {
    const imageInput = document.getElementById('imageInput');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const uploadContainer = document.querySelector('.image-upload-container');
    let selectedFiles = [];

    // Handle file selection
    imageInput.addEventListener('change', function(e) {
        handleFileSelection(e.target.files);
    });

    // Handle drag and drop
    uploadContainer.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadContainer.classList.add('drag-over');
    });

    uploadContainer.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadContainer.classList.remove('drag-over');
    });

    uploadContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadContainer.classList.remove('drag-over');
        handleFileSelection(e.dataTransfer.files);
    });

    function handleFileSelection(files) {
        for (let file of files) {
            if (file.type.startsWith('image/')) {
                if (file.size > 5 * 1024 * 1024) { // 5MB limit
                    alert(`File ${file.name} is too large. Maximum size is 5MB.`);
                    continue;
                }
                
                const fileIndex = selectedFiles.length;
                selectedFiles.push(file);
                createImagePreview(file, fileIndex);
            } else {
                alert(`File ${file.name} is not an image.`);
            }
        }
        updateFileInput();
    }

    function createImagePreview(file, index) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'image-preview-item';
            previewItem.dataset.index = index;
            
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="remove-image" onclick="removeImage(${index})">×</button>
                <div class="image-info">
                    <div class="image-name">${file.name}</div>
                    <div class="image-size">${formatFileSize(file.size)}</div>
                    <div class="image-description">
                        <input type="text" name="image_descriptions[${index}]" placeholder="Optional description..." class="form-control">
                    </div>
                </div>
            `;
            
            imagePreviewContainer.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
    }

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            if (file) dataTransfer.items.add(file);
        });
        imageInput.files = dataTransfer.files;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Global function to remove images
    window.removeImage = function(index) {
        selectedFiles[index] = null;
        const previewItem = document.querySelector(`.image-preview-item[data-index="${index}"]`);
        if (previewItem) {
            previewItem.remove();
        }
        updateFileInput();
    };
}
</script>
@endsection
