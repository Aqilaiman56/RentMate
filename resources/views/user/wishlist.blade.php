@extends('layouts.app')

@section('title', 'My Wishlist - RentMate')

@php
    $hideSearch = true;
@endphp

@push('styles')
<style>
    .wishlist-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .wishlist-count {
        font-size: 16px;
        color: #6b7280;
        font-weight: 500;
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
        opacity: 0.6;
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
        margin-bottom: 30px;
    }

    .browse-btn {
        background: #4461F2;
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .browse-btn:hover {
        background: #3651E2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
    }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }

    .wishlist-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }

    .wishlist-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .item-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background: #f3f4f6;
    }

    .wishlist-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        z-index: 10;
    }

    .wishlist-badge:hover {
        transform: scale(1.1);
        background: #fee2e2;
    }

    .wishlist-badge.loved {
        color: #ef4444;
        font-size: 20px;
    }

    .availability-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        z-index: 5;
    }

    .badge-available {
        background: #10b981;
        color: white;
    }

    .badge-unavailable {
        background: #ef4444;
        color: white;
    }

    .item-content {
        padding: 20px;
    }

    .item-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .item-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 15px;
    }

    .item-detail {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6b7280;
    }

    .item-price {
        font-size: 22px;
        font-weight: 700;
        color: #4461F2;
        margin-bottom: 15px;
    }

    .item-meta {
        display: flex;
        gap: 15px;
        padding: 12px 0;
        border-top: 1px solid #e5e7eb;
        margin-bottom: 15px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #6b7280;
    }

    .date-added {
        font-size: 12px;
        color: #9ca3af;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .item-actions {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
    }

    .btn-view {
        background: #e8eeff;
        color: #4461F2;
    }

    .btn-view:hover {
        background: #d0ddff;
    }

    .btn-remove {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-remove:hover {
        background: #fecaca;
    }

    @media (max-width: 768px) {
        .wishlist-container {
            padding: 20px 15px;
        }

        .page-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .wishlist-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="wishlist-container">
    <div class="page-header">
        <div>
            <h1 class="page-title">❤️ My Wishlist</h1>
            <p class="wishlist-count">{{ $wishlistItems->count() }} item(s) in your wishlist</p>
        </div>
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

    @if($wishlistItems->count() > 0)
        <div class="wishlist-grid">
            @foreach($wishlistItems as $wishlist)
                <div class="wishlist-card" id="wishlist-item-{{ $wishlist->item->ItemID }}">
                    @php
                        $firstImage = $wishlist->item->images->first();
                    @endphp
                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage->ImagePath) }}"
                             alt="{{ $wishlist->item->ItemName }}"
                             class="item-image"
                             onerror="this.src='https://via.placeholder.com/300x220/4461F2/fff?text={{ urlencode($wishlist->item->ItemName) }}'">
                    @else
                        <img src="https://via.placeholder.com/300x220/4461F2/fff?text={{ urlencode($wishlist->item->ItemName) }}"
                             alt="{{ $wishlist->item->ItemName }}"
                             class="item-image">
                    @endif
                    
                    <div class="wishlist-badge loved" 
                         onclick="removeFromWishlist({{ $wishlist->item->ItemID }})"
                         title="Remove from wishlist">
                        ❤️
                    </div>

                    <span class="availability-badge {{ $wishlist->item->Availability ? 'badge-available' : 'badge-unavailable' }}">
                        {{ $wishlist->item->Availability ? 'Available' : 'Unavailable' }}
                    </span>

                    <div class="item-content">
                        <h3 class="item-title">{{ $wishlist->item->ItemName }}</h3>
                        
                        <div class="item-details">
                            <div class="item-detail">
                                <span>📍</span>
                                <span>{{ $wishlist->item->location->LocationName ?? 'Location N/A' }}</span>
                            </div>
                            <div class="item-detail">
                                <span>🏷️</span>
                                <span>{{ $wishlist->item->category->CategoryName ?? 'Category N/A' }}</span>
                            </div>
                        </div>

                        <div class="item-price">
                            RM {{ number_format($wishlist->item->PricePerDay, 2) }} / day
                        </div>

                        <div class="item-meta">
                            <div class="meta-item">
                                <span>⭐</span>
                                <span>{{ number_format($wishlist->item->reviews->avg('Rating') ?? 0, 1) }} ({{ $wishlist->item->reviews->count() }})</span>
                            </div>
                            <div class="meta-item">
                                <span>📅</span>
                                <span>{{ $wishlist->item->bookings->count() }} bookings</span>
                            </div>
                        </div>

                        <div class="date-added">
                            <span>💝</span>
                            <span>Added {{ $wishlist->DateAdded->diffForHumans() }}</span>
                        </div>

                        <div class="item-actions">
                            <a href="{{ route('item.details', $wishlist->item->ItemID) }}" class="action-btn btn-view">
                                👁️ View Details
                            </a>
                            <button onclick="removeFromWishlist({{ $wishlist->item->ItemID }})" class="action-btn btn-remove">
                                🗑️ Remove
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">💔</div>
            <h2 class="empty-title">Your Wishlist is Empty</h2>
            <p class="empty-text">Start adding items you love to your wishlist and find them easily later!</p>
            <a href="{{ route('user.HomePage') }}" class="browse-btn">
                <span>🔍</span>
                Browse Items
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function removeFromWishlist(itemId) {
        console.log('Removing item:', itemId); // Debug log
        
        if (!confirm('Are you sure you want to remove this item from your wishlist?')) {
            return;
        }

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('Security token not found. Please refresh the page.');
            return;
        }

        console.log('Sending delete request...'); // Debug log

        fetch(`/wishlist/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status); // Debug log
            console.log('Response ok:', response.ok); // Debug log
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response:', text);
                    throw new Error(`Server error: ${response.status}`);
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data); // Debug log
            
            if (data.success) {
                const card = document.getElementById(`wishlist-item-${itemId}`);
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    
                    setTimeout(() => {
                        card.remove();
                        
                        const countElement = document.querySelector('.wishlist-count');
                        if (countElement) {
                            const currentCount = parseInt(countElement.textContent);
                            const newCount = currentCount - 1;
                            countElement.textContent = `${newCount} item(s) in your wishlist`;
                            
                            if (newCount === 0) {
                                location.reload();
                            }
                        }
                    }, 300);
                }

                showAlert('success', data.message);
            } else {
                showAlert('error', data.message || 'Failed to remove item from wishlist');
            }
        })
        .catch(error => {
            console.error('Fetch error:', error); // Debug log
            showAlert('error', 'An error occurred: ' + error.message);
        });
    }

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = (type === 'success' ? '✓ ' : '✗ ') + message;
        
        const container = document.querySelector('.wishlist-container');
        const header = container.querySelector('.page-header');
        
        if (container && header) {
            header.after(alertDiv);
            
            setTimeout(() => {
                alertDiv.style.transition = 'opacity 0.3s ease';
                alertDiv.style.opacity = '0';
                setTimeout(() => alertDiv.remove(), 300);
            }, 3000);
        }
    }
</script>
@endpush