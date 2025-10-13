{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    <div class="rentmate-login-container">
        <!-- Left Section with Branding -->
        <div class="rentmate-left-section">
            <div class="rentmate-brand">
                <h1 class="rentmate-logo">RentMate</h1>
                <p class="rentmate-tagline">Rent Anything, Anytime</p>
                <div class="rentmate-features">
                    <div class="rentmate-feature-item">
                        <div class="rentmate-feature-icon">🔒</div>
                        <div class="rentmate-feature-text">Safe & Secure</div>
                    </div>
                    <div class="rentmate-feature-item">
                        <div class="rentmate-feature-icon">💰</div>
                        <div class="rentmate-feature-text">Earn Extra Income</div>
                    </div>
                    <div class="rentmate-feature-item">
                        <div class="rentmate-feature-icon">⚡</div>
                        <div class="rentmate-feature-text">Quick & Easy</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section with Login Form -->
        <div class="rentmate-right-section">
            <div class="rentmate-login-card">
                <div class="rentmate-form-header">
                    <h2>Welcome Back</h2>
                    <p>Login to your account</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="rentmate-form">
                    @csrf

                    <!-- Email Address -->
                    <div class="rentmate-form-group">
                        <label for="email" class="rentmate-label">Email Address</label>
                        <input id="email" 
                               class="rentmate-input" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
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
                                   autocomplete="current-password"
                                   placeholder="Enter your password">
                            <button type="button" class="rentmate-password-toggle" onclick="togglePassword()">
                                <span class="rentmate-eye-icon">👁️</span>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="rentmate-error" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="rentmate-form-options">
                        <label class="rentmate-remember">
                            <input type="checkbox" name="remember" class="rentmate-checkbox">
                            <span>Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="rentmate-forgot">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="rentmate-btn-primary">
                        Login
                    </button>
                </form>

                <!-- Create Account -->
                <div class="rentmate-signup-section">
                    <p>Don't have an account? 
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rentmate-signup-link">Create Account</a>
                        @endif
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

        .rentmate-login-container {
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
        }

        .rentmate-login-card {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
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
            gap: 1.5rem;
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

        .rentmate-eye-icon {
            font-size: 1.2rem;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .rentmate-password-toggle:hover .rentmate-eye-icon {
            opacity: 1;
        }

        .rentmate-form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rentmate-remember {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #374151;
            cursor: pointer;
        }

        .rentmate-checkbox {
            width: 1rem;
            height: 1rem;
            cursor: pointer;
            accent-color: #4461F2;
        }

        .rentmate-forgot {
            font-size: 0.875rem;
            color: #4461F2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .rentmate-forgot:hover {
            color: #3651E2;
            text-decoration: underline;
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

        .rentmate-signup-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #E5E7EB;
            text-align: center;
        }

        .rentmate-signup-section p {
            font-size: 0.95rem;
            color: #6B7280;
        }

        .rentmate-signup-link {
            color: #4461F2;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .rentmate-signup-link:hover {
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
            .rentmate-login-container {
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

            .rentmate-login-card {
                padding: 2rem;
            }
        }

        @media (max-width: 480px) {
            .rentmate-login-container {
                margin: -1rem;
            }

            .rentmate-login-card {
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
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.querySelector('.rentmate-eye-icon');
            
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