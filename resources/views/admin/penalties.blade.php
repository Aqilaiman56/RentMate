@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Penalty Management</h1>
            <p class="header-description">Manage penalties and disciplinary actions for users</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="filterPenalties('all')">
                All Penalties
            </button>
            <button class="btn btn-primary" onclick="filterPenalties('pending')">
                Pending Review
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon red">⚠️</div>
            <div class="stat-content">
                <div class="stat-value">24</div>
                <div class="stat-label">Total Penalties</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">⏳</div>
            <div class="stat-content">
                <div class="stat-value">8</div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-content">
                <div class="stat-value">16</div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">💰</div>
            <div class="stat-content">
                <div class="stat-value">RM 2,450.00</div>
                <div class="stat-label">Total Amount</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Search penalties..." class="search-input" id="searchInput">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" id="statusFilter">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="resolved">Resolved</option>
                <option value="rejected">Rejected</option>
            </select>
            <select class="filter-select" id="sortFilter">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="amount-high">Highest Amount</option>
                <option value="amount-low">Lowest Amount</option>
            </select>
        </div>
    </div>

    <!-- Penalties Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Penalty Records</h3>
            <span class="table-count">Showing 5 penalties</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Penalty ID</th>
                        <th>User</th>
                        <th>Violation Type</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="id-badge">#001</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">J</div>
                                <div class="user-info">
                                    <div class="user-name">John Doe</div>
                                    <div class="user-email">john.doe@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="violation-badge">Late Return</span></td>
                        <td class="description-cell">Item returned 5 days after due date</td>
                        <td><span class="amount-badge">RM 50.00</span></td>
                        <td>Oct 5, 2025</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewPenalty(1)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit" onclick="editPenalty(1)">✏️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#002</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">S</div>
                                <div class="user-info">
                                    <div class="user-name">Sarah Smith</div>
                                    <div class="user-email">sarah.smith@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="violation-badge">Item Damage</span></td>
                        <td class="description-cell">Minor damage to rental equipment</td>
                        <td><span class="amount-badge">RM 150.00</span></td>
                        <td>Oct 3, 2025</td>
                        <td><span class="status-badge status-resolved">Resolved</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewPenalty(2)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#003</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">M</div>
                                <div class="user-info">
                                    <div class="user-name">Mike Johnson</div>
                                    <div class="user-email">mike.j@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="violation-badge">Policy Violation</span></td>
                        <td class="description-cell">Subletting rental item without permission</td>
                        <td><span class="amount-badge">RM 200.00</span></td>
                        <td>Oct 1, 2025</td>
                        <td><span class="status-badge status-approved">Approved</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewPenalty(3)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#004</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">E</div>
                                <div class="user-info">
                                    <div class="user-name">Emma Wilson</div>
                                    <div class="user-email">emma.w@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="violation-badge">Late Return</span></td>
                        <td class="description-cell">Item returned 2 days late</td>
                        <td><span class="amount-badge">RM 30.00</span></td>
                        <td>Sep 28, 2025</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewPenalty(4)">👁️</button>
                                <button class="btn-icon btn-edit" title="Edit" onclick="editPenalty(4)">✏️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#005</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">D</div>
                                <div class="user-info">
                                    <div class="user-name">David Lee</div>
                                    <div class="user-email">david.lee@example.com</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="violation-badge">Misuse</span></td>
                        <td class="description-cell">Item used for unauthorized commercial purpose</td>
                        <td><span class="amount-badge">RM 300.00</span></td>
                        <td>Sep 25, 2025</td>
                        <td><span class="status-badge status-resolved">Resolved</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewPenalty(5)">👁️</button>
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

        .stat-icon.red { background: #fee2e2; }
        .stat-icon.orange { background: #fed7aa; }
        .stat-icon.green { background: #d1fae5; }
        .stat-icon.blue { background: #dbeafe; }

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

        /* Table Cell Styles */
        .id-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #f3f4f6;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            color: #6b7280;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
        }

        .user-email {
            font-size: 12px;
            color: #6b7280;
        }

        .violation-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #fef3c7;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            color: #92400e;
        }

        .amount-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #dbeafe;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            color: #1e40af;
        }

        .description-cell {
            max-width: 250px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-resolved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-approved {
            background: #dbeafe;
            color: #1e40af;
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

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .empty-text {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .empty-subtext {
            font-size: 14px;
            color: #6b7280;
        }

        /* Pagination */
        .pagination {
            padding: 24px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: center;
        }
    </style>

    <script>
        function filterPenalties(status) {
            const statusFilter = document.getElementById('statusFilter');
            statusFilter.value = status;
            // Add filter logic here
            console.log('Filtering by:', status);
        }

        function viewPenalty(id) {
            // Add view logic here
            console.log('Viewing penalty:', id);
            alert('View penalty details for ID: ' + id);
        }

        function editPenalty(id) {
            // Add edit logic here
            console.log('Editing penalty:', id);
            alert('Edit penalty ID: ' + id);
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