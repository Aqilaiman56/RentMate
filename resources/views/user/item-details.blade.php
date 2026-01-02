{{-- resources/views/users/item-details.blade.php --}}
@extends('layouts.app')

@section('title', $item->ItemName . ' - GoRentUMS')

@push('styles')
<style>
    .item-details-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--color-primary);
        text-decoration: none;
        font-weight: var(--font-semibold);
        margin-bottom: 1.5rem;
        transition: var(--transition-base);
        font-size: var(--text-sm);
    }

    .back-button:hover {
        gap: 0.75rem;
        color: var(--color-primary-hover);
    }

    .item-content {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 2.5rem;
        margin-bottom: 2.5rem;
    }

    .item-image-section {
        position: relative;
    }

    .item-main-image {
        width: 100%;
        height: 550px;
        object-fit: cover;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-xl);
    }

    /* Image Grid Layouts */
    .images-grid {
        display: grid;
        gap: 0.75rem;
        border-radius: var(--radius-xl);
        overflow: hidden;
        box-shadow: var(--shadow-xl);
        position: relative;
    }

    .images-grid.count-1 {
        grid-template-columns: 1fr;
    }

    .images-grid.count-2 {
        grid-template-columns: 1fr 1fr;
    }

    .images-grid.count-3 {
        grid-template-columns: 2fr 1fr;
        grid-template-rows: 1fr 1fr;
    }

    .images-grid.count-3 .grid-image:first-child {
        grid-row: 1 / 3;
    }

    .images-grid.count-4 {
        grid-template-columns: 2fr 1fr;
        grid-template-rows: repeat(3, 1fr);
    }

    .images-grid.count-4 .grid-image:first-child {
        grid-row: 1 / 4;
    }

    .grid-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .grid-image:hover {
        transform: scale(1.02);
    }

    .images-grid.count-1 .grid-image {
        height: 550px;
    }

    .images-grid.count-2 .grid-image {
        height: 550px;
    }

    .images-grid.count-3 .grid-image {
        height: 268px;
    }

    .images-grid.count-3 .grid-image:first-child {
        height: 550px;
    }

    .images-grid.count-4 .grid-image {
        height: 176px;
    }

    .images-grid.count-4 .grid-image:first-child {
        height: 550px;
    }

    .wishlist-btn {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        background: var(--color-white);
        border: none;
        width: 50px;
        height: 50px;
        border-radius: var(--radius-full);
        font-size: var(--text-2xl);
        cursor: pointer;
        box-shadow: var(--shadow-md);
        transition: var(--transition-base);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .wishlist-btn:hover {
        transform: scale(1.08);
    }

    .item-header {
        margin-bottom: 1.5rem;
    }

    .item-category {
        display: inline-block;
        background: var(--color-primary-lighter);
        color: var(--color-primary);
        padding: 0.5rem 1.25rem;
        border-radius: var(--radius-full);
        font-size: var(--text-xs);
        font-weight: var(--font-semibold);
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .item-title {
        font-size: 2rem;
        font-weight: var(--font-bold);
        color: var(--color-gray-900);
        margin-bottom: 1rem;
        line-height: var(--line-height-tight);
    }

    .item-meta {
        display: flex;
        gap: 1.25rem;
        align-items: center;
        color: var(--color-gray-600);
        font-size: var(--text-sm);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .rating-stars {
        display: flex;
        gap: 0.125rem;
        color: #fbbf24;
    }

    .item-owner {
        background: var(--color-white);
        padding: 1.5rem;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        margin-top: 2rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--color-gray-200);
        transition: var(--transition-base);
    }

    .item-owner:hover {
        box-shadow: var(--shadow-lg);
    }

    .owner-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .owner-avatar {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-full);
        object-fit: cover;
        border: 2px solid var(--color-gray-200);
    }

    .owner-info h3 {
        font-size: var(--text-base);
        font-weight: var(--font-semibold);
        color: var(--color-gray-900);
        margin-bottom: 0.25rem;
    }

    .owner-info p {
        font-size: var(--text-sm);
        color: var(--color-gray-500);
    }

    .contact-owner-btn {
        width: 100%;
        background: var(--color-primary-lighter);
        color: var(--color-primary);
        border: 2px solid var(--color-primary-light);
        padding: 0.875rem 1rem;
        border-radius: var(--radius-lg);
        font-weight: var(--font-semibold);
        cursor: pointer;
        transition: var(--transition-base);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: var(--text-sm);
    }

    .contact-owner-btn:hover {
        background: var(--color-primary);
        color: var(--color-white);
        border-color: var(--color-primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .contact-owner-btn:active {
        transform: translateY(0);
    }

    .item-description {
        background: var(--color-white);
        padding: 1.75rem;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        margin-bottom: 1.5rem;
        border: 1px solid var(--color-gray-200);
        transition: var(--transition-base);
    }

    .item-description:hover {
        box-shadow: var(--shadow-lg);
    }

    .description-text {
        color: var(--color-gray-700);
        line-height: var(--line-height-relaxed);
        font-size: var(--text-base);
    }

    .description-text.collapsed {
        max-height: 150px;
        overflow: hidden;
        position: relative;
    }

    .description-text.collapsed::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50px;
        background: linear-gradient(to bottom, transparent, white);
    }

    .read-more-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--color-primary);
        background: none;
        border: none;
        font-weight: var(--font-semibold);
        cursor: pointer;
        padding: 0.5rem 0;
        margin-top: 0.75rem;
        font-size: var(--text-sm);
        transition: var(--transition-base);
    }

    .read-more-btn:hover {
        color: var(--color-primary-hover);
        gap: 0.75rem;
    }

    .read-more-btn i {
        transition: transform 0.3s;
    }

    .read-more-btn.expanded i {
        transform: rotate(180deg);
    }

    .item-details-list {
        background: var(--color-white);
        padding: 1.75rem;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        margin-bottom: 1.5rem;
        border: 1px solid var(--color-gray-200);
        transition: var(--transition-base);
    }

    .item-details-list:hover {
        box-shadow: var(--shadow-lg);
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid var(--color-gray-200);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: var(--color-gray-600);
        font-size: var(--text-sm);
        font-weight: var(--font-medium);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-value {
        color: var(--color-gray-900);
        font-weight: var(--font-semibold);
        font-size: var(--text-sm);
    }

    .booking-card {
        background: var(--color-white);
        padding: 2rem;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-xl);
        position: sticky;
        top: 100px;
        border: 2px solid var(--color-gray-200);
    }

    .price-display {
        font-size: 2.5rem;
        font-weight: var(--font-bold);
        color: var(--color-gray-900);
        margin-bottom: 0.5rem;
        line-height: 1;
    }

    .price-unit {
        font-size: var(--text-lg);
        color: var(--color-gray-500);
        font-weight: var(--font-normal);
    }

    .deposit-info {
        background: var(--color-info-light);
        padding: 1rem;
        border-radius: var(--radius-lg);
        margin: 1.25rem 0;
        font-size: var(--text-sm);
        color: var(--color-info-hover);
        border-left: 4px solid var(--color-info);
    }

    .booking-form {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        display: block;
        font-size: var(--text-sm);
        font-weight: var(--font-semibold);
        color: var(--color-gray-900);
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 1.5px solid var(--color-gray-300);
        border-radius: var(--radius-lg);
        font-size: var(--text-sm);
        transition: var(--transition-base);
        background: var(--color-white);
        font-family: inherit;
        color: var(--color-gray-900);
    }

    .form-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(68, 97, 242, 0.1);
    }

    .form-input:disabled,
    .form-input:read-only {
        background: var(--color-gray-50);
        color: var(--color-gray-600);
        cursor: not-allowed;
    }

    .total-calculation {
        background: var(--color-gray-50);
        padding: 1.25rem;
        border-radius: var(--radius-lg);
        margin: 1.25rem 0;
        border: 1px solid var(--color-gray-200);
    }

    .calc-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        font-size: var(--text-sm);
        color: var(--color-gray-700);
    }

    .calc-row:last-child {
        margin-bottom: 0;
    }

    .calc-row.total {
        font-size: var(--text-lg);
        font-weight: var(--font-bold);
        color: var(--color-gray-900);
        padding-top: 0.75rem;
        border-top: 2px solid var(--color-gray-300);
        margin-top: 0.75rem;
    }

    .book-now-btn {
        width: 100%;
        background: var(--color-primary);
        color: var(--color-white);
        border: none;
        padding: 1.125rem 1.5rem;
        border-radius: var(--radius-lg);
        font-size: var(--text-base);
        font-weight: var(--font-semibold);
        cursor: pointer;
        transition: var(--transition-base);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .book-now-btn:hover:not(:disabled) {
        background: var(--color-primary-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .book-now-btn:active {
        transform: translateY(0);
    }

    .book-now-btn:disabled {
        background: var(--color-gray-300);
        cursor: not-allowed;
        transform: none;
        opacity: 0.6;
    }

    .reviews-section {
        background: var(--color-white);
        padding: 2rem;
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-md);
        margin-top: 3rem;
        border: 1px solid var(--color-gray-200);
    }

    .reviews-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .reviews-summary {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: var(--color-gray-50);
        border-radius: var(--radius-xl);
        border: 1px solid var(--color-gray-200);
    }

    .rating-large {
        font-size: 3.5rem;
        font-weight: var(--font-bold);
        color: var(--color-gray-900);
        line-height: 1;
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
        font-size: 0.875rem;
        color: #718096;
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
        font-size: 0.875rem;
        color: #718096;
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
        font-size: 0.875rem;
        font-weight: 600;
        color: #1A202C;
        margin-bottom: 4px;
    }

    .review-date {
        font-size: 0.75rem;
        color: #A0AEC0;
    }

    .review-rating {
        display: flex;
        gap: 2px;
        color: #fbbf24;
        margin-bottom: 10px;
    }

    .review-text {
        color: #4A5568;
        line-height: 1.6;
        font-size: 0.875rem;
        margin-bottom: 12px;
    }

    .review-image-container {
        margin-top: 12px;
    }

    .review-image {
        max-width: 300px;
        width: 100%;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        border: 2px solid #e5e7eb;
    }

    .review-image:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .no-reviews {
        text-align: center;
        padding: 40px;
        color: #A0AEC0;
    }

    /* Image Modal */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        animation: fadeIn 0.3s;
    }

    .image-modal.show {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .image-modal-content {
        max-width: 90%;
        max-height: 90%;
        animation: zoomIn 0.3s;
    }

    @keyframes zoomIn {
        from { transform: scale(0.5); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .close-image-modal {
        position: absolute;
        top: 20px;
        right: 35px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-image-modal:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .availability-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--radius-full);
        font-size: var(--text-xs);
        font-weight: var(--font-semibold);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-available {
        background: var(--color-success-light);
        color: var(--color-success-hover);
        border: 1px solid var(--color-success);
    }

    .badge-unavailable {
        background: var(--color-danger-light);
        color: var(--color-danger-hover);
        border: 1px solid var(--color-danger);
    }

    /* Calendar Styles */
    .availability-calendar {
        margin-bottom: 1.5rem;
        padding: 1.5rem;
        background: var(--color-gray-50);
        border-radius: var(--radius-xl);
        border: 1px solid var(--color-gray-200);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .calendar-nav-btn {
        background: var(--color-white);
        border: 1px solid var(--color-gray-300);
        color: var(--color-primary);
        width: 36px;
        height: 36px;
        border-radius: var(--radius-lg);
        cursor: pointer;
        font-size: var(--text-sm);
        transition: var(--transition-base);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .calendar-nav-btn:hover {
        background: var(--color-primary);
        color: var(--color-white);
        border-color: var(--color-primary);
        transform: scale(1.05);
    }

    .calendar-month-year {
        font-weight: var(--font-semibold);
        color: var(--color-gray-900);
        font-size: var(--text-lg);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 4px;
    }

    .calendar-day-header {
        text-align: center;
        font-size: 0.75rem;
        font-weight: 600;
        color: #718096;
        padding: 8px 4px;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--text-sm);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        font-weight: var(--font-medium);
        background: var(--color-white);
        border: 1px solid transparent;
    }

    .calendar-day:not(.empty):not(.past):not(.unavailable):hover {
        background: var(--color-primary-lighter);
        transform: scale(1.1);
        border-color: var(--color-primary);
        box-shadow: var(--shadow-sm);
    }

    .calendar-day.empty {
        cursor: default;
        background: transparent;
    }

    .calendar-day.past {
        color: var(--color-gray-300);
        cursor: not-allowed;
        background: var(--color-gray-50);
    }

    .calendar-day.today {
        font-weight: var(--font-bold);
        border: 2px solid var(--color-primary);
        box-shadow: var(--shadow-sm);
    }

    .calendar-day.available {
        background: var(--color-success-light);
        color: var(--color-success-hover);
        font-weight: var(--font-semibold);
    }

    .calendar-day.unavailable {
        background: var(--color-danger-light);
        color: var(--color-danger-hover);
        cursor: not-allowed;
        font-weight: var(--font-semibold);
        text-decoration: line-through;
    }

    .calendar-day.selected {
        background: var(--color-primary);
        color: var(--color-white);
        font-weight: var(--font-bold);
        box-shadow: var(--shadow-md);
        transform: scale(1.05);
    }

    .calendar-day.in-range {
        background: var(--color-primary-light);
        color: var(--color-primary);
        border-color: var(--color-primary-light);
    }

    .calendar-legend {
        display: flex;
        gap: 1.25rem;
        justify-content: center;
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--color-gray-300);
        font-size: var(--text-xs);
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: var(--font-medium);
        color: var(--color-gray-700);
    }

    .legend-box {
        width: 18px;
        height: 18px;
        border-radius: var(--radius-md);
        border: 1px solid var(--color-gray-300);
    }

    @media (max-width: 968px) {
        .item-details-container {
            padding: 0 1rem;
        }

        .item-content {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .booking-card {
            position: static;
            margin-top: 2rem;
        }

        .item-main-image,
        .images-grid.count-1 .grid-image,
        .images-grid.count-2 .grid-image,
        .images-grid.count-3 .grid-image:first-child,
        .images-grid.count-4 .grid-image:first-child {
            height: 400px;
        }

        .item-title {
            font-size: var(--text-3xl);
        }

        .price-display {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="item-details-container">
    <a href="{{ route('user.HomePage') }}" class="back-button">
        <i class="fa-solid fa-arrow-left"></i> Back to Listings
    </a>

    <div class="item-content">
        <!-- Left Column - Images & Reviews -->
        <div>
            <div class="item-image-section">
                @if($item->images->count() > 0)
                    <div class="images-grid count-{{ $item->images->count() }}">
                        @foreach($item->images as $image)
                            <img src="{{ asset('storage/' . $image->ImagePath) }}"
                                 alt="{{ $item->ItemName }}"
                                 class="grid-image"
                                 onerror="this.src='https://via.placeholder.com/600x500/4461F2/fff?text={{ urlencode($item->ItemName) }}'">
                        @endforeach
                    </div>
                @else
                    <img src="https://via.placeholder.com/600x500/4461F2/fff?text={{ urlencode($item->ItemName) }}"
                         alt="{{ $item->ItemName }}"
                         class="item-main-image">
                @endif

                @if(auth()->id() !== $item->UserID)
                    <button class="wishlist-btn" onclick="toggleWishlist({{ $item->ItemID }})">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                @endif
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
                        <button class="contact-owner-btn" onclick="window.location.href='{{ route('messages.show', ['userId' => $item->user->UserID, 'item_id' => $item->ItemID]) }}'">
                            <i class="fa-solid fa-message"></i> Contact Owner
                        </button>
                    @else
                        <button class="contact-owner-btn" disabled style="background: #e5e7eb; color: #9ca3af; cursor: not-allowed;">
                            <i class="fa-solid fa-message"></i> This is Your Item
                        </button>
                    @endif
            </div>

            <div class="item-details-list">
                <h2 class="section-title" style="margin-bottom: 1.25rem;">Item Details</h2>
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-tag"></i>
                        Category
                    </span>
                    <span class="detail-value">{{ $item->category->CategoryName ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-location-dot"></i>
                        Location
                    </span>
                    <span class="detail-value">{{ $item->location->LocationName ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-shield-halved"></i>
                        Deposit Amount
                    </span>
                    <span class="detail-value">RM {{ number_format($item->DepositAmount, 2) }}</span>
                </div>
                 <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-calendar-check"></i>
                        Availability
                    </span>
                    <span class="detail-value">
                        @if($item->Availability)
                            <span class="availability-badge badge-available">
                                <i class="fa-solid fa-check"></i> Available
                            </span>
                        @else
                            <span class="availability-badge badge-unavailable">
                                <i class="fa-solid fa-xmark"></i> Not Available
                            </span>
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        Total Quantity
                    </span>
                    <span class="detail-value">{{ $item->Quantity }} {{ $item->Quantity > 1 ? 'units' : 'unit' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-clock"></i>
                        Listed
                    </span>
                    <span class="detail-value">{{ $item->DateAdded ? $item->DateAdded->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>

            <div class="item-description">
                <h2 class="section-title" style="margin-bottom: 1rem;">
                    <i class="fa-solid fa-align-left" style="font-size: 1.25rem; color: var(--color-primary);"></i>
                    Description
                </h2>
                <div class="description-text" id="descriptionText">{!! nl2br(e($item->Description)) !!}</div>
                <button type="button" class="read-more-btn" id="readMoreBtn" style="display: none;" onclick="toggleDescription()">
                    <span id="readMoreText">Read more</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
            </div>


        </div>

        <!-- Right Column - Booking & Item Info -->
        <div>
            <div class="item-header">
                <span class="item-category">{{ $item->category->CategoryName ?? 'Item' }}</span>
                <h1 class="item-title">{{ $item->ItemName }}</h1>
                <div class="item-meta">
                    <div class="meta-item">
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating))
                                    <i class="fa-solid fa-star"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span>{{ number_format($averageRating, 1) }} ({{ $totalReviews }} reviews)</span>
                    </div>
                </div>
            </div>

            <div class="booking-card">
                <div class="price-display">
                    RM {{ number_format($item->PricePerDay, 2) }}
                    <span class="price-unit">/ day</span>
                </div>
                
                <div class="deposit-info">
                    <i class="fa-solid fa-money-bill-wave"></i> Refundable deposit: RM {{ number_format($item->DepositAmount, 2) }}
                    <br>
                    <small style="color: #9ca3af; font-size: 12px;">Pay deposit online • Rental fee to owner</small>
                </div>

                @php
                    $isOwner = auth()->id() == $item->UserID;
                @endphp

                @if($isOwner)
                    <div style="text-align: center; padding: 20px; background: #f3f4f6; border-radius: 10px;">
                        <i class="fa-solid fa-info-circle" style="font-size: 24px; color: #6b7280; margin-bottom: 10px;"></i>
                        <p style="color: #6b7280; font-weight: 500;">This is your listing</p>
                        <p style="color: #9ca3af; font-size: 14px; margin-top: 5px;">You cannot book your own item</p>
                    </div>
                @else
                    <form action="{{ route('booking.confirm') }}" method="POST" class="booking-form" id="bookingForm">
                        @csrf

                        @if($errors->any())
                            <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                                <ul style="margin: 0; padding-left: 20px;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <input type="hidden" name="item_id" value="{{ $item->ItemID }}">

                        <!-- Availability Calendar -->
                        <div class="availability-calendar">
                            <div style="text-align: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--color-gray-300);">
                                <h3 style="font-size: var(--text-base); font-weight: var(--font-semibold); color: var(--color-gray-900); margin-bottom: 0.5rem;">
                                    <i class="fa-solid fa-calendar-days" style="color: var(--color-primary);"></i> Select Your Rental Dates
                                </h3>
                                <p style="font-size: var(--text-sm); color: var(--color-gray-600);">
                                    Click on the calendar to choose your start and end dates
                                </p>
                            </div>
                            <div class="calendar-header">
                                <button type="button" class="calendar-nav-btn" id="prevMonth">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                                <div class="calendar-month-year" id="currentMonthYear"></div>
                                <button type="button" class="calendar-nav-btn" id="nextMonth">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>
                            <div class="calendar-grid" id="calendarGrid"></div>
                            <div class="calendar-legend">
                                <div class="legend-item">
                                    <div class="legend-box" style="background: #d1fae5;"></div>
                                    <span>Available</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-box" style="background: #fee2e2;"></div>
                                    <span>Booked</span>
                                </div>
                            </div>
                            <div style="text-align: center; margin-top: 10px; font-size: 0.75rem; color: #6b7280;">
                                <i class="fa-solid fa-info-circle"></i> Calendar shows all bookings including past dates for reference
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="start_date">Start Date</label>
                            <input type="date" id="start_date" name="start_date" class="form-input" min="{{ date('Y-m-d') }}" required readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="end_date">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="form-input" min="{{ date('Y-m-d') }}" required readonly>
                        </div>

                        <div class="total-calculation" id="totalCalculation" style="display: none;">
                            <div class="calc-row">
                                <span>Rental (RM {{ number_format($item->PricePerDay, 2) }} × <span id="numDays">0</span> days)</span>
                                <span id="rentalTotal">RM 0.00</span>
                            </div>
                            <div class="calc-row" style="color: #f59e0b; font-weight: 600;">
                                <span><i class="fa-solid fa-money-bill"></i> Pay to Owner</span>
                                <span id="payToOwner">RM 0.00</span>
                            </div>
                            <div class="calc-row" style="border-top: 1px solid #e5e7eb; padding-top: 8px; margin-top: 8px;">
                                <span>Deposit (Online)</span>
                                <span>RM {{ number_format($item->DepositAmount, 2) }}</span>
                            </div>
                            <div class="calc-row">
                                <span>Service Fee (Online)</span>
                                <span>RM 1.00</span>
                            </div>
                            <div class="calc-row total" style="background: #eff6ff; padding: 8px; border-radius: 6px; margin-top: 8px;">
                                <span>Pay Online Now</span>
                                <span id="payOnline">RM {{ number_format($item->DepositAmount + 1.00, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="book-now-btn">
                            <i class="fa-solid fa-calendar-check"></i>
                            Book Now
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <div class="reviews-header">
            <h2 class="section-title" style="margin: 0;">
                <i class="fa-solid fa-star" style="color: #fbbf24; margin-right: 0.5rem;"></i>
                Reviews ({{ $totalReviews }})
            </h2>
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
                            @if($i <= $review->Rating)
                                <i class="fa-solid fa-star"></i>
                            @else
                                <i class="fa-regular fa-star"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="review-text">{{ $review->Comment }}</p>

                    @if($review->ReviewImage)
                        <div class="review-image-container">
                            <img src="{{ asset('storage/' . $review->ReviewImage) }}"
                                 alt="Review image"
                                 class="review-image"
                                 onclick="openImageModal('{{ asset('storage/' . $review->ReviewImage) }}')">
                        </div>
                    @endif
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
    const serviceFeeAmount = 1.00;
    const itemId = {{ $item->ItemID }};

    let unavailableDates = [];
    let currentMonth = new Date();
    let selectedStartDate = null;
    let selectedEndDate = null;

    // Fetch unavailable dates
    async function fetchUnavailableDates() {
        try {
            const response = await fetch(`/api/items/${itemId}/unavailable-dates`);
            const data = await response.json();
            unavailableDates = data.unavailable_dates || [];
            renderCalendar();
        } catch (error) {
            console.error('Error fetching unavailable dates:', error);
            renderCalendar();
        }
    }

    // Render calendar
    function renderCalendar() {
        const year = currentMonth.getFullYear();
        const month = currentMonth.getMonth();

        // Update month/year display
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'];
        document.getElementById('currentMonthYear').textContent = `${monthNames[month]} ${year}`;

        // Get first day of month and number of days
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        // Build calendar grid
        let html = '';

        // Day headers
        const dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        dayHeaders.forEach(day => {
            html += `<div class="calendar-day-header">${day}</div>`;
        });

        // Empty cells before first day
        for (let i = 0; i < firstDay; i++) {
            html += '<div class="calendar-day empty"></div>';
        }

        // Days of month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateStr = formatDate(date);
            const isPast = date < today;
            const isUnavailable = unavailableDates.includes(dateStr);
            const isToday = date.getTime() === today.getTime();

            let classes = ['calendar-day'];
            if (isPast) classes.push('past');
            else if (isUnavailable) classes.push('unavailable');
            else classes.push('available');

            if (isToday) classes.push('today');

            // Check if selected
            if (selectedStartDate && dateStr === formatDate(selectedStartDate)) {
                classes.push('selected');
            } else if (selectedEndDate && dateStr === formatDate(selectedEndDate)) {
                classes.push('selected');
            } else if (selectedStartDate && selectedEndDate && date > selectedStartDate && date < selectedEndDate) {
                classes.push('in-range');
            }

            const clickable = !isPast && !isUnavailable;
            const onclick = clickable ? `onclick="selectDate('${dateStr}')"` : '';

            html += `<div class="${classes.join(' ')}" ${onclick} data-date="${dateStr}">${day}</div>`;
        }

        document.getElementById('calendarGrid').innerHTML = html;
    }

    // Format date to YYYY-MM-DD
    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Select date from calendar
    function selectDate(dateStr) {
        const date = new Date(dateStr + 'T00:00:00');

        if (!selectedStartDate || (selectedStartDate && selectedEndDate)) {
            // Start new selection
            selectedStartDate = date;
            selectedEndDate = null;
            document.getElementById('start_date').value = dateStr;
            document.getElementById('end_date').value = '';
        } else if (date > selectedStartDate) {
            // Check if any unavailable dates in range
            const hasUnavailable = checkUnavailableInRange(selectedStartDate, date);
            if (hasUnavailable) {
                alert('Cannot select date range with unavailable dates. Please choose different dates.');
                return;
            }

            selectedEndDate = date;
            document.getElementById('end_date').value = dateStr;
        } else {
            // Selected date is before start date, make it the new start
            selectedStartDate = date;
            selectedEndDate = null;
            document.getElementById('start_date').value = dateStr;
            document.getElementById('end_date').value = '';
        }

        renderCalendar();
        calculateTotal();
    }

    // Check if there are unavailable dates in range
    function checkUnavailableInRange(start, end) {
        const current = new Date(start);
        while (current <= end) {
            if (unavailableDates.includes(formatDate(current))) {
                return true;
            }
            current.setDate(current.getDate() + 1);
        }
        return false;
    }

    // Navigate months
    document.getElementById('prevMonth').addEventListener('click', () => {
        currentMonth.setMonth(currentMonth.getMonth() - 1);
        renderCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currentMonth.setMonth(currentMonth.getMonth() + 1);
        renderCalendar();
    });

    document.getElementById('start_date').addEventListener('change', calculateTotal);
    document.getElementById('end_date').addEventListener('change', calculateTotal);


    function calculateTotal() {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);

        if (startDate && endDate && endDate > startDate) {
            const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
            const rentalTotal = pricePerDay * days;
            const payOnline = depositAmount + serviceFeeAmount;

            document.getElementById('numDays').textContent = days;
            document.getElementById('rentalTotal').textContent = 'RM ' + rentalTotal.toFixed(2);
            document.getElementById('payToOwner').textContent = 'RM ' + rentalTotal.toFixed(2);
            document.getElementById('payOnline').textContent = 'RM ' + payOnline.toFixed(2);
            document.getElementById('totalCalculation').style.display = 'block';
        } else {
            document.getElementById('totalCalculation').style.display = 'none';
        }
    }

    // Initialize calendar on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetchUnavailableDates();
        initializeDescriptionToggle();
    });

    // Description toggle functionality
    function initializeDescriptionToggle() {
        const descriptionText = document.getElementById('descriptionText');
        const readMoreBtn = document.getElementById('readMoreBtn');

        // Check if description is long enough to need truncation
        if (descriptionText && descriptionText.scrollHeight > 150) {
            descriptionText.classList.add('collapsed');
            readMoreBtn.style.display = 'inline-flex';
        }
    }

    function toggleDescription() {
        const descriptionText = document.getElementById('descriptionText');
        const readMoreBtn = document.getElementById('readMoreBtn');
        const readMoreText = document.getElementById('readMoreText');

        if (descriptionText.classList.contains('collapsed')) {
            descriptionText.classList.remove('collapsed');
            readMoreText.textContent = 'Read less';
            readMoreBtn.classList.add('expanded');
        } else {
            descriptionText.classList.add('collapsed');
            readMoreText.textContent = 'Read more';
            readMoreBtn.classList.remove('expanded');

            // Scroll back to description section
            document.querySelector('.item-description').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
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
            if (data.success) {
                const heartBtn = document.querySelector('.wishlist-btn');
                if(data.added) {
                    heartBtn.innerHTML = '<i class="fa-solid fa-heart"></i>';
                    heartBtn.style.color = '#FF6B6B';
                } else {
                    heartBtn.innerHTML = '<i class="fa-regular fa-heart"></i>';
                    heartBtn.style.color = '#000';
                }
            } else {
                // Show error message
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Please login to add items to wishlist');
        });
    }

    // DEBUG: Check if form submits
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('bookingForm');
        
        form.addEventListener('submit', function(e) {
            console.log('=== FORM SUBMITTING ===');
            console.log('Item ID:', document.querySelector('input[name="item_id"]').value);
            console.log('Start Date:', document.querySelector('input[name="start_date"]').value);
            console.log('End Date:', document.querySelector('input[name="end_date"]').value);
            console.log('Action:', form.action);
            console.log('Method:', form.method);
            
            // Don't prevent default - let it submit
        });
    });

    // Image Modal Functions
    function openImageModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.classList.add('show');
        modalImg.src = imageSrc;
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside the image
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeImageModal();
                }
            });
        }
    });

</script>
@endpush

<!-- Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="close-image-modal" onclick="closeImageModal()">&times;</span>
    <img class="image-modal-content" id="modalImage">
</div>