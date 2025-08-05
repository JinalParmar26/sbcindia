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
        }

        .visiting-card {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            position: relative;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .staff-role {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .contact-info {
            margin-bottom: 30px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: #34495e;
        }

        .contact-item i {
            width: 20px;
            margin-right: 10px;
            color: #667eea;
        }

        .contact-item a {
            color: #34495e;
            text-decoration: none;
        }

        .contact-item a:hover {
            color: #667eea;
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
