
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Withdrawal Request • {{ config('detailsApp.name') }}</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-yH4Y1s2sQp2P1r+3zq8G6Cqg1f1Q5xXbE3Z6T1/9Yq3f8k2b2jYq4tF6J1c1H3lQm2Yw==" crossorigin="anonymous"
        referrerpolicy="no-referrer" />

    <style>
        /* Modern & Professional Email Template Styles */
        :root {
            --primary: #0066ff;
            --primary-light: #6f86ff;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f1724;
            --gray-900: #111827;
            --gray-700: #374151;
            --gray-600: #4b5563;
            --gray-500: #6b7280;
            --gray-300: #d1d5db;
            --gray-200: #e5e7eb;
            --gray-100: #f3f4f6;
            --bg: #f8fafc;
            --card: #ffffff;
            --border: #e6eef8;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            background: var(--bg);
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--gray-700);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .wrap {
            width: 100%;
            padding: 32px 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0 auto;
        }

        .email-card {
            width: 100%;
            max-width: 700px;
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 12px 48px rgba(18, 38, 63, 0.12);
            overflow: hidden;
            border: 3px solid var(--primary);
            animation: slideIn 0.4s ease-out;
            margin: 0 auto;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Section */
        .email-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: #fff;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: relative;
            overflow: hidden;
        }

        .email-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(20px); }
        }

        .brand {
            display: flex;
            gap: 14px;
            align-items: center;
            z-index: 1;
        }

        .brand img {
            height: 48px;
            width: 48px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            object-fit: contain;
        }

        .brand-text .title {
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.3px;
        }

        .brand-text .subtitle {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 2px;
        }

        .header-meta {
            text-align: right;
            font-size: 13px;
            z-index: 1;
        }

        .header-meta .request-id {
            font-weight: 700;
            font-size: 13px;
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 10px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 4px;
        }

        .header-meta .date {
            font-size: 12px;
            opacity: 0.85;
        }

        /* Body Section */
        .email-body {
            padding: 28px;
        }

        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .lead {
            font-size: 14px;
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: 20px;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .status-badge i { font-size: 14px; }

        /* Summary Card */
        .card-summary {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin: 20px 0;
        }

        .summary-header {
            padding: 14px 16px;
            background: rgba(0, 102, 255, 0.05);
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            color: var(--primary);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            transition: background-color 0.3s ease;
        }

        .row:hover { background: rgba(0, 102, 255, 0.02); }

        .row + .row { border-top: 1px solid var(--border); }

        .row .label {
            display: flex;
            gap: 10px;
            align-items: center;
            color: var(--gray-700);
            font-weight: 600;
            width: 45%;
            min-width: 150px;
            font-size: 14px;
        }

        .row .label i {
            color: var(--primary);
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .row .value {
            color: var(--gray-900);
            text-align: right;
            width: 55%;
            font-weight: 500;
            font-size: 14px;
        }

        .value.highlight {
            color: var(--success);
            font-weight: 700;
            font-size: 15px;
            background: rgba(16, 185, 129, 0.08);
            padding: 4px 8px;
            border-radius: 4px;
        }

        .value.warning {
            color: var(--warning);
            font-weight: 600;
        }

        /* CTA Section */
        .cta-section {
            margin-top: 24px;
            padding: 20px;
            background: rgba(0, 102, 255, 0.05);
            border-radius: 10px;
            border-left: 4px solid var(--primary);
        }

        .cta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            color: #fff;
            padding: 11px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 102, 255, 0.3);
        }

        .btn-primary {
            background: var(--primary);
            box-shadow: 0 4px 12px rgba(0, 102, 255, 0.25);
        }

        .btn-success {
            background: var(--success);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }

        .btn-secondary {
            background: var(--gray-600);
            box-shadow: 0 4px 12px rgba(75, 85, 99, 0.15);
        }

        .notification-text {
            color: var(--gray-600);
            font-size: 13px;
            margin-top: 12px;
            line-height: 1.6;
        }

        /* Info Box */
        .info-box {
            background: #fffbeb;
            border-left: 4px solid var(--warning);
            padding: 12px 14px;
            border-radius: 6px;
            margin-top: 16px;
            font-size: 13px;
            color: var(--gray-700);
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .info-box i { color: var(--warning); margin-top: 2px; }

        /* Footer Section */
        .email-footer {
            padding: 20px;
            background: var(--gray-100);
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .footer-content {
            font-size: 13px;
            color: var(--gray-600);
        }

        .footer-content strong { color: var(--gray-900); }

        .footer-links {
            display: flex;
            gap: 12px;
            align-items: center;
            font-size: 12px;
        }

        .footer-links a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover { color: var(--primary-light); }

        .divider { color: var(--gray-300); }

        .small { font-size: 12px; color: var(--gray-500); }

        /* Responsive Design */
        @media (max-width: 640px) {
            .wrap { padding: 16px; }

            .email-header {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .header-meta { text-align: left; }

            .email-body { padding: 20px; }

            .row {
                flex-direction: column;
                align-items: flex-start;
            }

            .row .label,
            .row .value {
                width: 100%;
                text-align: left;
            }

            .cta {
                flex-direction: column;
                width: 100%;
            }

            .btn { width: 100%; justify-content: center; }

            .email-footer {
                flex-direction: column;
                align-items: flex-start;
                text-align: center;
            }

            .footer-links { flex-direction: column; }

            .greeting { font-size: 18px; }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="email-card" role="article" aria-label="Withdrawal Request Notification">
            <!-- Header -->
            <div class="email-header">
                <div class="brand">
                    <img src="{{ $message->embed(public_path('/logo/logo.png')) }}" alt="{{ config('detailsApp.name') }} logo">
                    <div class="brand-text">
                        <div class="title">{{ config('detailsApp.name') }}</div>
                        <div class="subtitle">Admin Notification</div>
                    </div>
                </div>
                <div class="header-meta">
                    <div class="request-id"><i class="fa-solid fa-bell"></i> New Request</div>
                    <div class="date"><i class="fa-solid fa-calendar"></i> {{ date('d M, Y H:i', strtotime($date)) }}</div>
                </div>
            </div>

            <!-- Body -->
            <div class="email-body">
                <h1 class="greeting"><i class="fa-solid fa-exclamation-circle"></i> New Withdrawal Request Submitted</h1>
                <p class="lead">A member has submitted a withdrawal request that requires your review and approval. Please check the details below and take appropriate action.</p>

                <div class="status-badge">
                    <i class="fa-solid fa-hourglass-end"></i> Awaiting Admin Review
                </div>

                <!-- Summary Card -->
                <div class="card-summary" role="table" aria-label="Withdrawal details">
                    <div class="summary-header">
                        <i class="fa-solid fa-user-check"></i> Member Information & Request Details
                    </div>

                    <div class="row" role="row">
                        <div class="label"><i class="fa-solid fa-user-circle"></i> Member Name</div>
                        <div class="value">{{ $name }}</div>
                    </div>

                    <div class="row" role="row">
                        <div class="label"><i class="fa-solid fa-id-card"></i> Member ID</div>
                        <div class="value" style="font-family: 'Courier New', monospace;">{{ $memberid }}</div>
                    </div>

                    <div class="row" role="row">
                        <div class="label"><i class="fa-solid fa-tag"></i> Request Type</div>
                        <div class="value">{{ $type }}</div>
                    </div>

                    <div class="row" role="row">
                        <div class="label"><i class="fa-solid fa-money-bill-wave"></i> Gross Amount Requested</div>
                        <div class="value">${{ $gross }}</div>
                    </div>

                    <div class="row" role="row">
                        <div class="label"><i class="fa-solid fa-percent"></i> Service Charges</div>
                        <div class="value warning">- ${{ $service }}</div>
                    </div>

                    <div class="row" role="row" style="background: rgba(16, 185, 129, 0.05);">
                        <div class="label"><i class="fa-solid fa-circle-check"></i> Net Payable Amount</div>
                        <div class="value highlight">${{ $net }}</div>
                    </div>

                    <div class="row" role="row">
                        <div class="label"><i class="fa-solid fa-wallet"></i> Payment Wallet</div>
                        <div class="value">{{ $wallet }}</div>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="cta-section">
                    <div class="cta">
                        <a href="{{ !empty($action_url) ? $action_url : (config('detailsApp.url').'/admin/new-withdrawal-request') }}" class="btn btn-primary" target="_blank" rel="noopener">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Review & Process
                        </a>

                        <a href="#" class="btn btn-success" onclick="copyRequestId(event)">
                            <i class="fa-solid fa-copy"></i> Copy Request ID
                        </a>

                        <a href="{{ config('detailsApp.url') }}" class="btn btn-secondary" target="_blank">
                            <i class="fa-solid fa-globe"></i> Visit Platform
                        </a>
                    </div>

                    <p class="notification-text">
                        <i class="fa-solid fa-info-circle"></i> Please log in to the admin panel to review, verify, and process this withdrawal request. Ensure all compliance checks are completed before approval.
                    </p>
                </div>

                <!-- Info Box -->
                <div class="info-box">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span><strong>Admin Action Required:</strong> This request is awaiting your verification. Please validate the member's account status and payment details before processing.</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <div class="footer-content">
                    <strong>{{ config('detailsApp.name') }} Admin Panel</strong><br>
                    <small>Portal: <a href="{{ config('detailsApp.url') }}" style="color: var(--primary); text-decoration: none;" target="_blank">{{ config('detailsApp.url') }}</a></small>
                </div>
                <div class="footer-links">
                    <div style="margin-right: 10px;">
                        <img src="{{ $message->embed(public_path('logo/logo-dark.png')) }}" alt="{{ config('detailsApp.name') }}" style="max-height: 24px; vertical-align: middle;">
                    </div>
                    <span class="divider">•</span>
                    <small>&copy; {{ date('Y') }} {{ config('detailsApp.name') }}. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>

</body>

</html>