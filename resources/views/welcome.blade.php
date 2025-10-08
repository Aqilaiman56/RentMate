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

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .clear-filter {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .clear-filter:hover {
            background: #dc2626;
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
            min-width: 120px;
            height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .category-card:hover {
            background: #4461F2;
            color: white;
            transform: translateY(-2px);
        }

        .category-card.active {
            background: #4461F2;
            color: white;
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
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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

        .item-category-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(68, 97, 242, 0.9);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            z-index: 1;
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

        .no-items {
            text-align: center;
            padding: 60px 20px;
            color: #6B7280;
        }

        .no-items-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .no-items-text {
            font-size: 16px;
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
                justify-content: flex-start;
            }

            .header {
                padding: 15px 20px;
            }

            .container {
                padding: 20px;
            }

            .category-header {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">RentMate</a>
        
        @if (Route::has('login'))
            <div class="auth-buttons">
                @auth
                    <a href="{{ route('user.HomePage') }}" class="btn btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-register">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </header>

    <div class="hero">
        <h1 class="hero-title">Rent Anything, Anytime</h1>
        <p class="hero-subtitle">
            Join our community of renters and lenders. Find what you need or earn money by renting out items you own.
        </p>
        <div class="cta-buttons">
            @auth
                <a href="{{ route('items.index') }}" class="btn-cta btn-primary">Browse Items</a>
                <a href="{{ route('items.create') }}" class="btn-cta btn-secondary">List an Item</a>
            @else
                <a href="{{ route('register') }}" class="btn-cta btn-primary">Get Started</a>
                <a href="{{ route('login') }}" class="btn-cta btn-secondary">Browse Items</a>
            @endauth
        </div>
    </div>

    <div class="container">
        @if($categories->count() > 0)
            <div class="category-header">
                <h2 class="section-title" style="margin-bottom: 0;">Popular Categories</h2>
                @if($selectedCategory)
                    <a href="{{ route('welcome') }}" class="clear-filter">✕ Clear Filter</a>
                @endif
            </div>
            
            <div class="categories">
                @php
                    $categoryIcons = [
                        'Gaming' => '🎮',
                        'Music' => '🎵',
                        'Computer' => '💻',
                        'Camera' => '📷',
                        'Attire' => '👔',
                        'Books' => '📚',
                        'Event' => '🎁',
                        'Sports' => '⚽',
                        'Electric' => '⚡',
                    ];
                @endphp
                
                @foreach($categories as $category)
                    <a href="{{ route('welcome', ['category' => $category->CategoryID]) }}" 
                       class="category-card {{ $selectedCategory && $selectedCategory->CategoryID == $category->CategoryID ? 'active' : '' }}">
                        <div class="category-icon">
                            {{ $categoryIcons[$category->CategoryName] ?? '📦' }}
                        </div>
                        <div class="category-name">{{ $category->CategoryName }}</div>
                    </a>
                @endforeach
            </div>
        @endif

        <h2 class="section-title">
            @if($selectedCategory)
                {{ $selectedCategory->CategoryName }} Items
            @else
                Featured Items
            @endif
        </h2>
        
        @if($featuredItems->count() > 0)
            <div class="items-grid">
                @foreach($featuredItems as $item)
                    <div class="item-card">
                        <span class="item-category-badge">{{ $item->category->CategoryName ?? 'Other' }}</span>
                        
                        @if($item->ImagePath)
                            <img src="{{ asset('storage/' . $item->ImagePath) }}" 
                                 alt="{{ $item->ItemName }}" 
                                 class="item-image">
                        @else
                            <img src="https://via.placeholder.com/300x200/4461F2/fff?text={{ urlencode($item->ItemName) }}" 
                                 class="item-image">
                        @endif
                        
                        <div class="item-details">
                            <div class="item-title">{{ $item->ItemName }}</div>
                            <div class="item-location">
                                📍 {{ $item->location->City ?? 'Malaysia' }}
                            </div>
                            <div class="item-price">RM {{ number_format($item->PricePerDay, 2) }} / day</div>
                        </div>
                        
                        @guest
                            <div class="item-overlay">
                                <div class="item-overlay-text">Sign up to view details</div>
                                <a href="{{ route('register') }}" class="btn btn-register" style="pointer-events: all;">Join Now</a>
                            </div>
                        @else
                            <div class="item-overlay">
                                <div class="item-overlay-text">View Item Details</div>
                                <a href="{{ route('item.details', $item->ItemID) }}" class="btn btn-register" style="pointer-events: all;">View Now</a>
                            </div>
                        @endguest
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-items">
                <h3 class="no-items-title">No Items Available in This Category</h3>
                <p class="no-items-text">Try browsing other categories or check back later!</p>
                <a href="{{ route('welcome') }}" class="btn-cta btn-primary" style="margin-top: 20px;">View All Items</a>
            </div>
        @endif

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
            @auth
                <a href="{{ route('items.index') }}" class="btn-cta btn-secondary">Browse All Items</a>
            @else
                <a href="{{ route('register') }}" class="btn-cta btn-secondary">Create Free Account</a>
            @endauth
        </div>
    </div>
</body>
</html>