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
            position: relative;
        }

        .header-search-bar {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Hide mobile search toggle on desktop */
        @media (min-width: 769px) {
            .mobile-search-toggle {
                display: none !important;
            }
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
        .profile-section {
            position: relative;
        }

        .profile-section .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            min-width: 220px;
            max-width: 280px;
            width: max-content;
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
            z-index: 1000;
            display: none;
            animation: fadeIn 0.2s ease-in-out;
        }

        .profile-section .dropdown-menu.show {
            display: block;
        }

        .profile-section .dropdown-item {
            white-space: nowrap;
            overflow: visible;
            text-overflow: clip;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #374151;
            transition: all 0.15s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .profile-section .dropdown-item:hover {
            background-color: #F3F4F6;
            color: #1F2937;
        }

        .profile-section .dropdown-item i {
            width: 20px;
            text-align: center;
            font-size: 1.125rem;
        }

        .profile-section .dropdown-item span {
            display: inline-block;
            flex: 1;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Mobile Search Styles */
        .mobile-search-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0.5rem;
            border-radius: var(--radius-lg);
            transition: var(--transition-base);
            color: var(--color-gray-700);
        }

        .mobile-search-toggle:hover {
            background-color: var(--color-gray-100);
        }

        /* Search Recommendations */
        .search-recommendations {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            right: 0;
            background: var(--color-white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            max-height: 400px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
            border: 1px solid var(--color-gray-200);
        }

        .search-recommendations.show {
            display: block;
            animation: fadeIn 0.2s ease-in-out;
        }

        .recommendation-item {
            padding: 0.875rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: var(--transition-base);
            border-bottom: 1px solid var(--color-gray-100);
            color: var(--color-gray-700);
            font-size: var(--text-sm);
        }

        .recommendation-item:last-child {
            border-bottom: none;
        }

        .recommendation-item:hover {
            background: var(--color-gray-50);
            color: var(--color-primary);
        }

        .recommendation-item i {
            color: var(--color-gray-400);
            font-size: var(--text-base);
        }

        .recommendation-item:hover i {
            color: var(--color-primary);
        }

        .recommendation-category {
            padding: 0.5rem 1rem;
            font-size: var(--text-xs);
            font-weight: var(--font-semibold);
            color: var(--color-gray-500);
            background: var(--color-gray-50);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .app-header {
                padding: 0.9375rem 1.25rem;
                flex-wrap: nowrap;
            }

            /* Desktop search container - hide on mobile when collapsed */
            .header-search-container.collapsed {
                display: none;
            }

            /* Show mobile search toggle button */
            .mobile-search-toggle {
                display: block !important;
            }

            /* When expanded, show search bar as overlay */
            .header-search-container.expanded {
                display: block;
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                width: 100%;
                padding: 0 1.25rem;
                z-index: 999;
                background: linear-gradient(180deg, var(--color-primary-light) 0%, var(--color-primary-lighter) 100%);
                padding-bottom: 1rem;
            }

            .header-search-container.expanded .header-search-bar {
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
                width: 100%;
                animation: slideDown 0.3s ease-out;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .header-search-input {
                flex: 1;
                min-width: 0;
            }

            .btn-primary {
                width: auto;
                flex-shrink: 0;
                padding: 0.625rem 1rem;
                font-size: var(--text-sm);
                white-space: nowrap;
            }

            .profile-name {
                display: none;
            }

            .main-content {
                padding: 1.25rem;
            }

            .search-recommendations {
                position: fixed;
                top: auto;
                left: 1.25rem;
                right: 1.25rem;
                max-height: 60vh;
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

        <!-- Search Bar in Header (Desktop) -->
        <div class="header-search-container collapsed" id="headerSearchContainer">
            <!-- Search Form -->
            <form action="{{ route('user.HomePage') }}" method="GET" class="header-search-bar" id="headerSearchBar">
                <span class="header-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input
                    type="text"
                    name="search"
                    class="header-search-input"
                    id="headerSearchInput"
                    placeholder="Search items, categories, or locations..."
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <button type="submit" class="btn btn-primary">Search</button>
            </form>

            <!-- Search Recommendations -->
            <div class="search-recommendations" id="searchRecommendations">
                <!-- Recommendations will be populated here -->
            </div>
        </div>

        <div class="header-icons">
            <!-- Mobile Search Toggle Button -->
            <button type="button" class="mobile-search-toggle icon-btn" id="mobileSearchToggle">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
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
                <i class="fas fa-chevron-down" style="font-size: 0.75rem; color: #6B7280; transition: transform 0.2s;" id="dropdownCaret"></i>

                <!-- Dropdown Menu -->
                <div class="dropdown-menu" id="profileDropdown">
                    @if(auth()->user()->IsAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item" style="background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); color: #4F46E5; font-weight: 600;">
                            <i class="fas fa-user-shield"></i>
                            <span>Admin Dashboard</span>
                        </a>
                        <div class="dropdown-divider"></div>
                    @endif
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
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="dropdown-item" style="color: #DC2626;" onclick="return confirmLogout(event)">
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
        // Mobile Search Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const searchContainer = document.getElementById('headerSearchContainer');
            const mobileSearchToggle = document.getElementById('mobileSearchToggle');
            const searchInput = document.getElementById('headerSearchInput');
            const searchRecommendations = document.getElementById('searchRecommendations');

            // Toggle search bar on mobile
            if (mobileSearchToggle) {
                mobileSearchToggle.addEventListener('click', function(e) {
                    e.stopPropagation();

                    // Toggle between collapsed and expanded
                    if (searchContainer.classList.contains('expanded')) {
                        searchContainer.classList.remove('expanded');
                        searchContainer.classList.add('collapsed');
                        searchRecommendations.classList.remove('show');
                    } else {
                        searchContainer.classList.remove('collapsed');
                        searchContainer.classList.add('expanded');

                        // Focus on search input after animation
                        setTimeout(() => {
                            searchInput.focus();
                        }, 300);
                    }
                });
            }

            // Close search bar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!searchContainer.contains(e.target) && !mobileSearchToggle.contains(e.target)) {
                        searchContainer.classList.remove('expanded');
                        searchContainer.classList.add('collapsed');
                        searchRecommendations.classList.remove('show');
                    }
                }
            });

            // Search input functionality with recommendations
            if (searchInput) {
                let debounceTimer;

                searchInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const query = this.value.trim();

                    if (query.length >= 2) {
                        debounceTimer = setTimeout(() => {
                            fetchSearchRecommendations(query);
                        }, 300);
                    } else {
                        searchRecommendations.classList.remove('show');
                    }
                });

                // Show recommendations on focus if there's existing text
                searchInput.addEventListener('focus', function() {
                    const query = this.value.trim();
                    if (query.length >= 2) {
                        fetchSearchRecommendations(query);
                    }
                });
            }

            // Fetch search recommendations
            function fetchSearchRecommendations(query) {
                // Sample recommendations - replace with actual API call
                const sampleRecommendations = {
                    popular: [
                        { icon: 'fa-laptop', text: 'Laptop', type: 'item' },
                        { icon: 'fa-camera', text: 'Camera', type: 'item' },
                        { icon: 'fa-gamepad', text: 'Gaming Console', type: 'item' }
                    ],
                    categories: [
                        { icon: 'fa-tag', text: 'Electronics', type: 'category' },
                        { icon: 'fa-tag', text: 'Sports Equipment', type: 'category' },
                        { icon: 'fa-tag', text: 'Tools', type: 'category' }
                    ],
                    locations: [
                        { icon: 'fa-location-dot', text: 'Kuala Lumpur', type: 'location' },
                        { icon: 'fa-location-dot', text: 'Selangor', type: 'location' },
                        { icon: 'fa-location-dot', text: 'Penang', type: 'location' }
                    ]
                };

                // Filter recommendations based on query
                const filteredItems = sampleRecommendations.popular.filter(item =>
                    item.text.toLowerCase().includes(query.toLowerCase())
                );
                const filteredCategories = sampleRecommendations.categories.filter(item =>
                    item.text.toLowerCase().includes(query.toLowerCase())
                );
                const filteredLocations = sampleRecommendations.locations.filter(item =>
                    item.text.toLowerCase().includes(query.toLowerCase())
                );

                // Build recommendations HTML
                let html = '';

                if (filteredItems.length > 0) {
                    html += '<div class="recommendation-category">Items</div>';
                    filteredItems.forEach(item => {
                        html += `
                            <div class="recommendation-item" onclick="selectRecommendation('${item.text}')">
                                <i class="fa-solid ${item.icon}"></i>
                                <span>${item.text}</span>
                            </div>
                        `;
                    });
                }

                if (filteredCategories.length > 0) {
                    html += '<div class="recommendation-category">Categories</div>';
                    filteredCategories.forEach(item => {
                        html += `
                            <div class="recommendation-item" onclick="selectRecommendation('${item.text}')">
                                <i class="fa-solid ${item.icon}"></i>
                                <span>${item.text}</span>
                            </div>
                        `;
                    });
                }

                if (filteredLocations.length > 0) {
                    html += '<div class="recommendation-category">Locations</div>';
                    filteredLocations.forEach(item => {
                        html += `
                            <div class="recommendation-item" onclick="selectRecommendation('${item.text}')">
                                <i class="fa-solid ${item.icon}"></i>
                                <span>${item.text}</span>
                            </div>
                        `;
                    });
                }

                if (html) {
                    searchRecommendations.innerHTML = html;
                    searchRecommendations.classList.add('show');
                } else {
                    searchRecommendations.classList.remove('show');
                }
            }

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    // Desktop mode - remove mobile classes
                    searchContainer.classList.remove('collapsed', 'expanded');
                    searchRecommendations.classList.remove('show');
                } else {
                    // Mobile mode - ensure collapsed state
                    if (!searchContainer.classList.contains('expanded')) {
                        searchContainer.classList.add('collapsed');
                    }
                }
            });

            // Initialize on load
            if (window.innerWidth <= 768) {
                searchContainer.classList.add('collapsed');
            } else {
                searchContainer.classList.remove('collapsed', 'expanded');
            }
        });

        // Select recommendation
        function selectRecommendation(text) {
            const searchInput = document.getElementById('headerSearchInput');
            const searchRecommendations = document.getElementById('searchRecommendations');

            if (searchInput) {
                searchInput.value = text;
                searchRecommendations.classList.remove('show');
                searchInput.focus();
            }
        }

        // Profile Dropdown Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const profileSection = document.getElementById('profileSection');
            const profileDropdown = document.getElementById('profileDropdown');
            const dropdownCaret = document.getElementById('dropdownCaret');

            if (profileSection && profileDropdown) {
                // Toggle dropdown on click
                profileSection.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isShowing = profileDropdown.classList.toggle('show');

                    // Rotate caret icon
                    if (dropdownCaret) {
                        dropdownCaret.style.transform = isShowing ? 'rotate(180deg)' : 'rotate(0deg)';
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileSection.contains(e.target)) {
                        profileDropdown.classList.remove('show');

                        // Reset caret rotation
                        if (dropdownCaret) {
                            dropdownCaret.style.transform = 'rotate(0deg)';
                        }
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
