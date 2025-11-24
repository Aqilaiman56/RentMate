<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoRentUMS - Admin Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Admin Layout Specific Styles */
        body {
            background: var(--color-primary-lighter);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Specific Styles */
        .admin-sidebar {
            padding: 0;
        }

        .sidebar-header {
            padding: 1.875rem 1.5625rem;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .logo {
            font-size: var(--text-2xl);
            font-weight: var(--font-bold);
            color: var(--color-primary);
        }

        .admin-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-hover) 100%);
            color: var(--color-white);
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-full);
            font-size: var(--text-xs);
            font-weight: var(--font-semibold);
            margin-top: 0.5rem;
        }

        .sidebar-nav {
            padding: 1.25rem 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.9375rem;
            padding: 0.9375rem 1.5625rem;
            color: var(--color-gray-700);
            text-decoration: none;
            transition: var(--transition-base);
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: var(--color-primary-lighter);
            color: var(--color-primary);
            border-left-color: var(--color-primary);
        }

        .nav-item.active {
            background: var(--color-primary-lighter);
            color: var(--color-primary);
            border-left-color: var(--color-primary);
            font-weight: var(--font-semibold);
        }

        .nav-icon {
            font-size: 1.375rem;
            width: 1.875rem;
            text-align: center;
        }

        .nav-label {
            flex: 1;
            font-size: var(--text-sm);
        }

        .nav-count {
            background: var(--color-gray-200);
            color: var(--color-gray-600);
            padding: 0.125rem 0.5rem;
            border-radius: var(--radius-full);
            font-size: var(--text-xs);
            font-weight: var(--font-semibold);
        }

        .nav-item:hover .nav-count {
            background: var(--color-primary);
            color: var(--color-white);
        }

        /* Main Content */
        .admin-main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 1.875rem;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .header-title {
            font-size: var(--text-3xl);
            font-weight: var(--font-bold);
            color: var(--color-gray-900);
        }

        .header-actions {
            display: flex;
            gap: 0.9375rem;
            align-items: center;
        }

        .notification-btn {
            background: var(--color-white);
            border: 2px solid var(--color-gray-200);
            padding: 0.625rem 0.9375rem;
            border-radius: var(--radius-xl);
            cursor: pointer;
            font-size: 1.125rem;
            transition: var(--transition-base);
            position: relative;
            color: var(--color-gray-700);
        }

        .notification-btn:hover {
            border-color: var(--color-primary);
            background: var(--color-primary-lighter);
        }

        .admin-profile-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--color-white);
            padding: 0.5rem 0.9375rem;
            border-radius: var(--radius-xl);
            border: 2px solid var(--color-gray-200);
            cursor: pointer;
            position: relative;
            transition: var(--transition-base);
        }

        .admin-profile-section:hover {
            border-color: var(--color-primary);
            background: var(--color-primary-lighter);
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-full);
            object-fit: cover;
        }

        .profile-name {
            font-weight: var(--font-semibold);
            color: var(--color-gray-900);
            font-size: var(--text-sm);
        }

        /* Dashboard Grid */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5625rem;
            margin-bottom: 2.5rem;
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.25rem;
        }

        .stat-icon-wrapper {
            width: 3.75rem;
            height: 3.75rem;
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            color: var(--color-white);
        }

        .stat-icon-wrapper.blue {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-hover) 100%);
        }

        .stat-icon-wrapper.green {
            background: linear-gradient(135deg, var(--color-success) 0%, var(--color-success-hover) 100%);
        }

        .stat-icon-wrapper.orange {
            background: linear-gradient(135deg, var(--color-warning) 0%, var(--color-warning-hover) 100%);
        }

        .stat-icon-wrapper.red {
            background: linear-gradient(135deg, var(--color-danger) 0%, var(--color-danger-hover) 100%);
        }

        .stat-icon-wrapper.purple {
            background: linear-gradient(135deg, var(--color-purple) 0%, var(--color-purple-hover) 100%);
        }

        .stat-icon-wrapper.teal {
            background: linear-gradient(135deg, var(--color-teal) 0%, var(--color-teal-hover) 100%);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.3125rem;
            font-size: var(--text-xs);
            font-weight: var(--font-semibold);
            padding: 0.3125rem 0.625rem;
            border-radius: var(--radius-lg);
        }

        .stat-trend.up {
            background: var(--color-success-light);
            color: var(--color-success-hover);
        }

        .stat-trend.down {
            background: var(--color-danger-light);
            color: var(--color-danger-hover);
        }

        .stat-card-body {
            margin-bottom: 0.9375rem;
        }

        .stat-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.9375rem;
            border-top: 1px solid var(--color-gray-200);
        }

        .stat-details {
            font-size: var(--text-xs);
            color: var(--color-gray-400);
        }

        .view-details {
            color: var(--color-primary);
            font-size: 0.8125rem;
            font-weight: var(--font-semibold);
            display: flex;
            align-items: center;
            gap: 0.3125rem;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 1.25rem;
            left: 1.25rem;
            z-index: var(--z-modal);
            background: var(--color-primary);
            color: var(--color-white);
            border: none;
            padding: 0.75rem 0.9375rem;
            border-radius: var(--radius-xl);
            font-size: 1.25rem;
            cursor: pointer;
            box-shadow: var(--shadow-lg);
        }

        /* Responsive */
        @media (max-width: 968px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform var(--transition-base);
            }

            .admin-sidebar.active {
                transform: translateX(0);
            }

            .admin-main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .admin-header {
                flex-direction: column;
                gap: 1.25rem;
                align-items: flex-start;
                margin-top: 3.75rem;
            }
        }

        @media (max-width: 480px) {
            .admin-main-content {
                padding: 1.25rem 0.9375rem;
            }

            .stat-card-value {
                font-size: var(--text-2xl);
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">☰</button>

    <!-- Sidebar -->
    <aside class="sidebar admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">GoRentUMS</div>
            <span class="admin-badge">Admin Panel</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chart-pie"></i></span>
                <span class="nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-users"></i></span>
                <span class="nav-label">Total Users</span>
                <span class="nav-count">{{ $totalUsers ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.listings') }}" class="nav-item {{ request()->routeIs('admin.listings') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-box"></i></span>
                <span class="nav-label">Total Listings</span>
                <span class="nav-count">{{ $totalListings ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.deposits') }}" class="nav-item {{ request()->routeIs('admin.deposits') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-coins"></i></span>
                <span class="nav-label">Total Deposits</span>
                <span class="nav-count">RM {{ number_format($totalDeposits ?? 0, 2) }}</span>
            </a>
            <a href="{{ route('admin.refund-queue') }}" class="nav-item {{ request()->routeIs('admin.refund-queue') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-hand-holding-usd"></i></span>
                <span class="nav-label">Refund Queue</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-flag"></i></span>
                <span class="nav-label">Reports</span>
                <span class="nav-count">{{ $totalReports ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.penalties') }}" class="nav-item {{ request()->routeIs('admin.penalties') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-exclamation-triangle"></i></span>
                <span class="nav-label">Penalty Actions</span>
                <span class="nav-count">{{ $totalPenalties ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.service-fees') }}" class="nav-item {{ request()->routeIs('admin.service-fees') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                <span class="nav-label">Service Fee Transactions</span>
                <span class="nav-count">{{ $serviceFeeCount ?? 0 }}</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main-content">
        @yield('main-content')
    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-menu-toggle');

            if (window.innerWidth <= 968 && sidebar.classList.contains('active')) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>
