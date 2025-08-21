@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Ticket Details</h4>
        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-primary">Edit Ticket</a>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="col-md-3">Subject</dt>
            <dd class="col-md-9">{{ $ticket->subject }}</dd>

            <dt class="col-md-3">Customer</dt>
            <dd class="col-md-9">{{ $ticket->customer->name ?? '-' }}</dd>

            <dt class="col-md-3">Order Product</dt>
            <dd class="col-md-9">
                {{ $ticket->orderProduct->product->name ?? 'N/A' }}
                (Order #{{ $ticket->orderProduct->order->id ?? 'N/A' }})
            </dd>

            <dt class="col-md-3">Assigned To</dt>
            <dd class="col-md-9">{{ $ticket->assignedTo->name ?? '-' }}</dd>

            <dt class="col-md-3">Additional Staff</dt>
            <dd class="col-md-9">
                @if($ticket->additionalStaff && $ticket->additionalStaff->isNotEmpty())
                <ul class="mb-0">
                    @foreach($ticket->additionalStaff as $staff)
                    <li>{{ $staff->name }}</li>
                    @endforeach
                </ul>
                @else
                <span class="text-muted">None</span>
                @endif
            </dd>

            <dt class="col-md-3">Created At</dt>
            <dd class="col-md-9">{{ $ticket->created_at->format('Y-m-d H:i') }}</dd>

            <dt class="col-md-3">Updated At</dt>
            <dd class="col-md-9">{{ $ticket->updated_at->format('Y-m-d H:i') }}</dd>
        </dl>
    </div>
</div>

<!-- Services Details Section -->
@if($ticket->services && count($ticket->services) > 0)
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Services details</h5>
    </div>
    <div class="card-body">
        @foreach($ticket->services as $index => $service)
        <div class="service-details mb-4 p-4 border rounded {{ $index > 0 ? 'mt-4' : '' }}" style="background-color: #f8f9fa;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-primary mb-0">
                    <i class="bi bi-wrench me-2"></i>
                    Service {{ $index + 1 }}: {{ ucfirst(str_replace('_', ' ', $service->service_type)) }}
                </h6>
                <span class="badge bg-{{ $service->payment_status === 'received' ? 'success' : 'warning' }}">
                    {{ ucfirst($service->payment_status ?? 'pending') }}
                </span>
            </div>
            
            <!-- Basic Information -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card bg-white border-0 shadow-sm">
                        <div class="card-header bg-primary text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Basic Information</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Contact Person:</dt>
                                <dd class="col-sm-7">{{ $service->contact_person_name ?? '-' }}</dd>
                                
                                <dt class="col-sm-5">Payment Type:</dt>
                                <dd class="col-sm-7">
                                    <span class="badge bg-{{ $service->payment_type === 'warranty' ? 'success' : ($service->payment_type === 'paid' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($service->payment_type ?? 'N/A') }}
                                    </span>
                                </dd>
                                
                                <dt class="col-sm-5">Unit Model:</dt>
                                <dd class="col-sm-7">{{ $service->unit_model_number ?? '-' }}</dd>
                                
                                <dt class="col-sm-5">Unit Serial No:</dt>
                                <dd class="col-sm-7">{{ $service->unit_sr_no ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card bg-white border-0 shadow-sm">
                        <div class="card-header bg-success text-white py-2">
                            <h6 class="mb-0"><i class="bi bi-calendar me-2"></i>Schedule & Location</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Start Date:</dt>
                                <dd class="col-sm-7">
                                    @if($service->start_date_time)
                                        {{ \Carbon\Carbon::parse($service->start_date_time)->format('M d, Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-5">End Date:</dt>
                                <dd class="col-sm-7">
                                    @if($service->end_date_time)
                                        {{ \Carbon\Carbon::parse($service->end_date_time)->format('M d, Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </dd>
                                
                                <dt class="col-sm-5">Start Location:</dt>
                                <dd class="col-sm-7">{{ $service->start_location_name ?? '-' }}</dd>
                                
                                <dt class="col-sm-5">End Location:</dt>
                                <dd class="col-sm-7">{{ $service->end_location_name ?? '-' }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Technical Readings -->
            @if($service->service_type === 'service_report' || $service->voltage || $service->refrigerant)
            <div class="mb-3">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header bg-info text-white py-2">
                        <h6 class="mb-0"><i class="bi bi-gear me-2"></i>Technical Readings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Electrical Readings -->
                            <div class="col-md-4">
                                <h6 class="text-secondary mb-2">Electrical</h6>
                                <dl class="row">
                                    <dt class="col-sm-6">Voltage:</dt>
                                    <dd class="col-sm-6">{{ $service->voltage ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Amp R:</dt>
                                    <dd class="col-sm-6">{{ $service->amp_r ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Amp Y:</dt>
                                    <dd class="col-sm-6">{{ $service->amp_y ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Amp B:</dt>
                                    <dd class="col-sm-6">{{ $service->amp_b ?? '-' }}</dd>
                                </dl>
                            </div>
                            
                            <!-- Pressure Readings -->
                            <div class="col-md-4">
                                <h6 class="text-secondary mb-2">Pressure & Gas</h6>
                                <dl class="row">
                                    <dt class="col-sm-6">Refrigerant:</dt>
                                    <dd class="col-sm-6">{{ $service->refrigerant ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Standing Pressure:</dt>
                                    <dd class="col-sm-6">{{ $service->standing_pressure ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Suction Pressure:</dt>
                                    <dd class="col-sm-6">{{ $service->suction_pressure ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Discharge Pressure:</dt>
                                    <dd class="col-sm-6">{{ $service->discharge_pressure ?? '-' }}</dd>
                                </dl>
                            </div>
                            
                            <!-- Temperature Readings -->
                            <div class="col-md-4">
                                <h6 class="text-secondary mb-2">Temperature</h6>
                                <dl class="row">
                                    <dt class="col-sm-6">Room Temp:</dt>
                                    <dd class="col-sm-6">{{ $service->room_temp ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Cabinet Temp:</dt>
                                    <dd class="col-sm-6">{{ $service->cabinet_temp ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Suction Temp:</dt>
                                    <dd class="col-sm-6">{{ $service->suction_temp ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Discharge Temp:</dt>
                                    <dd class="col-sm-6">{{ $service->discharge_temp ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Water Tank Temp:</dt>
                                    <dd class="col-sm-6">{{ $service->water_tank_temp ?? '-' }}</dd>
                                </dl>
                            </div>
                        </div>
                        
                        @if($service->chilled_water_in || $service->chilled_water_out)
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h6 class="text-secondary mb-2">Water Temperature</h6>
                                <dl class="row">
                                    <dt class="col-sm-6">Chilled Water In:</dt>
                                    <dd class="col-sm-6">{{ $service->chilled_water_in ?? '-' }}</dd>
                                    
                                    <dt class="col-sm-6">Chilled Water Out:</dt>
                                    <dd class="col-sm-6">{{ $service->chilled_water_out ?? '-' }}</dd>
                                </dl>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Service Description -->
            @if($service->service_description)
            <div class="mb-3">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white py-2">
                        <h6 class="mb-0"><i class="bi bi-file-text me-2"></i>Service Description</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $service->service_description }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Service Log -->
            @if($service->log)
            <div class="mb-3">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header bg-dark text-white py-2">
                        <h6 class="mb-0"><i class="bi bi-terminal me-2"></i>Service Log</h6>
                    </div>
                    <div class="card-body">
                        <div class="bg-light p-3 rounded">
                            <pre class="mb-0 text-muted" style="font-size: 0.875rem; white-space: pre-wrap; word-wrap: break-word;">{{ $service->log }}</pre>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Service Items -->
            @if($service->serviceItems && $service->serviceItems->isNotEmpty())
            <div class="mb-3">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark py-2">
                        <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Service Items Used</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalAmount = 0; @endphp
                                    @foreach($service->serviceItems as $item)
                                    <tr>
                                        <td>{{ $item->item }}</td>
                                        <td>{{ $item->qty }}</td>
                                        <td>₹{{ number_format($item->rate, 2) }}</td>
                                        <td>₹{{ number_format($item->amount, 2) }}</td>
                                    </tr>
                                    @php $totalAmount += $item->amount; @endphp
                                    @endforeach
                                    <tr class="table-info">
                                        <td colspan="3" class="text-end"><strong>Total</strong></td>
                                        <td><strong>₹{{ number_format($totalAmount, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <div class="card bg-white border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark py-2">
                        <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Attended by</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Photo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $service->assignedTo->name }}</td>
                                        <td>{{ $service->assignedTo->email }}</td>
                                        <td>{{ $service->assignedTo->phone_number }}</td>
                                        <td> 
                                            @if(!empty($service->assignedTo->profile_photo ))
                                            @php
                                                $signPath = $service->assignedTo->profile_photo ?? null;
                                                $signFull = $signPath ? asset('storage/' . $signPath) : null;
                                            @endphp
                                            <img src="{{ $signFull }}" alt="{{ $service->assignedTo->name }}" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            
            
            @endif
            
            <!-- Service Timestamps -->
            <div class="text-muted small d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                <span>
                    <i class="bi bi-calendar-plus me-1"></i>
                    Created: {{ $service->created_at->format('Y-m-d H:i') }}
                </span>
                <span>
                    <i class="bi bi-calendar-check me-1"></i>
                    Updated: {{ $service->updated_at->format('Y-m-d H:i') }}
                </span>
                <a href="{{ route('challan.single.pdf', $service->uuid) }}" class="btn btn-outline-primary me-2" target="_blank">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download Challan
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Services details</h5>
    </div>
    <div class="card-body text-center py-5">
        <i class="bi bi-wrench display-1 text-muted"></i>
        <p class="text-muted mt-3">No services have been recorded for this ticket yet.</p>
    </div>
</div>
@endif

<!-- Ticket Images Section -->
@if($ticket->ticketImages && $ticket->ticketImages->isNotEmpty())
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Ticket Images ({{ $ticket->ticketImages->count() }})</h5>
        <small class="text-muted">Click on images to view full size</small>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @foreach($ticket->ticketImages as $image)
            <div class="col-md-3 col-sm-4 col-6">
                <div class="image-card position-relative" id="image-card-{{ $image->id }}">
                    <img src="{{ $image->image_url }}" 
                         class="img-fluid rounded shadow-sm cursor-pointer ticket-image" 
                         alt="Ticket Image"
                         data-bs-toggle="modal" 
                         data-bs-target="#imageModal"
                         data-image-url="{{ $image->image_url }}"
                         data-description="{{ $image->description }}"
                         data-uploaded-by="{{ $image->uploadedBy->name ?? 'Unknown' }}"
                         data-uploaded-at="{{ $image->created_at->format('Y-m-d H:i') }}"
                         style="height: 150px; object-fit: cover; cursor: pointer;">
                    
                    <!-- Delete Button -->
                    <button type="button" 
                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 delete-image-btn" 
                            data-image-id="{{ $image->id }}"
                            data-bs-toggle="tooltip"
                            title="Delete Image"
                            style="font-size: 0.7rem; padding: 0.25rem 0.5rem; z-index: 10;">
                        <i class="bi bi-trash"></i>
                    </button>
                    
                    @if($image->description)
                    <div class="image-overlay position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white p-2 rounded-bottom">
                        <small>{{ Str::limit($image->description, 50) }}</small>
                    </div>
                    @endif
                </div>
                
                <div class="mt-2 text-center">
                    <small class="text-muted d-block">
                        By: {{ $image->uploadedBy->name ?? 'Unknown' }}
                    </small>
                    <small class="text-muted d-block">
                        {{ $image->created_at->format('M d, Y H:i') }}
                    </small>
                    <small class="text-muted d-block">
                        {{ $image->file_size_formatted }}
                    </small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ticket Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid rounded mb-3" alt="Ticket Image">
                <div id="imageDetails">
                    <p id="imageDescription" class="text-muted mb-2"></p>
                    <small class="text-muted">
                        Uploaded by: <span id="imageUploader"></span> on <span id="imageDate"></span>
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="downloadImage" href="" class="btn btn-primary" download>Download</a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteImageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Delete Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this image? This action cannot be undone.</p>
                <div class="text-center">
                    <img id="deleteImagePreview" src="" class="img-fluid rounded" style="max-height: 200px;" alt="Image to delete">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash"></i> Delete Image
                </button>
            </div>
        </div>
    </div>
</div>
@else
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Ticket Images</h5>
    </div>
    <div class="card-body text-center py-5">
        <i class="bi bi-camera display-1 text-muted"></i>
        <p class="text-muted mt-3">No images have been uploaded for this ticket yet.</p>
    </div>
</div>
@endif

<!-- Include Image Upload Component -->
@include('tickets.partials.image-upload')

<div class="card-footer d-flex justify-content-between mt-4">
    <a href="{{ route('tickets') }}" class="btn btn-secondary">Back to List</a>
    <div>

       
        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-primary">Edit Ticket</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle image modal
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function(event) {
            const trigger = event.relatedTarget;
            const imageUrl = trigger.getAttribute('data-image-url');
            const description = trigger.getAttribute('data-description');
            const uploadedBy = trigger.getAttribute('data-uploaded-by');
            const uploadedAt = trigger.getAttribute('data-uploaded-at');
            
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageDescription').textContent = description || 'No description available';
            document.getElementById('imageUploader').textContent = uploadedBy;
            document.getElementById('imageDate').textContent = uploadedAt;
            document.getElementById('downloadImage').href = imageUrl;
        });
    }

    // Handle image deletion
    let imageToDelete = null;
    const deleteImageModal = new bootstrap.Modal(document.getElementById('deleteImageModal'));
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Handle delete button clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-image-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.delete-image-btn');
            const imageId = button.getAttribute('data-image-id');
            const imageCard = button.closest('.image-card');
            const imageElement = imageCard.querySelector('img');
            
            // Store reference for deletion
            imageToDelete = {
                id: imageId,
                card: imageCard.closest('.col-md-3')
            };
            
            // Show preview in delete modal
            document.getElementById('deleteImagePreview').src = imageElement.src;
            
            // Show confirmation modal
            deleteImageModal.show();
        }
    });

    // Handle delete confirmation
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!imageToDelete) return;

        const button = this;
        const originalText = button.innerHTML;
        
        // Show loading state
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Deleting...';

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     document.querySelector('input[name="_token"]')?.value;

        // Make delete request
        fetch(`/tickets/images/${imageToDelete.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the image card from DOM
                imageToDelete.card.remove();
                
                // Update image count in header
                updateImageCount();
                
                // Close modal
                deleteImageModal.hide();
                
                // Show success message
                showAlert('Image deleted successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to delete image');
            }
        })
        .catch(error => {
            console.error('Delete failed:', error);
            showAlert('Failed to delete image: ' + error.message, 'danger');
        })
        .finally(() => {
            // Reset button state
            button.disabled = false;
            button.innerHTML = originalText;
            imageToDelete = null;
        });
    });

    // Function to update image count in header
    function updateImageCount() {
        const remainingImages = document.querySelectorAll('.ticket-image').length;
        const headerElement = document.querySelector('.card-header h5');
        if (headerElement) {
            headerElement.textContent = `Ticket Images (${remainingImages})`;
        }
        
        // If no images left, show empty state
        if (remainingImages === 0) {
            const imagesSection = document.querySelector('.row.g-3');
            if (imagesSection) {
                imagesSection.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-camera display-1 text-muted"></i>
                        <p class="text-muted mt-3">No images have been uploaded for this ticket yet.</p>
                    </div>
                `;
            }
        }
    }

    // Function to show alert messages
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at top of page
        const container = document.querySelector('.card');
        container.parentNode.insertBefore(alertDiv, container);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
});
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}

.image-card {
    overflow: hidden;
    border-radius: 0.375rem;
}

.image-card:hover img {
    transform: scale(1.05);
    transition: transform 0.2s ease-in-out;
}

.image-overlay {
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.image-card:hover .image-overlay {
    opacity: 1;
}

.service-details {
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
}

.badge {
    font-size: 0.75em;
}

dl dt {
    font-weight: 600;
    color: #495057;
}

dl dd {
    color: #6c757d;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.card-header h6 {
    font-size: 0.9rem;
    font-weight: 600;
}

.bg-light pre {
    font-size: 0.875rem;
    white-space: pre-wrap;
    word-wrap: break-word;
}

/* Enhanced styling for better readability */
.service-details .card {
    transition: all 0.2s ease-in-out;
}

.service-details .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

.border-top {
    border-top: 1px solid #dee2e6 !important;
}
</style>
@endsection
