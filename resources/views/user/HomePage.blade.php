@extends('layouts.app')

@section('title', 'GoRentUMS - Home')

@push('styles')
<style>
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
    }

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
        border: 1.5px solid #E2E8F0;
        border-radius: 50px;
        font-size: 0.95rem;
        transition: all 0.2s;
        background: white;
        font-family: inherit;
        color: #2D3748;
    }

    .search-input:focus {
        outline: none;
        border-color: #4A5FDC;
        box-shadow: 0 0 0 3px rgba(74, 95, 220, 0.1);
    }

    .search-input::placeholder {
        color: #A0AEC0;
    }

    .search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #A0AEC0;
        font-size: 18px;
    }

    .search-button {
        padding: 16px 32px;
        background: #4A5FDC;
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-family: inherit;
    }

    .search-button:hover {
        background: #3D4FC7;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(74, 95, 220, 0.3);
    }

    .search-button:active {
        transform: translateY(0);
    }

    .categories {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 15px;
        justify-items: center;
        margin-bottom: 40px;
    }

    .category-card {
        background: white;
        border: 1.5px solid #E2E8F0;
        border-radius: 10px;
        padding: 18px 10px;
        width: 100%;
        max-width: 110px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        text-decoration: none;
        color: #2D3748;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .category-card:hover,
    .category-card.active {
        background: #4A5FDC;
        color: white;
        border-color: #4A5FDC;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(74, 95, 220, 0.2);
    }

    .category-icon {
        font-size: 26px;
        margin-bottom: 6px;
        color: inherit;
    }

    .category-name {
        font-size: 0.75rem;
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
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
        text-decoration: none;
        color: inherit;
        display: block;
        border: 1px solid #E2E8F0;
    }

    .item-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .item-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #F7FAFC;
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
        font-size: 1rem;
        font-weight: 600;
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding-right: 10px;
        color: white;
    }

    .heart-btn {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        transition: color 0.2s;
        flex-shrink: 0;
        padding: 0;
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
        font-size: 0.75rem;
        color: #CBD5E0;
        margin-bottom: 12px;
    }

    .item-price {
        font-size: 1.125rem;
        font-weight: 700;
        color: white;
    }

    .no-items {
        text-align: center;
        padding: 60px 20px;
        color: #718096;
        font-size: 1.125rem;
    }

    .no-items p {
        color: #718096;
    }

    .pagination-wrapper {
        margin-top: 50px;
    }

    .clear-filter {
        display: inline-block;
        background: #E53E3E;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        margin-bottom: 20px;
    }

    .clear-filter:hover {
        background: #C53030;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(229, 62, 62, 0.3);
    }

    .clear-filter:active {
        transform: translateY(0);
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


<!-- Clear Filter Button -->
@if(request('category') || request('search'))
    <a href="{{ route('user.HomePage') }}" class="clear-filter"><i class="fa-solid fa-xmark"></i> Clear Filters</a>
@endif

<!-- Categories -->
<div class="categories">
    <a href="{{ route('user.HomePage') }}" class="category-card {{ !request('category') ? 'active' : '' }}">
        <div class="category-icon"><i class="fa-solid fa-house"></i></div>
        <div class="category-name">All</div>
    </a>
    @foreach($categories as $category)
        <a href="{{ route('user.HomePage', ['category' => $category->CategoryID] + request()->only('search')) }}" 
           class="category-card {{ request('category') == $category->CategoryID ? 'active' : '' }}">
            <div class="category-icon">
                @switch($category->CategoryName)
                    @case('Gaming') <i class="fa-solid fa-gamepad"></i> @break
                    @case('Music') <i class="fa-solid fa-music"></i> @break
                    @case('Computer') <i class="fa-solid fa-laptop"></i> @break
                    @case('Photography') <i class="fa-solid fa-camera"></i> @break
                    @case('Camera') <i class="fa-solid fa-camera-retro"></i> @break
                    @case('Attire') <i class="fa-solid fa-shirt"></i> @break
                    @case('Books') <i class="fa-solid fa-book"></i> @break
                    @case('Event') <i class="fa-solid fa-calendar-days"></i> @break
                    @case('Sports') <i class="fa-solid fa-futbol"></i> @break
                    @case('Electric') <i class="fa-solid fa-bolt"></i> @break
                    @default <i class="fa-solid fa-box"></i>
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
            @php
                $firstImage = $item->images->first();
            @endphp
            @if($firstImage)
                <img src="{{ asset('storage/' . $firstImage->ImagePath) }}" alt="{{ $item->ItemName }}" class="item-image">
            @else
                <img src="https://via.placeholder.com/300x200/4461F2/fff?text={{ urlencode($item->ItemName) }}" alt="{{ $item->ItemName }}" class="item-image">
            @endif

            <div class="item-details">
                <div class="item-header">
                    <div class="item-title">{{ $item->ItemName }}</div>
                    <button class="heart-btn {{ $item->isInWishlist ? 'active' : '' }}" onclick="toggleWishlist(event, {{ $item->ItemID }})">
                        @if($item->isInWishlist)
                            <i class="fa-solid fa-heart"></i>
                        @else
                            <i class="fa-regular fa-heart"></i>
                        @endif
                    </button>
                </div>
                <div class="item-location">
                    <i class="fa-solid fa-location-dot"></i> {{ $item->location->LocationName ?? 'Location not set' }}
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
            const heartBtn = event.target.closest('.heart-btn');
            if(data.added) {
                heartBtn.innerHTML = '<i class="fa-solid fa-heart"></i>';
                heartBtn.classList.add('active');
            } else {
                heartBtn.innerHTML = '<i class="fa-regular fa-heart"></i>';
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