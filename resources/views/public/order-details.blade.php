<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->title }} - SBC Cooling Systems</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fa;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            background: white;
            color: #1e3c72;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #1e3c72;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-logo {
            max-width: 150px;
            height: auto;
        }

        .order-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #1e3c72;
        }

        .order-subtitle {
            font-size: 16px;
            opacity: 0.8;
            color: #1e3c72;
        }

        .content-section {
            padding: 30px;
        }

        .section-title {
            color: #1e3c72;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 18px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 8px;
        }

        .info-item {
            display: flex;
            margin-bottom: 12px;
            padding: 8px 0;
        }

        .info-label {
            font-weight: 600;
            color: #555;
            width: 120px;
            flex-shrink: 0;
        }

        .info-value {
            color: #333;
            flex: 1;
        }

        .product-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #1e3c72;
        }

        .product-name {
            font-weight: 600;
            color: #1e3c72;
            margin-bottom: 8px;
        }

        .ticket-item {
            background: #fff3cd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #ffc107;
        }

        .ticket-subject {
            font-weight: 600;
            color: #856404;
            margin-bottom: 5px;
        }

        .ticket-meta {
            font-size: 14px;
            color: #856404;
        }

        .footer-section {
            background: #1e3c72;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 13px;
            border-radius: 15px;
        }

        .footer-section strong {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px;
                max-width: none;
            }

            .content-section {
                padding: 20px;
            }

            .info-item {
                flex-direction: column;
                gap: 4px;
            }

            .info-label {
                width: auto;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="order-card">
            <!-- Header Section -->
            <div class="card-header">
                <div class="logo-section">
                    <img src="https://sbccindia.com/assets/img/logo/sbc-logo.webp" alt="SBC Cooling Systems" class="company-logo">
                </div>
                <div class="order-title">Order #{{ $order->title }}</div>
                <div class="order-subtitle">SBC Cooling Systems - Order Details</div>
            </div>

            <div class="content-section">
                <!-- Products -->
                @if($order->orderProducts->count() > 0)
                <div class="section-title">
                    <i class="fas fa-box"></i> Products ({{ $order->orderProducts->count() }})
                </div>
                @foreach($order->orderProducts as $orderProduct)
                <div class="product-item">
                    <div class="product-name">{{ $orderProduct->product->name }}</div>
                    <div class="info-item">
                        <div class="info-label">Quantity:</div>
                        <div class="info-value">{{ $orderProduct->quantity ?? 'Not specified' }}</div>
                    </div>
                    @if($orderProduct->product->description)
                    <div class="info-item">
                        <div class="info-label">Description:</div>
                        <div class="info-value">{{ $orderProduct->product->description }}</div>
                    </div>
                    @endif
                </div>
                @endforeach
                @endif

                <!-- Customer Details -->
                <div class="section-title" style="margin-top: 30px;">
                    <i class="fas fa-user"></i> Customer Details
                </div>
                <div class="info-item">
                    <div class="info-label">Name:</div>
                    <div class="info-value">{{ $order->customer->name }}</div>
                </div>
                @if($order->customer->address)
                <div class="info-item">
                    <div class="info-label">Address:</div>
                    <div class="info-value">{{ $order->customer->address }}</div>
                </div>
                @endif

                <!-- Order Information -->
                <div class="section-title" style="margin-top: 30px;">
                    <i class="fas fa-shopping-cart"></i> Order Information
                </div>
                <div class="info-item">
                    <div class="info-label">Order Date:</div>
                    <div class="info-value">{{ $order->created_at->format('M d, Y') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Order ID:</div>
                    <div class="info-value">{{ $order->uuid }}</div>
                </div>

                <!-- Tickets (Only for authenticated users) -->
                @auth
                @if($order->tickets->count() > 0)
                <div class="section-title" style="margin-top: 30px;">
                    <i class="fas fa-ticket-alt"></i> Support Tickets ({{ $order->tickets->count() }})
                </div>
                @foreach($order->tickets as $ticket)
                <div class="ticket-item">
                    <div class="ticket-subject">{{ $ticket->subject }}</div>
                    <div class="ticket-meta">
                        Type: {{ ucfirst($ticket->type) }} | 
                        Status: {{ $ticket->end ? 'Closed' : 'Open' }}
                        @if($ticket->assignedTo)
                            | Assigned to: {{ $ticket->assignedTo->name }}
                        @endif
                    </div>
                </div>
                @endforeach
                @endif
                @endauth

                <!-- Our Services Section -->
                <div class="section-title" style="margin-top: 30px;">
                    <i class="fas fa-cogs"></i> Our Services
                </div>
                <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                    <a href="https://sbccindia.com/products.php" target="_blank" style="display: flex; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 8px; text-decoration: none; color: #1e3c72; border-left: 4px solid #1e3c72;">
                        <i class="fas fa-cube" style="margin-right: 10px; color: #1e3c72;"></i>
                        <strong>Products</strong>
                    </a>
                    <a href="https://sbccindia.com/contact.php" target="_blank" style="display: flex; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 8px; text-decoration: none; color: #1e3c72; border-left: 4px solid #17a2b8;">
                        <i class="fas fa-phone" style="margin-right: 10px; color: #17a2b8;"></i>
                        <strong>Contact</strong>
                    </a>
                </div>

                <!-- Company Brochure Section -->
                <div class="section-title">
                    <i class="fas fa-file-pdf"></i> Company Brochure
                </div>
                <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                    <a href="https://sbccindia.com/assets/brochure/sbc-company-brochure.pdf" target="_blank" style="display: flex; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 8px; text-decoration: none; color: #dc3545; border-left: 4px solid #dc3545;">
                        <i class="fas fa-download" style="margin-right: 10px; color: #dc3545;"></i>
                        <strong>Download Brochure</strong>
                    </a>
                    <a href="https://sbccindia.com/" target="_blank" style="display: flex; align-items: center; padding: 12px; background: #f8f9fa; border-radius: 8px; text-decoration: none; color: #28a745; border-left: 4px solid #28a745;">
                        <i class="fas fa-globe" style="margin-right: 10px; color: #28a745;"></i>
                        <strong>Visit Website</strong>
                    </a>
                </div>

                <!-- SBC Cooling Systems Information -->
                <div class="section-title" style="margin-top: 30px;">
                    <i class="fas fa-building"></i> SBC Cooling Systems
                </div>
            </div>
        </div>

        <!-- SBC Footer Information -->
        <div class="order-card">
            <div style="background: #1e3c72; color: white; padding: 30px; text-align: center;">
                <h5 style="margin-bottom: 20px; color: white;"><strong>SBC Cooling Systems</strong></h5>
                <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">
                    <div style="flex: 1; min-width: 250px;">
                        <p style="margin-bottom: 8px; color: white;"><strong>Address:</strong></p>
                        <p style="margin-bottom: 20px; color: white;">123 Industrial Area, Phase-II<br>Chandigarh - 160002, India</p>
                    </div>
                    <div style="flex: 1; min-width: 250px;">
                        <p style="margin-bottom: 8px; color: white;"><strong>Contact Information:</strong></p>
                        <p style="margin-bottom: 4px; color: white;"><strong>Office:</strong> <a href="tel:+91-172-1234567" style="color: #87ceeb;">+91-172-1234567</a></p>
                        <p style="margin-bottom: 4px; color: white;"><strong>Email:</strong> <a href="mailto:info@sbccindia.com" style="color: #87ceeb;">info@sbccindia.com</a></p>
                        <p style="margin-bottom: 0; color: white;"><strong>Hours:</strong> Mon-Sat 9:00 AM - 6:00 PM</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer-section">
            <strong>SBC Cooling Systems</strong>
            Industrial Cooling Solutions Excellence<br>
            <small>Order Reference: {{ $order->uuid }}</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
