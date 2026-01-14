<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $item->ItemName }} - GoRentUMS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
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
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
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

        /* Image Grid Layouts */
        .images-grid {
            display: grid;
            gap: 10px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        /* Single image */
        .images-grid.count-1 {
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
        }

        /* Two images side by side */
        .images-grid.count-2 {
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr;
        }

        /* Three images: 1 large + 2 small on right */
        .images-grid.count-3 {
            grid-template-columns: 2fr 1fr;
            grid-template-rows: 1fr 1fr;
        }

        .images-grid.count-3 .grid-image:first-child {
            grid-row: 1 / 3;
            aspect-ratio: 1 / 1.2;
        }

        .images-grid.count-3 .grid-image:nth-child(2),
        .images-grid.count-3 .grid-image:nth-child(3) {
            aspect-ratio: 1.5 / 1;
        }

        /* Four images: 1 large + 3 small on right */
        .images-grid.count-4 {
            grid-template-columns: 2fr 1fr;
            grid-template-rows: repeat(3, 1fr);
        }

        .images-grid.count-4 .grid-image:first-child {
            grid-row: 1 / 4;
            aspect-ratio: 1 / 1.5;
        }

        .images-grid.count-4 .grid-image:nth-child(2),
        .images-grid.count-4 .grid-image:nth-child(3),
        .images-grid.count-4 .grid-image:nth-child(4) {
            aspect-ratio: 1.5 / 1;
        }

        .grid-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s;
            display: block;
        }

        .grid-image:hover {
            transform: scale(1.02);
        }

        .images-grid.count-1 .grid-image {
            aspect-ratio: 5 / 4;
        }

        .images-grid.count-2 .grid-image {
            aspect-ratio: 1 / 1;
        }

        .images-grid.count-3 .grid-image {
            aspect-ratio: auto;
        }

        .images-grid.count-4 .grid-image {
            aspect-ratio: auto;
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
            color: #4A5FDC;
            background: none;
            border: none;
            font-weight: 600;
            cursor: pointer;
            padding: 0.5rem 0;
            margin-top: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .read-more-btn:hover {
            color: #3D4FC7;
            gap: 0.75rem;
        }

        .read-more-btn i {
            transition: transform 0.3s;
        }

        .read-more-btn.expanded i {
            transform: rotate(180deg);
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
            width: 30%;
            background: #4461F2;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: block;
            margin: 0 auto;
        }

        .book-now-btn:hover {
            background: #3651E2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
        }

        .quantity-btn:hover:not(:disabled) {
            background: #4461F2;
            color: white;
            border-color: #4461F2;
            transform: scale(1.05);
        }

        .quantity-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
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

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 450px;
            width: 90%;
            text-align: center;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-icon {
            font-size: 64px;
            color: #4461F2;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .modal-text {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .modal-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .modal-btn {
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            text-align: center;
        }

        .modal-btn-primary {
            background: #4461F2;
            color: white;
            border: none;
        }

        .modal-btn-primary:hover {
            background: #3651E2;
            transform: translateY(-2px);
        }

        .modal-btn-secondary {
            background: transparent;
            color: #4461F2;
            border: 2px solid #4461F2;
        }

        .modal-btn-secondary:hover {
            background: #f8f9ff;
        }

        .modal-close {
            background: transparent;
            color: #9ca3af;
            border: none;
            margin-top: 12px;
            cursor: pointer;
            font-size: 14px;
        }

        .modal-close:hover {
            color: #6b7280;
        }

        /* Calendar Styles */
        .availability-calendar {
            margin-bottom: 20px;
            padding: 20px;
            background: white;
            border-radius: 15px;
            border: 1px solid #E2E8F0;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .calendar-nav-btn {
            background: #e8eeff;
            border: none;
            color: #4461F2;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .calendar-nav-btn:hover {
            background: #4461F2;
            color: white;
        }

        .calendar-month-year {
            font-weight: 600;
            color: #1f2937;
            font-size: 16px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 4px;
        }

        .calendar-day-header {
            text-align: center;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            padding: 8px 4px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .calendar-day:not(.empty):not(.past):not(.unavailable):hover {
            background: #e8eeff;
            transform: scale(1.05);
        }

        .calendar-day.empty {
            cursor: default;
        }

        .calendar-day.past {
            color: #CBD5E0;
            cursor: not-allowed;
        }

        .calendar-day.today {
            font-weight: 700;
            border: 2px solid #4461F2;
        }

        .calendar-day.available {
            background: #d1fae5;
            color: #065f46;
            font-weight: 600;
        }

        .calendar-day.unavailable {
            background: #fee2e2;
            color: #991b1b;
            cursor: not-allowed;
            font-weight: 600;
        }

        .calendar-day.selected {
            background: #4461F2;
            color: white;
            font-weight: 700;
        }

        .calendar-day.in-range {
            background: #C7D2FE;
            color: #3730A3;
        }

        .calendar-legend {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-box {
            width: 16px;
            height: 16px;
            border-radius: 4px;
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
            color: #9ca3af;
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

            .modal-content {
                padding: 30px 20px;
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
        }

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
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="/" class="logo">
            <span class="logo-go">Go</span><span class="logo-rent">Rent</span><span class="logo-ums">UMS</span>
        </a>
        
        <div class="auth-buttons">
            <a href="{{ route('login') }}" class="btn btn-login">Log in</a>
            <a href="{{ route('register') }}" class="btn btn-register">Register</a>
        </div>
    </header>

    <div class="item-details-container">
        <a href="{{ route('welcome') }}" class="back-button">
            <i class="fa-solid fa-arrow-left"></i> Back to Listings
        </a>

        <div class="item-content">
            <!-- Left Column - Images & Details -->
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
                </div>

                <div class="item-details-list">
                    <h2 class="section-title">Item Details</h2>
                    <div class="detail-row">
                        <span class="detail-label">Category</span>
                        <span class="detail-value">{{ $item->category->CategoryName ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Location</span>
                        <span class="detail-value"><i class="fa-solid fa-location-dot"></i> {{ $item->location->LocationName ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Deposit Amount</span>
                        <span class="detail-value">RM {{ number_format($item->DepositAmount, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Availability</span>
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
                        <span class="detail-label">Total Quantity</span>
                        <span class="detail-value">{{ $item->Quantity }} {{ $item->Quantity > 1 ? 'units' : 'unit' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Listed</span>
                        <span class="detail-value">{{ $item->DateAdded ? $item->DateAdded->format('M d, Y') : 'N/A' }}</span>
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

                <div class="item-description">
                    <h2 class="section-title">Description</h2>
                    <div class="description-text" id="descriptionText">{!! nl2br(e($item->Description)) !!}</div>
                    <button type="button" class="read-more-btn" id="readMoreBtn" style="display: none;" onclick="toggleDescription()">
                        <span id="readMoreText">Read more</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>
            </div>

            <!-- Right Column - Booking Info -->
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
                        $isOwner = auth()->check() && auth()->id() == $item->UserID;
                    @endphp

                    @if($isOwner)
                        <div style="text-align: center; padding: 20px; background: #f3f4f6; border-radius: 10px;">
                            <i class="fa-solid fa-info-circle" style="font-size: 24px; color: #6b7280; margin-bottom: 10px;"></i>
                            <p style="color: #6b7280; font-weight: 500;">This is your listing</p>
                            <p style="color: #9ca3af; font-size: 14px; margin-top: 5px;">You cannot book your own item</p>
                        </div>
                    @else
                        <form id="bookingForm" class="booking-form">
                            <!-- Availability Calendar -->
                            <div class="availability-calendar">
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

                            @if($item->Quantity > 1)
                            <div class="form-group">
                                <label class="form-label" for="quantity">Number of Units</label>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <button type="button" class="quantity-btn" id="decreaseQty" style="width: 40px; height: 40px; border: 1.5px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-size: 1.125rem; font-weight: 600; transition: all 0.2s;">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" name="quantity" class="form-input" value="1" min="1" max="{{ $item->Quantity }}" required readonly style="text-align: center; width: 40px; height: 40px; padding: 0; margin: 0; font-weight: 600; font-size: 1rem; line-height: 38px; box-sizing: border-box;">
                                    <button type="button" class="quantity-btn" id="increaseQty" style="width: 40px; height: 40px; border: 1.5px solid #e5e7eb; border-radius: 8px; background: white; cursor: pointer; font-size: 1.125rem; font-weight: 600; transition: all 0.2s;">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                    <span style="color: #6b7280; font-size: 0.875rem; margin-left: 0.5rem;">
                                        (Max: {{ $item->Quantity }} units available)
                                    </span>
                                </div>
                                <small style="display: block; margin-top: 0.5rem; color: #6b7280; font-size: 0.75rem;">
                                    <i class="fa-solid fa-info-circle"></i> Deposit and booking fee stay the same regardless of units
                                </small>
                            </div>
                            @else
                            <input type="hidden" id="quantity" name="quantity" value="1">
                            @endif

                            <div class="total-calculation" id="totalCalculation" style="display: none;">
                                <div class="calc-row">
                                    <span>Rental (RM {{ number_format($item->PricePerDay, 2) }} × <span id="numDays">0</span> days × <span id="numUnits">1</span> <span id="unitLabel">unit</span>)</span>
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

                            <button type="submit" class="book-now-btn">Book Now</button>
                        </form>
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

    <!-- Auth Modal -->
    <div class="modal-overlay" id="authModal">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h2 class="modal-title">Sign in Required</h2>
            <p class="modal-text">To complete your booking and reserve this item, please create an account or log in to your existing account.</p>
            <div class="modal-buttons">
                <a href="{{ route('register', ['item' => $item->ItemID]) }}" class="modal-btn modal-btn-primary">Create Account</a>
                <a href="{{ route('login', ['item' => $item->ItemID]) }}" class="modal-btn modal-btn-secondary">Log In</a>
                <button class="modal-close" onclick="closeModal()">Continue Browsing</button>
            </div>
        </div>
    </div>

    <script>
        const pricePerDay = {{ $item->PricePerDay }};
        const depositAmount = {{ $item->DepositAmount }};
        const serviceFeeAmount = 1.00;
        const itemId = {{ $item->ItemID }};
        const maxQuantity = {{ $item->Quantity }};

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
            const quantityInput = document.getElementById('quantity');
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

            if (startDate && endDate && endDate > startDate) {
                const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
                const rentalTotal = pricePerDay * days * quantity;
                const payOnline = depositAmount + serviceFeeAmount;

                document.getElementById('numDays').textContent = days;
                document.getElementById('numUnits').textContent = quantity;
                document.getElementById('unitLabel').textContent = quantity > 1 ? 'units' : 'unit';
                document.getElementById('rentalTotal').textContent = 'RM ' + rentalTotal.toFixed(2);
                document.getElementById('payToOwner').textContent = 'RM ' + rentalTotal.toFixed(2);
                document.getElementById('payOnline').textContent = 'RM ' + payOnline.toFixed(2);
                document.getElementById('totalCalculation').style.display = 'block';
            } else {
                document.getElementById('totalCalculation').style.display = 'none';
            }
        }

        // Quantity controls
        const decreaseBtn = document.getElementById('decreaseQty');
        const increaseBtn = document.getElementById('increaseQty');
        const quantityInput = document.getElementById('quantity');

        if (decreaseBtn && increaseBtn && quantityInput) {
            decreaseBtn.addEventListener('click', function() {
                let currentQty = parseInt(quantityInput.value);
                if (currentQty > 1) {
                    quantityInput.value = currentQty - 1;
                    calculateTotal();
                    updateQuantityButtons();
                }
            });

            increaseBtn.addEventListener('click', function() {
                let currentQty = parseInt(quantityInput.value);
                if (currentQty < maxQuantity) {
                    quantityInput.value = currentQty + 1;
                    calculateTotal();
                    updateQuantityButtons();
                }
            });

            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                if (isNaN(value) || value < 1) {
                    this.value = 1;
                } else if (value > maxQuantity) {
                    this.value = maxQuantity;
                }
                calculateTotal();
                updateQuantityButtons();
            });
        }

        function updateQuantityButtons() {
            if (!quantityInput || !decreaseBtn || !increaseBtn) return;

            const currentQty = parseInt(quantityInput.value);

            if (currentQty <= 1) {
                decreaseBtn.disabled = true;
                decreaseBtn.style.opacity = '0.5';
                decreaseBtn.style.cursor = 'not-allowed';
            } else {
                decreaseBtn.disabled = false;
                decreaseBtn.style.opacity = '1';
                decreaseBtn.style.cursor = 'pointer';
            }

            if (currentQty >= maxQuantity) {
                increaseBtn.disabled = true;
                increaseBtn.style.opacity = '0.5';
                increaseBtn.style.cursor = 'not-allowed';
            } else {
                increaseBtn.disabled = false;
                increaseBtn.style.opacity = '1';
                increaseBtn.style.cursor = 'pointer';
            }
        }

        // Initialize calendar on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetchUnavailableDates();
            initializeDescriptionToggle();
            if (quantityInput) {
                updateQuantityButtons();
            }
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

        // Handle form submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Save booking data to sessionStorage before showing auth modal
            const quantityInput = document.getElementById('quantity');
            const bookingData = {
                item_id: {{ $item->ItemID }},
                start_date: document.getElementById('start_date').value,
                end_date: document.getElementById('end_date').value,
                quantity: quantityInput ? parseInt(quantityInput.value) : 1
            };
            sessionStorage.setItem('pendingBooking', JSON.stringify(bookingData));

            showModal();
        });

        function showModal() {
            document.getElementById('authModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('authModal').classList.remove('show');
        }

        // Close modal when clicking outside
        document.getElementById('authModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
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

        // Close image modal when clicking outside the image
        const imageModal = document.getElementById('imageModal');
        if (imageModal) {
            imageModal.addEventListener('click', function(e) {
                if (e.target === imageModal) {
                    closeImageModal();
                }
            });
        }
    </script>

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-image-modal" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>
</body>
</html>