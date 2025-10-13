{{-- resources/views/auth/register.blade.php --}}
<x-guest-layout>
    <div class="rentmate-register-container">
        <!-- Left Section with Branding -->
        <div class="rentmate-left-section">
            <div class="rentmate-brand">
                <h1 class="rentmate-logo">RentMate</h1>
                <p class="rentmate-tagline">Join Our Community</p>
                <div class="rentmate-features">
                    <div class="rentmate-feature-item">
                        <div class="rentmate-feature-icon">🎯</div>
                        <div class="rentmate-feature-text">List Your Items</div>
                    </div>
                    <div class="rentmate-feature-item">
                        <div class="rentmate-feature-icon">🌍</div>
                        <div class="rentmate-feature-text">Connect Locally</div>
                    </div>
                    <div class="rentmate-feature-item">
                        <div class="rentmate-feature-icon">✨</div>
                        <div class="rentmate-feature-text">Start Earning</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section with Register Form -->
        <div class="rentmate-right-section">
            <div class="rentmate-register-card">
                <div class="rentmate-form-header">
                    <h2>Create Account</h2>
                    <p>Sign up to get started</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="rentmate-form">
                    @csrf

                    <!-- Name -->
                    <div class="rentmate-form-group">
                        <label for="UserName" class="rentmate-label">Full Name</label>
                        <input id="UserName" 
                               class="rentmate-input" 
                               type="text" 
                               name="name" 
                               value="{{ old('name') }}" 
                               required 
                               autofocus 
                               autocomplete="name"
                               placeholder="Enter your full name">
                        <x-input-error :messages="$errors->get('name')" class="rentmate-error" />
                    </div>

                    <!-- Email Address -->
                    <div class="rentmate-form-group">
                        <label for="Email" class="rentmate-label">Email Address</label>
                        <input id="Email" 
                               class="rentmate-input" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="username"
                               placeholder="Enter your email">
                        <x-input-error :messages="$errors->get('email')" class="rentmate-error" />
                    </div>

                    <!-- Password -->
                    <div class="rentmate-form-group">
                        <label for="password" class="rentmate-label">Password</label>
                        <div class="rentmate-password-wrapper">
                            <input id="password"
                                   class="rentmate-input"
                                   type="password"
                                   name="password"
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Create a password (8+ characters)">
                            <button type="button" class="rentmate-password-toggle" onclick="togglePassword('password')">
                                <span class="rentmate-eye-icon">👁️</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('Password')" class="rentmate-error" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="rentmate-form-group">
                        <label for="password_confirmation" class="rentmate-label">Confirm Password</label>
                        <div class="rentmate-password-wrapper">
                            <input id="password_confirmation"
                                   class="rentmate-input"
                                   type="password"
                                   name="password_confirmation"
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Confirm your password">
                            <button type="button" class="rentmate-password-toggle" onclick="togglePassword('password_confirmation')">
                                <span class="rentmate-eye-icon-confirm">👁️</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('Password_confirmation')" class="rentmate-error" />
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="rentmate-btn-primary">
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="rentmate-login-section">
                    <p>Already have an account? 
                        <a href="{{ route('login') }}" class="rentmate-login-link">Login</a>
                    </p>
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

        .rentmate-register-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
            margin: -2rem;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Left Section - Branding */
        .rentmate-left-section {
            flex: 1;
            background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            color: white;
        }

        .rentmate-brand {
            max-width: 500px;
            text-align: center;
        }

        .rentmate-logo {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .rentmate-tagline {
            font-size: 1.5rem;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 4rem;
        }

        .rentmate-features {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            text-align: left;
        }

        .rentmate-feature-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 1.5rem;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }

        .rentmate-feature-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .rentmate-feature-icon {
            font-size: 2rem;
        }

        .rentmate-feature-text {
            font-size: 1.1rem;
            font-weight: 500;
        }

        /* Right Section - Form */
        .rentmate-right-section {
            flex: 1;
            background: #F5F7FF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow-y: auto;
        }

        .rentmate-register-card {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin: 2rem 0;
        }

        .rentmate-form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .rentmate-form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #1E3A5F;
            margin-bottom: 0.5rem;
        }

        .rentmate-form-header p {
            font-size: 1rem;
            color: #6B7280;
        }

        .rentmate-form {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .rentmate-form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .rentmate-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #374151;
        }

        .rentmate-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            font-size: 1rem;
            background: white;
            transition: all 0.2s;
            font-family: inherit;
        }

        .rentmate-input:focus {
            outline: none;
            border-color: #4461F2;
            box-shadow: 0 0 0 3px rgba(68, 97, 242, 0.1);
        }

        .rentmate-input::placeholder {
            color: #9CA3AF;
        }

        .rentmate-password-wrapper {
            position: relative;
        }

        .rentmate-password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .rentmate-eye-icon,
        .rentmate-eye-icon-confirm {
            font-size: 1.2rem;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .rentmate-password-toggle:hover .rentmate-eye-icon,
        .rentmate-password-toggle:hover .rentmate-eye-icon-confirm {
            opacity: 1;
        }

        .rentmate-btn-primary {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
            margin-top: 0.5rem;
        }

        .rentmate-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(68, 97, 242, 0.3);
        }

        .rentmate-btn-primary:active {
            transform: translateY(0);
        }

        .rentmate-login-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #E5E7EB;
            text-align: center;
        }

        .rentmate-login-section p {
            font-size: 0.95rem;
            color: #6B7280;
        }

        .rentmate-login-link {
            color: #4461F2;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .rentmate-login-link:hover {
            color: #3651E2;
            text-decoration: underline;
        }

        .rentmate-error {
            color: #EF4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .rentmate-register-container {
                flex-direction: column;
            }

            .rentmate-left-section {
                padding: 2rem;
                min-height: auto;
            }

            .rentmate-logo {
                font-size: 2.5rem;
            }

            .rentmate-tagline {
                font-size: 1.2rem;
                margin-bottom: 2rem;
            }

            .rentmate-features {
                flex-direction: row;
                gap: 1rem;
                overflow-x: auto;
            }

            .rentmate-feature-item {
                min-width: 200px;
            }

            .rentmate-right-section {
                padding: 2rem 1rem;
            }

            .rentmate-register-card {
                padding: 2rem;
            }
        }

        @media (max-width: 480px) {
            .rentmate-register-container {
                margin: -1rem;
            }

            .rentmate-register-card {
                padding: 1.5rem;
            }

            .rentmate-form-header h2 {
                font-size: 1.5rem;
            }

            .rentmate-features {
                flex-direction: column;
            }
        }
    </style>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = fieldId === 'password' 
                ? document.querySelector('.rentmate-eye-icon')
                : document.querySelector('.rentmate-eye-icon-confirm');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = '👁️';
            }
        }
    </script>
</x-guest-layout>