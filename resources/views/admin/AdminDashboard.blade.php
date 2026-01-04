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
            text-decoration: none;
            display: inline-block;
        }

        .notification-btn:hover {
            background-color: #F5F7FF;
            border-color: #4461F2;
            color: #4461F2;
        }

        .notification-btn:active {
            transform: scale(0.95);
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
        }
    </style>

        <div class="header-with-menu">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title">Dashboard Overview</h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.notifications.index') }}" class="notification-btn" id="notificationBtn">
                <i class="fas fa-bell"></i>
                @if(($notifications['total_count'] ?? 0) > 0)
                    <span class="notification-badge">{{ $notifications['total_count'] }}</span>
                @endif
            </a>

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

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const profileSection = document.getElementById('profileSection');
            const profileDropdown = document.getElementById('profileDropdown');

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