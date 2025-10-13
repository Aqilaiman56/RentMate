@extends('layouts.app')

@section('title', 'Confirm Booking - RentMate')

@php($hideSearch = true)

@push('styles')
<style>
    /* ... (keep all the previous styles) ... */
    
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
                    <span class="detail-label">Total Rental Cost</span>
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

                <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
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