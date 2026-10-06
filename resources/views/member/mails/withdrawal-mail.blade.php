<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('detailsApp.name') }} - Withdrawal Verification OTP</title>
    
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
            background: linear-gradient(135deg, #0f766e 0%, #047857 100%);
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
            background: linear-gradient(135deg, #0f766e 0%, #047857 100%);
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
            font-size: 30px;
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
        
        .member-badge {
            display: inline-block;
            background: #ccfbf1;
            color: #0f766e;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        
        .info-message {
            background: #f0fdfa;
            border-left: 4px solid #0f766e;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            color: #333333;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .member-info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .member-info-icon {
            color: #0f766e;
            font-size: 22px;
            flex-shrink: 0;
        }
        
        .member-info-text {
            color: #333333;
            font-size: 14px;
            font-weight: 500;
        }
        
        .member-id-highlight {
            font-weight: 700;
            color: #0f766e;
            font-family: 'Courier New', monospace;
            font-size: 15px;
        }
        
        .otp-section {
            margin-bottom: 35px;
        }
        
        .details-card {
            background: linear-gradient(135deg, #0f766e 0%, #047857 100%);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(15, 118, 110, 0.2);
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
        
        /* OTP Display */
        .otp-display {
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
        
        .otp-display:hover {
            background: #ffffff;
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        
        .otp-icon {
            color: #0f766e;
            font-size: 28px;
            min-width: 40px;
            text-align: center;
            flex-shrink: 0;
        }
        
        .otp-content {
            flex: 1;
        }
        
        .otp-label {
            font-size: 11px;
            font-weight: 700;
            color: #999999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: block;
        }
        
        .otp-value {
            font-size: 32px;
            font-weight: 700;
            color: #0f766e;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
        }
        
        .otp-timer {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }
        
        .timer-icon {
            color: #ffffff;
            margin-right: 6px;
        }
        
        .url-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 25px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }
        
        .url-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .url-icon {
            color: #0f766e;
            font-size: 20px;
        }
        
        .url-text {
            font-size: 13px;
            color: #64748b;
        }
        
        .url-btn {
            background: #0f766e;
            color: #ffffff !important;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .url-btn:hover {
            background: #115e59;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3);
        }
        
        .security-notice {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 15px 20px;
            margin-top: 20px;
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
            color: #0f766e;
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
                font-size: 24px;
            }
            
            .content-section {
                padding: 25px 20px;
            }
            
            .otp-display {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .otp-value {
                font-size: 26px;
                letter-spacing: 3px;
            }
            
            .welcome-icon {
                font-size: 40px;
            }
            
            .member-info-box {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .url-card {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .url-btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="logo-wrapper">
                <img src="{{ $message->embed(public_path('logo/logo.png')) }}" alt="{{ config('detailsApp.name') }} Logo">
            </div>
            <div class="welcome-icon">
                <i class="fas fa-money-check-alt"></i>
            </div>
            <h1>Withdrawal Verification</h1>
            <p>Authorize your withdrawal transaction</p>
        </div>
        
        <!-- Content Section -->
        <div class="content-section">
            <p class="greeting-text">
                <i class="fas fa-wave"></i> Hello Member!
            </p>
            
            <div class="member-badge">
                <i class="fas fa-shield-alt"></i> Security Verification
            </div>
            
            <div class="info-message">
                <i class="fas fa-info-circle" style="color: #0f766e; margin-right: 8px;"></i>
                A fund withdrawal request has been initiated from your account. Please use the One-Time Password (OTP) below to authorize and confirm this transaction.
            </div>
            
            <!-- Member ID Box -->
            <div class="member-info-box">
                <div class="member-info-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="member-info-text">
                    Account Member ID: <span class="member-id-highlight">{{ $memberid }}</span>
                </div>
            </div>
            
            <!-- OTP Details in Card -->
            <div class="otp-section">
                <div class="details-card">
                    <div class="card-title">
                        <i class="fas fa-lock"></i>
                        Withdrawal Authorization OTP
                    </div>
                    
                    <div class="otp-display">
                        <div class="otp-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <div class="otp-content">
                            <span class="otp-label">One-Time Password (OTP)</span>
                            <div class="otp-value">{{ $otp }}</div>
                        </div>
                    </div>
                    
                    <div class="otp-timer">
                        <i class="fas fa-hourglass-end timer-icon"></i>
                        This authorization code expires in <strong>10 minutes</strong>
                    </div>
                </div>
            </div>
            
            <!-- Platform URL Card -->
            <div class="url-card">
                <div class="url-info">
                    <i class="fas fa-globe url-icon"></i>
                    <div class="url-text">
                        <strong>Member Dashboard:</strong><br>{{ config('detailsApp.url') }}
                    </div>
                </div>
                <a href="{{ config('detailsApp.url') }}" class="url-btn" target="_blank">
                    <i class="fas fa-external-link-alt"></i> Open Portal
                </a>
            </div>
            
            <!-- Security Notice -->
            <div class="security-notice">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Security Alert:</strong> Never share this withdrawal OTP with anyone, including {{ config('detailsApp.name') }} support. If you did not initiate this withdrawal, please change your account password and contact support immediately.
            </div>
        </div>
        
        <!-- Footer Section -->
        <div class="footer-section">
            <p class="footer-text">
                <i class="fas fa-headset"></i> Need assistance? Contact our 24/7 member support
            </p>
            <p class="company-name">
                <i class="fas fa-building"></i> {{ config('detailsApp.name') }}
            </p>
            <div class="logo-footer">
                <img src="{{ $message->embed(public_path('logo/logo-dark.png')) }}" alt="{{ config('detailsApp.name') }}">
            </div>
            <p class="footer-text" style="margin-top: 15px; font-size: 12px; color: #999999;">
                © {{ date('Y') }} {{ config('detailsApp.name') }}. All rights reserved.
            </p>
        </div>
    </div>
    
</body>
</html>



