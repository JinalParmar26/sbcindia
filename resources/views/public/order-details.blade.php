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
                <!-- Customer Details -->
                <div class="section-title">
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

                <!-- Order Details -->
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

                <!-- Products -->
                @if($order->orderProducts->count() > 0)
                <div class="section-title" style="margin-top: 30px;">
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

                <!-- Tickets -->
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

                <!-- SBC Contact Information -->
                <div class="section-title" style="margin-top: 30px;">
                    <i class="fas fa-building"></i> SBC Cooling Systems Contact
                </div>
                <div class="info-item">
                    <div class="info-label">Address:</div>
                    <div class="info-value">123 Industrial Area, Phase-II<br>Chandigarh - 160002, India</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone:</div>
                    <div class="info-value">
                        <a href="tel:+91-172-1234567" style="color: #1e3c72;">+91-172-1234567</a>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email:</div>
                    <div class="info-value">
                        <a href="mailto:info@sbccindia.com" style="color: #1e3c72;">info@sbccindia.com</a>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Website:</div>
                    <div class="info-value">
                        <a href="https://sbccindia.com" target="_blank" style="color: #1e3c72;">www.sbccindia.com</a>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Hours:</div>
                    <div class="info-value">Mon-Sat 9:00 AM - 6:00 PM</div>
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
