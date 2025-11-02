@extends('layouts.app')

@section('title', 'Item Bookings - RentMate')

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

    .back-btn {
        background: #f3f4f6;
        color: #374151;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
    }

    .back-btn:hover {
        background: #e5e7eb;
    }

    .item-header {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .item-image {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .item-info {
        flex: 1;
    }

    .item-title {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .item-details {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    .item-detail {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #6b7280;
    }

    .item-detail i {
        color: #9ca3af;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .stat-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }

    .bookings-table-container {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .bookings-table {
        width: 100%;
        border-collapse: collapse;
    }

    .bookings-table thead {
        background: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
    }

    .bookings-table th {
        padding: 15px 20px;
        text-align: left;
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .bookings-table td {
        padding: 20px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #1f2937;
    }

    .bookings-table tbody tr:hover {
        background: #f9fafb;
    }

    .bookings-table tbody tr:last-child td {
        border-bottom: none;
    }

    .renter-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .renter-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
    }

    .avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 700;
        border: 2px solid #e5e7eb;
    }

    .renter-details {
        flex: 1;
    }

    .renter-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 2px;
    }

    .renter-email {
        font-size: 12px;
        color: #6b7280;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
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

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .pending-highlight {
        background: #fffbeb !important;
        border-left: 4px solid #f59e0b;
    }

    .action-btn {
        background: #4461F2;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn:hover {
        background: #3651E2;
        transform: translateY(-1px);
    }

    .empty-state {
        background: white;
        border-radius: 15px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
        color: #9ca3af;
    }

    .empty-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .empty-text {
        color: #6b7280;
        font-size: 14px;
    }

    .date-range {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .date-text {
        font-size: 13px;
    }

    .duration-text {
        font-size: 12px;
        color: #6b7280;
    }

    @media (max-width: 968px) {
        .bookings-container {
            padding: 20px 15px;
        }

        .item-header {
            flex-direction: column;
            text-align: center;
        }

        .item-image {
            width: 100%;
            height: 200px;
        }

        .bookings-table-container {
            overflow-x: auto;
        }

        .bookings-table {
            min-width: 800px;
        }
    }
</style>
@endpush

@section('content')
<div class="bookings-container">
    <div class="page-header">
        <a href="{{ route('user.listings') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to My Listings
        </a>
    </div>

    <div class="item-header">
        @php
            $firstImage = $item->images->first();
        @endphp
        @if($firstImage)
            <img src="{{ asset('storage/' . $firstImage->ImagePath) }}"
                 alt="{{ $item->ItemName }}"
                 class="item-image"
                 onerror="this.src='https://via.placeholder.com/120'">
        @else
            <img src="https://via.placeholder.com/120"
                 alt="{{ $item->ItemName }}"
                 class="item-image">
        @endif

        <div class="item-info">
            <h1 class="item-title">{{ $item->ItemName }}</h1>
            <div class="item-details">
                <div class="item-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $item->location->LocationName ?? 'N/A' }}</span>
                </div>
                <div class="item-detail">
                    <i class="fas fa-tag"></i>
                    <span>{{ $item->category->CategoryName ?? 'N/A' }}</span>
                </div>
                <div class="item-detail">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>RM {{ number_format($item->PricePerDay, 2) }} / day</span>
                </div>
            </div>
        </div>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Total Bookings</div>
            <div class="stat-value">{{ $bookings->total() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Confirmed Bookings</div>
            <div class="stat-value">{{ $bookings->where('Status', 'confirmed')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending Bookings</div>
            <div class="stat-value">{{ $bookings->where('Status', 'pending')->count() }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Completed Bookings</div>
            <div class="stat-value">{{ $bookings->where('Status', 'completed')->count() }}</div>
        </div>
    </div>

    <h2 class="section-title"><i class="fas fa-calendar-check"></i> All Bookings</h2>

    @if($bookings->count() > 0)
        <div class="bookings-table-container">
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Renter</th>
                        <th>Booking Date</th>
                        <th>Rental Period</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr class="{{ $booking->Status === 'pending' ? 'pending-highlight' : '' }}">
                            <td>
                                <div class="renter-info">
                                    @if($booking->user->ProfileImage)
                                        <img src="{{ asset('storage/' . $booking->user->ProfileImage) }}"
                                             alt="{{ $booking->user->UserName }}"
                                             class="renter-avatar">
                                    @else
                                        <div class="avatar-placeholder">
                                            {{ strtoupper(substr($booking->user->UserName, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="renter-details">
                                        <div class="renter-name">{{ $booking->user->UserName }}</div>
                                        <div class="renter-email">{{ $booking->user->Email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $booking->BookingDate ? $booking->BookingDate->format('d M Y') : 'N/A' }}
                            </td>
                            <td>
                                <div class="date-range">
                                    <span class="date-text">
                                        {{ $booking->StartDate ? $booking->StartDate->format('d M Y') : 'N/A' }}
                                        -
                                        {{ $booking->EndDate ? $booking->EndDate->format('d M Y') : 'N/A' }}
                                    </span>
                                    <span class="duration-text">
                                        ({{ ($booking->StartDate && $booking->EndDate) ? $booking->StartDate->diffInDays($booking->EndDate) : 0 }} days)
                                    </span>
                                </div>
                            </td>
                            <td>
                                <strong>RM {{ number_format($booking->TotalAmount + $booking->DepositAmount + 1.00, 2) }}</strong>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($booking->Status) }}">
                                    {{ ucfirst($booking->Status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('booking.show', $booking->BookingID) }}" class="action-btn">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $bookings->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-calendar-times"></i></div>
            <h3 class="empty-title">No Bookings Yet</h3>
            <p class="empty-text">This item hasn't received any bookings yet.</p>
        </div>
    @endif
</div>
@endsection
