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
            flex-wrap: nowrap;
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
            flex-shrink: 0;
        }

        .nav-label {
            flex: 1;
            font-size: var(--text-sm);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-count {
            background: var(--color-gray-200);
            color: var(--color-gray-600);
            padding: 0.125rem 0.5rem;
            border-radius: var(--radius-full);
            font-size: var(--text-xs);
            font-weight: var(--font-semibold);
            flex-shrink: 0;
            white-space: nowrap;
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
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 300px), 1fr));
            gap: 1.5625rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.25rem;
            gap: 0.75rem;
            flex-wrap: wrap;
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
            flex-shrink: 0;
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
            white-space: nowrap;
            flex-shrink: 0;
        }

        .stat-trend.up {
            background: var(--color-success-light);
            color: var(--color-success-hover);
        }

        .stat-trend.down {
            background: var(--color-danger-light);
            color: var(--color-danger-hover);
        }

        .stat-trend.neutral {
            background: var(--color-gray-200);
            color: var(--color-gray-600);
        }

        .stat-card-body {
            margin-bottom: 0.9375rem;
            flex: 1;
        }

        .stat-value {
            font-size: var(--text-3xl);
            font-weight: var(--font-bold);
            color: var(--color-gray-900);
            margin-bottom: 0.3125rem;
            word-break: break-word;
        }

        .stat-label {
            font-size: var(--text-sm);
            color: var(--color-gray-600);
            font-weight: var(--font-medium);
        }

        .stat-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.9375rem;
            border-top: 1px solid var(--color-gray-200);
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .stat-details {
            font-size: var(--text-xs);
            color: var(--color-gray-400);
            flex-shrink: 0;
        }

        .view-details {
            color: var(--color-primary);
            font-size: 0.8125rem;
            font-weight: var(--font-semibold);
            display: flex;
            align-items: center;
            gap: 0.3125rem;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 0.625rem 0.875rem;
            border-radius: 10px;
            font-size: 1.125rem;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
            transition: all 0.3s;
            flex-shrink: 0;
        }

        .mobile-menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .mobile-menu-toggle:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .header-with-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
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
                gap: 1.25rem;
            }

            .stat-card-header {
                gap: 0.625rem;
            }

            .stat-icon-wrapper {
                width: 3.25rem;
                height: 3.25rem;
                font-size: 1.5rem;
            }

            .stat-trend {
                font-size: 0.6875rem;
                padding: 0.25rem 0.5rem;
            }

            .stat-value {
                font-size: var(--text-2xl);
            }

            .stat-label {
                font-size: 0.8125rem;
            }

            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0.5rem 0.75rem;
                font-size: 1rem;
            }

            .header-with-menu {
                order: -1;
                width: 100%;
            }

            .admin-header {
                flex-direction: column;
                gap: 1.25rem;
                align-items: flex-start;
            }

            /* Sidebar Navigation for Mobile */
            .nav-item {
                padding: 0.875rem 1.25rem;
                gap: 0.75rem;
            }

            .nav-icon {
                font-size: 1.25rem;
                width: 1.5rem;
            }

            .nav-label {
                font-size: 0.875rem;
            }

            .nav-count {
                font-size: 0.6875rem;
                padding: 0.125rem 0.4375rem;
            }
        }

        @media (max-width: 768px) {
            .sidebar-header {
                padding: 1.5rem 1.25rem;
            }

            .logo {
                font-size: 1.5rem;
            }

            .nav-item {
                padding: 0.75rem 1rem;
                gap: 0.625rem;
            }

            .nav-icon {
                font-size: 1.125rem;
                width: 1.375rem;
            }

            .nav-label {
                font-size: 0.8125rem;
            }

            .nav-count {
                font-size: 0.625rem;
                padding: 0.1rem 0.375rem;
                max-width: 120px;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Dashboard Cards */
            .dashboard-grid {
                gap: 1rem;
                grid-template-columns: 1fr;
            }

            .stat-card-header {
                gap: 0.5rem;
                margin-bottom: 1rem;
            }

            .stat-icon-wrapper {
                width: 3rem;
                height: 3rem;
                font-size: 1.375rem;
            }

            .stat-trend {
                font-size: 0.625rem;
                padding: 0.25rem 0.4375rem;
            }

            .stat-card-body {
                margin-bottom: 0.75rem;
            }

            .stat-value {
                font-size: 1.75rem;
            }

            .stat-label {
                font-size: 0.75rem;
            }

            .stat-card-footer {
                padding-top: 0.75rem;
                gap: 0.375rem;
            }

            .stat-details {
                font-size: 0.6875rem;
            }

            .view-details {
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .admin-main-content {
                padding: 1.25rem 0.9375rem;
            }

            .mobile-menu-toggle {
                padding: 0.4375rem 0.625rem;
                font-size: 0.9375rem;
            }

            .header-with-menu {
                gap: 0.5rem;
            }

            /* Dashboard Grid */
            .dashboard-grid {
                gap: 0.875rem;
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-card-header {
                gap: 0.375rem;
                margin-bottom: 0.75rem;
                flex-wrap: nowrap;
            }

            .stat-icon-wrapper {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 1.125rem;
            }

            .stat-trend {
                font-size: 0.5625rem;
                padding: 0.1875rem 0.375rem;
                gap: 0.1875rem;
            }

            .stat-trend i {
                font-size: 0.5rem;
            }

            .stat-card-body {
                margin-bottom: 0.625rem;
            }

            .stat-value {
                font-size: 1.5rem;
                margin-bottom: 0.25rem;
            }

            .stat-label {
                font-size: 0.6875rem;
                line-height: 1.2;
            }

            .stat-card-footer {
                padding-top: 0.625rem;
                gap: 0.25rem;
            }

            .stat-details {
                font-size: 0.625rem;
            }

            .view-details {
                font-size: 0.6875rem;
                gap: 0.1875rem;
            }

            .view-details i {
                font-size: 0.625rem;
            }

            .sidebar-header {
                padding: 1.25rem 1rem;
            }

            .logo {
                font-size: 1.375rem;
            }

            .admin-badge {
                font-size: 0.625rem;
                padding: 0.1875rem 0.5625rem;
            }

            .sidebar-nav {
                padding: 1rem 0;
            }

            .nav-item {
                padding: 0.625rem 0.875rem;
                gap: 0.5rem;
            }

            .nav-icon {
                font-size: 1rem;
                width: 1.25rem;
            }

            .nav-label {
                font-size: 0.75rem;
            }

            .nav-count {
                font-size: 0.5625rem;
                padding: 0.0625rem 0.3125rem;
                max-width: 100px;
            }
        }

        @media (max-width: 360px) {
            .mobile-menu-toggle {
                padding: 0.375rem 0.5rem;
                font-size: 0.875rem;
            }

            /* Dashboard Grid */
            .dashboard-grid {
                gap: 0.75rem;
            }

            .stat-card {
                padding: 0.875rem;
            }

            .stat-card-header {
                gap: 0.3125rem;
                margin-bottom: 0.625rem;
            }

            .stat-icon-wrapper {
                width: 2.25rem;
                height: 2.25rem;
                font-size: 1rem;
            }

            .stat-trend {
                font-size: 0.5rem;
                padding: 0.125rem 0.3125rem;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            .stat-label {
                font-size: 0.625rem;
            }

            .stat-card-footer {
                padding-top: 0.5rem;
            }

            .stat-details {
                font-size: 0.5625rem;
            }

            .view-details {
                font-size: 0.625rem;
            }

            /* Sidebar Navigation */
            .nav-item {
                padding: 0.5rem 0.75rem;
                gap: 0.375rem;
            }

            .nav-icon {
                font-size: 0.9375rem;
                width: 1.125rem;
            }

            .nav-label {
                font-size: 0.6875rem;
            }

            .nav-count {
                font-size: 0.5rem;
                padding: 0.0625rem 0.25rem;
                max-width: 80px;
            }
        }
    </style>
</head>
<body>
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
