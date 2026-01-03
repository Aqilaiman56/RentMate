@extends('layouts.app')

@section('title', 'Payment Details - GoRentUMS')

@push('styles')
<style>
    .payment-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
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
        color: #6b7280;
        font-size: 14px;
    }

    .payment-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .card-header {
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(135deg, #4461F2 0%, #3651d4 100%);
    }

    .card-header h2 {
        font-size: 20px;
        font-weight: 600;
        color: white;
        margin: 0;
    }

    .card-body {
        padding: 24px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .status-successful {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-failed {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-refunded {
        background: #dbeafe;
        color: #1e40af;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }

    .detail-value {
        font-size: 14px;
        color: #1f2937;
        font-weight: 600;
        text-align: right;
    }

    .amount-highlight {
        font-size: 32px;
        font-weight: 700;
        color: #4461F2;
        margin: 16px 0;
    }

    .booking-summary {
        background: #f9fafb;
        padding: 20px;
        border-radius: 12px;
        margin-top: 20px;
    }

    .booking-summary h3 {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 16px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4461F2 0%, #3651d4 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.4);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    @media (max-width: 768px) {
        .payment-container {
            margin: 20px auto;
        }

        .page-title {
            font-size: 24px;
        }

        .detail-row {
            flex-direction: column;
            gap: 8px;
        }

        .detail-value {
            text-align: left;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="payment-container">
    <div class="page-header">
        <h1 class="page-title">Payment Details</h1>
        <p class="page-subtitle">View payment information and transaction details</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px; padding: 16px; background: #d1fae5; color: #065f46; border-radius: 12px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" style="margin-bottom: 20px; padding: 16px; background: #fee2e2; color: #991b1b; border-radius: 12px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Payment Information Card -->
    <div class="payment-card">
        <div class="card-header">
            <h2>Payment Information</h2>
        </div>
        <div class="card-body">
            <span class="status-badge status-{{ strtolower($payment->Status) }}">
                @if($payment->Status === 'successful')
                    ✓ Payment Successful
                @elseif($payment->Status === 'pending')
                    ⏳ Payment Pending
                @elseif($payment->Status === 'failed')
                    ✗ Payment Failed
                @else
                    ↩ Refunded
                @endif
            </span>

            <div class="amount-highlight">
                RM {{ number_format($payment->Amount, 2) }}
            </div>

            <div class="detail-row">
                <span class="detail-label">Payment ID</span>
                <span class="detail-value">#{{ $payment->PaymentID }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Booking ID</span>
                <span class="detail-value">
                    <a href="{{ route('booking.show', $payment->BookingID) }}" style="color: #4461F2; text-decoration: none;">
                        #{{ $payment->BookingID }}
                    </a>
                </span>
            </div>

            @if($payment->TransactionID)
            <div class="detail-row">
                <span class="detail-label">Transaction ID</span>
                <span class="detail-value">{{ $payment->TransactionID }}</span>
            </div>
            @endif

            @if($payment->PaymentMethod)
            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value">{{ $payment->PaymentMethod }}</span>
            </div>
            @endif

            @if($payment->PaymentDate)
            <div class="detail-row">
                <span class="detail-label">Payment Date</span>
                <span class="detail-value">{{ $payment->PaymentDate->format('d M Y, h:i A') }}</span>
            </div>
            @endif

            <div class="detail-row">
                <span class="detail-label">Created At</span>
                <span class="detail-value">{{ $payment->CreatedAt->format('d M Y, h:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- Booking Summary Card -->
    <div class="payment-card">
        <div class="card-header">
            <h2>Booking Summary</h2>
        </div>
        <div class="card-body">
            <div class="detail-row">
                <span class="detail-label">Item</span>
                <span class="detail-value">{{ $payment->booking->item->ItemName }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Renter</span>
                <span class="detail-value">{{ $payment->booking->user->UserName }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Rental Period</span>
                <span class="detail-value">
                    {{ $payment->booking->StartDate->format('d M Y') }} - {{ $payment->booking->EndDate->format('d M Y') }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Total Days</span>
                <span class="detail-value">{{ $payment->booking->StartDate->diffInDays($payment->booking->EndDate) }} days</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Rental Amount</span>
                <span class="detail-value">RM {{ number_format($payment->booking->TotalAmount, 2) }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Deposit Amount</span>
                <span class="detail-value">RM {{ number_format($payment->booking->DepositAmount, 2) }}</span>
            </div>

            <div class="booking-summary">
                <p style="margin: 0; font-size: 13px; color: #6b7280; line-height: 1.6;">
                    <strong>Note:</strong> The payment of RM {{ number_format($payment->Amount, 2) }} covers the security deposit and service fee. The rental fee of RM {{ number_format($payment->booking->TotalAmount, 2) }} should be paid directly to the item owner.
                </p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('booking.show', $payment->BookingID) }}" class="btn btn-primary">
            <i class="fas fa-eye"></i>
            View Booking Details
        </a>
        <a href="{{ route('user.HomePage') }}" class="btn btn-secondary">
            <i class="fas fa-home"></i>
            Back to Home
        </a>
    </div>
</div>
@endsection
