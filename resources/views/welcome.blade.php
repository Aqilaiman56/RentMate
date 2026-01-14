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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
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
            position: relative;
        }

        .header-search-bar {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-search-input {
            flex: 1;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
            font-family: inherit;
            color: #2D3748;
        }

        .header-search-input:focus {
            outline: none;
            border-color: #4A5FDC;
            box-shadow: 0 0 0 3px rgba(74, 95, 220, 0.1);
        }

        .header-search-input::placeholder {
            color: #A0AEC0;
        }

        .header-search-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: #A0AEC0;
            font-size: 0.875rem;
        }

        .header-search-button {
            padding: 0.625rem 1rem;
            background: #4A5FDC;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .header-search-button:hover {
            background: #3D4FC7;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(74, 95, 220, 0.3);
        }

        .header-search-button:active {
            transform: translateY(0);
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
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            padding: 18px 10px;
            width: 100%;
            max-width: 110px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            text-decoration: none;
            color: #2D3748;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .category-card:hover,
        .category-card.active {
            background: #4461F2;
            color: white;
            border-color: #4461F2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(68, 97, 242, 0.2);
        }

        .category-icon {
            font-size: 26px;
            margin-bottom: 6px;
            color: inherit;
        }

        .category-name {
            font-size: 0.75rem;
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
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
            position: relative;
            text-decoration: none;
            color: inherit;
            display: block;
            border: 1px solid #E2E8F0;
        }

        .item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .item-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #F7FAFC;
        }

        .item-details {
            background: white;
            color: #1f2937;
            padding: 20px;
        }

        .item-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #1f2937;
        }

        .item-location {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .item-price {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
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

        /* Mobile Search Toggle */
        .mobile-search-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0.5rem;
            border-radius: 8px;
            transition: all 0.2s;
            color: #4B5563;
        }

        .mobile-search-toggle:hover {
            background-color: #F3F4F6;
        }

        /* Hide mobile search toggle on desktop and show search container */
        @media (min-width: 769px) {
            .mobile-search-toggle {
                display: none !important;
            }

            /* On desktop, always show the search container regardless of collapsed class */
            .header-search-container,
            .header-search-container.collapsed {
                display: block !important;
            }
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
                flex-wrap: nowrap;
            }

            /* Desktop search container - hide on mobile when collapsed */
            .header-search-container.collapsed {
                display: none;
            }

            /* Show mobile search toggle button */
            .mobile-search-toggle {
                display: block !important;
            }

            /* When expanded, show search bar as overlay */
            .header-search-container.expanded {
                display: block;
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                width: 100%;
                padding: 0 1.25rem;
                z-index: 999;
                background: linear-gradient(180deg, #E8EEFF 0%, #F5F7FF 100%);
                padding-bottom: 1rem;
            }

            .header-search-container.expanded .header-search-bar {
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
                width: 100%;
                animation: slideDown 0.3s ease-out;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .header-search-input {
                flex: 1;
                min-width: 0;
            }

            .header-search-button {
                width: auto;
                flex-shrink: 0;
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
                white-space: nowrap;
            }

            .auth-buttons {
                gap: 8px;
                flex-shrink: 1;
            }

            .auth-buttons .btn {
                padding: 8px 14px;
                font-size: 12px;
                border-radius: 20px;
            }

            .auth-buttons .btn-login {
                border-width: 1.5px;
            }

            .auth-buttons .btn-register {
                border-width: 1.5px;
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
                display: flex;
                overflow-x: auto;
                overflow-y: hidden;
                justify-content: flex-start;
                gap: 12px;
                padding-bottom: 10px;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
            }

            .categories::-webkit-scrollbar {
                height: 6px;
            }

            .categories::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .categories::-webkit-scrollbar-thumb {
                background: #4461F2;
                border-radius: 10px;
            }

            .category-card {
                flex: 0 0 100px;
                scroll-snap-align: start;
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

        /* Extra small screens */
        @media (max-width: 400px) {
            .header {
                padding: 12px 15px;
                gap: 10px;
            }

            .logo {
                font-size: 18px;
            }

            .auth-buttons {
                gap: 6px;
            }

            .auth-buttons .btn {
                padding: 6px 10px;
                font-size: 11px;
            }

            .mobile-search-toggle {
                padding: 0.375rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">
            <span class="logo-go">Go</span><span class="logo-rent">Rent</span><span class="logo-ums">UMS</span>
        </a>

        <!-- Search Bar in Header (Desktop) -->
        <div class="header-search-container collapsed" id="headerSearchContainer">
            <form action="{{ route('welcome') }}" method="GET" class="header-search-bar">
                <span class="header-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input
                    type="text"
                    name="search"
                    class="header-search-input"
                    id="headerSearchInput"
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
                <!-- Mobile Search Toggle Button -->
                <button type="button" class="mobile-search-toggle" id="mobileSearchToggle">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

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

                        @php
                            $firstImage = $item->images->first();
                        @endphp
                        @if($firstImage)
                            <img src="{{ asset('storage/' . $firstImage->ImagePath) }}"
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
            
        </div>
        
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} GoRentUMS. All rights reserved. Made with <i class="fa-solid fa-heart" style="color: #ef4444;"></i> for the community.</p>
        </div>
    </footer>

    <script>
        // Mobile Search Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const searchContainer = document.getElementById('headerSearchContainer');
            const mobileSearchToggle = document.getElementById('mobileSearchToggle');
            const searchInput = document.getElementById('headerSearchInput');

            // Toggle search bar on mobile
            if (mobileSearchToggle) {
                mobileSearchToggle.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Toggle between collapsed and expanded
                    if (searchContainer.classList.contains('expanded')) {
                        searchContainer.classList.remove('expanded');
                        searchContainer.classList.add('collapsed');
                    } else {
                        searchContainer.classList.remove('collapsed');
                        searchContainer.classList.add('expanded');

                        // Focus on search input after animation
                        setTimeout(() => {
                            searchInput.focus();
                        }, 300);
                    }
                });
            }

            // Close search bar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!searchContainer.contains(e.target) && !mobileSearchToggle.contains(e.target)) {
                        searchContainer.classList.remove('expanded');
                        searchContainer.classList.add('collapsed');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    // Desktop mode - remove mobile classes
                    searchContainer.classList.remove('collapsed', 'expanded');
                } else {
                    // Mobile mode - ensure collapsed state
                    if (!searchContainer.classList.contains('expanded')) {
                        searchContainer.classList.add('collapsed');
                    }
                }
            });

            // Initialize on load
            if (window.innerWidth <= 768) {
                searchContainer.classList.add('collapsed');
            } else {
                searchContainer.classList.remove('collapsed', 'expanded');
            }
        });
    </script>
</body>
</html>