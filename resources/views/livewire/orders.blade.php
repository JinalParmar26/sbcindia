<div>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Volt</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Order List</li>
                </ol>
            </nav>
            <h2 class="h4">Order List</h2>
            <p class="mb-0">Your web analytics dashboard template.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <a href="{{ route('orders.create') }}" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    New Order
                </a>
                <a href="{{ route('customers.create') }}" class="btn btn-sm btn-outline-gray-600 d-inline-flex align-items-center">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>
                    </svg>
                    Add Customer
                </a>
            </div>
            <div class="btn-group ms-2 ms-lg-3">
                <button type="button" class="btn btn-sm btn-outline-gray-600" onclick="exportToCsv()">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="exportOrdersPdf()">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Download PDF
                </button>
            </div>
        </div>
    </div>

    <div class="table-settings mb-4">
        <div class="row justify-content-between align-items-center">
            <div class="col-9 col-lg-8 d-md-flex">
                <div class="input-group me-2 me-lg-3 fmxw-300">
                    <span class="input-group-text">
                        <svg class="icon icon-xs" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                  d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search orders">
                </div>
                <div class="input-group me-2 me-lg-3 fmxw-300">
                    <div class="searchable-dropdown">
                        <input type="text" 
                               id="customerSearchInput" 
                               class="form-control" 
                               placeholder="Search customers..." 
                               autocomplete="off">
                        <div class="dropdown-menu" id="customerDropdown" style="display: none;">
                            <div class="dropdown-item" data-value="all">All Customers</div>
                            @if(isset($customers) && count($customers) > 0)
                                @foreach($customers as $customer)
                                    <div class="dropdown-item" data-value="{{ $customer->id }}">{{ $customer->name }}</div>
                                @endforeach
                            @else
                                <div class="dropdown-item" data-value="">No customers found</div>
                            @endif
                        </div>
                        <input type="hidden" wire:model="customerFilter" id="customerFilterHidden" value="{{ $customerFilter }}">
                    </div>
                </div>
                 <div class="input-group me-2 me-lg-3 fmxw-300">
                    <select wire:model="yearFilter" id="yearFilter"  class="form-select d-none d-md-inline">
                        <option value="all">All Years</option>
                        @foreach($availableYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Month Filter -->
                <div class="col-6 col-md-3 col-lg-2">
                    <select wire:model="monthFilter" class="form-select">
                        <option value="all">All Months</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                        @endfor
                    </select>
                </div>

            </div>

            <div class="col-3 col-lg-4 d-flex justify-content-end">
                <div class="btn-group">
                    <div class="dropdown me-1">
                        <button class="btn btn-link text-dark dropdown-toggle dropdown-toggle-split m-0 p-1"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z">
                                </path>
                            </svg>
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end pb-0">
                            <span class="small ps-3 fw-bold text-dark">Show</span>
                            <a class="dropdown-item d-flex align-items-center fw-bold" href="#" wire:click.prevent="$set('perPage', 10)">10 <svg
                                    class="icon icon-xxs ms-auto" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                          clip-rule="evenodd"></path>
                                </svg></a>
                            <a class="dropdown-item fw-bold" href="#" wire:click.prevent="$set('perPage', 20)">20</a>
                            <a class="dropdown-item fw-bold rounded-bottom" href="#" wire:click.prevent="$set('perPage', 30)">30</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-hover align-items-center">
            <thead>
            <tr>
{{--                <th>--}}
{{--                    <input class="form-check-input" type="checkbox" wire:click="$toggle('selectAll')">--}}
{{--                </th>--}}
                <th wire:click="sortBy('title')" style="cursor: pointer;">
                    Order Title
                    @if ($sortField === 'title')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>
                <th wire:click="sortBy('customers.name')" style="cursor: pointer;">
                    Customer
                    @if ($sortField === 'customers.name')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>
                <th>Model Number</th>
                <th>Serial Number</th>
                <th wire:click="sortBy('created_at')" style="cursor: pointer;">
                    Date
                    @if ($sortField === 'created_at')
                        @if ($sortDirection === 'asc') ↑ @else ↓ @endif
                    @endif
                </th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($orders as $order)
            <tr>
{{--                <td>--}}
{{--                    <input class="form-check-input" type="checkbox" wire:model="selectedOrders" value="{{ $order->id }}">--}}
{{--                </td>--}}
                <td>
                    <a href="{{ route('orders.show', $order->uuid) }}" class="fw-bold text-primary">
                        {{ $order->title }}
                    </a>
                </td>
                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                <td>
                    @if($order->orderProducts && $order->orderProducts->count() > 0)
                        {{ $order->orderProducts->first()->model_number ?? $order->orderProducts->first()->product->name ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($order->orderProducts && $order->orderProducts->count() > 0)
                        {{ $order->orderProducts->first()->serial_number ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $order->created_at->format('M d, Y') }}</td>
                <td>
                    <div class="dropdown">
                        <a href="#" class="btn btn-sm btn-gray-600 dropdown-toggle" data-bs-toggle="dropdown">
                            Actions
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('orders.show', $order->uuid) }}">View</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.edit', $order->id) }}">Edit</a></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" wire:click.prevent="confirmDelete({{ $order->id }})">
                                    Delete
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">No orders found.</td>
            </tr>
            @endforelse
            </tbody>
        </table>

        <div wire:ignore.self class="modal fade" id="deleteOrderModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this order?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" wire:click="deleteOrder()" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- Inline styles to avoid multiple root elements --}}
    <style>
    .searchable-dropdown {
        position: relative;
        width: 100%;
    }

    .searchable-dropdown .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 9999;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        background: white;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin-top: 1px;
    }

    .searchable-dropdown .dropdown-item {
        padding: 0.5rem 1rem;
        cursor: pointer;
        border-bottom: 1px solid #f8f9fa;
        color: #212529;
        text-decoration: none;
        display: block;
        clear: both;
        font-weight: 400;
        white-space: nowrap;
        background-color: transparent;
        transition: background-color 0.15s ease-in-out;
    }

    .searchable-dropdown .dropdown-item:hover {
        background-color: #e9ecef;
        color: #16181b;
    }

    .searchable-dropdown .dropdown-item.selected {
        background-color: #0d6efd;
        color: white;
        font-weight: 500;
    }

    .searchable-dropdown .dropdown-item:last-child {
        border-bottom: none;
    }

    .searchable-dropdown .dropdown-item:first-child {
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }

    .searchable-dropdown .dropdown-item:last-child {
        border-bottom-left-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }

    .searchable-dropdown input[type="text"] {
        cursor: pointer;
    }

    .searchable-dropdown input[type="text"]:focus {
        cursor: text;
    }
    </style>

    {{-- Inline scripts to avoid multiple root elements --}}
    <script>
        document.addEventListener('livewire:load', function () {
            initializeSearchableDropdown();
        });

        document.addEventListener('livewire:update', function () {
            setTimeout(() => {
                initializeSearchableDropdown();
            }, 100);
        });

        function initializeSearchableDropdown() {
            const searchInput = document.getElementById('customerSearchInput');
            const dropdown = document.getElementById('customerDropdown');
            const hiddenInput = document.getElementById('customerFilterHidden');
            
            if (!searchInput || !dropdown || !hiddenInput) {
                return;
            }
            
            const dropdownItems = dropdown.querySelectorAll('.dropdown-item');
            
            // Set initial display value
            const currentValue = hiddenInput.value || 'all';
            const currentItem = dropdown.querySelector(`[data-value="${currentValue}"]`);
            if (currentItem) {
                searchInput.value = currentItem.textContent.trim();
                currentItem.classList.add('selected');
            } else {
                searchInput.value = 'All Customers';
            }
            
            // Remove existing event listeners to prevent duplicates
            searchInput.replaceWith(searchInput.cloneNode(true));
            const newSearchInput = document.getElementById('customerSearchInput');
            
            // Show dropdown on input focus
            newSearchInput.addEventListener('focus', function() {
                dropdown.style.display = 'block';
                filterItems();
            });
            
            // Filter items based on search input
            newSearchInput.addEventListener('input', function() {
                filterItems();
            });
            
            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!newSearchInput.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });
            
            // Handle item selection
            dropdownItems.forEach(item => {
                item.replaceWith(item.cloneNode(true));
            });
            
            dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const value = this.getAttribute('data-value');
                    const text = this.textContent.trim();
                    
                    // Update input and hidden field
                    newSearchInput.value = text;
                    hiddenInput.value = value;
                    
                    // Update selected state
                    dropdown.querySelectorAll('.dropdown-item').forEach(i => i.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    // Hide dropdown
                    dropdown.style.display = 'none';
                    
                    // Trigger Livewire update
                    @this.set('customerFilter', value);
                });
            });
            
            function filterItems() {
                const searchTerm = newSearchInput.value.toLowerCase();
                let hasVisibleItems = false;
                
                dropdown.querySelectorAll('.dropdown-item').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        item.style.display = 'block';
                        hasVisibleItems = true;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                if (hasVisibleItems) {
                    dropdown.style.display = 'block';
                }
            }
        }

        // Modal events
        window.addEventListener('show-delete-modal', () => {
            const modal = new bootstrap.Modal(document.getElementById('deleteOrderModal'));
            modal.show();
        });
        
        window.addEventListener('hide-delete-modal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteOrderModal'));
            if (modal) modal.hide();
        });

        // CSV Export function
        function exportToCsv() {
            const currentFilters = {
                search: @this.get('search') || '',
                customer_filter: @this.get('customerFilter') || 'all',
                year_filter: @this.get('yearFilter') || 'all',
                month_filter: @this.get('monthFilter') || 'all'
            };

            // Build URL with current filters
            const params = new URLSearchParams();
            Object.keys(currentFilters).forEach(key => {
                if (currentFilters[key] && currentFilters[key] !== 'all') {
                    params.append(key, currentFilters[key]);
                }
            });

            const url = '{{ route("orders.export.csv") }}' + (params.toString() ? '?' + params.toString() : '');
            window.open(url, '_blank');
        }

        // PDF Export function
        function exportOrdersPdf() {
            const currentFilters = {
                search: @this.get('search') || '',
                customer_filter: @this.get('customerFilter') || 'all',
                year_filter: @this.get('yearFilter') || 'all',
                month_filter: @this.get('monthFilter') || 'all'
            };

            // Build URL with current filters
            const params = new URLSearchParams();
            Object.keys(currentFilters).forEach(key => {
                if (currentFilters[key] && currentFilters[key] !== 'all') {
                    params.append(key, currentFilters[key]);
                }
            });

            const url = '{{ route("orders.export.pdf") }}' + (params.toString() ? '?' + params.toString() : '');
            window.open(url, '_blank');
        }

        // Make functions globally available
        window.exportToCsv = exportToCsv;
        window.exportOrdersPdf = exportOrdersPdf;
    </script>
</div>
