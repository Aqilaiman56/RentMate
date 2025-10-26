@extends('layouts.app')

@section('title', 'Booking Details - RentMate')

@php
    $hideSearch = true;
@endphp

@push('styles')
<style>
    .booking-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    }

    .back-btn:hover {
        background: #e5e7eb;
    }

    .booking-header {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .booking-id {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .booking-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 15px;
    }

    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
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

    .booking-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 20px;
        margin-bottom: 20px;
    }

    .booking-details-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
    }

    .item-preview {
        display: flex;
        gap: 15px;
        padding: 15px;
        background: #f9fafb;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .item-image {
        width: 100px;
        height: 100px;
        border-radius: 8px;
        object-fit: cover;
    }

    .item-info h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .item-info p {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 3px;
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

    .owner-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .owner-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .owner-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #4461F2;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 600;
    }

    .owner-details h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .owner-details p {
        font-size: 13px;
        color: #6b7280;
    }

    .contact-btn {
        width: 100%;
        background: #e8eeff;
        color: #4461F2;
        padding: 12px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .contact-btn:hover {
        background: #d0ddff;
    }

    .payment-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .amount-breakdown {
        background: #f9fafb;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .amount-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        color: #6b7280;
    }

    .amount-row.total {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        padding-top: 10px;
        border-top: 2px solid #e5e7eb;
        margin-top: 10px;
    }

    .rental-notice {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .rental-notice p {
        font-size: 13px;
        color: #92400e;
        margin: 0;
        line-height: 1.6;
    }

    .pay-btn {
        width: 100%;
        background: #4461F2;
        color: white;
        padding: 14px;
        border-radius: 10px;
        border: none;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pay-btn:hover {
        background: #3651E2;
        transform: translateY(-2px);
    }

    .cancel-btn {
        width: 100%;
        background: #fee2e2;
        color: #dc2626;
        padding: 12px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
        transition: all 0.2s;
    }

    .cancel-btn:hover {
        background: #fecaca;
    }

    .payment-success {
        background: #d1fae5;
        color: #065f46;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        font-weight: 600;
    }

    .payment-info {
        background: #f9fafb;
        padding: 15px;
        border-radius: 10px;
        margin-top: 15px;
    }

    .payment-info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
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

    .complete-btn {
        width: 100%;
        background: #10b981;
        color: white;
        padding: 14px;
        border-radius: 10px;
        border: none;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 15px;
    }

    .complete-btn:hover {
        background: #059669;
    }

    @media (max-width: 968px) {
        .booking-grid {
            grid-template-columns: 1fr;
        }

        .booking-container {
            padding: 20px 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="booking-container">
    <div class="page-header">
        <a href="{{ route('user.bookings') }}" class="back-btn">← Back to My Bookings</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="booking-header">
        <h1 class="booking-title">Booking Details</h1>
        <span class="status-badge status-{{ strtolower($booking->Status) }}">
            {{ ucfirst($booking->Status) }}
        </span>
    </div>

    <div class="booking-grid">
        <!-- Left Column -->
        <div>
            <div class="booking-details-card">
                <h2 class="section-title"><i class="fas fa-box"></i> Item Details</h2>
                
                <div class="item-preview">
                    @php
                        $firstImage = $booking->item->images ? $booking->item->images->first() : null;
                    @endphp
                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage->ImagePath) }}"
                             alt="{{ $booking->item->ItemName }}"
                             class="item-image">
                    @else
                        <img src="https://via.placeholder.com/100"
                             alt="{{ $booking->item->ItemName }}"
                             class="item-image">
                    @endif
                    
                    <div class="item-info">
                        <h3>{{ $booking->item->ItemName }}</h3>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $booking->item->location->LocationName ?? 'N/A' }}</p>
                        <p><i class="fas fa-money-bill-wave"></i> RM {{ number_format($booking->item->PricePerDay, 2) }} / day</p>
                    </div>
                </div>

                <h2 class="section-title"><i class="fas fa-calendar-alt"></i> Booking Information</h2>
                
                <div class="detail-row">
                    <span class="detail-label">Start Date</span>
                    <span class="detail-value">{{ $booking->StartDate ? $booking->StartDate->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">End Date</span>
                    <span class="detail-value">{{ $booking->EndDate ? $booking->EndDate->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value">{{ ($booking->StartDate && $booking->EndDate) ? $booking->StartDate->diffInDays($booking->EndDate) . ' days' : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Booking Date</span>
                    <span class="detail-value">{{ $booking->BookingDate ? $booking->BookingDate->format('d M Y, g:i A') : 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="status-badge status-{{ strtolower($booking->Status) }}">
                            {{ ucfirst($booking->Status) }}
                        </span>
                    </span>
                </div>
            </div>

            @if($booking->item->UserID !== auth()->id())
                <div class="owner-card">
                    <h2 class="section-title">Item Owner</h2>
                    
                    <div class="owner-info">
                        @if($booking->item->user->ProfileImage)
                            <img src="{{ asset('storage/' . $booking->item->user->ProfileImage) }}" 
                                 alt="{{ $booking->item->user->UserName }}" 
                                 class="owner-avatar">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($booking->item->user->UserName, 0, 1)) }}
                            </div>
                        @endif
                        
                        <div class="owner-details">
                            <h3>{{ $booking->item->user->UserName }}</h3>
                            <p>{{ $booking->item->user->Email }}</p>
                            @if($booking->item->user->PhoneNumber)
                                <p><i class="fas fa-phone"></i> {{ $booking->item->user->PhoneNumber }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <a href="{{ route('messages.show', ['userId' => $booking->item->user->UserID, 'item_id' => $booking->item->ItemID]) }}" class="contact-btn">
                        💬 Contact Owner
                    </a>
                </div>
            @endif
        </div>

        <!-- Right Column - Payment -->
        <div>
            <div class="payment-card">
                <h2 class="section-title"><i class="fas fa-credit-card"></i> Payment Summary</h2>
                
                <div class="amount-breakdown">
                    @php
                        $days = ($booking->StartDate && $booking->EndDate) ? $booking->StartDate->diffInDays($booking->EndDate) : 1;
                        $days = max(1, $days); // Minimum 1 day
                        $rentalAmount = $booking->item->PricePerDay * $days;
                        $depositAmount = $booking->item->DepositAmount ?? 0;
                    @endphp
                    <div class="amount-row">
                        <span>Rental ({{ $days }} day{{ $days > 1 ? 's' : '' }})</span>
                        <span>RM {{ number_format($rentalAmount, 2) }}</span>
                    </div>
                    <div class="amount-row" style="color: #f59e0b; font-weight: 600;">
                        <span><i class="fas fa-hand-holding-usd"></i> Pay to Owner</span>
                        <span>RM {{ number_format($rentalAmount, 2) }}</span>
                    </div>
                    <div class="amount-row" style="border-top: 1px solid #e5e7eb; padding-top: 10px; margin-top: 10px;">
                        <span>Deposit (Refundable)</span>
                        <span>RM {{ number_format($depositAmount, 2) }}</span>
                    </div>
                    <div class="amount-row">
                        <span>Tax</span>
                        <span>RM 1.00</span>
                    </div>
                    <div class="amount-row total">
                        <span>Pay Online</span>
                        <span>RM {{ number_format($depositAmount + 1.00, 2) }}</span>
                    </div>
                </div>

                <div class="rental-notice">
                    <p><strong><i class="fas fa-exclamation-triangle"></i> Important:</strong> The rental fee of RM {{ number_format($rentalAmount, 2) }} must be paid directly to the owner. Please arrange this with them via messages.</p>
                </div>

                @if($booking->Status === 'pending')
                    <form action="{{ route('payment.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->BookingID }}">
                        <button type="submit" class="pay-btn">
                            <i class="fas fa-credit-card"></i> Pay Deposit Now (RM {{ number_format($depositAmount + 1.00, 2) }})
                        </button>
                    </form>

                    <form action="{{ route('booking.cancel', $booking->BookingID) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                        @csrf
                        <button type="submit" class="cancel-btn">
                            ✗ Cancel Booking
                        </button>
                    </form>

                @elseif($booking->Status === 'confirmed')
                    <div class="payment-success">
                        <i class="fas fa-check-circle"></i> Deposit Payment Completed - Booking Confirmed
                    </div>

                    @if($booking->payment)
                        <div class="payment-info">
                            <div class="payment-info-row">
                                <span>Transaction ID:</span>
                                <span>{{ $booking->payment->TransactionID }}</span>
                            </div>
                            <div class="payment-info-row">
                                <span>Payment Date:</span>
                                <span>{{ $booking->payment->PaymentDate ? $booking->payment->PaymentDate->format('d M Y, g:i A') : 'N/A' }}</span>
                            </div>
                            <div class="payment-info-row">
                                <span>Payment Method:</span>
                                <span>{{ $booking->payment->PaymentMethod }}</span>
                            </div>
                            <div class="payment-info-row">
                                <span>Amount Paid:</span>
                                <span>RM {{ number_format($booking->payment->Amount, 2) }}</span>
                            </div>
                        </div>
                    @endif

                    @if($booking->item->UserID === auth()->id() && now()->gte($booking->EndDate))
                        <form action="{{ route('booking.complete', $booking->BookingID) }}" method="POST">
                            @csrf
                            <button type="submit" class="complete-btn" onclick="return confirm('Mark this booking as completed and refund the deposit?')">
                                <i class="fas fa-check"></i> Complete Booking & Refund Deposit
                            </button>
                        </form>
                    @endif

                @elseif($booking->Status === 'completed')
                    <div class="payment-success" style="background: #dbeafe; color: #1e40af;">
                        ✅ Booking Completed - Deposit Refunded
                    </div>

                    @if($booking->deposit)
                        <div class="payment-info">
                            <div class="payment-info-row">
                                <span>Deposit Amount:</span>
                                <span>RM {{ number_format($booking->deposit->Amount, 2) }}</span>
                            </div>
                            <div class="payment-info-row">
                                <span>Refund Date:</span>
                                <span>{{ $booking->deposit->DateRefunded ? $booking->deposit->DateRefunded->format('d M Y') : 'Processing' }}</span>
                            </div>
                            <div class="payment-info-row">
                                <span>Refund Status:</span>
                                <span>{{ ucfirst($booking->deposit->Status) }}</span>
                            </div>
                        </div>
                    @endif

                @elseif($booking->Status === 'cancelled')
                    <div class="payment-success" style="background: #fee2e2; color: #991b1b;">
                        ✗ Booking Cancelled
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection