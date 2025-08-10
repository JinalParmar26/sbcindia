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
                                <button onclick="downloadQRCode()" class="btn btn-sm btn-success">
                                    <svg class="icon icon-xs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download QR
                                </button>
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
<!-- Fallback QR Code Library -->
<script>
if (typeof QRCode === 'undefined') {
    console.log('Loading fallback QR Code library...');
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/qrcode@1.5.3/build/qrcode.min.js';
    script.onload = function() {
        console.log('Fallback QR Code library loaded');
        generateQRCode();
    };
    document.head.appendChild(script);
} else {
    generateQRCode();
}

function generateQRCode() {
    const publicUrl = "{{ route('order.public-details', $order->uuid) }}";
    const canvas = document.getElementById('qrcode');
    
    if (!canvas) {
        console.error('QR Code canvas element not found');
        return;
    }
    
    console.log('Generating QR code for URL:', publicUrl);
    
    // Check if QRCode library is loaded
    if (typeof QRCode === 'undefined') {
        console.error('QRCode library not loaded');
        // Create a fallback image-based QR code
        const img = document.createElement('img');
        img.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(publicUrl)}`;
        img.width = 150;
        img.height = 150;
        img.className = 'd-inline-block p-3 bg-white border rounded';
        canvas.parentNode.replaceChild(img, canvas);
        return;
    }
    
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
            // Fallback: use online QR generator
            const img = document.createElement('img');
            img.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(publicUrl)}`;
            img.width = 150;
            img.height = 150;
            img.className = 'd-inline-block p-3 bg-white border rounded';
            img.alt = 'QR Code';
            canvas.parentNode.replaceChild(img, canvas);
        } else {
            console.log('QR Code generated successfully for:', publicUrl);
        }
    });
}
</script>

<script>
// Initialize QR Code generation when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Check if QR library is already loaded
    if (typeof QRCode !== 'undefined') {
        generateQRCode();
    }
    // Otherwise, the fallback script above will handle it
});

function downloadQRCode() {
    const canvas = document.getElementById('qrcode');
    const img = document.querySelector('#qrcode, img[alt="QR Code"]');
    
    if (canvas && canvas.tagName === 'CANVAS') {
        // Download from canvas
        const link = document.createElement('a');
        link.download = 'order-{{ $order->uuid }}-qr-code.png';
        link.href = canvas.toDataURL();
        link.click();
    } else if (img && img.tagName === 'IMG') {
        // Download from image
        const link = document.createElement('a');
        link.download = 'order-{{ $order->uuid }}-qr-code.png';
        link.href = img.src;
        link.target = '_blank';
        link.click();
    } else {
        alert('QR Code not found. Please wait for it to load or refresh the page.');
    }
}

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
