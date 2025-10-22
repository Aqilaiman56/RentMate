@extends('layouts.app')

@section('title', 'Confirm Booking - RentMate')

@php($hideSearch = true)

@push('styles')
<style>
    .confirmation-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #4461F2;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        transition: gap 0.3s;
    }

    .back-link:hover {
        gap: 12px;
    }

    .confirmation-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .confirmation-icon {
        font-size: 64px;
        margin-bottom: 15px;
    }

    .confirmation-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .confirmation-subtitle {
        font-size: 16px;
        color: #6b7280;
    }

    .confirmation-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 20px;
        margin-bottom: 20px;
    }

    .confirmation-card {
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
        display: flex;
        align-items: center;
        gap: 8px;
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
        width: 120px;
        height: 120px;
        border-radius: 10px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .item-details h3 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .item-details p {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
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

    .owner-section {
        padding: 15px;
        background: #f9fafb;
        border-radius: 12px;
        margin-top: 20px;
    }

    .owner-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .owner-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #4461F2;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 600;
    }

    .owner-details h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 3px;
    }

    .owner-details p {
        font-size: 12px;
        color: #6b7280;
    }

    .payment-summary {
        position: sticky;
        top: 100px;
    }

    .rental-payment-info {
        background: #fef3c7;
        border: 2px solid #f59e0b;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .rental-payment-info h4 {
        font-size: 14px;
        font-weight: 600;
        color: #92400e;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rental-payment-info p {
        font-size: 13px;
        color: #92400e;
        margin: 0;
        line-height: 1.6;
    }

    .summary-breakdown {
        background: #f9fafb;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        color: #6b7280;
    }

    .summary-row.total {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        padding-top: 12px;
        border-top: 2px solid #e5e7eb;
        margin-top: 12px;
    }

    .price-highlight {
        color: #4461F2;
    }

    .payment-note {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 12px 15px;
        border-radius: 8px;
        margin-top: 10px;
    }

    .payment-note p {
        font-size: 13px;
        color: #1e40af;
        margin: 0;
        line-height: 1.6;
    }

    .info-box {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .info-box p {
        font-size: 13px;
        color: #1e40af;
        margin: 0;
        line-height: 1.6;
    }

    .warning-box {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .warning-box p {
        font-size: 13px;
        color: #92400e;
        margin: 0;
        line-height: 1.6;
    }

    .confirm-btn {
        width: 100%;
        background: #4461F2;
        color: white;
        padding: 16px;
        border-radius: 10px;
        border: none;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 10px;
    }

    .confirm-btn:hover {
        background: #3651E2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
    }

    .confirm-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
        opacity: 0.5;
    }

    .cancel-btn {
        width: 100%;
        background: white;
        color: #6b7280;
        padding: 12px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-align: center;
        text-decoration: none;
        display: block;
    }

    .cancel-btn:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .terms-checkbox {
        display: flex;
        align-items: start;
        gap: 10px;
        margin-bottom: 15px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
    }

    .terms-checkbox input[type="checkbox"] {
        margin-top: 3px;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .terms-checkbox label {
        font-size: 13px;
        color: #4b5563;
        cursor: pointer;
        line-height: 1.6;
    }

    .terms-link {
        color: #4461F2;
        text-decoration: underline;
    }

    .terms-link:hover {
        color: #3651E2;
    }

    @media (max-width: 968px) {
        .confirmation-grid {
            grid-template-columns: 1fr;
        }

        .payment-summary {
            position: static;
        }

        .item-preview {
            flex-direction: column;
        }

        .item-image {
            width: 100%;
            height: 200px;
        }
    }
</style>
@endpush

@section('content')
<div class="confirmation-container">
    <a href="{{ route('item.details', $item->ItemID) }}" class="back-link">
        ← Back to Item Details
    </a>

    <div class="confirmation-header">
        <div class="confirmation-icon">📋</div>
        <h1 class="confirmation-title">Confirm Your Booking</h1>
        <p class="confirmation-subtitle">Please review your booking details before proceeding</p>
    </div>

    <div class="confirmation-grid">
        <!-- Left Column - Booking Details -->
        <div>
            <div class="confirmation-card">
                <h2 class="section-title">📦 Item Details</h2>
                
                <div class="item-preview">
                    @if($item->ImagePath)
                        <img src="{{ asset('storage/' . $item->ImagePath) }}" 
                             alt="{{ $item->ItemName }}" 
                             class="item-image"
                             onerror="this.src='https://via.placeholder.com/120'">
                    @else
                        <img src="https://via.placeholder.com/120" 
                             alt="{{ $item->ItemName }}" 
                             class="item-image">
                    @endif
                    
                    <div class="item-details">
                        <h3>{{ $item->ItemName }}</h3>
                        <p>📍 {{ $item->location->LocationName ?? 'N/A' }}</p>
                        <p>🏷️ {{ $item->category->CategoryName ?? 'N/A' }}</p>
                        <p>💰 RM {{ number_format($item->PricePerDay, 2) }} per day</p>
                    </div>
                </div>

                <h2 class="section-title">📅 Rental Period</h2>
                
                <div class="detail-row">
                    <span class="detail-label">Start Date</span>
                    <span class="detail-value">{{ $start_date->format('D, d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">End Date</span>
                    <span class="detail-value">{{ $end_date->format('D, d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Duration</span>
                    <span class="detail-value">{{ $days }} day(s)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Total Booking Cost</span>
                    <span class="detail-value">RM {{ number_format($rental_amount, 2) }}</span>
                </div>

                <div class="owner-section">
                    <h4 style="font-size: 14px; font-weight: 600; margin-bottom: 10px; color: #1f2937;">Item Owner</h4>
                    <div class="owner-info">
                        @if($item->user->ProfileImage)
                            <img src="{{ asset('storage/' . $item->user->ProfileImage) }}" 
                                 alt="{{ $item->user->UserName }}" 
                                 class="owner-avatar">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($item->user->UserName, 0, 1)) }}
                            </div>
                        @endif
                        
                        <div class="owner-details">
                            <h4>{{ $item->user->UserName }}</h4>
                            <p>{{ $item->user->Email }}</p>
                            @if($item->user->PhoneNumber)
                                <p>📞 {{ $item->user->PhoneNumber }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Payment Summary -->
        <div>
            <div class="confirmation-card payment-summary">
                <h2 class="section-title">💳 Payment Summary</h2>
                
                <div class="rental-payment-info">
                    <h4>💵 Rental Payment</h4>
                    <p>The rental fee of <strong>RM {{ number_format($rental_amount, 2) }}</strong> will be paid directly to the owner upon pickup or as agreed. This is NOT included in the online payment.</p>
                </div>

                <div class="summary-breakdown">
                    <div class="summary-row">
                        <span>Security Deposit (Refundable)</span>
                        <span>RM {{ number_format($deposit_amount, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Service Tax</span>
                        <span>RM {{ number_format($tax_amount, 2) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Pay Now (Deposit + Tax)</span>
                        <span class="price-highlight">RM {{ number_format($total_amount, 2) }}</span>
                    </div>
                </div>

                <div class="payment-note">
                    <p><strong>📝 Payment Breakdown:</strong></p>
                    <p>• <strong>Deposit:</strong> RM {{ number_format($deposit_amount, 2) }} - Held as security</p>
                    <p>• <strong>Tax:</strong> RM {{ number_format($tax_amount, 2) }} - Service fee</p>
                    <p>• <strong>Rental:</strong> RM {{ number_format($rental_amount, 2) }} - Pay to owner</p>
                </div>

                <div class="info-box">
                    <p>💰 <strong>Deposit Refund:</strong> Your deposit of RM {{ number_format($deposit_amount, 2) }} will be automatically refunded within 3-5 business days after you return the item in good condition.</p>
                </div>

                <div class="warning-box">
                    <p>⚠️ <strong>Important:</strong> The rental fee (RM {{ number_format($rental_amount, 2) }}) must be paid separately to the owner. Contact them to arrange payment method.</p>
                </div>

                <form action="{{ route('bookings.create_and_pay') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->ItemID }}">
                    <input type="hidden" name="start_date" value="{{ $start_date->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $end_date->format('Y-m-d') }}">

                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            I understand that I need to pay the rental fee (RM {{ number_format($rental_amount, 2) }}) directly to the owner, and I agree to the <a href="#" class="terms-link">terms and conditions</a>.
                        </label>
                    </div>

                    <button type="submit" class="confirm-btn" id="confirmBtn">
                        ✓ Pay Deposit (RM {{ number_format($total_amount, 2) }})
                    </button>
                </form>

                <a href="{{ route('item.details', $item->ItemID) }}" class="cancel-btn">
                    Cancel Booking
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const bookingForm = document.getElementById('bookingForm');
    const termsCheckbox = document.getElementById('terms');
    const confirmBtn = document.getElementById('confirmBtn');

    bookingForm.addEventListener('submit', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Please agree to the terms and conditions before proceeding.');
            return false;
        }

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Processing...';
    });

    termsCheckbox.addEventListener('change', function() {
        confirmBtn.disabled = !this.checked;
        confirmBtn.style.opacity = this.checked ? '1' : '0.5';
        confirmBtn.style.cursor = this.checked ? 'pointer' : 'not-allowed';
    });

    confirmBtn.disabled = !termsCheckbox.checked;
    confirmBtn.style.opacity = termsCheckbox.checked ? '1' : '0.5';
    confirmBtn.style.cursor = termsCheckbox.checked ? 'pointer' : 'not-allowed';
</script>
@endpush