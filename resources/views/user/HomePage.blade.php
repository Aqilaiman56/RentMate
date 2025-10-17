{{-- resources/views/user/HomePage.blade.php --}}
@extends('layouts.app')

@section('title', 'RentMate - Home')

@push('styles')
<style>
    /* Search Bar Styles */
    .search-container {
        max-width: 100%;
        margin-bottom: 30px;
    }

    .search-bar {
        position: relative;
        display: flex;
        gap: 10px;
    }

    .search-input {
        flex: 1;
        padding: 16px 20px 16px 50px;
        border: 2px solid #e5e7eb;
        border-radius: 50px;
        font-size: 15px;
        transition: all 0.3s;
        background: white;
    }

    .search-input:focus {
        outline: none;
        border-color: #4461F2;
        box-shadow: 0 0 0 3px rgba(68, 97, 242, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 18px;
    }

    .search-button {
        padding: 16px 32px;
        background: #4461F2;
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .search-button:hover {
        background: #3651E2;
        transform: translateY(-2px);
    }

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
        min-width: 120px;
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
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 10px;
    }

    .heart-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: color 0.3s;
        flex-shrink: 0;
    }

    .heart-btn:hover {
        color: #FF6B6B;
    }

    .heart-btn.active {
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
    }

    .no-items {
        text-align: center;
        padding: 60px 20px;
        color: #6B7280;
        font-size: 18px;
    }

    .pagination-wrapper {
        margin-top: 50px;
    }

    .clear-filter {
        display: inline-block;
        background: #ef4444;
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        margin-bottom: 20px;
    }

    .clear-filter:hover {
        background: #dc2626;
    }

    @media (max-width: 768px) {
        .categories {
            overflow-x: scroll;
        }

        .search-bar {
            flex-direction: column;
        }

        .search-button {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<!-- Search Bar -->
<div class="search-container">
    <form action="{{ route('user.HomePage') }}" method="GET" class="search-bar">
        <span class="search-icon">🔍</span>
        <input 
            type="text" 
            name="search" 
            class="search-input" 
            placeholder="Search for items, categories, or locations..."
            value="{{ request('search') }}"
        >
        @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

<!-- Clear Filter Button -->
@if(request('category') || request('search'))
    <a href="{{ route('user.HomePage') }}" class="clear-filter">✕ Clear Filters</a>
@endif

<!-- Categories -->
<div class="categories">
    <a href="{{ route('user.HomePage') }}" class="category-card {{ !request('category') ? 'active' : '' }}">
        <div class="category-icon">🏠</div>
        <div class="category-name">All</div>
    </a>
    @foreach($categories as $category)
        <a href="{{ route('user.HomePage', ['category' => $category->CategoryID] + request()->only('search')) }}" 
           class="category-card {{ request('category') == $category->CategoryID ? 'active' : '' }}">
            <div class="category-icon">
                @switch($category->CategoryName)
                    @case('Gaming') 🎮 @break
                    @case('Music') 🎵 @break
                    @case('Computer') 💻 @break
                    @case('Photography') 📷 @break
                    @case('Camera') 📷 @break
                    @case('Attire') 👔 @break
                    @case('Books') 📚 @break
                    @case('Events') 🎁 @break
                    @case('Event') 🎁 @break
                    @case('Sport') ⚽ @break
                    @case('Sports') ⚽ @break
                    @case('Electric') ⚡ @break
                    @default 📦
                @endswitch
            </div>
            <div class="category-name">{{ $category->CategoryName }}</div>
        </a>
    @endforeach
</div>

<!-- Items Grid -->
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
                    <button class="heart-btn {{ $item->isInWishlist ? 'active' : '' }}" onclick="toggleWishlist(event, {{ $item->ItemID }})">
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
            </div>
        </a>
    @empty
        <div class="no-items" style="grid-column: 1/-1;">
            @if(request('search'))
                <p>No items found for "{{ request('search') }}"</p>
                <p style="margin-top: 10px; font-size: 14px;">Try a different search term or browse all items.</p>
            @else
                <p>No items available at the moment.</p>
                <p style="margin-top: 10px; font-size: 14px;">Be the first to list an item!</p>
            @endif
        </div>
    @endforelse
</div>

@if($items->hasPages())
    <div class="pagination-wrapper">
        @include('components.pagination', ['paginator' => $items])
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
                heartBtn.classList.add('active');
            } else {
                heartBtn.textContent = '♡';
                heartBtn.classList.remove('active');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Please login to add items to wishlist');
        });
    }
</script>
@endpush