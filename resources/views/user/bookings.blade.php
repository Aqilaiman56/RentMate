@extends('layouts.app')

@section('title', 'My Bookings - RentMate')

@php
    $hideSearch = true;
@endphp

@push('styles')
<style>
    .bookings-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #6b7280;
    }

    .bookings-grid {
        display: grid;
        gap: 20px;
    }

    .booking-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 20px;
        transition: transform 0.2s;
    }

    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .booking-image {
        width: 150px;
        height: 150px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .booking-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 10px;
    }

    .booking-id {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 600;
    }

    .booking-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .booking-dates {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 10px;
    }

    .booking-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: #6b7280;
    }

    .booking-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .booking-price {
        font-size: 20px;
        font-weight: 700;
        color: #4461F2;
    }

    .view-btn {
        background: #4461F2;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
    }

    .view-btn:hover {
        background: #3651E2;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-confirmed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-completed {
        background: #dbeafe;
        color: #1e40af;
    }

    .empty-state {
        background: white;
        border-radius: 15px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
    }

    .empty-text {
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 20px;
    }

    .browse-btn {
        background: #4461F2;
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
        transition: all 0.2s;
    }

    .browse-btn:hover {
        background: #3651E2;
        transform: translateY(-2px);
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    @media (max-width: 768px) {
        .bookings-container {
            padding: 20px 15px;
        }

        .booking-card {
            flex-direction: column;
        }

        .booking-image {
            width: 100%;
            height: 200px;
        }

        .booking-footer {
            flex-direction: column;
            gap: 10px;
            align-items: stretch;
        }

        .view-btn {
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="bookings-container">
    <div class="page-header">
        <h1 class="page-title">📅 My Bookings</h1>
        <p class="page-subtitle">{{ $bookings->total() }} booking(s) found</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            ✗ {{ session('error') }}
        </div>
    @endif

    @if($bookings->count() > 0)
        <div class="bookings-grid">
            @foreach($bookings as $booking)
                <div class="booking-card">
                    @php
                        $firstImage = $booking->item->images ? $booking->item->images->first() : null;
                    @endphp
                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage->ImagePath) }}"
                             alt="{{ $booking->item->ItemName }}"
                             class="booking-image"
                             onerror="this.src='https://via.placeholder.com/150'">
                    @else
                        <img src="https://via.placeholder.com/150"
                             alt="{{ $booking->item->ItemName }}"
                             class="booking-image">
                    @endif

                    <div class="booking-content">
                        <div class="booking-header">
                            <div>
                                <h3 class="booking-title">{{ $booking->item->ItemName }}</h3>
                            </div>
                            <span class="status-badge status-{{ strtolower($booking->Status) }}">
                                {{ ucfirst($booking->Status) }}
                            </span>
                        </div>

                        <div class="booking-dates">
                            📅 {{ $booking->StartDate->format('d M Y') }} - {{ $booking->EndDate->format('d M Y') }}
                            <span style="color: #9ca3af;">({{ $booking->StartDate->diffInDays($booking->EndDate) }} days)</span>
                        </div>

                        <div class="booking-meta">
                            <div class="meta-item">
                                <span>📍</span>
                                <span>{{ $booking->item->location->LocationName ?? 'N/A' }}</span>
                            </div>
                            <div class="meta-item">
                                <span>📆</span>
                                <span>Booked {{ $booking->BookingDate ? $booking->BookingDate->diffForHumans() : 'N/A' }}</span>
                            </div>
                            @if($booking->payment)
                                <div class="meta-item">
                                    <span>💳</span>
                                    <span>{{ $booking->payment->Status === 'successful' ? 'Paid' : 'Pending Payment' }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="booking-footer">
                            <div class="booking-price">
                                RM {{ number_format($booking->TotalAmount + $booking->DepositAmount + 1.00, 2) }}
                            </div>
                            <a href="{{ route('booking.show', $booking->BookingID) }}" class="view-btn">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($bookings->hasPages())
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $bookings->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon">📅</div>
            <h2 class="empty-title">No Bookings Yet</h2>
            <p class="empty-text">You haven't made any bookings yet. Start exploring items to rent!</p>
            <a href="{{ route('user.HomePage') }}" class="browse-btn">
                🔍 Browse Items
            </a>
        </div>
    @endif
</div>
@endsection