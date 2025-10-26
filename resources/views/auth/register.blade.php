{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <div class="gorent-register-container">
        <!-- Left Section with Illustration -->
        <div class="gorent-left-section">
            <div class="gorent-illustration">
                <div class="gorent-brand-logo">
                    <h1>GO<span class="rent-blue">Rent</span>UMS</h1>
                    <p class="gorent-subtitle">Join Our Community</p>
                </div>
                <!-- Illustration SVG -->
                <div class="illustration-wrapper">
                    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background shapes -->
                        <rect x="30" y="50" width="90" height="110" fill="#B5EAD7" rx="4"/>
                        <rect x="130" y="40" width="70" height="100" fill="#C7CEEA" rx="4"/>
                        <rect x="210" y="60" width="80" height="95" fill="#FFB7B2" rx="4"/>
                        <rect x="300" y="45" width="75" height="105" fill="#FFDAC1" rx="4"/>
                        
                        <!-- Bottom shapes -->
                        <rect x="40" y="290" width="85" height="75" fill="#FF9AA2" rx="4"/>
                        <rect x="135" y="305" width="75" height="65" fill="#E2F0CB" rx="4"/>
                        <rect x="220" y="295" width="80" height="70" fill="#FFD3E0" rx="4"/>
                        <rect x="310" y="300" width="70" height="70" fill="#C7CEEA" rx="4"/>
                        
                        <!-- People illustrations -->
                        <!-- Person 1 - Left top -->
                        <circle cx="75" cy="95" r="22" fill="#8B6F47"/>
                        <path d="M 53 117 Q 75 130 97 117 L 97 170 L 53 170 Z" fill="#4A5FDC"/>
                        
                        <!-- Person 2 - Center -->
                        <circle cx="210" cy="105" r="28" fill="#D4A5A5"/>
                        <path d="M 182 133 Q 210 150 238 133 L 238 200 L 182 200 Z" fill="#87CEEB"/>
                        
                        <!-- Person 3 - Right -->
                        <circle cx="340" cy="100" r="24" fill="#5B4A8C"/>
                        <path d="M 316 124 Q 340 138 364 124 L 364 185 L 316 185 Z" fill="#FFB7B2"/>
                        
                        <!-- Person 4 - Bottom left -->
                        <circle cx="80" cy="325" r="20" fill="#F4A460"/>
                        <path d="M 60 345 Q 80 358 100 345 L 100 390 L 60 390 Z" fill="#B5EAD7"/>
                        
                        <!-- Person 5 - Bottom center -->
                        <circle cx="170" cy="335" r="22" fill="#B8A8D6"/>
                        <path d="M 148 357 Q 170 372 192 357 L 192 400 L 148 400 Z" fill="#4A5FDC"/>
                        
                        <!-- Person 6 - Bottom right -->
                        <circle cx="260" cy="330" r="19" fill="#8B6F47"/>
                        <path d="M 241 349 Q 260 360 279 349 L 279 390 L 241 390 Z" fill="#9DC8E8"/>
                        
                        <!-- Items/Devices -->
                        <rect x="145" y="190" width="35" height="55" fill="#FFD700" rx="5"/>
                        <rect x="150" y="195" width="25" height="35" fill="#FFA500" rx="2"/>
                        
                        <circle cx="290" cy="230" r="22" fill="#4A5FDC" opacity="0.7"/>
                        <circle cx="290" cy="230" r="17" fill="#87CEEB" opacity="0.8"/>
                        
                        <!-- Connection lines -->
                        <line x1="75" y1="117" x2="210" y2="133" stroke="#E5E7EB" stroke-width="2" stroke-dasharray="5,5" opacity="0.5"/>
                        <line x1="210" y1="133" x2="340" y2="124" stroke="#E5E7EB" stroke-width="2" stroke-dasharray="5,5" opacity="0.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right Section with Register Form -->
        <div class="gorent-right-section">
            <div class="gorent-register-card">
                <div class="gorent-form-header">
                    <h2>Create Account</h2>
                    <p>Sign up to get started</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="gorent-form">
                    @csrf

                    <!-- Name -->
                    <div class="gorent-form-group">
                        <label for="UserName" class="gorent-label">Full Name</label>
                        <input id="UserName" 
                               class="gorent-input" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               placeholder="Enter your full name">
                        <x-input-error :messages="$errors->get('name')" class="gorent-error" />
                    </div>

                    <!-- Email Address -->
                    <div class="gorent-form-group">
                        <label for="Email" class="gorent-label">Email Address</label>
                        <input id="Email" 
                               class="gorent-input" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username"
                               placeholder="Enter your email">
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

                    <!-- Terms Notice -->
                    <div class="gorent-terms-notice">
                        By creating an account you agree to <a href="#" class="gorent-link">terms and conditions</a> of aloha.
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="gorent-btn-primary">
                        Create Account
                    </button>

                    <!-- Login Link -->
                    <div class="gorent-login-section">
                        <a href="{{ route('login') }}" class="gorent-login-link">Already have an account? Login</a>
                    </div>
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

        .gorent-register-container {
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

        /* Right Section - Form */
        .gorent-right-section {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow-y: auto;
        }

        .gorent-register-card {
            width: 100%;
            max-width: 420px;
            padding: 2rem 0;
        }

        .gorent-form-header {
            margin-bottom: 2rem;
        }

        .gorent-form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #1A202C;
            margin-bottom: 0.5rem;
        }

        .gorent-form-header p {
            font-size: 0.95rem;
            color: #718096;
        }

        .gorent-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
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

        .gorent-terms-notice {
            font-size: 0.875rem;
            color: #718096;
            line-height: 1.5;
            margin-top: -0.25rem;
        }

        .gorent-link {
            color: #4A5FDC;
            text-decoration: none;
            font-weight: 500;
        }

        .gorent-link:hover {
            text-decoration: underline;
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

        .gorent-login-section {
            margin-top: 0.5rem;
            text-align: center;
        }

        .gorent-login-link {
            color: #4A5FDC;
            font-weight: 600;
            text-decoration: underline;
            font-size: 0.95rem;
            transition: color 0.2s;
        }

        .gorent-login-link:hover {
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

            .gorent-register-container {
                flex-direction: column !important;
                position: relative !important;
                overflow-x: hidden !important;
                overflow-y: auto !important;
                min-height: 100vh !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 2rem 1rem !important;
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
                max-height: 95vh !important;
                overflow-y: auto !important;
                max-width: 450px !important;
                width: calc(100% - 2rem) !important;
                box-shadow: 0 10px 80px rgba(0, 0, 0, 0.2) !important;
                border-radius: 20px !important;
                margin: 0 !important;
                flex: none !important;
            }

            .gorent-register-card {
                padding: 0 !important;
                background: transparent !important;
                max-width: 100% !important;
            }
        }

        @media (max-width: 480px) {
            .gorent-register-container {
                margin: -1rem;
            }

            .gorent-form-header h2 {
                font-size: 1.75rem;
            }

            .gorent-right-section {
                padding: 1.5rem 1rem;
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