@extends('layouts.admin')

@section('title', 'Listing Details - Admin')

@section('content')
<div class="listing-detail-container">
    <!-- Back Button -->
    <div class="back-nav">
        <a href="{{ route('admin.listings') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Listings
        </a>
    </div>

    <!-- Item Header -->
    <div class="item-header-card">
        <div class="item-header-content">
            <div class="item-images-section">
                <div class="main-image">
                    @php
                        $firstImage = $item->images->first();
                    @endphp
                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage->ImagePath) }}" alt="{{ $item->ItemName }}" id="mainImage">
                    @else
                        <div class="no-image-placeholder">
                            <i class="fas fa-image"></i>
                            <p>No Image Available</p>
                        </div>
                    @endif
                </div>
                @if($item->images->count() > 1)
                    <div class="thumbnail-gallery">
                        @foreach($item->images as $image)
                            <img src="{{ asset('storage/' . $image->ImagePath) }}"
                                 alt="{{ $item->ItemName }}"
                                 class="thumbnail"
                                 onclick="changeMainImage('{{ asset('storage/' . $image->ImagePath) }}')">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="item-info-section">
                <div class="item-category-badge">
                    <i class="fas fa-tag"></i> {{ $item->category->CategoryName ?? 'Uncategorized' }}
                </div>

                <h1 class="item-title">{{ $item->ItemName }}</h1>

                <div class="item-status-badges">
                    @if($item->Availability)
                        <span class="status-badge status-active">
                            <i class="fas fa-check-circle"></i> Available
                        </span>
                    @else
                        <span class="status-badge status-inactive">
                            <i class="fas fa-times-circle"></i> Unavailable
                        </span>
                    @endif

                    @if($item->AvailabilityStatus)
                        <span class="status-badge status-{{ strtolower($item->AvailabilityStatus) }}">
                            {{ $item->AvailabilityStatus }}
                        </span>
                    @endif
                </div>

                <div class="item-owner-section">
                    <strong>Owner:</strong>
                    <div class="owner-info">
                        @if($item->user->ProfileImage)
                            <img src="{{ asset('storage/' . $item->user->ProfileImage) }}" alt="{{ $item->user->UserName }}" class="owner-avatar-img">
                        @else
                            <div class="owner-avatar-placeholder">
                                {{ strtoupper(substr($item->user->UserName, 0, 2)) }}
                            </div>
                        @endif
                        <div>
                            <div class="owner-name">{{ $item->user->UserName }}</div>
                            <div class="owner-email">{{ $item->user->Email }}</div>
                        </div>
                    </div>
                </div>

                <div class="item-pricing">
                    <div class="price-item">
                        <span class="price-label">Rental Price:</span>
                        <span class="price-value">RM {{ number_format($item->PricePerDay, 2) }}/day</span>
                    </div>
                    <div class="price-item">
                        <span class="price-label">Deposit Amount:</span>
                        <span class="price-value">RM {{ number_format($item->DepositAmount, 2) }}</span>
                    </div>
                </div>

                @if($averageRating > 0)
                    <div class="item-rating">
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

                <div class="admin-actions">
                    <button class="btn btn-warning" onclick="toggleAvailability({{ $item->ItemID }}, {{ $item->Availability ? 'true' : 'false' }})">
                        <i class="fas fa-{{ $item->Availability ? 'ban' : 'check' }}"></i>
                        {{ $item->Availability ? 'Mark Unavailable' : 'Mark Available' }}
                    </button>
                    <button class="btn btn-danger" onclick="deleteListing({{ $item->ItemID }}, '{{ $item->ItemName }}')">
                        <i class="fas fa-trash"></i> Delete Listing
                    </button>
                    <a href="{{ route('user.public.profile', $item->UserID) }}" class="btn btn-secondary" target="_blank">
                        <i class="fas fa-user"></i> View Owner Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon blue">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalBookings }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon green">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeBookings }}</div>
                <div class="stat-label">Active Bookings</div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon orange">
                <i class="fas fa-check-double"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $completedBookings }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon purple">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($totalRevenue, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>

    <!-- Description Section -->
    <div class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-align-left"></i> Description</h2>
        </div>
        <div class="description-content">
            {{ $item->Description ?? 'No description provided.' }}
        </div>
    </div>

    <!-- Item Details -->
    <div class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-info-circle"></i> Item Details</h2>
        </div>
        <div class="details-grid">
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-tag"></i> Category:</span>
                <span class="detail-value">{{ $item->category->CategoryName ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Location:</span>
                <span class="detail-value">{{ $item->location->LocationName ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar-plus"></i> Date Added:</span>
                <span class="detail-value">{{ $item->DateAdded ? \Carbon\Carbon::parse($item->DateAdded)->format('M d, Y') : 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label"><i class="fas fa-calendar-alt"></i> Last Updated:</span>
                <span class="detail-value">{{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('M d, Y h:i A') : 'N/A' }}</span>
            </div>
        </div>
    </div>

    <!-- Bookings Section -->
    <div class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-calendar-check"></i> Recent Bookings</h2>
        </div>

        @if($item->bookings->count() > 0)
            <div class="bookings-table">
                <table>
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Customer</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Booked On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->bookings->take(10) as $booking)
                            <tr>
                                <td>#{{ $booking->BookingID }}</td>
                                <td>
                                    <div class="customer-info">
                                        @if($booking->user && $booking->user->ProfileImage)
                                            <img src="{{ asset('storage/' . $booking->user->ProfileImage) }}" alt="{{ $booking->user->UserName }}" class="customer-avatar">
                                        @else
                                            <div class="customer-avatar-placeholder">
                                                {{ $booking->user ? strtoupper(substr($booking->user->UserName, 0, 1)) : '?' }}
                                            </div>
                                        @endif
                                        <span>{{ $booking->user->UserName ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td>{{ $booking->StartDate ? \Carbon\Carbon::parse($booking->StartDate)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $booking->EndDate ? \Carbon\Carbon::parse($booking->EndDate)->format('M d, Y') : 'N/A' }}</td>
                                <td>RM {{ number_format($booking->TotalAmount, 2) }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($booking->Status) }}">
                                        {{ $booking->Status }}
                                    </span>
                                </td>
                                <td>{{ $booking->BookingDate ? \Carbon\Carbon::parse($booking->BookingDate)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>No bookings yet</p>
            </div>
        @endif
    </div>

    <!-- Reviews Section -->
    @if($item->reviews->count() > 0)
        <div class="content-section">
            <div class="section-header">
                <h2><i class="fas fa-star"></i> Customer Reviews</h2>
            </div>

            <div class="reviews-list">
                @foreach($item->reviews->take(10) as $review)
                    <div class="review-card">
                        <div class="review-header">
                            <div class="reviewer-info">
                                @if($review->user && $review->user->ProfileImage)
                                    <img src="{{ asset('storage/' . $review->user->ProfileImage) }}" alt="{{ $review->user->UserName }}" class="reviewer-avatar">
                                @else
                                    <div class="reviewer-avatar-placeholder">
                                        {{ $review->user ? strtoupper(substr($review->user->UserName, 0, 1)) : '?' }}
                                    </div>
                                @endif
                                <div>
                                    <div class="reviewer-name">{{ $review->user->UserName ?? 'Anonymous' }}</div>
                                    <div class="review-date">{{ $review->CreatedAt ? \Carbon\Carbon::parse($review->CreatedAt)->diffForHumans() : 'N/A' }}</div>
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

<style>
    .listing-detail-container {
        padding: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .back-nav {
        margin-bottom: 20px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        color: #374151;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .item-header-card {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .item-header-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }

    .item-images-section {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .main-image {
        width: 100%;
        height: 400px;
        border-radius: 12px;
        overflow: hidden;
        background: #f3f4f6;
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .no-image-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
    }

    .no-image-placeholder i {
        font-size: 64px;
        margin-bottom: 12px;
    }

    .thumbnail-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
    }

    .thumbnail {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.2s;
    }

    .thumbnail:hover {
        border-color: #3b82f6;
        transform: scale(1.05);
    }

    .item-category-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #f3f4f6;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .item-title {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 16px;
    }

    .item-status-badges {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .status-active {
        background: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-available {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-rented, .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-approved, .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .item-owner-section {
        margin-bottom: 20px;
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
    }

    .item-owner-section strong {
        display: block;
        margin-bottom: 12px;
        color: #6b7280;
        font-size: 14px;
    }

    .owner-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .owner-avatar-img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
    }

    .owner-avatar-placeholder {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .owner-name {
        font-weight: 600;
        color: #1f2937;
    }

    .owner-email {
        font-size: 13px;
        color: #6b7280;
    }

    .item-pricing {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price-label {
        color: #6b7280;
        font-size: 14px;
    }

    .price-value {
        font-size: 20px;
        font-weight: 700;
        color: #3b82f6;
    }

    .item-rating {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
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

    .admin-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 14px;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #f9fafb;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-box {
        background: white;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .stat-label {
        font-size: 14px;
        color: #6b7280;
    }

    .content-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .section-header {
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .section-header h2 {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-header h2 i {
        color: #3b82f6;
    }

    .description-content {
        color: #4b5563;
        line-height: 1.8;
        font-size: 15px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
    }

    .detail-label {
        color: #6b7280;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-label i {
        color: #3b82f6;
    }

    .detail-value {
        font-weight: 600;
        color: #1f2937;
    }

    .bookings-table {
        overflow-x: auto;
    }

    .bookings-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .bookings-table th {
        text-align: left;
        padding: 12px;
        background: #f9fafb;
        color: #6b7280;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bookings-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        font-size: 14px;
    }

    .customer-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .customer-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
    }

    .customer-avatar-placeholder {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
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

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
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

    @media (max-width: 968px) {
        .item-header-content {
            grid-template-columns: 1fr;
        }

        .admin-actions {
            flex-direction: column;
        }

        .admin-actions .btn {
            width: 100%;
        }
    }
</style>

<script>
    function changeMainImage(src) {
        document.getElementById('mainImage').src = src;
    }

    function toggleAvailability(id, currentStatus) {
        const action = currentStatus ? 'mark as unavailable' : 'mark as available';
        if (confirm(`Are you sure you want to ${action} this listing?`)) {
            alert('Toggle availability functionality - to be implemented');
            // TODO: Implement AJAX call to toggle availability
        }
    }

    function deleteListing(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"?\n\nThis action cannot be undone.`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/listings/${id}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
