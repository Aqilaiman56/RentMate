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
                <div class="stat-value">156</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-content">
                <div class="stat-value">142</div>
                <div class="stat-label">Active Users</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">👨‍🎓</div>
            <div class="stat-content">
                <div class="stat-value">98</div>
                <div class="stat-label">Students</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">👨‍🏫</div>
            <div class="stat-content">
                <div class="stat-value">44</div>
                <div class="stat-label">Staff</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Search users by name or email..." class="search-input" id="searchInput">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" id="userTypeFilter">
                <option value="all">All Types</option>
                <option value="student">Students</option>
                <option value="staff">Staff</option>
            </select>
            <select class="filter-select" id="statusFilter">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
            </select>
            <select class="filter-select" id="sortFilter">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="name-az">Name (A-Z)</option>
                <option value="name-za">Name (Z-A)</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">User Accounts</h3>
            <span class="table-count">Showing 8 users</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>User Type</th>
                        <th>Phone</th>
                        <th>Listings</th>
                        <th>Bookings</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar blue">AM</div>
                                <div class="user-info">
                                    <div class="user-name">Ahmad Mahmud</div>
                                    <div class="user-email">ahmad.m@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge student">👨‍🎓 Student</span></td>
                        <td>+60 12-345 6789</td>
                        <td><span class="count-badge">5</span></td>
                        <td><span class="count-badge">12</span></td>
                        <td>Jan 15, 2024</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Profile" onclick="viewUser(1)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit User" onclick="editUser(1)">✏️</button>
                                <button class="btn-icon btn-more" title="More Actions" onclick="showMoreActions(1)">⋮</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar pink">SL</div>
                                <div class="user-info">
                                    <div class="user-name">Siti Lina</div>
                                    <div class="user-email">siti.lina@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge student">👨‍🎓 Student</span></td>
                        <td>+60 11-234 5678</td>
                        <td><span class="count-badge">3</span></td>
                        <td><span class="count-badge">8</span></td>
                        <td>Feb 3, 2024</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Profile" onclick="viewUser(2)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit User" onclick="editUser(2)">✏️</button>
                                <button class="btn-icon btn-more" title="More Actions" onclick="showMoreActions(2)">⋮</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar green">TW</div>
                                <div class="user-info">
                                    <div class="user-name">Tan Wei Ming</div>
                                    <div class="user-email">tan.wei@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge staff">👨‍🏫 Staff</span></td>
                        <td>+60 19-876 5432</td>
                        <td><span class="count-badge">7</span></td>
                        <td><span class="count-badge">15</span></td>
                        <td>Dec 12, 2023</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Profile" onclick="viewUser(3)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit User" onclick="editUser(3)">✏️</button>
                                <button class="btn-icon btn-more" title="More Actions" onclick="showMoreActions(3)">⋮</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar orange">RK</div>
                                <div class="user-info">
                                    <div class="user-name">Raj Kumar</div>
                                    <div class="user-email">raj.k@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge student">👨‍🎓 Student</span></td>
                        <td>+60 16-543 2109</td>
                        <td><span class="count-badge">2</span></td>
                        <td><span class="count-badge">6</span></td>
                        <td>Mar 8, 2024</td>
                        <td><span class="status-badge status-suspended">Suspended</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Profile" onclick="viewUser(4)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit User" onclick="editUser(4)">✏️</button>
                                <button class="btn-icon btn-more" title="More Actions" onclick="showMoreActions(4)">⋮</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar purple">NZ</div>
                                <div class="user-info">
                                    <div class="user-name">Nurul Zahra</div>
                                    <div class="user-email">nurul.z@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge student">👨‍🎓 Student</span></td>
                        <td>+60 13-987 6543</td>
                        <td><span class="count-badge">4</span></td>
                        <td><span class="count-badge">10</span></td>
                        <td>Jan 20, 2024</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Profile" onclick="viewUser(5)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit User" onclick="editUser(5)">✏️</button>
                                <button class="btn-icon btn-more" title="More Actions" onclick="showMoreActions(5)">⋮</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar teal">LC</div>
                                <div class="user-info">
                                    <div class="user-name">Lee Chong</div>
                                    <div class="user-email">lee.chong@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge staff">👨‍🏫 Staff</span></td>
                        <td>+60 17-234 8901</td>
                        <td><span class="count-badge">6</span></td>
                        <td><span class="count-badge">9</span></td>
                        <td>Nov 5, 2023</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Profile" onclick="viewUser(6)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit User" onclick="editUser(6)">✏️</button>
                                <button class="btn-icon btn-more" title="More Actions" onclick="showMoreActions(6)">⋮</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar red">FA</div>
                                <div class="user-info">
                                    <div class="user-name">Fatimah Ali</div>
                                    <div class="user-email">fatimah.a@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge student">👨‍🎓 Student</span></td>
                        <td>+60 14-567 8901</td>
                        <td><span class="count-badge">1</span></td>
                        <td><span class="count-badge">4</span></td>
                        <td>Apr 12, 2024</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Profile" onclick="viewUser(7)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit User" onclick="editUser(7)">✏️</button>
                                <button class="btn-icon btn-more" title="More Actions" onclick="showMoreActions(7)">⋮</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar indigo">KC</div>
                                <div class="user-info">
                                    <div class="user-name">Kevin Chen</div>
                                    <div class="user-email">kevin.chen@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge staff">👨‍🏫 Staff</span></td>
                        <td>+60 18-345 6789</td>
                        <td><span class="count-badge">8</span></td>
                        <td><span class="count-badge">14</span></td>
                        <td>Oct 1, 2023</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Profile" onclick="viewUser(8)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit User" onclick="editUser(8)">✏️</button>
                                <button class="btn-icon btn-more" title="More Actions" onclick="showMoreActions(8)">⋮</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
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