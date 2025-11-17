@extends('layouts.app')

@section('title', 'My Wishlist - GoRentUMS')

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

    .wishlist-grid {
        display: grid;
        gap: 20px;
    }

    .wishlist-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 20px;
        transition: transform 0.2s;
    }

    .wishlist-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .item-image {
        width: 150px;
        height: 150px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .item-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 10px;
    }

    .item-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
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

    .item-meta {
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

    .item-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .item-price {
        font-size: 20px;
        font-weight: 700;
        color: #4461F2;
    }

    .item-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view {
        background: #4461F2;
        color: white;
    }

    .btn-view:hover {
        background: #3651E2;
    }

    .btn-remove {
        background: #ef4444;
        color: white;
    }

    .btn-remove:hover {
        background: #dc2626;
    }

    @media (max-width: 768px) {
        .wishlist-container {
            padding: 20px 15px;
        }

        .wishlist-card {
            flex-direction: column;
        }

        .item-image {
            width: 100%;
            height: 200px;
        }

        .item-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .item-actions {
            width: 100%;
        }

        .action-btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="wishlist-container">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-heart"></i> My Wishlist</h1>
        <p class="page-subtitle">{{ $wishlistItems->count() }} item(s) in your wishlist</p>
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
                             onerror="this.src='https://via.placeholder.com/150'">
                    @else
                        <img src="https://via.placeholder.com/150"
                             alt="{{ $wishlist->item->ItemName }}"
                             class="item-image">
                    @endif

                    <div class="item-content">
                        <div class="item-header">
                            <div>
                                <h3 class="item-title">{{ $wishlist->item->ItemName }}</h3>
                            </div>
                            <span class="status-badge {{ $wishlist->item->Availability ? 'badge-available' : 'badge-unavailable' }}">
                                {{ $wishlist->item->Availability ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>

                        <div class="item-meta">
                            <div class="meta-item">
                                <span><i class="fas fa-map-marker-alt"></i></span>
                                <span>{{ $wishlist->item->location->LocationName ?? 'N/A' }}</span>
                            </div>
                            <div class="meta-item">
                                <span><i class="fas fa-tag"></i></span>
                                <span>{{ $wishlist->item->category->CategoryName ?? 'N/A' }}</span>
                            </div>
                            <div class="meta-item">
                                <span><i class="fas fa-heart"></i></span>
                                <span>Added {{ $wishlist->DateAdded->diffForHumans() }}</span>
                            </div>
                            <div class="meta-item">
                                <span><i class="fas fa-star"></i></span>
                                <span>{{ number_format($wishlist->item->reviews->avg('Rating') ?? 0, 1) }} ({{ $wishlist->item->reviews->count() }})</span>
                            </div>
                            <div class="meta-item">
                                <span><i class="fas fa-calendar-check"></i></span>
                                <span>{{ $wishlist->item->bookings->count() }} bookings</span>
                            </div>
                        </div>

                        <div class="item-footer">
                            <div class="item-price">
                                RM {{ number_format($wishlist->item->PricePerDay, 2) }} / day
                            </div>
                            <div class="item-actions">
                                <a href="{{ route('item.details', $wishlist->item->ItemID) }}" class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <button onclick="removeFromWishlist({{ $wishlist->item->ItemID }})" class="action-btn btn-remove">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-heart-broken"></i></div>
            <h2 class="empty-title">Your Wishlist is Empty</h2>
            <p class="empty-text">Start adding items you love to your wishlist and find them easily later!</p>
            <a href="{{ route('user.HomePage') }}" class="browse-btn">
                <i class="fas fa-search"></i> Browse Items
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

                        const countElement = document.querySelector('.page-subtitle');
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
        const icon = type === 'success' ? '<i class="fas fa-check"></i> ' : '<i class="fas fa-times"></i> ';
        alertDiv.innerHTML = icon + message;
        
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