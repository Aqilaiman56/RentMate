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

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
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

    .review-btn {
        background: #10b981;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .review-btn:hover {
        background: #059669;
    }

    .reviewed-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #d1fae5;
        color: #065f46;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    }

    /* Review Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        animation: fadeIn 0.3s;
    }

    .modal.show {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        border-radius: 15px;
        padding: 30px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    /* Custom scrollbar for modal */
    .modal-content::-webkit-scrollbar {
        width: 8px;
    }

    .modal-content::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .modal-content::-webkit-scrollbar-thumb {
        background: #4461F2;
        border-radius: 10px;
    }

    .modal-content::-webkit-scrollbar-thumb:hover {
        background: #3651E2;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 28px;
        color: #6b7280;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .close-modal:hover {
        background: #f3f4f6;
        color: #1f2937;
    }

    .star-rating {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        justify-content: center;
    }

    .star {
        font-size: 36px;
        color: #d1d5db;
        cursor: pointer;
        transition: all 0.2s;
    }

    .star:hover,
    .star.active {
        color: #fbbf24;
        transform: scale(1.1);
    }

    .form-group-modal {
        margin-bottom: 20px;
    }

    .form-label-modal {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        min-height: 120px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #4461F2;
    }

    .submit-review-btn {
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

    .submit-review-btn:hover {
        background: #3651E2;
    }

    .submit-review-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
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

        .view-btn,
        .review-btn {
            text-align: center;
            justify-content: center;
        }

        .modal-content {
            width: 95%;
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="bookings-container">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-calendar"></i> My Bookings</h1>
        <p class="page-subtitle">{{ $bookings->total() }} booking(s) found</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-times"></i> {{ session('error') }}
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
                            <i class="fas fa-calendar"></i> {{ $booking->StartDate->format('d M Y') }} - {{ $booking->EndDate->format('d M Y') }}
                            <span style="color: #9ca3af;">({{ $booking->StartDate->diffInDays($booking->EndDate) }} days)</span>
                        </div>

                        <div class="booking-meta">
                            <div class="meta-item">
                                <span><i class="fas fa-map-marker-alt"></i></span>
                                <span>{{ $booking->item->location->LocationName ?? 'N/A' }}</span>
                            </div>
                            <div class="meta-item">
                                <span><i class="fas fa-clock"></i></span>
                                <span>Booked {{ $booking->BookingDate ? $booking->BookingDate->diffForHumans() : 'N/A' }}</span>
                            </div>
                            @if($booking->payment)
                                <div class="meta-item">
                                    <span><i class="fas fa-credit-card"></i></span>
                                    <span>{{ $booking->payment->Status === 'successful' ? 'Paid' : 'Pending Payment' }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="booking-footer">
                            <div class="booking-price">
                                RM {{ number_format($booking->TotalAmount + $booking->DepositAmount + 1.00, 2) }}
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <a href="{{ route('booking.show', $booking->BookingID) }}" class="view-btn">
                                    View Details
                                </a>
                                @if($booking->Status === 'completed')
                                    @php
                                        $hasReviewed = $booking->item->reviews->where('UserID', auth()->id())->isNotEmpty();
                                    @endphp
                                    @if(!$hasReviewed)
                                        <button type="button" class="review-btn" onclick="openReviewModal({{ $booking->item->ItemID }}, '{{ $booking->item->ItemName }}')">
                                            <i class="fas fa-star"></i> Add Review
                                        </button>
                                    @else
                                        <span class="reviewed-badge">
                                            <i class="fas fa-check-circle"></i> Reviewed
                                        </span>
                                    @endif
                                @endif
                            </div>
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
            <div class="empty-icon"><i class="fas fa-calendar"></i></div>
            <h2 class="empty-title">No Bookings Yet</h2>
            <p class="empty-text">You haven't made any bookings yet. Start exploring items to rent!</p>
            <a href="{{ route('user.HomePage') }}" class="browse-btn">
                <i class="fas fa-search"></i> Browse Items
            </a>
        </div>
    @endif
</div>

<!-- Review Modal -->
<div id="reviewModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Write a Review</h2>
            <button type="button" class="close-modal" onclick="closeReviewModal()">&times;</button>
        </div>

        <form id="reviewForm" method="POST" action="{{ route('review.add') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="ItemID" id="reviewItemID">

            <div class="form-group-modal">
                <label class="form-label-modal">Item: <span id="reviewItemName" style="color: #4461F2; font-weight: 700;"></span></label>
            </div>

            <div class="form-group-modal">
                <label class="form-label-modal">Rating *</label>
                <div class="star-rating">
                    <i class="fas fa-star star" data-rating="1" onclick="setRating(1)"></i>
                    <i class="fas fa-star star" data-rating="2" onclick="setRating(2)"></i>
                    <i class="fas fa-star star" data-rating="3" onclick="setRating(3)"></i>
                    <i class="fas fa-star star" data-rating="4" onclick="setRating(4)"></i>
                    <i class="fas fa-star star" data-rating="5" onclick="setRating(5)"></i>
                </div>
                <input type="hidden" name="Rating" id="ratingValue" required>
            </div>

            <div class="form-group-modal">
                <label for="reviewComment" class="form-label-modal">Your Review *</label>
                <textarea
                    id="reviewComment"
                    name="Comment"
                    class="form-textarea"
                    placeholder="Share your experience with this item..."
                    required
                    minlength="10"
                    maxlength="500"></textarea>
                <small style="color: #6b7280; font-size: 12px;">Minimum 10 characters, maximum 500 characters</small>
            </div>

            <div class="form-group-modal">
                <label for="reviewImage" class="form-label-modal">
                    <i class="fas fa-image"></i> Add Photo (Optional)
                </label>
                <input
                    type="file"
                    id="reviewImage"
                    name="ReviewImage"
                    class="form-textarea"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    onchange="previewReviewImage(event)">
                <small style="color: #6b7280; font-size: 12px;">Max 2MB, JPG/PNG/GIF only</small>
                <div id="imagePreviewContainer" style="display: none; margin-top: 10px; position: relative;">
                    <img id="imagePreview" style="max-width: 100%; border-radius: 8px; max-height: 200px; object-fit: cover;">
                    <button type="button" onclick="removeImagePreview()" style="position: absolute; top: 5px; right: 5px; background: #ef4444; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="submit-review-btn" id="submitReviewBtn" disabled>
                <i class="fas fa-paper-plane"></i> Submit Review
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedRating = 0;

    function openReviewModal(itemId, itemName) {
        document.getElementById('reviewItemID').value = itemId;
        document.getElementById('reviewItemName').textContent = itemName;
        document.getElementById('reviewModal').classList.add('show');
        document.body.style.overflow = 'hidden';

        // Reset form
        selectedRating = 0;
        document.getElementById('ratingValue').value = '';
        document.getElementById('reviewComment').value = '';
        document.querySelectorAll('.star').forEach(star => {
            star.classList.remove('active');
        });
        updateSubmitButton();
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function setRating(rating) {
        selectedRating = rating;
        document.getElementById('ratingValue').value = rating;

        // Update star display
        document.querySelectorAll('.star').forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });

        updateSubmitButton();
    }

    function updateSubmitButton() {
        const submitBtn = document.getElementById('submitReviewBtn');
        const comment = document.getElementById('reviewComment').value.trim();

        if (selectedRating > 0 && comment.length >= 10) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    // Update button state when typing
    document.getElementById('reviewComment').addEventListener('input', updateSubmitButton);

    // Close modal when clicking outside
    document.getElementById('reviewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReviewModal();
        }
    });

    // Prevent form submission if rating not selected
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        if (selectedRating === 0) {
            e.preventDefault();
            alert('Please select a rating before submitting your review.');
            return false;
        }
    });

    // Image preview function
    function previewReviewImage(event) {
        const file = event.target.files[0];
        if (file) {
            // Check file size (2MB = 2097152 bytes)
            if (file.size > 2097152) {
                alert('File size must be less than 2MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    // Remove image preview
    function removeImagePreview() {
        document.getElementById('reviewImage').value = '';
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.getElementById('imagePreview').src = '';
    }
</script>
@endpush