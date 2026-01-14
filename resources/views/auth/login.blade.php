{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <div class="gorent-login-container">
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
                        
                        <!-- Person 4 - Bottom left -->
                        <circle cx="90" cy="320" r="18" fill="#D4A5A5"/>
                        <path d="M 72 338 Q 90 348 108 338 L 108 380 L 72 380 Z" fill="#9DC8E8"/>
                        
                        <!-- Person 5 - Bottom center -->
                        <circle cx="180" cy="330" r="20" fill="#B8A8D6"/>
                        <path d="M 160 350 Q 180 362 200 350 L 200 400 L 160 400 Z" fill="#5B4A8C"/>
                        
                        <!-- Devices/Items -->
                        <rect x="140" y="180" width="40" height="60" fill="#4A5FDC" rx="6"/>
                        <rect x="145" y="185" width="30" height="40" fill="#87CEEB" rx="2"/>
                        
                        <circle cx="280" cy="220" r="25" fill="#FFD700" opacity="0.8"/>
                        <circle cx="280" cy="220" r="20" fill="#FFA500" opacity="0.6"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right Section with Login Form -->
        <div class="gorent-right-section">
            <div class="gorent-login-card">
                <div class="gorent-form-header">
                    <h2>Login Account</h2>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}{{ isset($itemId) && $itemId ? '?item=' . $itemId : '' }}" class="gorent-form" id="loginForm">
                    @csrf

                    <!-- Email -->
                    <div class="gorent-form-group">
                        <label for="email" class="gorent-label">Email</label>
                        <input id="email" 
                               class="gorent-input" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="Email"
                               placeholder="Email Address">
                        <x-input-error :messages="$errors->get('email')" class="gorent-error" />
                    </div>

                    <!-- Password -->
                    <div class="gorent-form-group">
                        <label for="password" class="gorent-label">Password</label>
                        <div class="gorent-password-wrapper">
                            <input id="password"
                                   class="gorent-input gorent-input-password"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="8+ characters">
                            <button type="button" class="gorent-password-toggle" onclick="togglePassword()">
                                <i class="far fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="gorent-error" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="gorent-remember-forgot">
                        <label class="gorent-checkbox-label">
                            <input type="checkbox" name="remember" id="remember_me" class="gorent-checkbox">
                            <span class="gorent-checkbox-text">Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="gorent-forgot-link">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="gorent-btn-primary">
                        Login
                    </button>

                    <!-- Create Account -->
                    <div class="gorent-signup-section">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}{{ isset($itemId) && $itemId ? '?item=' . $itemId : '' }}" class="gorent-signup-link">Create Account</a>
                        @endif
                    </div>
                </form>

                @if(isset($itemId) && $itemId)
                    <div style="margin-top: 1rem; padding: 1rem; background: #EBF8FF; border-left: 4px solid #4A5FDC; border-radius: 8px;">
                        <p style="font-size: 0.9rem; color: #2C5282;">
                            <i class="fas fa-info-circle"></i> After login, you'll be redirected to complete your booking.
                        </p>
                    </div>
                @endif
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

        .gorent-login-container {
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

        .gorent-login-card {
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

        .gorent-remember-forgot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: -0.5rem;
        }

        .gorent-checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            user-select: none;
        }

        .gorent-forgot-link {
            color: #4A5FDC;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .gorent-forgot-link:hover {
            color: #3D4FC7;
            text-decoration: underline;
        }

        .gorent-checkbox {
            width: 18px;
            height: 18px;
            border: 2px solid #CBD5E0;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            accent-color: #4A5FDC;
        }

        .gorent-checkbox:hover {
            border-color: #4A5FDC;
        }

        .gorent-checkbox:checked {
            background-color: #4A5FDC;
            border-color: #4A5FDC;
        }

        .gorent-checkbox-text {
            font-size: 0.9rem;
            color: #2D3748;
            font-weight: 500;
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
        }

        .gorent-btn-primary:hover {
            background: #3D4FC7;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(74, 95, 220, 0.3);
        }

        .gorent-btn-primary:active {
            transform: translateY(0);
        }

        .gorent-signup-section {
            margin-top: 0.5rem;
            text-align: center;
        }

        .gorent-signup-link {
            color: #4A5FDC;
            font-weight: 600;
            text-decoration: underline;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .gorent-signup-link:hover {
            color: #3D4FC7;
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

            .gorent-login-container {
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

            .gorent-login-card {
                padding: 0 !important;
                background: transparent !important;
                max-width: 100% !important;
            }
        }

        @media (max-width: 480px) {
            .gorent-login-container {
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
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
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