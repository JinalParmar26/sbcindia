<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $staff->name }} - Staff Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            margin: 0 auto 20px;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            overflow: hidden;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .profile-role {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .profile-content {
            padding: 40px 0;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .info-card h5 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 16px;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #6c757d;
            font-size: 16px;
        }

        .contact-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .contact-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .status-online {
            color: #27ae60;
        }

        .status-offline {
            color: #e74c3c;
        }

        .qr-code {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 10px;
            margin-top: 20px;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(255,255,255,0.9);
            color: #667eea;
            border: none;
            padding: 10px 15px;
            border-radius: 25px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
        }

        .back-button:hover {
            background: white;
            color: #667eea;
        }
    </style>
</head>
<body>
    <a href="{{ url('/staff/card/' . $staff->uuid) }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Back to Card
    </a>

    <div class="profile-header">
        <div class="container">
            <div class="profile-photo">
                @if($staff->profile_photo)
                    <img src="{{ asset('storage/' . $staff->profile_photo) }}" alt="{{ $staff->name }}">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            <div class="profile-name">{{ $staff->name }}</div>
            <div class="profile-role">{{ $staff->role ?? 'Staff Member' }}</div>
            <div class="mb-3">
                <span class="status-online">
                    <i class="fas fa-circle"></i> Active
                </span>
            </div>
            <div>
                <a href="mailto:{{ $staff->email }}" class="contact-button me-2">
                    <i class="fas fa-envelope"></i> Send Email
                </a>
                @if($staff->phone_number)
                <a href="tel:{{ $staff->phone_number }}" class="contact-button">
                    <i class="fas fa-phone"></i> Call Now
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="profile-content">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="info-card">
                        <h5><i class="fas fa-user"></i> Contact Information</h5>
                        
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">
                                    <a href="mailto:{{ $staff->email }}">{{ $staff->email }}</a>
                                </div>
                            </div>
                        </div>

                        @if($staff->phone_number)
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">
                                    <a href="tel:{{ $staff->phone_number }}">{{ $staff->phone_number }}</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Company</div>
                                <div class="info-value">SBC Cooling Systems</div>
                            </div>
                        </div>

                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Role</div>
                                <div class="info-value">{{ $staff->role ?? 'Staff Member' }}</div>
                            </div>
                        </div>
                    </div>

                    @if($staff->working_hours_start && $staff->working_hours_end)
                    <div class="info-card">
                        <h5><i class="fas fa-clock"></i> Working Hours</h5>
                        
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Daily Hours</div>
                                <div class="info-value">
                                    {{ date('g:i A', strtotime($staff->working_hours_start)) }} - 
                                    {{ date('g:i A', strtotime($staff->working_hours_end)) }}
                                </div>
                            </div>
                        </div>

                        @if($staff->working_days)
                        <div class="info-row">
                            <div class="info-icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Working Days</div>
                                <div class="info-value">{{ $staff->working_days }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="info-card">
                        <h5><i class="fas fa-share-alt"></i> Share Profile</h5>
                        <p class="text-muted">Share this profile with others:</p>
                        
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="copyToClipboard('{{ url()->current() }}')">
                                <i class="fas fa-copy"></i> Copy Link
                            </button>
                            <a href="whatsapp://send?text={{ urlencode('Check out ' . $staff->name . ' profile: ' . url()->current()) }}" 
                               class="btn btn-success">
                                <i class="fab fa-whatsapp"></i> Share on WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="info-card">
                        <h5><i class="fas fa-id-card"></i> Quick Actions</h5>
                        <div class="d-grid gap-2">
                            <a href="{{ url('/staff/card/' . $staff->uuid) }}" class="btn btn-outline-primary">
                                <i class="fas fa-id-card"></i> View Visiting Card
                            </a>
                            <a href="mailto:{{ $staff->email }}" class="btn btn-outline-success">
                                <i class="fas fa-envelope"></i> Send Email
                            </a>
                            @if($staff->phone_number)
                            <a href="tel:{{ $staff->phone_number }}" class="btn btn-outline-warning">
                                <i class="fas fa-phone"></i> Call Now
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Profile link copied to clipboard!');
            });
        }
    </script>
</body>
</html>
