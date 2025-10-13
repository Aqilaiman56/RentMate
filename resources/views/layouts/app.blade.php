{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RentMate')</title>
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
            position: sticky;
            top: 0;
            z-index: 100;
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

        /* Main Content Area */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 40px;
        }

        @media (max-width: 768px) {
            .search-bar {
                display: none;
            }

            .profile-name {
                display: none;
            }

            .header {
                padding: 15px 20px;
            }

            .main-content {
                padding: 20px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <header class="header">
        <a href="{{ route('user.HomePage') }}" class="logo">RentMate</a>
        
        <form action="{{ route('user.HomePage') }}" method="GET" class="search-bar">
            <input type="date" name="availability" class="search-input" placeholder="Availability" value="{{ request('availability') }}">
            <input type="text" name="search" class="search-input" placeholder="Item name" value="{{ request('search') }}">
            <select name="location" class="search-input">
                <option value="">Select Location</option>
                @php
                    $allLocations = \App\Models\Location::all();
                @endphp
                @foreach($allLocations as $location)
                    <option value="{{ $location->LocationID }}" {{ request('location') == $location->LocationID ? 'selected' : '' }}>
                        {{ $location->LocationName }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="search-btn">Search</button>
        </form>

        <div class="header-icons">
            <a href="{{ route('notifications.index') }}" class="icon-btn">🔔</a>
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
                        <button type="submit" class="dropdown-item logout" onclick="return confirmLogout(event)">
                            <span class="dropdown-icon">🚪</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        @yield('content')
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
    </script>

    @stack('scripts')
</body>
</html>