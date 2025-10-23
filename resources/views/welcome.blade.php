<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GoRentUMS - Rent Anything, Anytime</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            gap: 30px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .logo-go {
            color: #1e3a8a;
        }

        .logo-rent {
            color: #60a5fa;
        }

        .logo-ums {
            color: #1e3a8a;
        }

        .header-search-container {
            flex: 1;
            max-width: 600px;
        }

        .header-search-bar {
            position: relative;
            display: flex;
            gap: 10px;
        }

        .header-search-input {
            flex: 1;
            padding: 12px 20px 12px 45px;
            border: 2px solid #e5e7eb;
            border-radius: 50px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        .header-search-input:focus {
            outline: none;
            border-color: #4461F2;
            box-shadow: 0 0 0 3px rgba(68, 97, 242, 0.1);
        }

        .header-search-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 16px;
        }

        .header-search-button {
            padding: 12px 24px;
            background: #4461F2;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .header-search-button:hover {
            background: #3651E2;
            transform: translateY(-1px);
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
            flex-shrink: 0;
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
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 15px;
        justify-items: center;
        margin-bottom: 40px;
        }

        .category-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 15px;
            padding: 18px 10px;
            width: 100%;
            max-width: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            text-decoration: none;
            color: #1f2937;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .category-card:hover, 
        .category-card.active {
            background: #4461F2;
            color: white;
            transform: translateY(-3px);
        }

        .category-icon {
            font-size: 26px;
            margin-bottom: 6px;
            color: inherit;
        }

        .category-name {
            font-size: 12px;
            font-weight: 600;
            color: inherit;
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
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .item-card:hover {
            transform: translateY(-5px);
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
            color: #4461F2;
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

        .footer {
            background: white;
            padding: 60px 40px 30px;
            margin-top: 60px;
            border-top: 1px solid #e5e7eb;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1E3A5F;
            margin-bottom: 20px;
        }

        .footer-section p {
            color: #6B7280;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #6B7280;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: #4461F2;
        }

        .footer-bottom {
            max-width: 1400px;
            margin: 0 auto;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #9CA3AF;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
                flex-wrap: wrap;
            }

            .header-search-container {
                order: 3;
                width: 100%;
                margin-top: 15px;
                max-width: 100%;
            }

            .header-search-bar {
                flex-direction: column;
            }

            .header-search-button {
                width: 100%;
            }

            .auth-buttons {
                flex-wrap: wrap;
            }

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

            .container {
                padding: 20px;
            }

            .category-header {
                flex-direction: column;
                gap: 10px;
            }

            .footer {
                padding: 40px 20px 20px;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">
            <span class="logo-go">Go</span><span class="logo-rent">Rent</span><span class="logo-ums">UMS</span>
        </a>
        
        <!-- Search Bar in Header -->
        <div class="header-search-container">
            <form action="{{ route('welcome') }}" method="GET" class="header-search-bar">
                <span class="header-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input 
                    type="text" 
                    name="search" 
                    class="header-search-input" 
                    placeholder="Search items, categories, or locations..."
                    value="{{ request('search') }}"
                >
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit" class="header-search-button">Search</button>
            </form>
        </div>
        
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
                <a href="#items-section" class="btn-cta btn-secondary">Browse Items</a>
            @endauth
        </div>
    </div>

    <div class="container" id="items-section">
        @if($categories->count() > 0)
            <div class="category-header">
                <h2 class="section-title" style="margin-bottom: 0;">Categories</h2>
                @if($selectedCategory || request('search'))
                    <a href="{{ route('welcome') }}" class="clear-filter"><i class="fa-solid fa-xmark"></i> Clear Filter</a>
                @endif
            </div>
            
            <div class="categories">
                @foreach($categories as $category)
                    <a href="{{ route('welcome', ['category' => $category->CategoryID] + request()->only('search')) }}" 
                       class="category-card {{ $selectedCategory && $selectedCategory->CategoryID == $category->CategoryID ? 'active' : '' }}">
                        <div class="category-icon">
                           @switch($category->CategoryName)
                                @case('Gaming') <i class="fa-solid fa-gamepad"></i> @break
                                @case('Music') <i class="fa-solid fa-music"></i> @break
                                @case('Computer') <i class="fa-solid fa-laptop"></i> @break
                                @case('Photography') <i class="fa-solid fa-camera"></i> @break
                                @case('Camera') <i class="fa-solid fa-camera-retro"></i> @break
                                @case('Attire') <i class="fa-solid fa-shirt"></i> @break
                                @case('Books') <i class="fa-solid fa-book"></i> @break
                                @case('Event') <i class="fa-solid fa-calendar-days"></i> @break
                                @case('Sports') <i class="fa-solid fa-futbol"></i> @break
                                @case('Electric') <i class="fa-solid fa-bolt"></i> @break
                                @default <i class="fa-solid fa-box"></i>
                             @endswitch
                        </div>

                        <div class="category-name">{{ $category->CategoryName }}</div>
                    </a>
                @endforeach
            </div>
        @endif

        <h2 class="section-title">
            @if(request('search'))
                Search Results for "{{ request('search') }}"
            @elseif($selectedCategory)
                {{ $selectedCategory->CategoryName }} Items
            @else
                Featured Items
            @endif
        </h2>
        
        @if($featuredItems->count() > 0)
            <div class="items-grid">
                @foreach($featuredItems as $item)
                    <a href="{{ route('welcome.item.details', $item->ItemID) }}" class="item-card">
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
                                <i class="fa-solid fa-location-dot"></i> {{ $item->location->LocationName ?? 'Malaysia' }}
                            </div>
                            <div class="item-price">RM {{ number_format($item->PricePerDay, 2) }} / day</div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="no-items">
                <h3 class="no-items-title">
                    @if(request('search'))
                        No items found for "{{ request('search') }}"
                    @else
                        No Items Available in This Category
                    @endif
                </h3>
                <p class="no-items-text">Try browsing other categories or check back later!</p>
                <a href="{{ route('welcome') }}" class="btn-cta btn-primary" style="margin-top: 20px;">View All Items</a>
            </div>
        @endif

        <div class="features">
            <h2 class="section-title">Why Choose GoRentUMS?</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3 class="feature-title">Safe & Secure</h3>
                    <p class="feature-desc">All transactions are protected with secure payment processing and verified users.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                    <h3 class="feature-title">Earn Extra Income</h3>
                    <p class="feature-desc">List your unused items and start earning money from things you already own.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-earth-asia"></i></div>
                    <h3 class="feature-title">Local Community</h3>
                    <p class="feature-desc">Connect with people in your area and support the sharing economy.</p>
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                    <h3 class="feature-title">Quick & Easy</h3>
                    <p class="feature-desc">Simple booking process with instant confirmation and flexible rental periods.</p>
                </div>
            </div>
        </div>

        <div class="cta-section">
            <h2 class="cta-section-title">Ready to Get Started?</h2>
            <p class="cta-section-text">Join thousands of users who are already renting and lending on GoRentUMS</p>
            @auth
                <a href="{{ route('items.index') }}" class="btn-cta btn-secondary">Browse All Items</a>
            @else
                <a href="{{ route('register') }}" class="btn-cta btn-secondary">Create Free Account</a>
            @endauth
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About GoRentUMS</h3>
                <p>GoRentUMS is your trusted platform for renting and lending items within your community. Join thousands of users who are making the most of the sharing economy.</p>
            </div>
            
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('welcome') }}">Home</a></li>
                    @auth
                        <li><a href="{{ route('user.HomePage') }}">Dashboard</a></li>
                        <li><a href="{{ route('items.create') }}">List an Item</a></li>
                    @else
                        <li><a href="{{ route('register') }}">Sign Up</a></li>
                        <li><a href="{{ route('login') }}">Log In</a></li>
                    @endauth
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Categories</h3>
                <ul class="footer-links">
                    @foreach($categories->take(5) as $category)
                        <li><a href="{{ route('welcome', ['category' => $category->CategoryID]) }}">{{ $category->CategoryName }}</a></li>
                    @endforeach
                </ul>
            </div>
            
            <div class="footer-section">
                <h3>Support</h3>
                <ul class="footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Safety Tips</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} GoRentUMS. All rights reserved. Made with <i class="fa-solid fa-heart" style="color: #ef4444;"></i> for the community.</p>
        </div>
    </footer>
</body>
</html>