@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Users Management</h1>
            <p class="header-description">Manage all registered users, view profiles, and handle account actions</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportUsers()">
                📥 Export Users
            </button>
            <button class="btn btn-primary" onclick="addNewUser()">
                ➕ Add New User
            </button>
        </div>
    </div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue">👥</div>
        <div class="stat-content">
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">✓</div>
        <div class="stat-content">
            <div class="stat-value">{{ $activeUsers }}</div>
            <div class="stat-label">Active This Month</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">👨‍💼</div>
        <div class="stat-content">
            <div class="stat-value">{{ $regularUserCount }}</div>
            <div class="stat-label">Regular Users</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">🔐</div>
        <div class="stat-content">
            <div class="stat-value">{{ $adminCount }}</div>
            <div class="stat-label">Administrators</div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<form action="{{ route('admin.users') }}" method="GET" class="table-controls">
    <div class="search-box">
        <span class="search-icon">🔍</span>
        <input type="text" 
               name="search" 
               placeholder="Search users by name or email..." 
               class="search-input" 
               value="{{ request('search') }}">
    </div>
    <div class="filter-buttons">
        <select class="filter-select" name="user_type" onchange="this.form.submit()">
            <option value="all" {{ request('user_type') == 'all' ? 'selected' : '' }}>All Types</option>
            <option value="User" {{ request('user_type') == 'User' ? 'selected' : '' }}>Users</option>
            <option value="Admin" {{ request('user_type') == 'Admin' ? 'selected' : '' }}>Admins</option>
        </select>
        <select class="filter-select" name="sort" onchange="this.form.submit()">
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
            <option value="name-az" {{ request('sort') == 'name-az' ? 'selected' : '' }}>Name (A-Z)</option>
            <option value="name-za" {{ request('sort') == 'name-za' ? 'selected' : '' }}>Name (Z-A)</option>
        </select>
        <button type="submit" class="btn btn-primary">Apply Filters</button>
    </div>
</form>

<!-- Users Table -->
<div class="table-card">
    <div class="table-header">
        <h3 class="table-title">User Accounts</h3>
        <span class="table-count">Showing {{ $users->count() }} of {{ $users->total() }} users</span>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>User Type</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="user-cell">
                                @if($user->ProfileImage)
                                    <img src="{{ asset('storage/' . $user->ProfileImage) }}" 
                                         alt="{{ $user->UserName }}" 
                                         class="user-avatar-img">
                                @else
                                    <div class="user-avatar {{ ['blue', 'pink', 'green', 'orange', 'purple', 'teal'][($user->UserID) % 6] }}">
                                        {{ strtoupper(substr($user->UserName, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="user-info">
                                    <div class="user-name">{{ $user->UserName }}</div>
                                    <div class="user-email">{{ $user->Email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="type-badge {{ $user->IsAdmin ? 'admin' : 'user' }}">
                                {{ $user->IsAdmin ? '🔐 Admin' : '👤 User' }}
                            </span>
                        </td>
                        <td>{{ $user->CreatedAt ? \Carbon\Carbon::parse($user->CreatedAt)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.show', $user->UserID) }}" 
                                   class="btn-icon btn-view" 
                                   title="View Profile">👁️</a>
                                
                                <button class="btn-icon btn-more" 
                                        title="More Actions" 
                                        onclick="showMoreActions({{ $user->UserID }}, '{{ $user->UserName }}')">⋮</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 40px; color: #6b7280;">
                            No users found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="pagination-container">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @endif
</div>


    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding: 0 20px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-content {
            flex: 1;
        }

        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 8px 0;
        }

        .header-description {
            font-size: 16px;
            color: #6b7280;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
            padding: 0 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.blue { background: #dbeafe; }
        .stat-icon.green { background: #d1fae5; }
        .stat-icon.orange { background: #fed7aa; }
        .stat-icon.purple { background: #e9d5ff; }

        .stat-content {
            flex: 1;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
        }

        /* Table Controls */
        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding: 0 20px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 250px;
            max-width: 400px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
        }

        .search-input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: 0 20px;
            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid #e5e7eb;
        }

        .table-title {
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .table-count {
            font-size: 14px;
            color: #6b7280;
        }

        .table-container {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: #f9fafb;
        }

        .data-table th {
            padding: 16px 20px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .data-table td {
            padding: 16px 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #374151;
        }

        .data-table tbody tr {
            transition: background-color 0.2s;
        }

        .data-table tbody tr:hover {
            background: #f9fafb;
        }

        /* User Cell */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: white;
        }

        .user-avatar.blue { background: #3b82f6; }
        .user-avatar.pink { background: #ec4899; }
        .user-avatar.green { background: #10b981; }
        .user-avatar.orange { background: #f97316; }
        .user-avatar.purple { background: #a855f7; }
        .user-avatar.teal { background: #14b8a6; }
        .user-avatar.red { background: #ef4444; }
        .user-avatar.indigo { background: #6366f1; }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
        }

        .user-email {
            font-size: 12px;
            color: #6b7280;
        }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .type-badge.student {
            background: #dbeafe;
            color: #1e40af;
        }

        .type-badge.staff {
            background: #e9d5ff;
            color: #6b21a8;
        }

        /* Count Badge */
        .count-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #f3f4f6;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            color: #374151;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-suspended {
            background: #fee2e2;
            color: #991b1b;
        }


        .type-badge.user {
    background: #dbeafe;
    color: #1e40af;
}

.type-badge.admin {
    background: #fce7f3;
    color: #9f1239;
}

.user-avatar-img {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    object-fit: cover;
}

.pagination-container {
    padding: 20px;
    display: flex;
    justify-content: center;
}

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .btn-view {
            background: #dbeafe;
        }

        .btn-view:hover {
            background: #bfdbfe;
        }

        .btn-edit {
            background: #fef3c7;
        }

        .btn-edit:hover {
            background: #fde68a;
        }

        .btn-more {
            background: #f3f4f6;
        }

        .btn-more:hover {
            background: #e5e7eb;
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }
    </style>

    <script>
        function exportUsers() {
            alert('Exporting users data...');
            console.log('Export users functionality');
        }

        function addNewUser() {
            alert('Add new user form will open here');
            console.log('Add new user functionality');
        }

        function viewUser(id) {
            alert('View user profile for ID: ' + id);
            console.log('Viewing user:', id);
        }

        function editUser(id) {
            alert('Edit user form for ID: ' + id);
            console.log('Editing user:', id);
        }

        function showMoreActions(id) {
            alert('More actions for user ID: ' + id + '\n- Suspend User\n- Reset Password\n- View Activity Log\n- Delete User');
            console.log('More actions for user:', id);
        }

        // Search functionality
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.data-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
@endsection