<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $staff->name }} - Digital Visiting Card</title>
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
            max-width: 400px;
            margin: 0 auto;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 10px;
        }

        .visiting-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            height: 80px;
            position: relative;
        }

        .profile-section {
            padding: 30px;
            text-align: center;
            position: relative;
            margin-top: -40px;
        }

        .profile-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid white;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #6c757d;
            overflow: hidden;
            margin: 0 auto 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .staff-name {
            font-size: 24px;
            font-weight: 700;
            color: #1e3c72;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .staff-designation {
            color: #666;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 25px;
            text-transform: capitalize;
        }

        .contact-info {
            margin-bottom: 25px;
            text-align: left;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            color: #333;
            font-size: 14px;
            padding: 8px 0;
        }

        .contact-item i {
            width: 24px;
            margin-right: 12px;
            color: #1e3c72;
            font-size: 16px;
        }

        .contact-item a {
            color: #333;
            text-decoration: none;
            flex: 1;
        }

        .contact-item a:hover {
            color: #1e3c72;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 25px;
        }

        .btn-contact {
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.3);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
            color: white;
        }

        .company-info {
            background: #f8f9fa;
            padding: 20px;
            margin: 0 -30px 25px;
            border-top: 1px solid #e9ecef;
            border-bottom: 1px solid #e9ecef;
        }

        .company-info h6 {
            color: #1e3c72;
            margin-bottom: 15px;
            font-weight: 700;
            text-align: center;
            font-size: 16px;
        }

        .company-info .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 8px;
            font-size: 13px;
            color: #555;
        }

        .company-info .info-item i {
            width: 16px;
            margin-right: 8px;
            color: #1e3c72;
            margin-top: 2px;
        }

        .services-section {
            margin-bottom: 25px;
        }

        .services-section h6 {
            color: #1e3c72;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
            font-size: 16px;
        }

        .services-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .service-btn {
            padding: 12px 8px;
            background: white;
            border: 2px solid #1e3c72;
            color: #1e3c72;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .service-btn:hover {
            background: #1e3c72;
            color: white;
            transform: translateY(-1px);
        }

        .customers-section {
            margin-bottom: 20px;
            text-align: center;
        }

        .customers-section h6 {
            color: #1e3c72;
            margin-bottom: 15px;
            font-weight: 700;
            font-size: 16px;
        }

        .customer-logos {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            align-items: center;
        }

        .customer-logo {
            width: 100%;
            height: 35px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
            text-align: center;
            font-weight: 600;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .footer-section {
            background: #1e3c72;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 13px;
            border-radius: 15px;
            margin-top: 10px;
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

            .visiting-card {
                margin-bottom: 15px;
            }
            
            .action-buttons {
                grid-template-columns: 1fr;
            }

            .services-grid {
                grid-template-columns: 1fr;
            }

            .customer-logos {
                grid-template-columns: repeat(2, 1fr);
            }

            .profile-section {
                padding: 20px;
            }

            .company-info {
                margin: 0 -20px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo Section -->
        <div class="logo-section">
            <img src="https://sbccindia.com/assets/img/logo/sbc-logo.webp" alt="SBC Cooling Systems" class="company-logo">
        </div>

        <!-- Main Visiting Card -->
        <div class="visiting-card">
            <div class="card-header">
                <div class="status-badge">
                    <i class="fas fa-circle"></i> Active
                </div>
            </div>
            
            <div class="profile-section">
                <div class="profile-photo">
                    @if($staff->profile_photo)
                        <img src="{{ asset('storage/' . $staff->profile_photo) }}" alt="{{ $staff->name }}">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                
                <div class="staff-name">{{ $staff->name }}</div>
                <div class="staff-designation">{{ $staff->role ?? 'Staff Member' }}</div>
                
                <div class="contact-info">
                    @if($staff->phone_number)
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <a href="tel:{{ $staff->phone_number }}">{{ $staff->phone_number }}</a>
                    </div>
                    @endif
                    
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:{{ $staff->email }}">{{ $staff->email }}</a>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button onclick="saveContact()" class="btn-contact btn-success">
                        <i class="fas fa-download"></i> Save Contact
                    </button>
                    <a href="https://sbccindia.com/" target="_blank" class="btn-contact btn-primary">
                        <i class="fas fa-globe"></i> Visit Website
                    </a>
                </div>

                <!-- Company Information -->
                <div class="company-info">
                    <h6><i class="fas fa-building"></i> SBC Cooling Systems</h6>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <strong>Address:</strong><br>
                            123 Industrial Area, Phase-II<br>
                            Chandigarh - 160002, India
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <div><strong>Office:</strong> +91-172-1234567</div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div><strong>Email:</strong> info@sbccindia.com</div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div><strong>Hours:</strong> Mon-Sat 9:00 AM - 6:00 PM</div>
                    </div>
                </div>

                <!-- Services Section -->
                <div class="services-section">
                    <h6><i class="fas fa-cogs"></i> Our Services</h6>
                    <div class="services-grid">
                        <a href="https://sbccindia.com/download/brochure" target="_blank" class="service-btn">
                            <i class="fas fa-download"></i> Brochure
                        </a>
                        <a href="https://sbccindia.com/products.php" target="_blank" class="service-btn">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </div>
                </div>

                <!-- Customers Section -->
                <div class="customers-section">
                    <h6><i class="fas fa-handshake"></i> Our Valuable Customers</h6>
                    <div class="customer-logos">
                        <div class="customer-logo">Reliance</div>
                        <div class="customer-logo">TATA</div>
                        <div class="customer-logo">Bajaj</div>
                        <div class="customer-logo">Mahindra</div>
                        <div class="customer-logo">L&T</div>
                        <div class="customer-logo">Godrej</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer-section">
            <strong>SBC Cooling Systems</strong>
            Industrial Cooling Solutions Excellence
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function saveContact() {
            // Create vCard data
            const vCardData = `BEGIN:VCARD
VERSION:3.0
FN:{{ $staff->name }}
ORG:SBC Cooling Systems
TITLE:{{ $staff->role ?? 'Staff Member' }}
TEL:{{ $staff->phone_number ?? '' }}
EMAIL:{{ $staff->email }}
URL:https://sbccindia.com/
ADR:;;123 Industrial Area, Phase-II;Chandigarh;;160002;India
NOTE:Industrial Cooling Solutions Excellence
END:VCARD`;

            // Create blob and download
            const blob = new Blob([vCardData], { type: 'text/vcard' });
            const url = window.URL.createObjectURL(blob);
            
            // Create download link
            const link = document.createElement('a');
            link.href = url;
            link.download = '{{ str_replace(" ", "_", $staff->name) }}_SBC_Contact.vcf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
            
            // Show success message
            showToast('Contact saved successfully!', 'success');
        }

        function showToast(message, type) {
            // Create toast element
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#28a745' : '#dc3545'};
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 9999;
                font-size: 14px;
                font-weight: 500;
                max-width: 300px;
            `;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                if (document.body.contains(toast)) {
                    document.body.removeChild(toast);
                }
            }, 3000);
        }

        // Add click analytics
        document.querySelectorAll('a[href^="http"]').forEach(link => {
            link.addEventListener('click', function() {
                console.log('Link clicked:', this.href);
                // You can add analytics tracking here
            });
        });

        // Preload company logo
        const logo = new Image();
        logo.src = 'https://sbccindia.com/assets/img/logo/sbc-logo.webp';
    </script>
</body>
</html>
        }

        .working-hours {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .working-hours h6 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .working-hours p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn-contact {
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-outline {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .company-footer {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }

        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #27ae60;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
        }

        @media (max-width: 480px) {
            .visiting-card {
                margin: 20px;
                max-width: none;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="visiting-card">
        <div class="card-header">
            <div class="status-badge">
                <i class="fas fa-circle"></i> Active
            </div>
        </div>
        
        <div class="profile-photo">
            @if($staff->profile_photo)
                <img src="{{ asset('storage/' . $staff->profile_photo) }}" alt="{{ $staff->name }}">
            @else
                <i class="fas fa-user"></i>
            @endif
        </div>
        
        <div class="card-body">
            <div class="staff-name">{{ $staff->name }}</div>
            <div class="staff-role">{{ $staff->role ?? 'Staff Member' }}</div>
            
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:{{ $staff->email }}">{{ $staff->email }}</a>
                </div>
                
                @if($staff->phone_number)
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <a href="tel:{{ $staff->phone_number }}">{{ $staff->phone_number }}</a>
                </div>
                @endif
            </div>
            
            @if($staff->working_hours_start && $staff->working_hours_end)
            <div class="working-hours">
                <h6><i class="fas fa-clock"></i> Working Hours</h6>
                <p>{{ date('g:i A', strtotime($staff->working_hours_start)) }} - {{ date('g:i A', strtotime($staff->working_hours_end)) }}</p>
                @if($staff->working_days)
                <p>{{ $staff->working_days }}</p>
                @endif
            </div>
            @endif
            
            <div class="action-buttons">
                <a href="mailto:{{ $staff->email }}" class="btn-contact btn-primary">
                    <i class="fas fa-envelope"></i> Email
                </a>
                @if($staff->phone_number)
                <a href="tel:{{ $staff->phone_number }}" class="btn-contact btn-outline">
                    <i class="fas fa-phone"></i> Call
                </a>
                @endif
            </div>
        </div>
        
        <div class="company-footer">
            <strong>SBC Cooling Systems</strong><br>
            Industrial Cooling Solutions
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
