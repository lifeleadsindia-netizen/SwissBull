<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <title>{{ config('detailsApp.name', 'SYNC TRADE') }} - Email Verification OTP</title>
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0; padding: 0; width: 100% !important; min-width: 100%; background-color: #08090C; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        .email-wrapper { width: 100%; background-color: #08090C; padding: 30px 10px; }
        .email-container { max-width: 580px; margin: 0 auto; background-color: #12151e; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); border: 1px solid rgba(245, 158, 11, 0.25); }
        .header-bg { background: linear-gradient(135deg, #0c0e14 0%, #151821 50%, #1c1f2b 100%); padding: 36px 30px; text-align: center; border-bottom: 2px solid #f59e0b; }
        .header-title { color: #ffffff; font-size: 26px; font-weight: 700; margin: 12px 0 6px 0; letter-spacing: -0.3px; }
        .header-sub { color: #fcd34d; font-size: 14px; margin: 0; font-weight: 400; }
        .content-body { padding: 36px 32px 28px 32px; background-color: #12151e; color: #e2e8f0; }
        .greeting { font-size: 18px; font-weight: 700; color: #ffffff; margin: 0 0 12px 0; }
        .info-text { font-size: 14px; line-height: 1.6; color: #cbd5e1; margin: 0 0 24px 0; }
        .badge { display: inline-block; padding: 5px 14px; background-color: rgba(245, 158, 11, 0.15); color: #f59e0b; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 16px; border: 1px solid rgba(245, 158, 11, 0.3); }
        .otp-box { background: linear-gradient(135deg, #08090C 0%, #0d0f14 100%); border: 2px solid #10b981; border-radius: 12px; padding: 26px 20px; text-align: center; margin: 24px 0; }
        .otp-label { color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-bottom: 8px; }
        .otp-digits { font-family: 'Courier New', Courier, monospace; font-size: 38px; font-weight: 800; color: #10b981; letter-spacing: 8px; margin: 6px 0 10px 0; }
        .otp-timer { color: #facc15; font-size: 12px; font-weight: 500; }
        .notice-box { background-color: rgba(245, 158, 11, 0.10); border-left: 4px solid #f59e0b; padding: 14px 16px; border-radius: 6px; margin: 22px 0; font-size: 13px; color: #fcd34d; line-height: 1.5; }
        .portal-box { background-color: #0c0e14; border: 1px solid rgba(245, 158, 11, 0.20); border-radius: 8px; padding: 16px; margin: 20px 0; text-align: center; }
        .portal-btn { display: inline-block; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #08090C !important; padding: 10px 24px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 700; margin-top: 10px; }
        .footer { padding: 26px 30px; background-color: #0c0e14; border-top: 1px solid rgba(255, 255, 255, 0.08); text-align: center; font-size: 12px; color: #94a3b8; line-height: 1.6; }
        .footer a { color: #f59e0b; text-decoration: none; }
        @media only screen and (max-width: 600px) {
            .content-body { padding: 24px 18px !important; }
            .header-bg { padding: 26px 18px !important; }
            .otp-digits { font-size: 30px !important; letter-spacing: 5px !important; }
        }
    </style>
</head>
<body>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="email-wrapper">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="email-container" width="100%">
                    <!-- Header -->
                    <tr>
                        <td class="header-bg">
                            @if(file_exists(public_path('logo/logo.png')))
                                <img src="{{ $message->embed(public_path('logo/logo.png')) }}" alt="{{ config('detailsApp.name', 'SYNC TRADE') }}" width="70" style="max-width: 70px; height: auto; margin-bottom: 10px;" />
                            @endif
                            <div class="header-title">Email Verification</div>
                            <div class="header-sub">Verify your email to complete registration</div>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content-body">
                            <div class="badge">Verification Required</div>
                            <h2 class="greeting">Hello,</h2>
                            <p class="info-text">
                                Thank you for choosing <strong>{{ config('detailsApp.name', 'SYNC TRADE') }}</strong>. Please use the following One-Time Password (OTP) to verify your email address and activate your account:
                            </p>
                            
                            <!-- OTP Box -->
                            <div class="otp-box">
                                <div class="otp-label">Your One-Time Password (OTP)</div>
                                <div class="otp-digits">{{ $otp }}</div>
                                <div class="otp-timer">⏱ Valid for <strong>10 minutes</strong> only</div>
                            </div>
                            
                            <!-- Security Notice -->
                            <div class="notice-box">
                                <strong>⚠️ Security Reminder:</strong> Never share this OTP with anyone, including {{ config('detailsApp.name', 'SYNC TRADE') }} representatives. We will never ask for your password or OTP.
                            </div>
                            
                            <!-- Platform Portal -->
                            <div class="portal-box">
                                <div style="font-size: 13px; color: #64748b;">Official Platform Website:</div>
                                <div style="font-weight: 600; color: #f59e0b; margin: 4px 0 8px 0; font-size: 14px;">{{ config('detailsApp.url', 'https://mathwallet.live/') }}</div>
                                <a href="{{ config('detailsApp.url', 'https://mathwallet.live/') }}" class="portal-btn" target="_blank" rel="noopener noreferrer">Visit Website</a>
                            </div>
                            
                            <p class="info-text" style="font-size: 12px; color: #94a3b8; margin: 18px 0 0 0;">
                                If you did not request this verification code, please ignore this email or contact our support team.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <p style="margin: 0 0 8px 0;">
                                Need assistance? Contact us at <a href="mailto:{{ config('mail.from.address', 'support@mathwallet.live') }}">{{ config('mail.from.address', 'support@mathwallet.live') }}</a>
                            </p>
                            @if(file_exists(public_path('logo/logo-dark.png')))
                                <div style="margin: 12px 0;">
                                    <img src="{{ $message->embed(public_path('logo/logo-dark.png')) }}" alt="{{ config('detailsApp.name', 'SYNC TRADE') }}" width="60" style="max-width: 60px; height: auto; opacity: 0.75;" />
                                </div>
                            @endif
                            <p style="margin: 6px 0 0 0; color: #9ca3af; font-size: 11px;">
                                © {{ date('Y') }} {{ config('detailsApp.name', 'SYNC TRADE') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
