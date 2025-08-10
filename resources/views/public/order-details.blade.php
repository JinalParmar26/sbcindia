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
                <div style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 15px; border-left: 4px solid #1e3c72;">
                    <div style="font-weight: 600; font-size: 16px; color: #1e3c72; margin-bottom: 10px;">{{ $ticket->subject }}</div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 14px;">
                        <div><strong>Type:</strong> <span style="background: #e9ecef; padding: 2px 8px; border-radius: 4px;">{{ ucfirst($ticket->type) }}</span></div>
                        <div><strong>Status:</strong> 
                            @if($ticket->end)
                                <span style="background: #d4edda; color: #155724; padding: 2px 8px; border-radius: 4px;">Closed</span>
                            @elseif($ticket->start)
                                <span style="background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 4px;">In Progress</span>
                            @else
                                <span style="background: #d1ecf1; color: #0c5460; padding: 2px 8px; border-radius: 4px;">Pending</span>
                            @endif
                        </div>
                        @if($ticket->assignedTo)
                        <div><strong>Assigned to:</strong> {{ $ticket->assignedTo->name }}</div>
                        @endif
                        @if($ticket->start)
                        <div><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($ticket->start)->format('M d, Y') }}</div>
                        @endif
                        @if($ticket->end)
                        <div><strong>End Date:</strong> {{ \Carbon\Carbon::parse($ticket->end)->format('M d, Y') }}</div>
                        @endif
                        <div><strong>Ticket ID:</strong> {{ $ticket->uuid }}</div>
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
                    <button onclick="window.open('https://sbccindia.com/products.php', '_blank')" style="display: flex; align-items: center; padding: 15px; background: #1e3c72; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.3s;">
                        <i class="fas fa-cube" style="margin-right: 10px;"></i>
                        Products
                    </button>
                    <button onclick="window.open('https://sbccindia.com/contact.php', '_blank')" style="display: flex; align-items: center; padding: 15px; background: #17a2b8; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.3s;">
                        <i class="fas fa-phone" style="margin-right: 10px;"></i>
                        Contact
                    </button>
                </div>

                <!-- Company Brochure Section -->
                <div class="section-title">
                    <i class="fas fa-file-pdf"></i> Company Brochure
                </div>
                <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px;">
                    <button onclick="window.open('https://sbccindia.com/assets/brochure/sbc-company-brochure.pdf', '_blank')" style="display: flex; align-items: center; padding: 15px; background: #dc3545; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.3s;">
                        <i class="fas fa-download" style="margin-right: 10px;"></i>
                        Download Brochure
                    </button>
                    <button onclick="window.open('https://sbccindia.com/', '_blank')" style="display: flex; align-items: center; padding: 15px; background: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.3s;">
                        <i class="fas fa-globe" style="margin-right: 10px;"></i>
                        Visit Website
                    </button>
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
                <p style="margin-bottom: 15px; color: #87ceeb; font-style: italic;">Industrial Cooling Solutions Excellence</p>
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
                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #87ceeb;">
                    <small style="color: #87ceeb;">Order Reference: {{ $order->uuid }}</small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
