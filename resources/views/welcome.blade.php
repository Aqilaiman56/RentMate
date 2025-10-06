<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RentMate - Rent Anything, Anytime</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #E8EEFF 0%, #F5F7FF 100%);
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #4461F2;
            text-decoration: none;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 28px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }

        .btn-login {
            background: transparent;
            color: #4461F2;
            border: 2px solid #4461F2;
        }

        .btn-login:hover {
            background: #4461F2;
            color: white;
        }

        .btn-register {
            background: #4461F2;
            color: white;
            border: 2px solid #4461F2;
        }

        .btn-register:hover {
            background: #3651E2;
            border-color: #3651E2;
        }

        .hero {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 40px 40px;
            text-align: center;
        }

        .hero-title {
            font-size: 48px;
            font-weight: 700;
            color: #1E3A5F;
            margin-bottom: 20px;
        }

        .hero-subtitle {
            font-size: 18px;
            color: #6B7280;
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 60px;
        }

        .btn-cta {
            padding: 16px 40px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #4461F2;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #3651E2;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(68, 97, 242, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #4461F2;
            border: 2px solid #4461F2;
        }

        .btn-secondary:hover {
            background: #f8f9ff;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 40px;
        }

        .section-title {
            font-size: 32px;
            font-weight: 700;
            color: #1E3A5F;
            text-align: center;
            margin-bottom: 40px;
        }

        .categories {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            padding: 20px 0;
            margin-bottom: 30px;
        }

        .category-card {
            background: white;
            border: 3px solid #4461F2;
            border-radius: 15px;
            padding: 20px;
            width: 120px;
            height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .category-card:hover {
            background: #4461F2;
            color: white;
            transform: translateY(-2px);
        }

        .category-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .category-name {
            font-size: 13px;
            font-weight: 600;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .item-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
            position: relative;
        }

        .item-card:hover {
            transform: translateY(-5px);
        }

        .item-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(30, 58, 95, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .item-card:hover::after {
            opacity: 1;
        }

        .item-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }

        .item-card:hover .item-overlay {
            opacity: 1;
        }

        .item-overlay-text {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .item-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .item-details {
            background: #1E3A5F;
            color: white;
            padding: 20px;
        }

        .item-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .item-location {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #9CA3AF;
            margin-bottom: 12px;
        }

        .item-price {
            font-size: 18px;
            font-weight: 700;
        }

        .features {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            margin: 40px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-top: 40px;
        }

        .feature-item {
            text-align: center;
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .feature-title {
            font-size: 20px;
            font-weight: 600;
            color: #1E3A5F;
            margin-bottom: 10px;
        }

        .feature-desc {
            font-size: 14px;
            color: #6B7280;
            line-height: 1.6;
        }

        .cta-section {
            text-align: center;
            padding: 60px 40px;
            background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
            border-radius: 20px;
            color: white;
            margin: 40px 0;
        }

        .cta-section-title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .cta-section-text {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 32px;
            }

            .hero-subtitle {
                font-size: 16px;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .section-title {
                font-size: 24px;
            }

            .categories {
                overflow-x: scroll;
            }

            .header {
                padding: 15px 20px;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">RentMate</a>
        
        @if (Route::has('login'))
            <div class="auth-buttons">
                <a href="{{ route('login') }}" class="btn btn-login">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn btn-register">Register</a>
                @endif
            </div>
        @endif
    </header>

    <div class="hero">
        <h1 class="hero-title">Rent Anything, Anytime</h1>
        <p class="hero-subtitle">
            Join our community of renters and lenders. Find what you need or earn money by renting out items you own.
        </p>
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn-cta btn-primary">Get Started</a>
            <a href="{{ route('login') }}" class="btn-cta btn-secondary">Browse Items</a>
        </div>
    </div>

    <div class="container">
        <h2 class="section-title">Popular Categories</h2>
        
        <div class="categories">
            <div class="category-card">
                <div class="category-icon">🎮</div>
                <div class="category-name">Gaming</div>
            </div>
            <div class="category-card">
                <div class="category-icon">🎵</div>
                <div class="category-name">Music</div>
            </div>
            <div class="category-card">
                <div class="category-icon">💻</div>
                <div class="category-name">Computer</div>
            </div>
            <div class="category-card">
                <div class="category-icon">📷</div>
                <div class="category-name">Photography</div>
            </div>
            <div class="category-card">
                <div class="category-icon">👔</div>
                <div class="category-name">Attire</div>
            </div>
            <div class="category-card">
                <div class="category-icon">📚</div>
                <div class="category-name">Books</div>
            </div>
            <div class="category-card">
                <div class="category-icon">🎁</div>
                <div class="category-name">Events</div>
            </div>
            <div class="category-card">
                <div class="category-icon">⚽</div>
                <div class="category-name">Sport</div>
            </div>
            <div class="category-card">
                <div class="category-icon">⚡</div>
                <div class="category-name">Electric</div>
            </div>
        </div>

        <h2 class="section-title">Featured Items</h2>
        
        <div class="items-grid">
            <div class="item-card">
                <img src="https://via.placeholder.com/300x200/4461F2/fff?text=Gaming+Console" class="item-image">
                <div class="item-details">
                    <div class="item-title">PlayStation 5</div>
                    <div class="item-location">📍 Kuala Lumpur</div>
                    <div class="item-price">RM 50.00 / day</div>
                </div>
                <div class="item-overlay">
                    <div class="item-overlay-text">Sign up to view details</div>
                    <a href="{{ route('register') }}" class="btn btn-register" style="pointer-events: all;">Join Now</a>
                </div>
            </div>

            <div class="item-card">
                <img src="https://via.placeholder.com/300x200/764ba2/fff?text=DSLR+Camera" class="item-image">
                <div class="item-details">
                    <div class="item-title">Canon EOS R6</div>
                    <div class="item-location">📍 Penang</div>
                    <div class="item-price">RM 120.00 / day</div>
                </div>
                <div class="item-overlay">
                    <div class="item-overlay-text">Sign up to view details</div>
                    <a href="{{ route('register') }}" class="btn btn-register" style="pointer-events: all;">Join Now</a>
                </div>
            </div>

            <div class="item-card">
                <img src="https://via.placeholder.com/300x200/667eea/fff?text=MacBook+Pro" class="item-image">
                <div class="item-details">
                    <div class="item-title">MacBook Pro M2</div>
                    <div class="item-location">📍 Johor Bahru</div>
                    <div class="item-price">RM 80.00 / day</div>
                </div>
                <div class="item-overlay">
                    <div class="item-overlay-text">Sign up to view details</div>
                    <a href="{{ route('register') }}" class="btn btn-register" style="pointer-events: all;">Join Now</a>
                </div>
            </div>

            <div class="item-card">
                <img src="https://via.placeholder.com/300x200/f093fb/fff?text=Guitar" class="item-image">
                <div class="item-details">
                    <div class="item-title">Fender Stratocaster</div>
                    <div class="item-location">📍 Selangor</div>
                    <div class="item-price">RM 35.00 / day</div>
                </div>
                <div class="item-overlay">
                    <div class="item-overlay-text">Sign up to view details</div>
                    <a href="{{ route('register') }}" class="btn btn-register" style="pointer-events: all;">Join Now</a>
                </div>
            </div>
        </div>

        <div class="features">
            <h2 class="section-title">Why Choose RentMate?</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">🔒</div>
                    <h3 class="feature-title">Safe & Secure</h3>
                    <p class="feature-desc">All transactions are protected with secure payment processing and verified users.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">💰</div>
                    <h3 class="feature-title">Earn Extra Income</h3>
                    <p class="feature-desc">List your unused items and start earning money from things you already own.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🌍</div>
                    <h3 class="feature-title">Local Community</h3>
                    <p class="feature-desc">Connect with people in your area and support the sharing economy.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">⚡</div>
                    <h3 class="feature-title">Quick & Easy</h3>
                    <p class="feature-desc">Simple booking process with instant confirmation and flexible rental periods.</p>
                </div>
            </div>
        </div>

        <div class="cta-section">
            <h2 class="cta-section-title">Ready to Get Started?</h2>
            <p class="cta-section-text">Join thousands of users who are already renting and lending on RentMate</p>
            <a href="{{ route('register') }}" class="btn-cta btn-secondary">Create Free Account</a>
        </div>
    </div>
</body>
</html>