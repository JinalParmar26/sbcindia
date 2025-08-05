@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Add User</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Name -->
                <div class="col-md-6 mb-3">
                    <label>Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label>Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label>Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6 mb-3">
                    <label>Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="profile_photo">Profile Photo</label>
                    <input type="file" name="profile_photo" class="form-control">
                    @error('profile_photo') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <!-- Phone Number -->
                <div class="col-md-6 mb-3">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                    @error('phone_number') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-control searchable-dropdown" required>
                        <option value="" disabled selected>Select a role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Is Active -->
                <div class="col-md-6 mb-3 d-flex align-items-center">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="isActive" value="1" id="isActive" {{ old('isActive') ? 'checked' : '' }}>
                        <label class="form-check-label" for="isActive">Active</label>
                    </div>
                </div>



                <!-- Working Days -->
                <div class="col-md-12 mb-3">
                    <label>Working Days</label>
                    <div class="d-flex flex-wrap gap-3 mt-2">
                        @foreach ($days as $day)
                        <div class="form-check me-3">
                            <input class="form-check-input" type="checkbox" name="working_days[]" value="{{ $day }}" id="day-{{ $day }}"
                                   {{ is_array(old('working_days')) && in_array($day, old('working_days')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="day-{{ $day }}">{{ ucfirst($day) }}</label>
                        </div>
                        @endforeach
                    </div>
                    @error('working_days') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <!-- Working Hours -->
                <div class="col-md-6 mb-3">
                    <label>Working Hours Start</label>
                    <input type="time" name="working_hours_start" class="form-control" value="{{ old('working_hours_start') }}">
                    @error('working_hours_start') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Working Hours End</label>
                    <input type="time" name="working_hours_end" class="form-control" value="{{ old('working_hours_end') }}">
                    @error('working_hours_end') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('users') }}" class="btn btn-secondary me-2">Cancel</a>
                <button type="submit" class="btn btn-primary">Save User</button>
            </div>
        </form>
    </div>
</div>

<style>
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
</script>
@endsection
