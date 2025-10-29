@extends('layouts.admin')

@section('main-content')
    <div class="header">

    <style>
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
        }

        .profile-section:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .profile-name {
            font-weight: 500;
            font-size: 14px;
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
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .notification-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }
    </style>

        <h1 class="header-title">Dashboard Overview</h1>
        <div class="header-actions">
            <button class="notification-btn">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </button>
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

        <!-- Tax Count Card -->
        <a href="{{ route('admin.taxes') }}" class="stat-card">
            <div class="stat-card-header">
                <div class="stat-icon-wrapper teal"><i class="fas fa-chart-line"></i></div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i> Real-time
                </div>
            </div>
            <div class="stat-card-body">
                <div class="stat-value">{{ $taxCount ?? 0 }}</div>
                <div class="stat-label">Tax Transactions</div>
            </div>
            <div class="stat-card-footer">
                <span class="stat-details">RM {{ number_format($totalTaxAmount ?? 0, 2) }} collected</span>
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