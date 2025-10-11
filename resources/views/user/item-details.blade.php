{{-- resources/views/users/item-details.blade.php --}}
@extends('layouts.app')

@section('title', $item->ItemName . ' - RentMate')

@push('styles')
<style>
    .item-details-container {
        max-width: 1200px;
        margin: 0 auto;
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

    .wishlist-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: white;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .wishlist-btn:hover {
        transform: scale(1.1);
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

    .contact-owner-btn {
        width: 100%;
        background: #e8eeff;
        color: #4461F2;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .contact-owner-btn:hover {
        background: #d0ddff;
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

    .booking-form {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #4461F2;
    }

    .total-calculation {
        background: #f9fafb;
        padding: 15px;
        border-radius: 10px;
        margin: 15px 0;
    }

    .calc-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
        color: #6b7280;
    }

    .calc-row.total {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        padding-top: 8px;
        border-top: 2px solid #e5e7eb;
        margin-top: 8px;
    }

    .book-now-btn {
        width: 100%;
        background: #4461F2;
        color: white;
        border: none;
        padding: 16px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .book-now-btn:hover {
        background: #3651E2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
    }

    .book-now-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
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
    }
</style>
@endpush

@section('content')
<div class="item-details-container">
    <a href="{{ route('user.HomePage') }}" class="back-button">
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
                
                <button class="wishlist-btn" onclick="toggleWishlist({{ $item->ItemID }})">
                    ♡
                </button>
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
                        <span class="availability-badge {{ $item->Availability ? 'badge-available' : 'badge-unavailable' }}">
                            {{ $item->Availability ? '✓ Available' : '✗ Unavailable' }}
                        </span>
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
                    @if($item->user->ProfileImage)
                        <img src="{{ asset('storage/' . $item->user->ProfileImage) }}" alt="{{ $item->user->UserName }}" class="owner-avatar">
                    @else
                        <img src="https://via.placeholder.com/50" alt="{{ $item->user->UserName }}" class="owner-avatar">
                    @endif
                    <div class="owner-info">
                        <h3>{{ $item->user->UserName ?? 'Unknown' }}</h3>
                        <p>Owner • Member since {{ $item->user && $item->user->created_at ? $item->user->created_at->format('Y') : 'N/A' }}</p>
                    </div>
                </div>
                    @if(auth()->id() !== $item->UserID)
                        <button class="contact-owner-btn" onclick="window.location.href='{{ route('messages.show', $item->user->UserID) }}'">
                            💬 Contact Owner
                        </button>
                    @else
                        <button class="contact-owner-btn" disabled style="background: #e5e7eb; color: #9ca3af; cursor: not-allowed;">
                            💬 This is Your Item
                        </button>
                    @endif
            </div>

            <div class="booking-card">
                <div class="price-display">
                    RM {{ number_format($item->PricePerDay, 2) }}
                    <span class="price-unit">/ day</span>
                </div>
                
                <div class="deposit-info">
                    💰 Refundable deposit: RM {{ number_format($item->DepositAmount, 2) }}
                </div>

                @if($item->Availability)
                    <form action="{{ route('bookings.store') }}" method="POST" class="booking-form" id="bookingForm">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->ItemID }}">
                        
                        <div class="form-group">
                            <label class="form-label" for="start_date">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-input" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-input" min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="total-calculation" id="totalCalculation" style="display: none;">
                            <div class="calc-row">
                                <span>RM {{ number_format($item->PricePerDay, 2) }} × <span id="numDays">0</span> days</span>
                                <span id="rentalTotal">RM 0.00</span>
                            </div>
                            <div class="calc-row">
                                <span>Deposit</span>
                                <span>RM {{ number_format($item->DepositAmount, 2) }}</span>
                            </div>
                            <div class="calc-row total">
                                <span>Total</span>
                                <span id="grandTotal">RM {{ number_format($item->DepositAmount, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="book-now-btn">Book Now</button>
                    </form>
                @else
                    <div style="text-align: center; padding: 20px; color: #9ca3af;">
                        <p>This item is currently unavailable</p>
                    </div>
                @endif
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
@endsection

@push('scripts')
<script>
    const pricePerDay = {{ $item->PricePerDay }};
    const depositAmount = {{ $item->DepositAmount }};
    
    document.getElementById('start_date').addEventListener('change', calculateTotal);
    document.getElementById('end_date').addEventListener('change', calculateTotal);

    function calculateTotal() {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);

        if (startDate && endDate && endDate > startDate) {
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            const rentalTotal = pricePerDay * days;
            const grandTotal = rentalTotal + depositAmount;

            document.getElementById('numDays').textContent = days;
            document.getElementById('rentalTotal').textContent = 'RM ' + rentalTotal.toFixed(2);
            document.getElementById('grandTotal').textContent = 'RM ' + grandTotal.toFixed(2);
            document.getElementById('totalCalculation').style.display = 'block';
        } else {
            document.getElementById('totalCalculation').style.display = 'none';
        }
    }

    function toggleWishlist(itemId) {
        fetch(`/wishlist/toggle/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            const heartBtn = document.querySelector('.wishlist-btn');
            if(data.added) {
                heartBtn.textContent = '♥';
                heartBtn.style.color = '#FF6B6B';
            } else {
                heartBtn.textContent = '♡';
                heartBtn.style.color = '#000';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Please login to add items to wishlist');
        });
    }
</script>
@endpush