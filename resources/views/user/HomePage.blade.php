{{-- resources/views/user/HomePage.blade.php --}}
@extends('layouts.app')

@section('title', 'RentMate - Home')

@push('styles')
<style>
    .categories {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding: 20px 0;
        margin-bottom: 30px;
    }

    .category-card {
        background: white;
        border: 3px solid #4461F2;
        border-radius: 15px;
        padding: 20px;
        width: 120px;
        height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        flex-shrink: 0;
    }

    .category-card:hover,
    .category-card.active {
        background: #4461F2;
        color: white;
        transform: translateY(-2px);
    }

    .category-icon {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .category-name {
        font-size: 13px;
        font-weight: 600;
        color: inherit;
    }

    .items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .item-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s;
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .item-card:hover {
        transform: translateY(-5px);
    }

    .item-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f3f4f6;
    }

    .item-details {
        background: #1E3A5F;
        color: white;
        padding: 20px;
    }

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 12px;
    }

    .item-title {
        font-size: 16px;
        font-weight: 600;
    }

    .heart-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: color 0.3s;
    }

    .heart-btn:hover {
        color: #FF6B6B;
    }

    .item-location {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #9CA3AF;
        margin-bottom: 12px;
    }

    .item-price {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .view-detail-btn {
        width: 100%;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 10px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
    }

    .view-detail-btn:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .no-items {
        text-align: center;
        padding: 60px 20px;
        color: #6B7280;
        font-size: 18px;
    }

    @media (max-width: 768px) {
        .categories {
            overflow-x: scroll;
        }
    }
</style>
@endpush

@section('content')
<div class="categories">
    <a href="{{ route('user.HomePage') }}" class="category-card {{ !request('category') ? 'active' : '' }}">
        <div class="category-icon">🏠</div>
        <div class="category-name">All</div>
    </a>
    @foreach($categories as $category)
        <a href="{{ route('user.HomePage', ['category' => $category->CategoryID]) }}" 
           class="category-card {{ request('category') == $category->CategoryID ? 'active' : '' }}">
            <div class="category-icon">
                @switch($category->CategoryName)
                    @case('Gaming') 🎮 @break
                    @case('Music') 🎵 @break
                    @case('Computer') 💻 @break
                    @case('Photography') 📷 @break
                    @case('Attire') 👔 @break
                    @case('Books') 📚 @break
                    @case('Events') 🎁 @break
                    @case('Sport') ⚽ @break
                    @case('Electric') ⚡ @break
                    @default 📦
                @endswitch
            </div>
            <div class="category-name">{{ $category->CategoryName }}</div>
        </a>
    @endforeach
</div>

<div class="items-grid">
    @forelse($items as $item)
        <a href="{{ route('item.details', $item->ItemID) }}" class="item-card">
            @if($item->ImagePath)
                <img src="{{ asset('storage/' . $item->ImagePath) }}" alt="{{ $item->ItemName }}" class="item-image">
            @else
                <img src="https://via.placeholder.com/300x200/4461F2/fff?text={{ urlencode($item->ItemName) }}" alt="{{ $item->ItemName }}" class="item-image">
            @endif
            
            <div class="item-details">
                <div class="item-header">
                    <div class="item-title">{{ $item->ItemName }}</div>
                    <button class="heart-btn" onclick="toggleWishlist(event, {{ $item->ItemID }})">
                        @if($item->isInWishlist)
                            ♥
                        @else
                            ♡
                        @endif
                    </button>
                </div>
                <div class="item-location">
                    📍 {{ $item->location->LocationName ?? 'Location not set' }}
                </div>
                <div class="item-price">
                    @if(isset($item->PricePerDay))
                        RM {{ number_format($item->PricePerDay, 2) }} / day
                    @else
                        Price on request
                    @endif
                </div>
                <button class="view-detail-btn">View Detail</button>
            </div>
        </a>
    @empty
        <div class="no-items" style="grid-column: 1/-1;">
            <p>No items available at the moment.</p>
            <p style="margin-top: 10px; font-size: 14px;">Be the first to list an item!</p>
        </div>
    @endforelse
</div>

@if($items->hasPages())
    <div style="margin-top: 40px; display: flex; justify-content: center;">
        {{ $items->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
    function toggleWishlist(event, itemId) {
        event.preventDefault();
        event.stopPropagation();
        
        fetch(`/wishlist/toggle/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            const heartBtn = event.target;
            if(data.added) {
                heartBtn.textContent = '♥';
                heartBtn.style.color = '#FF6B6B';
            } else {
                heartBtn.textContent = '♡';
                heartBtn.style.color = 'white';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Please login to add items to wishlist');
        });
    }
</script>
@endpush