<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('detailsApp.name')}} - Admin Password Reset</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Inter', 'Poppins', sans-serif;
        }
        
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        
        .header-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .logo-wrapper {
            position: relative;
            z-index: 1;
            margin-bottom: 20px;
        }
        
        .logo-wrapper img {
            max-width: 80px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }
        
        .welcome-icon {
            font-size: 48px;
            color: #ffffff;
            margin-bottom: 15px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .header-section h1 {
            color: #ffffff;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }
        
        .header-section p {
            color: rgba(255, 255, 255, 0.95);
            font-size: 16px;
            margin: 10px 0 0 0;
            font-weight: 300;
        }
        
        .content-section {
            padding: 40px 30px;
        }
        
        .greeting-text {
            color: #333333;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .admin-badge {
            display: inline-block;
            background: #e3f2fd;
            color: #2a5298;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        
        .info-message {
            background: #e3f2fd;
            border-left: 4px solid #2a5298;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            color: #333333;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .admin-info-box {
            background: #f8f9fc;
            border: 1px solid #e8eef7;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .admin-info-icon {
            color: #2a5298;
            font-size: 22px;
            flex-shrink: 0;
        }
        
        .admin-info-text {
            color: #333333;
            font-size: 14px;
            font-weight: 500;
        }
        
        .admin-email-highlight {
            font-weight: 700;
            color: #1e3c72;
        }
        
        .password-section {
            margin-bottom: 35px;
        }
        
        .details-card {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(30, 60, 114, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .details-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .details-card::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }
        
        .card-title {
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }
        
        .card-title i {
            font-size: 22px;
        }
        
        /* Password Display */
        .password-display {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .password-display:hover {
            background: #ffffff;
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .password-icon {
            color: #2a5298;
            font-size: 28px;
            min-width: 40px;
            text-align: center;
            flex-shrink: 0;
        }
        
        .password-content {
            flex: 1;
        }
        
        .password-label {
            font-size: 11px;
            font-weight: 700;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }
        
        .password-value {
            font-size: 26px;
            font-weight: 700;
            color: #1e3c72;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }
        
        .password-notice {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        .password-notice i {
            margin-right: 6px;
        }
        
        .security-notice {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 15px 20px;
            margin-top: 25px;
            color: #856404;
            font-size: 13px;
            line-height: 1.6;
        }
        
        .security-notice i {
            color: #ff9800;
            margin-right: 8px;
            font-weight: bold;
        }
        
        .footer-section {
            background: #f8f9fc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e8eef7;
        }
        
        .footer-text {
            color: #666666;
            font-size: 14px;
            margin: 10px 0;
            line-height: 1.6;
        }
        
        .company-name {
            font-weight: 700;
            color: #2a5298;
            font-size: 18px;
            margin-top: 15px;
        }
        
        .logo-footer {
            margin-top: 20px;
        }
        
        .logo-footer img {
            max-width: 60px;
            height: auto;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }
        
        /* Responsive Design */
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 12px;
            }
            
            .header-section {
                padding: 30px 20px;
            }
            
            .header-section h1 {
                font-size: 26px;
            }
            
            .content-section {
                padding: 25px 20px;
            }
            
            .password-display {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .password-value {
                font-size: 22px;
                letter-spacing: 1px;
            }
            
            .welcome-icon {
                font-size: 40px;
            }
            
            .admin-info-box {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="logo-wrapper">
                <img src="{{$message->embed(public_path().'/logo/logo.png')}}" alt="{{config('detailsApp.name')}} Logo">
            </div>
            <div class="welcome-icon">
                <i class="fas fa-key"></i>
            </div>
            <h1>Admin Password Reset</h1>
            <p>Your administrative account password has been reset</p>
        </div>
        
        <!-- Content Section -->
        <div class="content-section">
            <p class="greeting-text">
                <i class="fas fa-wave"></i> Welcome to Admin Panel!
            </p>
            
            <div class="admin-badge">
                <i class="fas fa-shield-alt"></i> Administrator
            </div>
            
            <div class="info-message">
                <i class="fas fa-info-circle" style="color: #2a5298; margin-right: 8px;"></i>
                A request was received to reset your admin account password. Below is your newly generated temporary password.
            </div>
            
            <!-- Admin Account Info -->
            <div class="admin-info-box">
                <div class="admin-info-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="admin-info-text">
                    Admin Account: <span class="admin-email-highlight">{{$email}}</span>
                </div>
            </div>
            
            <!-- Password Details in Card -->
            <div class="password-section">
                <div class="details-card">
                    <div class="card-title">
                        <i class="fas fa-lock"></i>
                        Your New Password
                    </div>
                    
                    <div class="password-display">
                        <div class="password-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="password-content">
                            <span class="password-label">Temporary Password</span>
                            <div class="password-value">{{$password}}</div>
                        </div>
                    </div>
                    
                    <div class="password-notice">
                        <i class="fas fa-shield-alt"></i>
                        Please log in and update your password for optimal security.
                    </div>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Security Alert:</strong> Never share this password with anyone. Keep it confidential at all times. If you did not request this password reset, please secure your account immediately.
            </div>
        </div>
        
        <!-- Footer Section -->
        <div class="footer-section">
            <p class="footer-text">
                <i class="fas fa-headset"></i> Need assistance? Contact your system administrator
            </p>
            <p class="company-name">
                <i class="fas fa-building"></i> {{config('detailsApp.name')}} Admin Panel
            </p>
            <div class="logo-footer">
                <img src="{{$message->embed(public_path('logo/logo-dark.png'))}}" alt="{{config('detailsApp.name')}}">
            </div>
            <p class="footer-text" style="margin-top: 15px; font-size: 12px; color: #999999;">
                © {{ date('Y') }} {{config('detailsApp.name')}}. All rights reserved.
            </p>
        </div>
    </div>
    
</body>
</html>



