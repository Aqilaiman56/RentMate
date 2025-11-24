{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GoRentUMS')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Layout-specific styles that use design system variables */
        body {
            background: linear-gradient(180deg, var(--color-primary-light) 0%, var(--color-primary-lighter) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header specific styles */
        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 2.5rem;
            gap: 1.875rem;
            overflow: visible;
        }

        .logo {
            font-size: var(--text-2xl);
            font-weight: var(--font-bold);
            text-decoration: none;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .logo-go { color: var(--color-secondary); }
        .logo-rent { color: var(--color-accent); }
        .logo-ums { color: var(--color-secondary); }

        .header-search-container {
            flex: 1;
            max-width: 600px;
        }

        .header-search-bar {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-search-input {
            flex: 1;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1.5px solid var(--color-gray-200);
            border-radius: var(--radius-lg);
            font-size: var(--text-sm);
            transition: var(--transition-base);
            background: var(--color-white);
        }

        .header-search-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(68, 97, 242, 0.1);
        }

        .header-search-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-gray-400);
            font-size: var(--text-sm);
        }

        .header-icons {
            display: flex;
            gap: 1.25rem;
            align-items: center;
            position: relative;
        }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            text-decoration: none;
            padding: 0.5rem;
            border-radius: var(--radius-lg);
            transition: var(--transition-base);
            position: relative;
            color: var(--color-gray-700);
        }

        .icon-btn:hover {
            background-color: var(--color-gray-100);
        }

        .notification-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: var(--color-danger);
            color: var(--color-white);
            border-radius: var(--radius-full);
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6875rem;
            font-weight: var(--font-semibold);
            padding: 0 0.25rem;
            box-shadow: var(--shadow-sm);
        }

        .notification-badge.hidden {
            display: none;
        }

        .profile-section {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.5rem 0.75rem;
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: var(--transition-base);
        }

        .profile-section:hover {
            background-color: var(--color-gray-100);
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-full);
            object-fit: cover;
        }

        .profile-name {
            font-weight: var(--font-medium);
            font-size: var(--text-sm);
            color: var(--color-gray-800);
        }

        .main-content {
            max-width: var(--container-max-width);
            margin: 0 auto;
            padding: 1.875rem 2.5rem;
            flex: 1;
            width: 100%;
        }

        /* Profile dropdown specific overrides */
        .profile-section .dropdown-menu {
            min-width: 200px !important;
            max-width: none !important;
            width: max-content !important;
        }

        .profile-section .dropdown-item {
            white-space: nowrap !important;
            overflow: visible !important;
            text-overflow: clip !important;
            display: flex !important;
            align-items: center !important;
            gap: 0.75rem !important;
        }

        .profile-section .dropdown-item span {
            display: inline-block;
        }

        @media (max-width: 768px) {
            .app-header {
                padding: 0.9375rem 1.25rem;
                flex-wrap: wrap;
            }

            .header-search-container {
                order: 3;
                width: 100%;
                margin-top: 0.9375rem;
                max-width: 100%;
            }

            .header-search-bar {
                flex-direction: row;
            }

            .btn-primary {
                width: auto;
                flex-shrink: 0;
                padding: 0.375rem 0.625rem;
                font-size: var(--text-xs);
            }

            .profile-name {
                display: none;
            }

            .main-content {
                padding: 1.25rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @if(!isset($hideHeader) || !$hideHeader)
    <header class="header app-header">
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
                <button type="submit" class="btn btn-primary">Search</button>
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

            <div class="profile-section dropdown" id="profileSection">
                @if(auth()->user()->ProfileImage)
                    <img src="{{ asset('storage/' . auth()->user()->ProfileImage) }}" alt="Profile" class="profile-pic">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Profile" class="profile-pic" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2240%22 height=%2240%22 viewBox=%220 0 40 40%22%3E%3Crect fill=%22%23e5e7eb%22 width=%2240%22 height=%2240%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22sans-serif%22 font-size=%2216%22 fill=%22%236b7280%22%3E{{ substr(auth()->user()->UserName ?? 'U', 0, 1) }}%3C/text%3E%3C/svg%3E'">
                @endif
                <span class="profile-name">{{ auth()->user()->UserName ?? 'User' }}</span>

                <!-- Dropdown Menu -->
                <div class="dropdown-menu" id="profileDropdown">
                    <a href="{{ route('user.profile') }}" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        <span>Profile Settings</span>
                    </a>
                    <a href="{{ route('user.listings') }}" class="dropdown-item">
                        <i class="fas fa-box"></i>
                        <span>My Listings</span>
                    </a>
                    <a href="{{ route('user.add-listing') }}" class="dropdown-item">
                        <i class="fas fa-plus"></i>
                        <span>Add Listing</span>
                    </a>
                    <a href="{{ route('user.bookings') }}" class="dropdown-item">
                        <i class="fas fa-calendar"></i>
                        <span>My Bookings</span>
                    </a>
                    <a href="{{ route('user.wishlist') }}" class="dropdown-item">
                        <i class="fas fa-heart"></i>
                        <span>Wishlist</span>
                    </a>
                    <a href="{{ route('user.report') }}" class="dropdown-item">
                        <i class="fas fa-flag"></i>
                        <span>Report Issue</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-red-600 hover:bg-red-50" onclick="return confirmLogout(event)">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    @endif

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
