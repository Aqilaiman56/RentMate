{{-- resources/views/auth/reset-password.blade.php --}}
<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="gorent-reset-container">
        <!-- Left Section with Illustration -->
        <div class="gorent-left-section">
            <div class="gorent-illustration">
                <div class="gorent-brand-logo">
                    <h1>GO<span class="rent-blue">Rent</span>UMS</h1>
                </div>
                <!-- Illustration SVG -->
                <div class="illustration-wrapper">
                    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background shapes -->
                        <rect x="40" y="40" width="80" height="100" fill="#FF9AA2" rx="4"/>
                        <rect x="130" y="30" width="60" height="120" fill="#FFB7B2" rx="4"/>
                        <rect x="200" y="50" width="70" height="90" fill="#FFDAC1" rx="4"/>
                        <rect x="280" y="40" width="80" height="110" fill="#E2F0CB" rx="4"/>

                        <!-- Bottom shapes -->
                        <rect x="50" y="280" width="90" height="80" fill="#B5EAD7" rx="4"/>
                        <rect x="150" y="300" width="70" height="60" fill="#C7CEEA" rx="4"/>
                        <rect x="230" y="290" width="80" height="70" fill="#FFDFD3" rx="4"/>
                        <rect x="320" y="280" width="60" height="80" fill="#FFB7B2" rx="4"/>

                        <!-- People illustrations -->
                        <!-- Person 1 - Left -->
                        <circle cx="70" cy="90" r="20" fill="#5B4A8C"/>
                        <path d="M 50 110 Q 70 120 90 110 L 90 160 L 50 160 Z" fill="#FFB7B2"/>

                        <!-- Person 2 - Center back -->
                        <circle cx="200" cy="100" r="25" fill="#F4A460"/>
                        <path d="M 175 125 Q 200 140 225 125 L 225 190 L 175 190 Z" fill="#87CEEB"/>

                        <!-- Person 3 - Right -->
                        <circle cx="330" cy="95" r="22" fill="#8B6F47"/>
                        <path d="M 308 117 Q 330 130 352 117 L 352 175 L 308 175 Z" fill="#4A5FDC"/>

                        <!-- Lock Icon for Password Reset -->
                        <circle cx="280" cy="220" r="30" fill="#4A5FDC" opacity="0.2"/>
                        <rect x="265" y="210" width="30" height="35" fill="#4A5FDC" rx="3"/>
                        <circle cx="280" cy="218" r="8" fill="white"/>
                        <rect x="277" y="218" width="6" height="12" fill="white"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right Section with Reset Password Form -->
        <div class="gorent-right-section">
            <div class="gorent-reset-card">
                <div class="gorent-form-header">
                    <h2>Reset Password</h2>
                    <p class="gorent-subtitle">Enter your new password below</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="gorent-form">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div class="gorent-form-group">
                        <label for="email" class="gorent-label">Email Address</label>
                        <input id="email"
                               class="gorent-input"
                               type="email"
                               name="email"
                               value="{{ old('email', $request->email) }}"
                               required
                               autofocus
                               autocomplete="username"
                               placeholder="Enter your email">
                        <x-input-error :messages="$errors->get('email')" class="gorent-error" />
                    </div>

                    <!-- Password -->
                    <div class="gorent-form-group">
                        <label for="password" class="gorent-label">New Password</label>
                        <div class="gorent-password-wrapper">
                            <input id="password"
                                   class="gorent-input gorent-input-password"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="new-password"
                                   placeholder="8+ characters">
                            <button type="button" class="gorent-password-toggle" onclick="togglePassword('password')">
                                <i class="far fa-eye" id="toggleIconPassword"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="gorent-error" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="gorent-form-group">
                        <label for="password_confirmation" class="gorent-label">Confirm Password</label>
                        <div class="gorent-password-wrapper">
                            <input id="password_confirmation"
                                   class="gorent-input gorent-input-password"
                                   type="password"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Confirm your password">
                            <button type="button" class="gorent-password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="far fa-eye" id="toggleIconConfirm"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="gorent-error" />
                    </div>

                    <!-- Reset Button -->
                    <button type="submit" class="gorent-btn-primary">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                </form>
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

        .gorent-reset-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
            margin: -2rem;
        }

        /* Left Section - Illustration */
        .gorent-left-section {
            flex: 1;
            background: linear-gradient(135deg, #FFF5F5 0%, #F0F4FF 100%);
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
        }

        .rent-blue {
            color: #4A5FDC;
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

        /* Right Section - Form */
        .gorent-right-section {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .gorent-reset-card {
            width: 100%;
            max-width: 420px;
        }

        .gorent-form-header {
            margin-bottom: 2.5rem;
        }

        .gorent-form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #1A202C;
            margin-bottom: 0.5rem;
        }

        .gorent-subtitle {
            font-size: 0.95rem;
            color: #718096;
            line-height: 1.6;
        }

        .gorent-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .gorent-form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .gorent-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #2D3748;
        }

        .gorent-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.95rem;
            background: white;
            transition: all 0.2s;
            font-family: inherit;
            color: #2D3748;
        }

        .gorent-input:focus {
            outline: none;
            border-color: #4A5FDC;
            box-shadow: 0 0 0 3px rgba(74, 95, 220, 0.1);
        }

        .gorent-input::placeholder {
            color: #A0AEC0;
        }

        .gorent-password-wrapper {
            position: relative;
        }

        .gorent-input-password {
            padding-right: 3rem;
        }

        .gorent-password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #A0AEC0;
            transition: color 0.2s;
        }

        .gorent-password-toggle:hover {
            color: #4A5FDC;
        }

        .gorent-password-toggle i {
            font-size: 1.1rem;
        }

        .gorent-btn-primary {
            width: 100%;
            padding: 1rem;
            background: #4A5FDC;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .gorent-btn-primary:hover {
            background: #3D4FC7;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 95, 220, 0.3);
        }

        .gorent-btn-primary:active {
            transform: translateY(0);
        }

        .gorent-error {
            color: #E53E3E;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Responsive Design */
        @media screen and (max-width: 968px) {
            body {
                background: linear-gradient(135deg, #FFF5F5 0%, #F0F4FF 100%) !important;
            }

            .gorent-reset-container {
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

            /* Make left section a background overlay on mobile */
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

            .gorent-illustration {
                width: 100% !important;
                height: 100% !important;
                max-width: none !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .gorent-brand-logo {
                display: none !important;
                visibility: hidden !important;
            }

            .illustration-wrapper {
                max-width: 100% !important;
                width: 100% !important;
                height: 100vh !important;
                opacity: 1 !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }

            .illustration-wrapper svg {
                width: 100% !important;
                height: 100% !important;
                max-width: none !important;
                transform: scale(1.5) !important;
            }

            /* Make right section overlay on top - CENTERED AND SMALLER */
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
                max-width: 450px !important;
                width: calc(100% - 2rem) !important;
                box-shadow: 0 10px 80px rgba(0, 0, 0, 0.2) !important;
                border-radius: 20px !important;
                margin: 0 !important;
                flex: none !important;
            }

            .gorent-reset-card {
                padding: 0 !important;
                background: transparent !important;
                max-width: 100% !important;
            }
        }

        @media (max-width: 480px) {
            .gorent-reset-container {
                margin: 0 !important;
                padding: 0.5rem !important;
            }

            .gorent-form-header h2 {
                font-size: 1.75rem;
            }

            .gorent-right-section {
                padding: 1.5rem 1rem !important;
                width: calc(100% - 1rem) !important;
                max-width: 380px !important;
            }

            .gorent-form {
                gap: 1rem !important;
            }
        }
    </style>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = fieldId === 'password'
                ? document.getElementById('toggleIconPassword')
                : document.getElementById('toggleIconConfirm');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</x-guest-layout>
