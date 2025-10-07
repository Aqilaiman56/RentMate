<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentMate - Home</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #E8EEFF 0%, #F5F7FF 100%);
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #4461F2;
            text-decoration: none;
        }

        .search-bar {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-input {
            padding: 12px 20px;
            border: 2px solid #E5E7EB;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            min-width: 150px;
        }

        .search-input:focus {
            border-color: #4461F2;
        }

        .search-btn {
            background: #4461F2;
            color: white;
            padding: 12px 32px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .search-btn:hover {
            background: #3651E2;
        }

        .header-icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            text-decoration: none;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .icon-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        /* Profile Section with Dropdown */
        .profile-section {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .profile-section:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-name {
            font-weight: 500;
            font-size: 14px;
            color: #1f2937;
        }

        /* Dropdown Menu */
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow: hidden;
        }

        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.2s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: #f5f5f5;
        }

        .dropdown-item.logout {
            color: #dc3545;
            border-top: 1px solid #e0e0e0;
        }

        .dropdown-item.logout:hover {
            background-color: #fff5f5;
        }

        .dropdown-icon {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .logout-form {
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 40px;
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
            .search-bar {
                display: none;
            }
            
            .categories {
                overflow-x: scroll;
            }

            .profile-name {
                display: none;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('user.HomePage') }}" class="logo">RentMate</a>
        
        <form action="{{ route('user.HomePage') }}" method="GET" class="search-bar">
            <input type="date" name="availability" class="search-input" placeholder="Availability" value="{{ request('availability') }}">
            <input type="text" name="search" class="search-input" placeholder="Item name" value="{{ request('search') }}">
            <select name="location" class="search-input">
                <option value="">Select Location</option>
                @foreach($locations as $location)
                    <option value="{{ $location->LocationID }}" {{ request('location') == $location->LocationID ? 'selected' : '' }}>
                        {{ $location->LocationName }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="search-btn">Search</button>
        </form>

        <div class="header-icons">
            <a href="{{ route('notifications') }}" class="icon-btn">🔔</a>
            <a href="{{ route('messages.index') }}" class="icon-btn">✉️</a>
            
            <div class="profile-section" id="profileSection">
                @if(auth()->user()->ProfileImage)
                    <img src="{{ asset('storage/' . auth()->user()->ProfileImage) }}" alt="Profile" class="profile-pic">
                @else
                    <img src="https://via.placeholder.com/40" alt="Profile" class="profile-pic">
                @endif
                <span class="profile-name">{{ auth()->user()->UserName ?? 'User' }}</span>
                
                <!-- Dropdown Menu -->
                <div class="profile-dropdown" id="profileDropdown">
                    <a href="{{ route('user.profile') }}" class="dropdown-item">
                        <span class="dropdown-icon">👤</span>
                        <span>Profile Settings</span>
                    </a>
                    <a href="{{ route('user.listings') }}" class="dropdown-item">
                        <span class="dropdown-icon">📦</span>
                        <span>My Listings</span>
                    </a>
                    <a href="{{ route('user.add-listing') }}" class="dropdown-item">
                        <span class="dropdown-icon">➕</span>
                        <span>Add Listing</span>
                    </a>
                    <a href="{{ route('user.bookings') }}" class="dropdown-item">
                        <span class="dropdown-icon">📅</span>
                        <span>My Bookings</span>
                    </a>
                    <a href="{{ route('user.wishlist') }}" class="dropdown-item">
                        <span class="dropdown-icon">❤️</span>
                        <span>Wishlist</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item logout" onclick="confirmLogout(event)">
                            <span class="dropdown-icon">🚪</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
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
    </div>

    <script>
        // Profile Dropdown Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const profileSection = document.getElementById('profileSection');
            const profileDropdown = document.getElementById('profileDropdown');
            
            if (profileSection && profileDropdown) {
                // Toggle dropdown on click
                profileSection.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('show');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileSection.contains(e.target)) {
                        profileDropdown.classList.remove('show');
                    }
                });
                
                // Prevent dropdown from closing when clicking inside it
                profileDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });

        // Logout Confirmation
        function confirmLogout(event) {
            event.preventDefault();
            
            if (confirm('Are you sure you want to logout?')) {
                event.target.closest('form').submit();
            }
            
            return false;
        }

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
</body>
</html>