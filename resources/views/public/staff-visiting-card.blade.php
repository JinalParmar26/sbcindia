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
            padding: 20px 30px 10px;
            background: white;
        }

        .company-logo {
            max-width: 150px;
            height: auto;
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
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            align-items: center;
            margin-bottom: 15px;
        }

        .customer-logos-more {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            align-items: center;
            margin-top: 10px;
        }

        .customer-logo-img {
            width: 100%;
            height: 50px;
            object-fit: contain;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 5px;
            transition: transform 0.2s ease;
        }

        .customer-logo-img:hover {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .show-more-customers {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 10px 0;
        }

        .show-more-customers:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
        }

        .brochure-section {
            margin-bottom: 25px;
            text-align: center;
        }

        .brochure-section h6 {
            color: #1e3c72;
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .brochure-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-brochure {
            padding: 12px 8px;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
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

        .btn-brochure:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
            color: white;
        }

        .btn-website {
            padding: 12px 8px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
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

        .btn-website:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
            color: white;
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

            .brochure-actions {
                grid-template-columns: 1fr;
            }

            .customer-logos,
            .customer-logos-more {
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
        <!-- Main Visiting Card -->
        <div class="visiting-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <img src="https://sbccindia.com/assets/img/logo/sbc-logo.webp" alt="SBC Cooling Systems" class="company-logo">
            </div>
            
            <div class="card-header">
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
                        <a href="https://sbccindia.com/products.php" target="_blank" class="service-btn">
                            <i class="fas fa-box"></i> Products
                        </a>
                        <a href="https://sbccindia.com/contact.php" target="_blank" class="service-btn">
                            <i class="fas fa-phone"></i> Contact
                        </a>
                    </div>
                </div>

                <!-- Brochure Section -->
                <div class="brochure-section">
                    <h6><i class="fas fa-file-pdf"></i> Company Brochure</h6>
                    <div class="brochure-actions">
                        <a href="https://sbccindia.com/download/brochure" target="_blank" class="btn-brochure">
                            <i class="fas fa-download"></i> Download Brochure
                        </a>
                        <a href="https://sbccindia.com/" target="_blank" class="btn-website">
                            <i class="fas fa-globe"></i> Visit Website
                        </a>
                    </div>
                </div>

                <!-- Customers Section -->
                <div class="customers-section">
                    <h6><i class="fas fa-handshake"></i> Our Valuable Customers</h6>
                    <div class="customer-logos">
                        <img src="https://sbccindia.com/assets/img/client/Client-1.webp" alt="Client 1" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-2.webp" alt="Client 2" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-3.webp" alt="Client 3" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-4.webp" alt="Client 4" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-5.webp" alt="Client 5" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-6.webp" alt="Client 6" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-7.webp" alt="Client 7" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-8.webp" alt="Client 8" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-9.webp" alt="Client 9" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-10.webp" alt="Client 10" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-11.webp" alt="Client 11" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-12.webp" alt="Client 12" class="customer-logo-img">
                    </div>
                    
                    <!-- Show More Customers Button -->
                    <button class="show-more-customers" onclick="toggleMoreCustomers()">
                        <i class="fas fa-plus"></i> Show More Customers
                    </button>
                    
                    <!-- Additional Customer Logos (Initially Hidden) -->
                    <div class="customer-logos-more" id="moreCustomers" style="display: none;">
                        <img src="https://sbccindia.com/assets/img/client/Client-13.webp" alt="Client 13" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-14.webp" alt="Client 14" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-15.webp" alt="Client 15" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-16.webp" alt="Client 16" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-17.webp" alt="Client 17" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-18.webp" alt="Client 18" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-19.webp" alt="Client 19" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-20.webp" alt="Client 20" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-21.webp" alt="Client 21" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-22.webp" alt="Client 22" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-23.webp" alt="Client 23" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-24.webp" alt="Client 24" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-25.webp" alt="Client 25" class="customer-logo-img">
                        <img src="https://sbccindia.com/assets/img/client/Client-26.webp" alt="Client 26" class="customer-logo-img">
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
    const vCardData = `BEGIN:VCARD
VERSION:3.0
FN:staff222
ORG:SBC Cooling Systems
TITLE:staff
TEL:
EMAIL:staff2@test.com
URL:https://sbccindia.com/
ADR:;;123 Industrial Area, Phase-II;Chandigarh;;160002;India
NOTE:Industrial Cooling Solutions Excellence
END:VCARD`;

    // Create a blob and a URL for it
    const blob = new Blob([vCardData], { type: 'text/vcard' });
    const url = URL.createObjectURL(blob);

    // On phones, opening the URL instead of "downloading" will
    // trigger the native contacts app
    window.location.href = url;

    // Revoke URL later
    setTimeout(() => URL.revokeObjectURL(url), 1000);
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

        // Toggle more customers function
        function toggleMoreCustomers() {
            const moreCustomers = document.getElementById('moreCustomers');
            const toggleBtn = document.querySelector('.show-more-customers');
            
            if (moreCustomers.style.display === 'none' || moreCustomers.style.display === '') {
                moreCustomers.style.display = 'grid';
                toggleBtn.innerHTML = '<i class="fas fa-minus"></i> Show Less Customers';
            } else {
                moreCustomers.style.display = 'none';
                toggleBtn.innerHTML = '<i class="fas fa-plus"></i> Show More Customers';
            }
        }
    </script>
</body>
</html>
