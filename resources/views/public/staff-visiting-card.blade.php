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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
            padding: 20px 0;
        }

        .visiting-card {
            max-width: 420px;
            margin: 20px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }

        .card-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            height: 120px;
            position: relative;
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 6px solid white;
            position: absolute;
            top: 60px;
            left: 50%;
            transform: translateX(-50%);
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #6c757d;
            overflow: hidden;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-body {
            padding: 80px 30px 30px;
            text-align: center;
        }

        .staff-name {
            font-size: 26px;
            font-weight: bold;
            color: #1e3c72;
            margin-bottom: 5px;
        }

        .staff-designation {
            color: #2a5298;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
            text-transform: capitalize;
        }

        .contact-info {
            margin-bottom: 25px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            color: #34495e;
            font-size: 14px;
        }

        .contact-item i {
            width: 20px;
            margin-right: 10px;
            color: #1e3c72;
        }

        .contact-item a {
            color: #34495e;
            text-decoration: none;
        }

        .contact-item a:hover {
            color: #1e3c72;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 25px;
        }

        .btn-contact {
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 60, 114, 0.4);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            border: none;
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
            color: white;
        }

        .company-info {
            background: #f8f9fa;
            padding: 20px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: left;
        }

        .company-info h6 {
            color: #1e3c72;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        .company-info p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 13px;
        }

        .services-section {
            margin: 20px 0;
        }

        .services-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 15px;
        }

        .service-btn {
            padding: 12px 10px;
            background: white;
            border: 2px solid #1e3c72;
            color: #1e3c72;
            border-radius: 8px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .service-btn:hover {
            background: #1e3c72;
            color: white;
        }

        .customers-section {
            margin: 25px 0;
            text-align: center;
        }

        .customers-section h6 {
            color: #1e3c72;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .customer-logos {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            align-items: center;
        }

        .customer-logo {
            width: 60px;
            height: 40px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #6c757d;
            text-align: center;
            margin: 0 auto;
        }

        .company-footer {
            background: #1e3c72;
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
                margin: 10px;
                max-width: none;
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
                <p><i class="fas fa-map-marker-alt"></i> <strong>Address:</strong></p>
                <p>123 Industrial Area, Phase-II<br>
                   Chandigarh - 160002, India</p>
                <p><i class="fas fa-phone"></i> <strong>Office:</strong> +91-172-1234567</p>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> info@sbccindia.com</p>
                <p><i class="fas fa-clock"></i> <strong>Hours:</strong> Mon-Sat 9:00 AM - 6:00 PM</p>
            </div>

            <!-- Services Section -->
            <div class="services-section">
                <h6 style="color: #1e3c72; font-weight: bold; margin-bottom: 15px;">
                    <i class="fas fa-cogs"></i> Our Services
                </h6>
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
        
        <div class="company-footer">
            <strong>SBC Cooling Systems</strong><br>
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
            link.download = '{{ str_replace(' ', '_', $staff->name) }}_SBC_Contact.vcf';
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
                background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
                color: white;
                padding: 15px 20px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0,0,0,0.2);
                z-index: 9999;
                font-size: 14px;
                max-width: 300px;
            `;
            toast.textContent = message;
            
            document.body.appendChild(toast);
            
            // Remove toast after 3 seconds
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 3000);
        }

        // Add click analytics
        document.querySelectorAll('a[href^="http"]').forEach(link => {
            link.addEventListener('click', function() {
                console.log('Link clicked:', this.href);
                // You can add analytics tracking here
            });
        });
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
