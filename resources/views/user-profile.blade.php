@extends('layouts.app')

@section('title', $user->UserName . ' - Profile')

@section('content')
<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-cover"></div>
        <div class="profile-info-wrapper">
            <div class="profile-avatar-section">
                @if($user->ProfileImage)
                    <img src="{{ Storage::url($user->ProfileImage) }}" alt="{{ $user->UserName }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">
                        {{ strtoupper(substr($user->UserName, 0, 2)) }}
                    </div>
                @endif
            </div>

            <div class="profile-details">
                <h1 class="profile-name">{{ $user->UserName }}</h1>
                <div class="profile-meta">
                    @if($user->Location)
                        <span class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $user->Location }}
                        </span>
                    @endif
                    <span class="meta-item">
                        <i class="fas fa-calendar"></i>
                        Member since {{ $user->CreatedAt ? \Carbon\Carbon::parse($user->CreatedAt)->format('M Y') : 'N/A' }}
                    </span>
                    @if($user->UserType)
                        <span class="meta-item">
                            <i class="fas fa-user-tag"></i>
                            {{ $user->UserType }}
                        </span>
                    @endif
                </div>

                <!-- Rating Display -->
                @if($totalReviews > 0)
                    <div class="profile-rating">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating))
                                    <i class="fas fa-star filled"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fas fa-star-half-alt filled"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="rating-text">{{ number_format($averageRating, 1) }} ({{ $totalReviews }} reviews)</span>
                    </div>
                @endif
            </div>

            @auth
                @if(auth()->id() !== $user->UserID)
                    <div class="profile-actions">
                        <a href="{{ route('messages.show', $user->UserID) }}" class="btn btn-primary">
                            <i class="fas fa-envelope"></i> Message
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalListings }}</div>
                <div class="stat-label">Total Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeListings }}</div>
                <div class="stat-label">Active Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $completedBookings }}</div>
                <div class="stat-label">Completed Rentals</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ number_format($averageRating, 1) }}</div>
                <div class="stat-label">Average Rating</div>
            </div>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="profile-content">
        <!-- Active Listings -->
        <div class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-box"></i> Active Listings</h2>
                @if($totalListings > 6)
                    <a href="{{ route('items.index') }}?user={{ $user->UserID }}" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                @endif
            </div>

            @if($user->items->count() > 0)
                <div class="listings-grid">
                    @foreach($user->items as $item)
                        <div class="listing-card">
                            <div class="listing-image">
                                @if($item->ImagePath)
                                    <img src="{{ Storage::url($item->ImagePath) }}" alt="{{ $item->ItemName }}">
                                @else
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <span class="availability-badge available">
                                    {{ $item->AvailabilityStatus }}
                                </span>
                            </div>
                            <div class="listing-info">
                                <h3 class="listing-title">{{ $item->ItemName }}</h3>
                                <p class="listing-description">{{ Str::limit($item->Description, 200) }}</p>
                                <div class="listing-meta">
                                    <span class="price">RM {{ number_format($item->RentalPrice, 2) }}/day</span>
                                    <span class="category">{{ $item->Category }}</span>
                                </div>
                                <a href="{{ route('item.details', $item->ItemID) }}" class="btn btn-sm btn-outline">
                                    View Details <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>No active listings yet</p>
                </div>
            @endif
        </div>

        <!-- Recent Reviews -->
        @if($recentReviews->count() > 0)
            <div class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-star"></i> Recent Reviews</h2>
                </div>

                <div class="reviews-list">
                    @foreach($recentReviews as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    @if($review->user && $review->user->ProfileImage)
                                        <img src="{{ Storage::url($review->user->ProfileImage) }}" alt="{{ $review->user->UserName }}" class="reviewer-avatar">
                                    @else
                                        <div class="reviewer-avatar-placeholder">
                                            {{ $review->user ? strtoupper(substr($review->user->UserName, 0, 1)) : '?' }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="reviewer-name">{{ $review->user ? $review->user->UserName : 'Anonymous' }}</div>
                                        <div class="review-date">{{ \Carbon\Carbon::parse($review->CreatedAt)->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->Rating)
                                            <i class="fas fa-star filled"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            @if($review->Comment)
                                <p class="review-comment">{{ $review->Comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .profile-header {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 32px;
    }

    .profile-cover {
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .profile-info-wrapper {
        padding: 0 32px 32px;
        position: relative;
    }

    .profile-avatar-section {
        margin-top: -60px;
        margin-bottom: 20px;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .profile-details {
        flex: 1;
    }

    .profile-name {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
    }

    .profile-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 16px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 14px;
    }

    .meta-item i {
        color: #3b82f6;
    }

    .profile-rating {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 12px;
    }

    .stars {
        display: flex;
        gap: 4px;
    }

    .stars i {
        color: #d1d5db;
        font-size: 18px;
    }

    .stars i.filled {
        color: #fbbf24;
    }

    .rating-text {
        font-size: 16px;
        font-weight: 600;
        color: #374151;
    }

    .profile-actions {
        position: absolute;
        top: 20px;
        right: 32px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-icon.orange { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
    .stat-icon.purple { background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 14px;
        color: #6b7280;
    }

    .profile-content {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .content-section {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .section-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-header h2 i {
        color: #3b82f6;
    }

    .view-all {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: gap 0.2s;
    }

    .view-all:hover {
        gap: 10px;
    }

    .listings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
    }

    .listing-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .listing-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .listing-image {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f3f4f6;
    }

    .listing-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #d1d5db;
    }

    .availability-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .availability-badge.available {
        background: #10b981;
        color: white;
    }

    .listing-info {
        padding: 20px;
    }

    .listing-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .listing-description {
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 16px;
        line-height: 1.6;
    }

    .listing-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .price {
        font-size: 20px;
        font-weight: 700;
        color: #3b82f6;
    }

    .category {
        background: #f3f4f6;
        color: #6b7280;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 14px;
    }

    .btn-outline {
        background: white;
        color: #3b82f6;
        border: 2px solid #3b82f6;
    }

    .btn-outline:hover {
        background: #3b82f6;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 64px;
        opacity: 0.3;
        margin-bottom: 16px;
    }

    .empty-state p {
        font-size: 16px;
    }

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .review-card {
        padding: 20px;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .reviewer-info {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    .reviewer-avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .reviewer-name {
        font-weight: 600;
        color: #1f2937;
        font-size: 14px;
    }

    .review-date {
        font-size: 12px;
        color: #9ca3af;
    }

    .review-rating {
        display: flex;
        gap: 2px;
    }

    .review-rating i {
        color: #d1d5db;
        font-size: 14px;
    }

    .review-rating i.filled {
        color: #fbbf24;
    }

    .review-comment {
        color: #4b5563;
        line-height: 1.6;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 12px;
        }

        .profile-info-wrapper {
            padding: 0 20px 24px;
        }

        .profile-name {
            font-size: 24px;
        }

        .profile-actions {
            position: static;
            margin-top: 16px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .listings-grid {
            grid-template-columns: 1fr;
        }

        .content-section {
            padding: 20px;
        }
    }
</style>
@endsection
