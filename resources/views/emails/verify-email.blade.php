<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #1f2937;
            font-size: 24px;
            margin: 0 0 20px 0;
            font-weight: 600;
        }
        .email-body p {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 16px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
        }
        .verify-button:hover {
            box-shadow: 0 6px 12px rgba(102, 126, 234, 0.4);
            transform: translateY(-2px);
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .alternative-link {
            background-color: #f9fafb;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .alternative-link p {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #6b7280;
        }
        .alternative-link a {
            color: #667eea;
            word-break: break-all;
            font-size: 12px;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #6b7280;
            font-size: 14px;
            margin: 5px 0;
        }
        .welcome-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .feature-list {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .feature-item {
            display: flex;
            align-items: start;
            margin: 12px 0;
        }
        .feature-icon {
            color: #667eea;
            margin-right: 12px;
            font-size: 20px;
            line-height: 1.6;
        }
        .feature-text {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="welcome-icon">🎉</div>
            <h1>{{ config('app.name') }}</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Welcome to {{ config('app.name') }}!</h2>

            <p>Hi {{ $notifiable->UserName ?? 'there' }},</p>

            <p>Thank you for signing up! We're excited to have you on board. To get started and access all features, please verify your email address by clicking the button below:</p>

            <!-- Verify Button -->
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    ✓ Verify Email Address
                </a>
            </div>

            <!-- Features List -->
            <div class="feature-list">
                <div class="feature-item">
                    <span class="feature-icon">🏠</span>
                    <span class="feature-text">Browse and rent items from our community</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">📝</span>
                    <span class="feature-text">List your own items for rent</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">💬</span>
                    <span class="feature-text">Connect with other users</span>
                </div>
                <div class="feature-item">
                    <span class="feature-icon">🔒</span>
                    <span class="feature-text">Secure payments and transactions</span>
                </div>
            </div>

            <p><strong>This verification link will expire in 60 minutes.</strong></p>

            <p>If you didn't create an account with {{ config('app.name') }}, please ignore this email.</p>

            <!-- Alternative Link -->
            <div class="alternative-link">
                <p><strong>Having trouble with the button?</strong> Copy and paste this URL into your browser:</p>
                <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Your trusted rental marketplace</p>
            <p style="margin-top: 15px; font-size: 12px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
