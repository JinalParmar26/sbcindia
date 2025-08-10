@extends('layouts.main')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Order: {{ $order->title }}</h4>
    </div>
    <div class="card-body">
        <p><strong>Customer:</strong> {{ $order->customer->name }} ({{ $order->customer->company_name }})</p>

        <hr>
        <h5>Product Details</h5>
        @foreach ($order->orderProducts as $item)
        <div class="border p-3 mb-3">
            <p><strong>Product:</strong> {{ $item->product->name }}</p>
            <p><strong>Model Number:</strong> {{ $item->model_number ?? '-' }}</p>
            <p><strong>Serial Number:</strong> {{ $item->serial_number ?? '-' }}</p>
            
            @if($item->tickets->count() > 0)
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Related Tickets ({{ $item->tickets->count() }})</h6>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#tickets-{{ $item->id }}" aria-expanded="false">
                        <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        Show/Hide
                    </button>
                </div>
                <div class="collapse" id="tickets-{{ $item->id }}">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item->tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($ticket->type) }}</span>
                                    </td>
                                    <td>
                                        @if($ticket->end)
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($ticket->start)
                                            <span class="badge bg-warning">In Progress</span>
                                        @else
                                            <span class="badge bg-info">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $ticket->assignedTo ? $ticket->assignedTo->name : '-' }}</td>
                                    <td>{{ $ticket->start ? \Carbon\Carbon::parse($ticket->start)->format('M d, Y') : '-' }}</td>
                                    <td>{{ $ticket->end ? \Carbon\Carbon::parse($ticket->end)->format('M d, Y') : '-' }}</td>
                                    <td>
                                        <a href="{{ route('tickets.show', $ticket->uuid) }}" class="btn btn-sm btn-outline-primary">
                                            <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="alert alert-info mt-2">
                    <small>
                        <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        No tickets found for this product.
                    </small>
                </div>
            @endif
        </div>
        @endforeach

        @if($order->images->count() > 0)
        <hr>
        <h5>Order Images</h5>
        <div class="row">
            @foreach ($order->images as $image)
            <div class="col-md-3 mb-3">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="image-container" style="height: 200px; overflow: hidden;">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                 alt="Order Image" 
                                 class="img-fluid w-100 h-100" 
                                 style="object-fit: cover; cursor: pointer;"
                                 onclick="openImageModal('{{ asset('storage/' . $image->image_path) }}')">
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">{{ $image->image_name }}</small>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <small class="text-muted">{{ number_format($image->image_size / 1024, 1) }} KB</small>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteImage({{ $image->id }})">
                                    <svg class="icon icon-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Edit Order</a>
            <a href="{{ route('orders.single.pdf', $order->id) }}" class="btn btn-danger" target="_blank">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Download PDF
            </a>
            <a href="{{ route('orders') }}" class="btn btn-secondary">Back</a>
        </div>

        <!-- QR Code Section -->
        <div class="mt-4">
            <div class="card bg-light">
                <div class="card-header">
                    <h6 class="mb-0">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Public Order Details QR Code
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="mb-2"><strong>Share this order with customers:</strong></p>
                            <p class="text-muted small mb-3">
                                Scan this QR code to view order details, customer information, products, and support tickets in a public format.
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('order.public-details', $order->uuid) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Open Public View
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <canvas id="qrcode" class="d-inline-block p-3 bg-white border rounded" width="150" height="150"></canvas>
                            <div class="mt-2">
                                <small class="text-muted">Scan with mobile device</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="mt-4">
            <div class="card bg-light">
                <div class="card-header">
                    <h6 class="mb-0">
                        <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Our Services & Company Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Services -->
                        <div class="col-md-6 mb-3">
                            <h6><strong>Our Services</strong></h6>
                            <div class="d-flex flex-column gap-2">
                                <a href="https://sbccindia.com/products.php" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Products
                                </a>
                                <a href="https://sbccindia.com/contact.php" target="_blank" class="btn btn-sm btn-outline-info">
                                    <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    Contact
                                </a>
                            </div>
                        </div>
                        
                        <!-- Company Brochure -->
                        <div class="col-md-6 mb-3">
                            <h6><strong>Company Brochure</strong></h6>
                            <div class="d-flex flex-column gap-2">
                                <a href="https://sbccindia.com/assets/brochure/sbc-company-brochure.pdf" target="_blank" class="btn btn-sm btn-outline-danger">
                                    <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Brochure
                                </a>
                                <a href="https://sbccindia.com/" target="_blank" class="btn btn-sm btn-outline-success">
                                    <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                                    </svg>
                                    Visit Website
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SBC Footer Information -->
        <div class="mt-4">
            <div class="card" style="background: #1e3c72; color: white;">
                <div class="card-body text-center">
                    <h5 class="mb-3"><strong>SBC Cooling Systems</strong></h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Address:</strong></p>
                            <p class="mb-3">123 Industrial Area, Phase-II<br>Chandigarh - 160002, India</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Contact Information:</strong></p>
                            <p class="mb-1"><strong>Office:</strong> +91-172-1234567</p>
                            <p class="mb-1"><strong>Email:</strong> info@sbccindia.com</p>
                            <p class="mb-0"><strong>Hours:</strong> Mon-Sat 9:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Order Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Order Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- QR Code Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>

<script>
// Generate QR Code
document.addEventListener('DOMContentLoaded', function() {
    const publicUrl = "{{ route('order.public-details', $order->uuid) }}";
    const canvas = document.getElementById('qrcode');
    
    QRCode.toCanvas(canvas, publicUrl, {
        width: 150,
        height: 150,
        margin: 2,
        color: {
            dark: '#1e3c72',
            light: '#ffffff'
        }
    }, function (error) {
        if (error) {
            console.error('QR Code generation error:', error);
        } else {
            console.log('QR Code generated successfully');
        }
    });
});

function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    imageModal.show();
}

function deleteImage(imageId) {
    if (confirm('Are you sure you want to delete this image?')) {
        fetch(`/orders/images/${imageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting image: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting image');
        });
    }
}
</script>
@endsection
