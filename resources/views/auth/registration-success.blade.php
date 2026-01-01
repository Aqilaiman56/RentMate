{{-- resources/views/auth/registration-success.blade.php --}}
<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="gorent-success-container">
        <!-- Left Section with Illustration -->
        <div class="gorent-left-section">
            <div class="gorent-illustration">
                <div class="gorent-brand-logo">
                    <h1>GO<span class="rent-blue">Rent</span>UMS</h1>
                    <p class="gorent-subtitle">Welcome to Our Community</p>
                </div>
                <!-- Success Illustration SVG -->
                <div class="illustration-wrapper">
                    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                        <!-- Success checkmark circle -->
                        <circle cx="200" cy="200" r="120" fill="#B5EAD7" opacity="0.3"/>
                        <circle cx="200" cy="200" r="100" fill="#4A5FDC" opacity="0.2"/>
                        <circle cx="200" cy="200" r="80" fill="#4A5FDC"/>

                        <!-- Checkmark -->
                        <path d="M 160 200 L 185 225 L 245 165"
                              stroke="white"
                              stroke-width="12"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              fill="none"/>

                        <!-- Email envelope -->
                        <rect x="100" y="280" width="200" height="100" fill="#FFB7B2" rx="8"/>
                        <path d="M 100 280 L 200 340 L 300 280"
                              fill="#FF9AA2"/>
                        <line x1="100" y1="280" x2="200" y2="340" stroke="#FFDAC1" stroke-width="3"/>
                        <line x1="300" y1="280" x2="200" y2="340" stroke="#FFDAC1" stroke-width="3"/>

                        <!-- Decorative elements -->
                        <circle cx="80" cy="100" r="15" fill="#C7CEEA" opacity="0.6"/>
                        <circle cx="320" cy="120" r="20" fill="#FFD3E0" opacity="0.6"/>
                        <circle cx="350" cy="280" r="12" fill="#E2F0CB" opacity="0.6"/>
                        <circle cx="60" cy="350" r="18" fill="#FFDAC1" opacity="0.6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right Section with Success Message -->
        <div class="gorent-right-section">
            <div class="gorent-success-card">
                <div class="gorent-success-icon">
                    <i class="fas fa-check-circle"></i>
                </div>

                <div class="gorent-success-header">
                    <h2>Thank You for Registering!</h2>
                    <p>Your account has been created successfully</p>
                </div>

                <div class="gorent-verification-notice">
                    <div class="notice-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="notice-content">
                        <h3>Verify Your Email Address</h3>
                        <p>We've sent a verification email to:</p>
                        <div class="email-display">
                            <strong>{{ $email }}</strong>
                        </div>
                        <p class="notice-subtext">
                            Please check your inbox and click the verification link to activate your account.
                            You won't be able to login until your email is verified.
                        </p>
                    </div>
                </div>

                <div class="gorent-info-box">
                    <div class="info-item">
                        <i class="fas fa-circle-info"></i>
                        <span>Check your spam folder if you don't see the email</span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <span>The verification link will expire in 60 minutes</span>
                    </div>
                </div>

                <div class="gorent-actions">
                    <a href="{{ route('login') }}" class="gorent-btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        Proceed to Login
                    </a>
                    <a href="{{ route('welcome') }}" class="gorent-btn-secondary">
                        <i class="fas fa-home"></i>
                        Back to Home
                    </a>
                </div>

                <div class="gorent-help-section">
                    <p>Didn't receive the email?</p>
                    <a href="{{ route('login') }}" class="gorent-link">
                        Go to login and resend verification email
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
        }

        .gorent-success-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
            margin: -2rem;
        }

        /* Left Section - Illustration */
        .gorent-left-section {
            flex: 1;
            background: linear-gradient(135deg, #F0F9FF 0%, #F0F4FF 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .gorent-illustration {
            max-width: 500px;
            width: 100%;
            position: relative;
        }

        .gorent-brand-logo {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 10;
        }

        .gorent-brand-logo h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #2D3748;
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
        }

        .rent-blue {
            color: #4A5FDC;
        }

        .gorent-subtitle {
            font-size: 1.25rem;
            color: #4A5B7C;
            font-weight: 300;
        }

        .illustration-wrapper {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }

        .illustration-wrapper svg {
            width: 100%;
            height: auto;
            filter: drop-shadow(0 10px 30px rgba(0, 0, 0, 0.1));
        }

        /* Right Section - Success Card */
        .gorent-right-section {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow-y: auto;
        }

        .gorent-success-card {
            width: 100%;
            max-width: 520px;
            padding: 2rem 0;
        }

        .gorent-success-icon {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .gorent-success-icon i {
            font-size: 4rem;
            color: #48BB78;
            animation: scaleIn 0.5s ease-out;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .gorent-success-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .gorent-success-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #1A202C;
            margin-bottom: 0.5rem;
        }

        .gorent-success-header p {
            font-size: 1rem;
            color: #718096;
        }

        .gorent-verification-notice {
            background: linear-gradient(135deg, #EBF4FF 0%, #F0F4FF 100%);
            border: 2px solid #4A5FDC;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            gap: 1rem;
        }

        .notice-icon {
            flex-shrink: 0;
        }

        .notice-icon i {
            font-size: 2.5rem;
            color: #4A5FDC;
        }

        .notice-content h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1A202C;
            margin-bottom: 0.75rem;
        }

        .notice-content p {
            font-size: 0.95rem;
            color: #4A5B7C;
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .email-display {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            margin: 0.75rem 0;
            border: 1px solid #CBD5E0;
        }

        .email-display strong {
            color: #4A5FDC;
            font-size: 1rem;
        }

        .notice-subtext {
            font-size: 0.875rem !important;
            color: #718096 !important;
            margin-top: 0.75rem !important;
        }

        .gorent-info-box {
            background: #FFF5F5;
            border-left: 4px solid #FC8181;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #742A2A;
            font-size: 0.875rem;
            margin: 0.5rem 0;
        }

        .info-item i {
            font-size: 1rem;
            color: #FC8181;
        }

        .gorent-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .gorent-btn-primary,
        .gorent-btn-secondary {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .gorent-btn-primary {
            background: #4A5FDC;
            color: white;
        }

        .gorent-btn-primary:hover {
            background: #3D4FC7;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 95, 220, 0.3);
        }

        .gorent-btn-secondary {
            background: #F7FAFC;
            color: #4A5B7C;
            border: 1.5px solid #E2E8F0;
        }

        .gorent-btn-secondary:hover {
            background: #EDF2F7;
            border-color: #CBD5E0;
        }

        .gorent-help-section {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #E2E8F0;
        }

        .gorent-help-section p {
            font-size: 0.875rem;
            color: #718096;
            margin-bottom: 0.5rem;
        }

        .gorent-link {
            color: #4A5FDC;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .gorent-link:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media screen and (max-width: 968px) {
            body {
                background: linear-gradient(135deg, #F0F9FF 0%, #F0F4FF 100%) !important;
            }

            .gorent-success-container {
                flex-direction: column !important;
                position: relative !important;
                overflow-x: hidden !important;
                overflow-y: auto !important;
                min-height: 100vh !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 1rem !important;
                margin: 0 !important;
            }

            .gorent-left-section {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                width: 100vw !important;
                height: 100vh !important;
                margin: 0 !important;
                padding: 0 !important;
                opacity: 0.5 !important;
                z-index: 0 !important;
                pointer-events: none !important;
            }

            .gorent-right-section {
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                z-index: 10 !important;
                background: rgba(255, 255, 255, 0.75) !important;
                backdrop-filter: blur(20px) !important;
                -webkit-backdrop-filter: blur(20px) !important;
                padding: 2.5rem 2rem !important;
                min-height: auto !important;
                max-height: 95vh !important;
                overflow-y: auto !important;
                max-width: 500px !important;
                width: calc(100% - 2rem) !important;
                box-shadow: 0 10px 80px rgba(0, 0, 0, 0.2) !important;
                border-radius: 20px !important;
                margin: 0 !important;
                flex: none !important;
            }

            .gorent-success-card {
                padding: 0 !important;
            }

            .gorent-brand-logo {
                display: none !important;
            }
        }

        @media (max-width: 480px) {
            .gorent-success-header h2 {
                font-size: 1.5rem;
            }

            .gorent-right-section {
                padding: 1.5rem 1rem !important;
                width: calc(100% - 1rem) !important;
            }

            .gorent-verification-notice {
                flex-direction: column;
                text-align: center;
            }

            .notice-icon i {
                font-size: 2rem;
            }
        }
    </style>
</x-guest-layout>
