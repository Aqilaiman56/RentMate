{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GoRentUMS')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    


    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
            background: linear-gradient(180deg, #E8EEFF 0%, #F5F7FF 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            gap: 30px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .logo-go {
            color: #1e3a8a;
        }

        .logo-rent {
            color: #60a5fa;
        }

        .logo-ums {
            color: #1e3a8a;
        }

        .header-search-container {
            flex: 1;
            max-width: 600px;
        }

        .header-search-bar {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-search-input {
            flex: 1;
            padding: 10px 16px 10px 40px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            background: white;
        }

        .header-search-input:focus {
            outline: none;
            border-color: #4461F2;
            box-shadow: 0 0 0 3px rgba(68, 97, 242, 0.1);
        }

        .header-search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }

        .header-search-button {
            padding: 10px 20px;
            background: #4461F2;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 4px;
            flex-shrink: 0;
            min-height: 42px;
        }

        .header-search-button:hover {
            background: #3651E2;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(68, 97, 242, 0.3);
        }

        .header-search-button:active {
            transform: translateY(0);
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
            position: relative;
        }

        .icon-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            padding: 0 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .notification-badge.hidden {
            display: none;
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
            flex: 1;
            width: 100%;
        }

        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
                flex-wrap: wrap;
            }

            .header-search-container {
                order: 3;
                width: 100%;
                margin-top: 15px;
                max-width: 100%;
            }

            .header-search-bar {
                flex-direction: row;
            }

            .header-search-button {
                width: auto;
                flex-shrink: 0;
                padding: 6px 10px;
                font-size: 12px;
            }

            .profile-name {
                display: none;
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
        <a href="{{ route('user.HomePage') }}" class="logo">
            <span class="logo-go">Go</span><span class="logo-rent">Rent</span><span class="logo-ums">UMS</span>
        </a>

        <!-- Search Bar in Header -->
        <div class="header-search-container">
            <form action="{{ route('user.HomePage') }}" method="GET" class="header-search-bar">
                <span class="header-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input
                    type="text"
                    name="search"
                    class="header-search-input"
                    placeholder="Search items, categories, or locations..."
                    value="{{ request('search') }}"
                >
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit" class="header-search-button">Search</button>
            </form>
        </div>

        <div class="header-icons">
            <a href="{{ route('notifications.index') }}" class="icon-btn" id="notificationIcon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge hidden" id="notificationBadge">0</span>
            </a>
            <a href="{{ route('messages.index') }}" class="icon-btn" id="messageIcon">
                <i class="fas fa-envelope"></i>
                <span class="notification-badge hidden" id="messageBadge">0</span>
            </a>
            
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
                        <span class="dropdown-icon"><i class="fas fa-user"></i></span>
                        <span>Profile Settings</span>
                    </a>
                    <a href="{{ route('user.listings') }}" class="dropdown-item">
                        <span class="dropdown-icon"><i class="fas fa-box"></i></span>
                        <span>My Listings</span>
                    </a>
                    <a href="{{ route('user.add-listing') }}" class="dropdown-item">
                        <span class="dropdown-icon"><i class="fas fa-plus"></i></span>
                        <span>Add Listing</span>
                    </a>
                    <a href="{{ route('user.bookings') }}" class="dropdown-item">
                        <span class="dropdown-icon"><i class="fas fa-calendar"></i></span>
                        <span>My Bookings</span>
                    </a>
                    <a href="{{ route('user.wishlist') }}" class="dropdown-item">
                        <span class="dropdown-icon"><i class="fas fa-heart"></i></span>
                        <span>Wishlist</span>
                    </a>
                    <a href="{{ route('user.report') }}" class="dropdown-item">
                        <span class="dropdown-icon"><i class="fas fa-flag"></i></span>
                        <span>Report Issue</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item logout" onclick="return confirmLogout(event)">
                            <span class="dropdown-icon"><i class="fas fa-sign-out-alt"></i></span>
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

    {{-- Include Footer --}}
    @include('components.footer')

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

    <script>
        // Notification and message count updater
        function updateNotificationCounts() {
            // Fetch notification count
            fetch('{{ route("notifications.unreadCount") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificationBadge');
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error fetching notification count:', error));

            // Fetch message count
            fetch('{{ route("messages.unreadCount") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('messageBadge');
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error fetching message count:', error));
        }

        // Update counts on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationCounts();

            // Update every 30 seconds
            setInterval(updateNotificationCounts, 30000);
        });
    </script>

    @stack('scripts')
</body>
</html>