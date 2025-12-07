@extends('layouts.app')

@section('title', 'Confirm Booking - GoRentUMS')

@php($hideSearch = true)

@push('styles')
<style>
    .confirmation-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--color-primary);
        text-decoration: none;
        font-weight: var(--font-semibold);
        margin-bottom: 2rem;
        transition: var(--transition-base);
        font-size: var(--text-sm);
    }

    .back-link:hover {
        gap: 0.75rem;
        color: var(--color-primary-hover);
    }

    .confirmation-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .confirmation-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--color-primary);
        animation: fadeInScale 0.6s ease-out;
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .confirmation-title {
        font-size: 2rem;
        font-weight: var(--font-bold);
        color: var(--color-gray-900);
        margin-bottom: 0.5rem;
        line-height: var(--line-height-tight);
    }

    .confirmation-subtitle {
        font-size: var(--text-base);
        color: var(--color-gray-600);
    }

    .confirmation-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .confirmation-card {
        background: var(--color-white);
        border-radius: var(--radius-xl);
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--color-gray-200);
        transition: var(--transition-base);
    }

    .confirmation-card:hover {
        box-shadow: var(--shadow-lg);
    }

    .section-title {
        font-size: var(--text-xl);
        font-weight: var(--font-semibold);
        color: var(--color-gray-900);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        color: var(--color-primary);
        font-size: 1.25rem;
    }

    .item-preview {
        display: flex;
        gap: 1.25rem;
        padding: 1.25rem;
        background: var(--color-gray-50);
        border-radius: var(--radius-xl);
        margin-bottom: 2rem;
        border: 1px solid var(--color-gray-200);
    }

    .item-image {
        width: 140px;
        height: 140px;
        border-radius: var(--radius-lg);
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
    }

    .item-details h3 {
        font-size: var(--text-lg);
        font-weight: var(--font-semibold);
        color: var(--color-gray-900);
        margin-bottom: 0.75rem;
    }

    .item-details p {
        font-size: var(--text-sm);
        color: var(--color-gray-600);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .item-details p i {
        width: 16px;
        color: var(--color-gray-400);
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

    .owner-section {
        padding: 1.25rem;
        background: var(--color-gray-50);
        border-radius: var(--radius-lg);
        margin-top: 1.5rem;
        border: 1px solid var(--color-gray-200);
    }

    .owner-section h4 {
        font-size: var(--text-sm);
        font-weight: var(--font-semibold);
        margin-bottom: 0.75rem;
        color: var(--color-gray-900);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .owner-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .owner-avatar {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-full);
        object-fit: cover;
        border: 2px solid var(--color-gray-200);
    }

    .avatar-placeholder {
        width: 56px;
        height: 56px;
        border-radius: var(--radius-full);
        background: var(--color-primary);
        color: var(--color-white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--text-xl);
        font-weight: var(--font-bold);
        border: 2px solid var(--color-gray-200);
    }

    .owner-details h4 {
        font-size: var(--text-base);
        font-weight: var(--font-semibold);
        color: var(--color-gray-900);
        margin-bottom: 0.25rem;
    }

    .owner-details p {
        font-size: var(--text-sm);
        color: var(--color-gray-600);
        margin: 0;
    }

    .payment-summary {
        position: sticky;
        top: 100px;
    }

    .rental-payment-info {
        background: var(--color-warning-light);
        border: 2px solid var(--color-warning);
        padding: 1.25rem;
        border-radius: var(--radius-lg);
        margin-bottom: 1.25rem;
    }

    .rental-payment-info h4 {
        font-size: var(--text-sm);
        font-weight: var(--font-semibold);
        color: var(--color-warning-hover);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .rental-payment-info p {
        font-size: var(--text-sm);
        color: var(--color-warning-hover);
        margin: 0;
        line-height: var(--line-height-relaxed);
    }

    .summary-breakdown {
        background: var(--color-gray-50);
        padding: 1.25rem;
        border-radius: var(--radius-lg);
        margin-bottom: 1.25rem;
        border: 1px solid var(--color-gray-200);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        font-size: var(--text-sm);
        color: var(--color-gray-700);
    }

    .summary-row:last-child {
        margin-bottom: 0;
    }

    .summary-row.total {
        font-size: var(--text-xl);
        font-weight: var(--font-bold);
        color: var(--color-gray-900);
        padding-top: 1rem;
        border-top: 2px solid var(--color-gray-300);
        margin-top: 1rem;
    }

    .price-highlight {
        color: var(--color-primary);
    }

    .payment-note {
        background: var(--color-info-light);
        border-left: 4px solid var(--color-info);
        padding: 1rem;
        border-radius: var(--radius-lg);
        margin-top: 1rem;
    }

    .payment-note p {
        font-size: var(--text-sm);
        color: var(--color-info-hover);
        margin: 0;
        line-height: var(--line-height-relaxed);
    }

    .payment-note p + p {
        margin-top: 0.5rem;
    }

    .info-box {
        background: var(--color-info-light);
        border-left: 4px solid var(--color-info);
        padding: 1rem;
        border-radius: var(--radius-lg);
        margin-bottom: 1.25rem;
    }

    .info-box p {
        font-size: var(--text-sm);
        color: var(--color-info-hover);
        margin: 0;
        line-height: var(--line-height-relaxed);
    }

    .warning-box {
        background: var(--color-warning-light);
        border-left: 4px solid var(--color-warning);
        padding: 1rem;
        border-radius: var(--radius-lg);
        margin-bottom: 1.25rem;
    }

    .warning-box p {
        font-size: var(--text-sm);
        color: var(--color-warning-hover);
        margin: 0;
        line-height: var(--line-height-relaxed);
    }

    .confirm-btn {
        width: 100%;
        background: var(--color-primary);
        color: var(--color-white);
        padding: 1.125rem 1.5rem;
        border-radius: var(--radius-lg);
        border: none;
        font-size: var(--text-base);
        font-weight: var(--font-semibold);
        cursor: pointer;
        transition: var(--transition-base);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .confirm-btn:hover:not(:disabled) {
        background: var(--color-primary-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .confirm-btn:disabled {
        background: var(--color-gray-300);
        cursor: not-allowed;
        transform: none;
        opacity: 0.6;
    }

    .cancel-btn {
        width: 100%;
        background: var(--color-white);
        color: var(--color-gray-700);
        padding: 0.875rem 1rem;
        border-radius: var(--radius-lg);
        border: 2px solid var(--color-gray-300);
        font-size: var(--text-sm);
        font-weight: var(--font-semibold);
        cursor: pointer;
        transition: var(--transition-base);
        text-align: center;
        text-decoration: none;
        display: block;
    }

    .cancel-btn:hover {
        background: var(--color-gray-50);
        border-color: var(--color-gray-400);
    }

    .terms-checkbox {
        display: flex;
        align-items: start;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding: 1rem;
        background: var(--color-gray-50);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-gray-200);
    }

    .terms-checkbox input[type="checkbox"] {
        margin-top: 0.25rem;
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--color-primary);
    }

    .terms-checkbox label {
        font-size: var(--text-sm);
        color: var(--color-gray-700);
        cursor: pointer;
        line-height: var(--line-height-relaxed);
    }

    .terms-link {
        color: var(--color-primary);
        text-decoration: underline;
        font-weight: var(--font-medium);
    }

    .terms-link:hover {
        color: var(--color-primary-hover);
    }

    @media (max-width: 968px) {
        .confirmation-container {
            padding: 1.5rem 1rem;
        }

        .confirmation-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .payment-summary {
            position: static;
        }

        .item-preview {
            flex-direction: column;
        }

        .item-image {
            width: 100%;
            height: 220px;
        }

        .confirmation-title {
            font-size: var(--text-3xl);
        }

        .confirmation-icon {
            font-size: 3rem;
        }
    }
</style>
@endpush

@section('content')
<div class="confirmation-container">
    <a href="{{ route('item.details', $item->ItemID) }}" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Back to Item Details
    </a>

    <div class="confirmation-header">
        <div class="confirmation-icon"><i class="fas fa-clipboard-check"></i></div>
        <h1 class="confirmation-title">Confirm Your Booking</h1>
        <p class="confirmation-subtitle">Please review your booking details before proceeding</p>
    </div>

    <div class="confirmation-grid">
        <!-- Left Column - Booking Details -->
        <div>
            <div class="confirmation-card">
                <h2 class="section-title"><i class="fas fa-box"></i> Item Details</h2>

                <div class="item-preview">
                    @if($item->images?->first())
                        <img src="{{ asset('storage/' . $item->images->first()->ImagePath) }}"
                             alt="{{ $item->ItemName }}"
                             class="item-image"
                             onerror="this.src='https://via.placeholder.com/140?text={{ urlencode($item->ItemName) }}'">
                    @else
                        <img src="https://via.placeholder.com/140?text={{ urlencode($item->ItemName) }}"
                             alt="{{ $item->ItemName }}"
                             class="item-image">
                    @endif

                    <div class="item-details">
                        <h3>{{ $item->ItemName }}</h3>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $item->location->LocationName ?? 'N/A' }}</p>
                        <p><i class="fas fa-tag"></i> {{ $item->category->CategoryName ?? 'N/A' }}</p>
                        <p><i class="fas fa-money-bill-wave"></i> RM {{ number_format($item->PricePerDay, 2) }} per day</p>
                    </div>
                </div>

                <h2 class="section-title"><i class="fas fa-calendar-alt"></i> Rental Period</h2>

                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-calendar-day"></i>
                        Start Date
                    </span>
                    <span class="detail-value">{{ $start_date->format('D, d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-calendar-check"></i>
                        End Date
                    </span>
                    <span class="detail-value">{{ $end_date->format('D, d M Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-clock"></i>
                        Duration
                    </span>
                    <span class="detail-value">{{ $days }} day(s)</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">
                        <i class="fa-solid fa-calculator"></i>
                        Total Rental Cost
                    </span>
                    <span class="detail-value">RM {{ number_format($rental_amount, 2) }}</span>
                </div>

                <div class="owner-section">
                    <h4><i class="fas fa-user"></i> Item Owner</h4>
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
                <h2 class="section-title"><i class="fas fa-credit-card"></i> Payment Summary</h2>

                <div class="rental-payment-info">
                    <h4><i class="fas fa-dollar-sign"></i> Rental Payment</h4>
                    <p>The rental fee of <strong>RM {{ number_format($rental_amount, 2) }}</strong> will be paid directly to the owner upon pickup or as agreed. This is NOT included in the online payment.</p>
                </div>

                <div class="summary-breakdown">
                    <div class="summary-row">
                        <span>Security Deposit (Refundable)</span>
                        <span>RM {{ number_format($deposit_amount, 2) }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Service Fee</span>
                        <span>RM {{ number_format($service_fee_amount, 2) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Pay Now</span>
                        <span class="price-highlight">RM {{ number_format($total_amount, 2) }}</span>
                    </div>
                </div>

                <div class="payment-note">
                    <p><strong><i class="fas fa-file-invoice-dollar"></i> Payment Breakdown:</strong></p>
                    <p>• <strong>Deposit:</strong> RM {{ number_format($deposit_amount, 2) }} - Held as security</p>
                    <p>• <strong>Service Fee:</strong> RM {{ number_format($service_fee_amount, 2) }} - Platform fee</p>
                    <p>• <strong>Rental:</strong> RM {{ number_format($rental_amount, 2) }} - Pay to owner</p>
                </div>

                <div class="info-box">
                    <p><i class="fas fa-money-check-alt"></i> <strong>Deposit Refund:</strong> Your deposit of RM {{ number_format($deposit_amount, 2) }} will be automatically refunded within 3-5 business days after you return the item in good condition.</p>
                </div>

                <div class="warning-box">
                    <p><i class="fas fa-exclamation-triangle"></i> <strong>Important:</strong> The rental fee (RM {{ number_format($rental_amount, 2) }}) must be paid separately to the owner. Contact them to arrange payment method.</p>
                </div>

                <form action="{{ route('bookings.create_and_pay') }}" method="POST" id="bookingForm">
                    @csrf
                    <input type="hidden" name="item_id" value="{{ $item->ItemID }}">
                    <input type="hidden" name="start_date" value="{{ $start_date->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $end_date->format('Y-m-d') }}">

                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            I understand that I need to pay the rental fee (RM {{ number_format($rental_amount, 2) }}) directly to the owner, and I agree to the <a href="{{ route('terms') }}" class="terms-link" target="_blank">terms and conditions</a>.
                        </label>
                    </div>

                    <button type="submit" class="confirm-btn" id="confirmBtn">
                        <i class="fas fa-check-circle"></i> Pay Deposit & Confirm (RM {{ number_format($total_amount, 2) }})
                    </button>
                </form>

                <a href="{{ route('item.details', $item->ItemID) }}" class="cancel-btn">
                    <i class="fas fa-times"></i> Cancel Booking
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
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    });

    termsCheckbox.addEventListener('change', function() {
        confirmBtn.disabled = !this.checked;
        confirmBtn.style.opacity = this.checked ? '1' : '0.6';
        confirmBtn.style.cursor = this.checked ? 'pointer' : 'not-allowed';
    });

    // Initialize button state
    confirmBtn.disabled = !termsCheckbox.checked;
    confirmBtn.style.opacity = termsCheckbox.checked ? '1' : '0.6';
    confirmBtn.style.cursor = termsCheckbox.checked ? 'pointer' : 'not-allowed';
</script>
@endpush
