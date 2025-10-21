<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $item->ItemName }} - RentMate</title>
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

        .item-details-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #4461F2;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: gap 0.3s;
        }

        .back-button:hover {
            gap: 12px;
        }

        .item-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .item-image-section {
            position: relative;
        }

        .item-main-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .item-info-section {
            display: flex;
            flex-direction: column;
        }

        .item-header {
            margin-bottom: 20px;
        }

        .item-category {
            display: inline-block;
            background: #e8eeff;
            color: #4461F2;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .item-title {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .item-meta {
            display: flex;
            gap: 20px;
            align-items: center;
            color: #6b7280;
            font-size: 14px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .rating-stars {
            display: flex;
            gap: 2px;
            color: #fbbf24;
        }

        .item-owner {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .owner-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .owner-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .owner-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .owner-info p {
            font-size: 13px;
            color: #6b7280;
        }

        .item-description {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 15px;
        }

        .description-text {
            color: #4b5563;
            line-height: 1.6;
        }

        .item-details-list {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6b7280;
            font-size: 14px;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 600;
            font-size: 14px;
        }

        .booking-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 100px;
        }

        .price-display {
            font-size: 36px;
            font-weight: 700;
            color: #4461F2;
            margin-bottom: 8px;
        }

        .price-unit {
            font-size: 16px;
            color: #6b7280;
            font-weight: 400;
        }

        .deposit-info {
            background: #f9fafb;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 13px;
            color: #6b7280;
        }

        .auth-required-box {
            background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            color: white;
            margin-top: 20px;
        }

        .auth-required-box h3 {
            font-size: 20px;
            margin-bottom: 12px;
        }

        .auth-required-box p {
            font-size: 14px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .auth-buttons-vertical {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-auth {
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
        }

        .btn-white {
            background: white;
            color: #4461F2;
            border: none;
        }

        .btn-white:hover {
            background: #f0f4ff;
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .availability-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .badge-available {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-unavailable {
            background: #fee2e2;
            color: #991b1b;
        }

        .reviews-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-top: 40px;
        }

        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .reviews-summary {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 10px;
        }

        .rating-large {
            font-size: 48px;
            font-weight: 700;
            color: #1f2937;
        }

        .rating-breakdown {
            flex: 1;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .bar-label {
            width: 60px;
            font-size: 13px;
            color: #6b7280;
        }

        .bar-container {
            flex: 1;
            height: 8px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: #fbbf24;
            border-radius: 4px;
        }

        .bar-count {
            width: 40px;
            text-align: right;
            font-size: 13px;
            color: #6b7280;
        }

        .review-card {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .review-card:last-child {
            border-bottom: none;
        }

        .review-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
        }

        .reviewer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .reviewer-info h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .review-date {
            font-size: 12px;
            color: #9ca3af;
        }

        .review-rating {
            display: flex;
            gap: 2px;
            color: #fbbf24;
            margin-bottom: 10px;
        }

        .review-text {
            color: #4b5563;
            line-height: 1.6;
            font-size: 14px;
        }

        .no-reviews {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
        }

        @media (max-width: 968px) {
            .item-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .booking-card {
                position: static;
            }

            .item-main-image {
                height: 400px;
            }

            .item-title {
                font-size: 24px;
            }

            .header {
                padding: 15px 20px;
            }

            .item-details-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">RentMate</a>
        
        <div class="auth-buttons">
            <a href="{{ route('login') }}" class="btn btn-login">Log in</a>
            <a href="{{ route('register') }}" class="btn btn-register">Register</a>
        </div>
    </header>

    <div class="item-details-container">
        <a href="{{ route('welcome') }}" class="back-button">
            ← Back to Listings
        </a>

        <div class="item-content">
            <!-- Left Column - Images & Info -->
            <div>
                <div class="item-image-section">
                    @if($item->ImagePath)
                        <img src="{{ asset('storage/' . $item->ImagePath) }}" alt="{{ $item->ItemName }}" class="item-main-image" onerror="this.src='https://via.placeholder.com/600x500/4461F2/fff?text={{ urlencode($item->ItemName) }}'">
                    @else
                        <img src="https://via.placeholder.com/600x500/4461F2/fff?text={{ urlencode($item->ItemName) }}" alt="{{ $item->ItemName }}" class="item-main-image">
                    @endif
                </div>

                <div class="item-description">
                    <h2 class="section-title">Description</h2>
                    <p class="description-text">{{ $item->Description }}</p>
                </div>

                <div class="item-details-list">
                    <h2 class="section-title">Item Details</h2>
                    <div class="detail-row">
                        <span class="detail-label">Category</span>
                        <span class="detail-value">{{ $item->category->CategoryName ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Location</span>
                        <span class="detail-value">📍 {{ $item->location->LocationName ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Deposit Amount</span>
                        <span class="detail-value">RM {{ number_format($item->DepositAmount, 2) }}</span>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">Availability</span>
                        <span class="detail-value">
                            @if($item->Availability && $item->AvailableQuantity > 0)
                                <span class="availability-badge badge-available">
                                    ✓ {{ $item->AvailableQuantity }} {{ $item->AvailableQuantity > 1 ? 'units' : 'unit' }} available
                                </span>
                            @else
                                <span class="availability-badge badge-unavailable">
                                    ✗ Currently unavailable
                                </span>
                            @endif
                        </span>
                      </div>

                    <div class="detail-row">
                        <span class="detail-label">Listed</span>
                        <span class="detail-value">{{ $item->DateAdded ? $item->DateAdded->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Booking -->
            <div>
                <div class="item-header">
                    <span class="item-category">{{ $item->category->CategoryName ?? 'Item' }}</span>
                    <h1 class="item-title">{{ $item->ItemName }}</h1>
                    <div class="item-meta">
                        <div class="meta-item">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($averageRating))
                                        ★
                                    @elseif($i - 0.5 <= $averageRating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <span>{{ number_format($averageRating, 1) }} ({{ $totalReviews }} reviews)</span>
                        </div>
                    </div>
                </div>

                <div class="item-owner">
                    <div class="owner-header">
                        @if($item->user && $item->user->ProfileImage)
                            <img src="{{ asset('storage/' . $item->user->ProfileImage) }}" alt="{{ $item->user->UserName }}" class="owner-avatar">
                        @else
                            <img src="https://via.placeholder.com/50" alt="Owner" class="owner-avatar">
                        @endif
                        <div class="owner-info">
                            <h3>{{ $item->user->UserName ?? 'Unknown' }}</h3>
                            <p>Owner • Member since {{ $item->user && $item->user->created_at ? $item->user->created_at->format('Y') : 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="booking-card">
                    <div class="price-display">
                        RM {{ number_format($item->PricePerDay, 2) }}
                        <span class="price-unit">/ day</span>
                    </div>
                    
                    <div class="deposit-info">
                        💰 Refundable deposit: RM {{ number_format($item->DepositAmount, 2) }}
                    </div>

                    <div class="auth-required-box">
                        <h3>🔒 Sign in to Book</h3>
                        <p>Create an account or log in to rent this item and connect with the owner</p>
                        <div class="auth-buttons-vertical">
                            <a href="{{ route('register') }}" class="btn-auth btn-white">Create Account</a>
                            <a href="{{ route('login') }}" class="btn-auth btn-outline">Log In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="reviews-section">
            <div class="reviews-header">
                <h2 class="section-title" style="margin: 0;">Reviews ({{ $totalReviews }})</h2>
            </div>

            @if($totalReviews > 0)
                <div class="reviews-summary">
                    <div class="rating-large">{{ number_format($averageRating, 1) }}</div>
                    <div class="rating-breakdown">
                        @foreach($ratingDistribution as $stars => $count)
                            <div class="rating-bar">
                                <span class="bar-label">{{ $stars }} stars</span>
                                <div class="bar-container">
                                    <div class="bar-fill" style="width: {{ $totalReviews > 0 ? ($count / $totalReviews * 100) : 0 }}%"></div>
                                </div>
                                <span class="bar-count">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                @foreach($item->reviews as $review)
                    <div class="review-card">
                        <div class="review-header">
                            @if($review->user && $review->user->ProfileImage)
                                <img src="{{ asset('storage/' . $review->user->ProfileImage) }}" alt="{{ $review->user->UserName }}" class="reviewer-avatar">
                            @else
                                <img src="https://via.placeholder.com/40" alt="User" class="reviewer-avatar">
                            @endif
                            <div class="reviewer-info">
                                <h4>{{ $review->user->UserName ?? 'Anonymous' }}</h4>
                                <span class="review-date">{{ $review->DatePosted ? $review->DatePosted->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="review-rating">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= $review->Rating ? '★' : '☆' }}
                            @endfor
                        </div>
                        <p class="review-text">{{ $review->Comment }}</p>
                    </div>
                @endforeach
            @else
                <div class="no-reviews">
                    <p>No reviews yet. Be the first to review this item!</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>