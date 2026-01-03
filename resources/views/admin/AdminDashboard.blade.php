@extends('layouts.admin')

@section('main-content')
    <div class="header">

    <style>
        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title {
            font-size: 2rem;
            font-weight: bold;
            color: #1f2937;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 0.9375rem;
            align-items: center;
            margin-left: auto;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            border-radius: 10px;
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            font-size: 18px;
            transition: all 0.3s;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .mobile-menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        /* Profile Section */
        .profile-section {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
            background: white;
            border: 2px solid #E5E7EB;
        }

        .profile-section:hover {
            background-color: #F5F7FF;
            border-color: #4461F2;
        }

        .profile-name {
            font-weight: 500;
            font-size: 14px;
            color: #374151;
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

        /* Notification Button */
        .notification-btn {
            position: relative;
            background: white;
            border: 2px solid #E5E7EB;
            font-size: 20px;
            cursor: pointer;
            padding: 10px 14px;
            border-radius: 10px;
            transition: all 0.3s;
            color: #374151;
        }

        .notification-btn:hover {
            background-color: #F5F7FF;
            border-color: #4461F2;
            color: #4461F2;
        }

        .notification-btn:active {
            transform: scale(0.95);
        }

        .notification-btn.active {
            background-color: #4461F2;
            border-color: #4461F2;
            color: white;
        }

        .notification-btn.active .notification-badge {
            animation: none;
            background: #ef4444;
        }

        .notification-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            min-width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            padding: 0 5px;
            animation: pulse 2s infinite;
            border: 2px solid white;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Notification Dropdown */
        .notification-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
            width: 420px;
            max-width: 90vw;
            max-height: 600px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1001;
            overflow: hidden;
        }

        .notification-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 2px solid #e5e7eb;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .notification-header h3 {
            font-size: 16px;
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .notification-count {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
        }

        .notification-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 20px;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
            position: relative;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f9fafb;
            transform: translateX(2px);
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .notification-red .notification-icon {
            background: #fee2e2;
            color: #dc2626;
        }

        .notification-orange .notification-icon {
            background: #fed7aa;
            color: #ea580c;
        }

        .notification-blue .notification-icon {
            background: #dbeafe;
            color: #2563eb;
        }

        .notification-yellow .notification-icon {
            background: #fef3c7;
            color: #d97706;
        }

        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .notification-message {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .notification-time {
            font-size: 11px;
            color: #9ca3af;
        }

        .notification-badge-small {
            font-size: 10px;
            background: #f3f4f6;
            color: #6b7280;
            padding: 4px 8px;
            border-radius: 8px;
            font-weight: 600;
            white-space: nowrap;
        }

        .notification-empty {
            padding: 60px 20px;
            text-align: center;
            color: #9ca3af;
        }

        .notification-empty i {
            font-size: 48px;
            margin-bottom: 12px;
            color: #d1d5db;
        }

        .notification-empty p {
            font-size: 14px;
            margin: 0;
        }

        .notification-footer {
            padding: 12px 20px;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .notification-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .summary-item {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-item.red {
            background: #fee2e2;
            color: #dc2626;
        }

        .summary-item.orange {
            background: #fed7aa;
            color: #ea580c;
        }

        .summary-item.blue {
            background: #dbeafe;
            color: #2563eb;
        }

        .summary-item.yellow {
            background: #fef3c7;
            color: #d97706;
        }

        .notification-actions {
            display: flex;
            gap: 8px;
        }

        .notification-action-btn {
            flex: 1;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
        }

        .notification-action-btn.read-all {
            background: #4461F2;
            color: white;
        }

        .notification-action-btn.read-all:hover {
            background: #3651E2;
            transform: translateY(-1px);
        }

        .notification-action-btn.clear-all {
            background: #dc2626;
            color: white;
        }

        .notification-action-btn.clear-all:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        .notification-action-btn.view-all {
            background: #e5e7eb;
            color: #374151;
        }

        .notification-action-btn.view-all:hover {
            background: #d1d5db;
            transform: translateY(-1px);
        }

        /* Responsive Styles */
        @media (max-width: 968px) {
            .header {
                align-items: center;
            }

            .header-title {
                font-size: 1.5rem;
            }

            .header-actions {
                margin-left: 0;
                width: 100%;
                justify-content: flex-end;
            }

            .header-with-menu {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .mobile-menu-toggle {
                display: flex;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .header-with-menu {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                gap: 0.625rem;
                width: 100%;
            }

            .header-title {
                font-size: 1.5rem;
                text-align: left;
                flex: 1;
            }

            .header-actions {
                width: 100%;
                justify-content: flex-end;
            }

            .notification-dropdown {
                position: fixed;
                top: auto;
                right: 10px;
                left: 10px;
                width: auto;
                max-height: 500px;
            }

            .notification-btn {
                padding: 8px 12px;
                font-size: 18px;
            }

            .profile-section {
                padding: 6px 10px;
            }

            .profile-name {
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .header-with-menu {
                gap: 0.5rem;
            }

            .header-title {
                font-size: 1.125rem;
                text-align: left;
            }

            .header-actions {
                gap: 0.5rem;
            }

            .notification-btn {
                padding: 6px 10px;
                font-size: 16px;
            }

            .profile-section {
                padding: 5px 8px;
            }

            .profile-name {
                display: none;
            }

            .profile-section .fa-chevron-down {
                display: none !important;
            }

            .profile-section::before {
                content: '\f007';
                font-family: 'Font Awesome 6 Free';
                font-weight: 900;
                font-size: 16px;
            }

            .notification-dropdown {
                max-height: 400px;
            }

            .notification-header h3 {
                font-size: 14px;
            }

            .notification-item {
                padding: 10px 14px;
            }

            .notification-icon {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }

            .notification-title {
                font-size: 13px;
            }

            .notification-message {
                font-size: 12px;
            }
        }

        /* Notification List Scrollbar */
        .notification-list::-webkit-scrollbar {
            width: 6px;
        }

        .notification-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .notification-list::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>

        <div class="header-with-menu">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title">Dashboard Overview</h1>
        </div>
        <div class="header-actions">
            <div style="position: relative;">
                <button class="notification-btn" id="notificationBtn" type="button">
                    <i class="fas fa-bell"></i>
                    @if(($notifications['total_count'] ?? 0) > 0)
                        <span class="notification-badge">{{ $notifications['total_count'] }}</span>
                    @endif
                </button>

                <!-- Notification Dropdown -->
                <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <h3>Notifications</h3>
                    <span class="notification-count">{{ $notifications['total_count'] ?? 0 }} new</span>
                </div>
                <div class="notification-list">
                    @forelse($notifications['items'] ?? [] as $notification)
                        <a href="{{ $notification['link'] }}" class="notification-item notification-{{ $notification['color'] }}">
                            <div class="notification-icon">
                                <i class="fas {{ $notification['icon'] }}"></i>
                            </div>
                            <div class="notification-content">
                                <div class="notification-title">{{ $notification['title'] }}</div>
                                <div class="notification-message">{{ $notification['message'] }}</div>
                                <div class="notification-time">{{ $notification['time'] }}</div>
                            </div>
                            @if(isset($notification['badge']))
                                <div class="notification-badge-small">{{ $notification['badge'] }}</div>
                            @endif
                        </a>
                    @empty
                        <div class="notification-empty">
                            <i class="fas fa-check-circle"></i>
                            <p>No new notifications</p>
                        </div>
                    @endforelse
                </div>
                @if(($notifications['total_count'] ?? 0) > 0)
                    <div class="notification-footer">
                        <div class="notification-summary">
                            @if(($notifications['counts']['reports'] ?? 0) > 0)
                                <span class="summary-item red">{{ $notifications['counts']['reports'] }} Reports</span>
                            @endif
                            @if(($notifications['counts']['refunds'] ?? 0) > 0)
                                <span class="summary-item orange">{{ $notifications['counts']['refunds'] }} Refunds</span>
                            @endif
                            @if(($notifications['counts']['deposits'] ?? 0) > 0)
                                <span class="summary-item blue">{{ $notifications['counts']['deposits'] }} Deposits</span>
                            @endif
                            @if(($notifications['counts']['penalties'] ?? 0) > 0)
                                <span class="summary-item yellow">{{ $notifications['counts']['penalties'] }} Penalties</span>
                            @endif
                        </div>
                        <div class="notification-actions">
                            <form action="{{ route('admin.notifications.readAll') }}" method="POST" style="flex: 1;">
                                @csrf
                                <button type="submit" class="notification-action-btn read-all">
                                    <i class="fas fa-check-double"></i> Mark Read
                                </button>
                            </form>
                            <form action="{{ route('admin.notifications.clearAll') }}" method="POST" style="flex: 1;" onsubmit="return confirm('Clear all notifications? This cannot be undone.')">
                                @csrf
                                <button type="submit" class="notification-action-btn clear-all">
                                    <i class="fas fa-trash-alt"></i> Clear All
                                </button>
                            </form>
                            <a href="{{ route('admin.notifications.index') }}" class="notification-action-btn view-all">
                                <i class="fas fa-list"></i> View All
                            </a>
                        </div>
                    </div>
                @endif
                </div>
            </div>

            <div class="profile-section" id="profileSection">
                <span class="profile-name">{{ auth()->user()->UserName ?? 'Admin' }}</span>
                <i class="fas fa-chevron-down" style="font-size: 12px; color: #666;"></i>

                <!-- Dropdown Menu -->
                <div class="profile-dropdown" id="profileDropdown">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <span class="dropdown-icon"><i class="fas fa-user"></i></span>
                        <span>Profile Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item logout" onclick="confirmLogout(event)">
                            <span class="dropdown-icon"><i class="fas fa-sign-out-alt"></i></span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Total Users Card -->
        <a href="{{ route('admin.users') }}" class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon-wrapper blue"><i class="fas fa-users"></i></div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> Real-time
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                <div class="stat-label">Total Users</div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-details">Registered users</span>
                <span class="view-details">View details <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>

        <!-- Total Listings Card -->
        <a href="{{ route('admin.listings') }}" class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon-wrapper green"><i class="fas fa-box"></i></div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> Real-time
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-value">{{ $totalListings ?? 0 }}</div>
                <div class="stat-label">Total Listings</div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-details">Items available</span>
                <span class="view-details">View details <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>

        <!-- Total Deposits Card -->
        <a href="{{ route('admin.deposits') }}" class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon-wrapper orange"><i class="fas fa-coins"></i></div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> Real-time
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-value">RM {{ number_format($totalDeposits ?? 0, 2) }}</div>
                <div class="stat-label">Total Deposits</div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-details">Held & refunded</span>
                <span class="view-details">View details <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>

        <!-- Reports Card -->
        <a href="{{ route('admin.reports') }}" class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon-wrapper red"><i class="fas fa-flag"></i></div>
                <div class="stat-trend {{ $pendingReports > 0 ? 'up' : 'neutral' }}">
                    <i class="fas fa-exclamation-circle"></i> {{ $pendingReports ?? 0 }} pending
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-value">{{ $totalReports ?? 0 }}</div>
                <div class="stat-label">Reports from Users</div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-details">{{ $pendingReports ?? 0 }} need review</span>
                <span class="view-details">View details <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>

        <!-- Penalty Actions Card -->
        <a href="{{ route('admin.penalties') }}" class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon-wrapper purple"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> Real-time
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-value">{{ $totalPenalties ?? 0 }}</div>
                <div class="stat-label">Penalty Actions</div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-details">RM {{ number_format($totalPenaltyAmount ?? 0, 2) }} collected</span>
                <span class="view-details">View details <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>

        <!-- Service Fee Count Card -->
        <a href="{{ route('admin.service-fees') }}" class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon-wrapper teal"><i class="fas fa-chart-line"></i></div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> Real-time
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-value">{{ $serviceFeeCount ?? 0 }}</div>
                <div class="stat-label">Service Fee Transactions</div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-details">RM {{ number_format($totalServiceFeeAmount ?? 0, 2) }} collected</span>
                <span class="view-details">View details <i class="fas fa-arrow-right"></i></span>
            </div>
        </a>
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

        // Notification Dropdown Toggle
        function toggleNotifications(event) {
            if (event) {
                event.stopPropagation();
            }

            const dropdown = document.getElementById('notificationDropdown');
            const profileDropdown = document.getElementById('profileDropdown');
            const notificationBtn = document.getElementById('notificationBtn');

            // Close profile dropdown if open
            if (profileDropdown) {
                profileDropdown.classList.remove('show');
            }

            // Toggle notification dropdown
            if (dropdown) {
                const isOpen = dropdown.classList.toggle('show');

                // Toggle active class on button
                if (notificationBtn) {
                    if (isOpen) {
                        notificationBtn.classList.add('active');
                    } else {
                        notificationBtn.classList.remove('active');
                    }
                }
            }
        }

        // Initialize notification dropdown handlers
        document.addEventListener('DOMContentLoaded', function() {
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');

            // Prevent dropdown from closing when clicking inside (except for action buttons)
            if (notificationDropdown) {
                notificationDropdown.addEventListener('click', function(e) {
                    // Allow form submissions and links to work
                    if (e.target.closest('form') || e.target.closest('a.notification-action-btn')) {
                        return;
                    }
                    e.stopPropagation();
                });
            }

            // Handle notification button click
            if (notificationBtn) {
                notificationBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleNotifications(e);
                });
            }
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            const notificationBtn = document.getElementById('notificationBtn');
            const notificationDropdown = document.getElementById('notificationDropdown');
            const profileSection = document.getElementById('profileSection');
            const profileDropdown = document.getElementById('profileDropdown');

            // Close notification dropdown if clicking outside
            if (notificationDropdown && notificationBtn) {
                if (!notificationBtn.contains(e.target) && !notificationDropdown.contains(e.target)) {
                    notificationDropdown.classList.remove('show');
                    notificationBtn.classList.remove('active');
                }
            }

            // Close profile dropdown if clicking outside
            if (profileSection && profileDropdown) {
                if (!profileSection.contains(e.target)) {
                    profileDropdown.classList.remove('show');
                }
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
@endsection