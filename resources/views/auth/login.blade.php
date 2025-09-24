{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    <div class="rentmate-login-container">
        <!-- Left Section with Illustration -->
        <div class="rentmate-left-section">
            <div class="rentmate-illustration">
                <!-- Brand Logo -->
                <div class="rentmate-brand-logo">
                    <h1>Rent<span>Mate</span></h1>
                </div>

                <!-- Abstract shapes and characters -->
                <div class="rentmate-shape rentmate-shape-1"></div>
                <div class="rentmate-shape rentmate-shape-2"></div>
                <div class="rentmate-shape rentmate-shape-3"></div>
                <div class="rentmate-shape rentmate-shape-4"></div>
                <div class="rentmate-shape rentmate-shape-5"></div>
                <div class="rentmate-shape rentmate-shape-6"></div>
                
                <div class="rentmate-character rentmate-character-1"></div>
                <div class="rentmate-character rentmate-character-2"></div>
            </div>
        </div>

        <!-- Right Section with Login Form -->
        <div class="rentmate-right-section">
            <div class="rentmate-login-form">
                <div class="rentmate-form-header">
                    <h2>Login Account</h2>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="rentmate-form-group">
                        <x-input-label for="email" :value="__('Username')" class="rentmate-label" />
                        <x-text-input id="email" 
                                    class="rentmate-form-input" 
                                    type="email" 
                                    name="email" 
                                    :value="old('email')" 
                                    required 
                                    autofocus 
                                    autocomplete="username"
                                    placeholder="Username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="rentmate-form-group">
                        <x-input-label for="password" :value="__('Password')" class="rentmate-label" />
                        <div class="rentmate-password-container">
                            <x-text-input id="password"
                                        class="rentmate-form-input"
                                        type="password"
                                        name="password"
                                        required 
                                        autocomplete="current-password"
                                        placeholder="8+ characters" />
                            <button type="button" class="rentmate-password-toggle" onclick="togglePassword()">
                                👁️
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="rentmate-remember-section">
                        <label for="remember_me" class="rentmate-remember-label">
                            <input id="remember_me" type="checkbox" class="rentmate-checkbox" name="remember">
                            <span class="rentmate-remember-text">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <!-- Terms Text -->
                    <div class="rentmate-terms-text">
                        By signing up you agree to <a href="#" class="rentmate-terms-link">terms and conditions</a> at zoho.
                    </div>

                    <!-- Login Button and Forgot Password -->
                    <div class="rentmate-form-actions">
                        <x-primary-button class="rentmate-login-btn">
                            {{ __('Login') }}
                        </x-primary-button>

                        @if (Route::has('password.request'))
                            <div class="rentmate-forgot-password">
                                <a class="rentmate-forgot-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </form>

                <div class="rentmate-create-account">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="rentmate-create-link">Create Account</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Override guest layout default styles */
        .rentmate-login-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
            margin: -2rem;
            background: #f8f9fa;
        }

        /* Left Section */
        .rentmate-left-section {
            flex: 1;
            background: linear-gradient(135deg, #f5f0eb 0%, #e8ddd4 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .rentmate-illustration {
            position: relative;
            width: 100%;
            max-width: 500px;
            height: 100%;
            max-height: 600px;
        }

        .rentmate-brand-logo {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }

        .rentmate-brand-logo h1 {
            font-size: 3rem;
            font-weight: 800;
            color: #2d5aa0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            margin: 0;
        }

        .rentmate-brand-logo h1 span {
            color: #1a1a1a;
        }

        /* Abstract shapes */
        .rentmate-shape {
            position: absolute;
            border-radius: 20px;
        }

        .rentmate-shape-1 {
            width: 120px;
            height: 80px;
            background: #4a90e2;
            top: 10%;
            left: 5%;
            transform: rotate(-15deg);
        }

        .rentmate-shape-2 {
            width: 100px;
            height: 100px;
            background: #ff8a80;
            top: 15%;
            right: 10%;
            border-radius: 50%;
        }

        .rentmate-shape-3 {
            width: 80px;
            height: 120px;
            background: #81c784;
            bottom: 20%;
            left: 10%;
            transform: rotate(25deg);
        }

        .rentmate-shape-4 {
            width: 90px;
            height: 90px;
            background: #ffb74d;
            bottom: 10%;
            right: 15%;
            border-radius: 50%;
        }

        .rentmate-shape-5 {
            width: 60px;
            height: 100px;
            background: #ba68c8;
            top: 30%;
            left: 25%;
            transform: rotate(-30deg);
        }

        .rentmate-shape-6 {
            width: 110px;
            height: 70px;
            background: #4db6ac;
            top: 45%;
            right: 20%;
            transform: rotate(15deg);
        }

        .rentmate-character {
            position: absolute;
            width: 80px;
            height: 120px;
            border-radius: 40px 40px 10px 10px;
        }

        .rentmate-character-1 {
            bottom: 25%;
            left: 30%;
            background: linear-gradient(180deg, #fbbf24 0%, #f59e0b 100%);
        }

        .rentmate-character-2 {
            bottom: 30%;
            right: 25%;
            background: linear-gradient(180deg, #60a5fa 0%, #3b82f6 100%);
        }

        /* Right Section */
        .rentmate-right-section {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .rentmate-login-form {
            width: 100%;
            max-width: 400px;
        }

        .rentmate-form-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .rentmate-form-header h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0;
        }

        /* Form Styles */
        .rentmate-form-group {
            margin-bottom: 1.5rem;
        }

        .rentmate-label {
            display: block !important;
            font-size: 0.95rem !important;
            font-weight: 500 !important;
            color: #374151 !important;
            margin-bottom: 0.5rem !important;
        }

        .rentmate-form-input {
            width: 100% !important;
            padding: 0.875rem 1rem !important;
            border: 2px solid #e5e7eb !important;
            border-radius: 12px !important;
            font-size: 1rem !important;
            background: #f9fafb !important;
            transition: all 0.2s ease !important;
        }

        .rentmate-form-input:focus {
            outline: none !important;
            border-color: #3b82f6 !important;
            background: white !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .rentmate-password-container {
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
            color: #9ca3af;
            font-size: 1.1rem;
        }

        .rentmate-remember-section {
            margin: 1rem 0;
        }

        .rentmate-remember-label {
            display: flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }

        .rentmate-checkbox {
            width: auto !important;
            margin: 0 !important;
        }

        .rentmate-remember-text {
            font-size: 0.875rem;
            color: #6b7280;
        }

        .rentmate-terms-text {
            font-size: 0.875rem;
            color: #6b7280;
            text-align: center;
            margin: 1.5rem 0;
        }

        .rentmate-terms-link {
            color: #3b82f6;
            text-decoration: underline;
        }

        .rentmate-form-actions {
            margin-bottom: 1.5rem;
        }

        .rentmate-login-btn {
            width: 100% !important;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
            color: white !important;
            border: none !important;
            padding: 1rem !important;
            border-radius: 12px !important;
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            justify-content: center !important;
            margin-bottom: 1rem !important;
            transition: all 0.2s ease !important;
        }

        .rentmate-login-btn:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3) !important;
        }

        .rentmate-forgot-password {
            text-align: center;
        }

        .rentmate-forgot-link {
            color: #6b7280 !important;
            text-decoration: underline !important;
            font-size: 0.875rem !important;
        }

        .rentmate-forgot-link:hover {
            color: #3b82f6 !important;
        }

        .rentmate-create-account {
            text-align: center;
        }

        .rentmate-create-link {
            color: #6b7280 !important;
            text-decoration: underline !important;
            font-weight: 500 !important;
        }

        .rentmate-create-link:hover {
            color: #3b82f6 !important;
        }

        /* Animations */
        @keyframes float {
            0% { transform: translateY(0px) rotate(var(--rotation, 0deg)); }
            100% { transform: translateY(-10px) rotate(var(--rotation, 0deg)); }
        }

        .rentmate-shape, .rentmate-character {
            animation: float 3s ease-in-out infinite alternate;
        }

        .rentmate-shape-1 { animation-delay: 0s; }
        .rentmate-shape-2 { animation-delay: 0.2s; }
        .rentmate-shape-3 { animation-delay: 0.4s; }
        .rentmate-shape-4 { animation-delay: 0.6s; }
        .rentmate-shape-5 { animation-delay: 0.8s; }
        .rentmate-shape-6 { animation-delay: 1s; }
        .rentmate-character-1 { animation-delay: 1.2s; }
        .rentmate-character-2 { animation-delay: 1.4s; }

        /* Responsive Design */
        @media (max-width: 768px) {
            .rentmate-login-container {
                flex-direction: column;
                margin: -1rem;
            }

            .rentmate-left-section {
                min-height: 200px;
                flex: none;
            }

            .rentmate-brand-logo h1 {
                font-size: 2rem;
            }

            .rentmate-right-section {
                flex: none;
                min-height: calc(100vh - 200px);
                padding: 1rem;
            }

            .rentmate-shape, .rentmate-character {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .rentmate-form-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.rentmate-password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }
    </script>
</x-guest-layout>