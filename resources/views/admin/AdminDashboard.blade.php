<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentMate - Admin Dashboard</title>
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
            background: #F5F7FF;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid #E5E7EB;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #4461F2;
        }

        .admin-badge {
            display: inline-block;
            background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 25px;
            color: #374151;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: #F5F7FF;
            color: #4461F2;
            border-left-color: #4461F2;
        }

        .nav-item.active {
            background: #F5F7FF;
            color: #4461F2;
            border-left-color: #4461F2;
            font-weight: 600;
        }

        .nav-icon {
            font-size: 22px;
            width: 30px;
            text-align: center;
        }

        .nav-label {
            flex: 1;
            font-size: 14px;
        }

        .nav-count {
            background: #E5E7EB;
            color: #6B7280;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
        }

        .nav-item:hover .nav-count {
            background: #4461F2;
            color: white;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            color: #1E3A5F;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .notification-btn {
            background: white;
            border: 2px solid #E5E7EB;
            padding: 10px 15px;
            border-radius: 12px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s;
            position: relative;
        }

        .notification-btn:hover {
            border-color: #4461F2;
            background: #F5F7FF;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #EF4444;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 8px 15px;
            border-radius: 12px;
            border: 2px solid #E5E7EB;
            cursor: pointer;
            position: relative;
            transition: all 0.3s;
        }

        .profile-section:hover {
            border-color: #4461F2;
            background: #F5F7FF;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-name {
            font-weight: 600;
            color: #1E3A5F;
            font-size: 14px;
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1000;
        }

        .profile-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
            border-bottom: 1px solid #F3F4F6;
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-item:hover {
            background: #F5F7FF;
            color: #4461F2;
        }

        .dropdown-item.logout {
            color: #EF4444;
        }

        .dropdown-item.logout:hover {
            background: #FEE2E2;
        }

        .dropdown-icon {
            font-size: 18px;
        }

        .logout-form {
            display: contents;
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(68, 97, 242, 0.15);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .stat-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .stat-icon-wrapper.blue {
            background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
        }

        .stat-icon-wrapper.green {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .stat-icon-wrapper.orange {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }

        .stat-icon-wrapper.red {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        }

        .stat-icon-wrapper.purple {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        }

        .stat-icon-wrapper.teal {
            background: linear-gradient(135deg, #14B8A6 0%, #0D9488 100%);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 8px;
        }

        .stat-trend.up {
            background: #DCFCE7;
            color: #059669;
        }

        .stat-trend.down {
            background: #FEE2E2;
            color: #DC2626;
        }

        .stat-card-body {
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1E3A5F;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #6B7280;
            font-weight: 500;
        }

        .stat-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #F3F4F6;
        }

        .stat-details {
            font-size: 12px;
            color: #9CA3AF;
        }

        .view-details {
            color: #4461F2;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 101;
            background: #4461F2;
            color: white;
            border: none;
            padding: 12px 15px;
            border-radius: 12px;
            font-size: 20px;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                gap: 20px;
                align-items: flex-start;
                margin-top: 60px;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 20px 15px;
            }

            .stat-value {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">☰</button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">RentMate</div>
            <span class="admin-badge">Admin Panel</span>
        </div>
        
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
                <span class="nav-icon">📊</span>
                <span class="nav-label">Dashboard</span>
            </a>
            <a href="#users" class="nav-item">
                <span class="nav-icon">👥</span>
                <span class="nav-label">Total Users</span>
                <span class="nav-count">{{ $totalUsers ?? 0 }}</span>
            </a>
            <a href="#listings" class="nav-item">
                <span class="nav-icon">📦</span>
                <span class="nav-label">Total Listings</span>
                <span class="nav-count">{{ $totalListings ?? 0 }}</span>
            </a>
            <a href="#deposits" class="nav-item">
                <span class="nav-icon">💰</span>
                <span class="nav-label">Total Deposits</span>
                <span class="nav-count">RM {{ number_format($totalDeposits ?? 0, 2) }}</span>
            </a>
            <a href="#reports" class="nav-item">
                <span class="nav-icon">📋</span>
                <span class="nav-label">Reports</span>
                <span class="nav-count">{{ $totalReports ?? 0 }}</span>
            </a>
            <a href="#penalties" class="nav-item">
                <span class="nav-icon">⚠️</span>
                <span class="nav-label">Penalty Actions</span>
                <span class="nav-count">{{ $totalPenalties ?? 0 }}</span>
            </a>
            <a href="#taxes" class="nav-item">
                <span class="nav-icon">📊</span>
                <span class="nav-label">Tax Count</span>
                <span class="nav-count">{{ $taxCount ?? 0 }}</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <h1 class="header-title">Dashboard Overview</h1>
            <div class="header-actions">
                <button class="notification-btn">
                    🔔
                    <span class="notification-badge">3</span>
                </button>
                <div class="profile-section" id="profileSection">
                    @if(auth()->user()->ProfileImage)
                        <img src="{{ asset('storage/' . auth()->user()->ProfileImage) }}" alt="Admin" class="profile-pic">
                    @else
                        <img src="https://via.placeholder.com/40" alt="Admin" class="profile-pic">
                    @endif
                    <span class="profile-name">{{ auth()->user()->UserName ?? 'Admin' }}</span>
                    
                    <!-- Dropdown Menu -->
                    <div class="profile-dropdown" id="profileDropdown">
                        <a href="#" class="dropdown-item">
                            <span class="dropdown-icon">👤</span>
                            <span>Profile Settings</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <span class="dropdown-icon">⚙️</span>
                            <span>Admin Settings</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item logout">
                                <span class="dropdown-icon">🚪</span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <!-- Total Users Card -->
            <a href="#users" class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper blue">👥</div>
                    <div class="stat-trend up">
                        ↑ 12%
                    </div>
                </div>
                <div class="stat-card-body">
                    <div class="stat-value">{{ $totalUsers ?? 0 }}</div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card-footer">
                    <span class="stat-details">Active this month</span>
                    <span class="view-details">View details →</span>
                </div>
            </a>

            <!-- Total Listings Card -->
            <a href="#listings" class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper green">📦</div>
                    <div class="stat-trend up">
                        ↑ 8%
                    </div>
                </div>
                <div class="stat-card-body">
                    <div class="stat-value">{{ $totalListings ?? 0 }}</div>
                    <div class="stat-label">Total Listings</div>
                </div>
                <div class="stat-card-footer">
                    <span class="stat-details">Items available</span>
                    <span class="view-details">View details →</span>
                </div>
            </a>

            <!-- Total Deposits Card -->
            <a href="#deposits" class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper orange">💰</div>
                    <div class="stat-trend up">
                        ↑ 15%
                    </div>
                </div>
                <div class="stat-card-body">
                    <div class="stat-value">RM {{ number_format($totalDeposits ?? 0, 2) }}</div>
                    <div class="stat-label">Total Deposits</div>
                </div>
                <div class="stat-card-footer">
                    <span class="stat-details">Collected deposits</span>
                    <span class="view-details">View details →</span>
                </div>
            </a>

            <!-- Reports Card -->
            <a href="#reports" class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper red">📋</div>
                    <div class="stat-trend down">
                        ↓ 5%
                    </div>
                </div>
                <div class="stat-card-body">
                    <div class="stat-value">{{ $totalReports ?? 0 }}</div>
                    <div class="stat-label">Reports from Users</div>
                </div>
                <div class="stat-card-footer">
                    <span class="stat-details">{{ $pendingReports ?? 0 }} pending</span>
                    <span class="view-details">View details →</span>
                </div>
            </a>

            <!-- Penalty Actions Card -->
            <a href="#penalties" class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper purple">⚠️</div>
                    <div class="stat-trend down">
                        ↓ 3%
                    </div>
                </div>
                <div class="stat-card-body">
                    <div class="stat-value">{{ $totalPenalties ?? 0 }}</div>
                    <div class="stat-label">Penalty Actions</div>
                </div>
                <div class="stat-card-footer">
                    <span class="stat-details">RM {{ number_format($totalPenaltyAmount ?? 0, 2) }} collected</span>
                    <span class="view-details">View details →</span>
                </div>
            </a>

            <!-- Tax Count Card -->
            <a href="#taxes" class="stat-card">
                <div class="stat-card-header">
                    <div class="stat-icon-wrapper teal">📊</div>
                    <div class="stat-trend up">
                        ↑ 7%
                    </div>
                </div>
                <div class="stat-card-body">
                    <div class="stat-value">{{ $taxCount ?? 0 }}</div>
                    <div class="stat-label">Tax Transactions</div>
                </div>
                <div class="stat-card-footer">
                    <span class="stat-details">RM {{ number_format($totalTaxAmount ?? 0, 2) }} collected</span>
                    <span class="view-details">View details →</span>
                </div>
            </a>
        </div>
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        function toggleProfileDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');
            const dropdown = document.getElementById('profileDropdown');
            const profileSection = document.querySelector('.profile-section');
            
            // Close sidebar on mobile
            if (window.innerWidth <= 968 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }

            // Close dropdown if clicking outside
            if (!profileSection.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });
    </script>
</body>
</html>