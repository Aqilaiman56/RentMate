@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Reports & Complaints</h1>
            <p class="header-description">View and manage user reports, disputes, and complaints</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportReports()">
                📥 Export Reports
            </button>
            <button class="btn btn-primary" onclick="filterPending()">
                ⚡ Pending Only
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon red">📋</div>
            <div class="stat-content">
                <div class="stat-value">42</div>
                <div class="stat-label">Total Reports</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">⏳</div>
            <div class="stat-content">
                <div class="stat-value">15</div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-content">
                <div class="stat-value">23</div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">❌</div>
            <div class="stat-content">
                <div class="stat-value">4</div>
                <div class="stat-label">Dismissed</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Search reports..." class="search-input" id="searchInput">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" id="typeFilter">
                <option value="all">All Types</option>
                <option value="item-damage">Item Damage</option>
                <option value="late-return">Late Return</option>
                <option value="dispute">Dispute</option>
                <option value="fraud">Fraud</option>
                <option value="harassment">Harassment</option>
            </select>
            <select class="filter-select" id="statusFilter">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="investigating">Investigating</option>
                <option value="resolved">Resolved</option>
                <option value="dismissed">Dismissed</option>
            </select>
            <select class="filter-select" id="priorityFilter">
                <option value="all">All Priority</option>
                <option value="high">High Priority</option>
                <option value="medium">Medium Priority</option>
                <option value="low">Low Priority</option>
            </select>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Report Records</h3>
            <span class="table-count">Showing 8 reports</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Report ID</th>
                        <th>Reporter</th>
                        <th>Against</th>
                        <th>Type</th>
                        <th>Priority</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="id-badge">#R001</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar blue">AM</div>
                                <div class="user-info">
                                    <div class="user-name">Ahmad Mahmud</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar pink">SL</div>
                                <div class="user-info">
                                    <div class="user-name">Siti Lina</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge type-damage">Item Damage</span></td>
                        <td><span class="priority-badge priority-high">High</span></td>
                        <td class="subject-cell">Laptop returned with screen damage</td>
                        <td>Oct 6, 2025</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewReport(1)">👁️</button>
                                <button class="btn-icon btn-action" title="Take Action" onclick="takeAction(1)">⚡</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#R002</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar green">TW</div>
                                <div class="user-info">
                                    <div class="user-name">Tan Wei Ming</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar orange">RK</div>
                                <div class="user-info">
                                    <div class="user-name">Raj Kumar</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge type-late">Late Return</span></td>
                        <td><span class="priority-badge priority-medium">Medium</span></td>
                        <td class="subject-cell">Item returned 5 days late without notice</td>
                        <td>Oct 5, 2025</td>
                        <td><span class="status-badge status-investigating">Investigating</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewReport(2)">👁️</button>
                                <button class="btn-icon btn-action" title="Take Action" onclick="takeAction(2)">⚡</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#R003</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar purple">NZ</div>
                                <div class="user-info">
                                    <div class="user-name">Nurul Zahra</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar teal">LC</div>
                                <div class="user-info">
                                    <div class="user-name">Lee Chong</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge type-dispute">Dispute</span></td>
                        <td><span class="priority-badge priority-high">High</span></td>
                        <td class="subject-cell">Disagreement over item condition assessment</td>
                        <td>Oct 4, 2025</td>
                        <td><span class="status-badge status-resolved">Resolved</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewReport(3)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#R004</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar red">FA</div>
                                <div class="user-info">
                                    <div class="user-name">Fatimah Ali</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar indigo">KC</div>
                                <div class="user-info">
                                    <div class="user-name">Kevin Chen</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge type-fraud">Fraud</span></td>
                        <td><span class="priority-badge priority-high">High</span></td>
                        <td class="subject-cell">Fake product description and photos</td>
                        <td>Oct 3, 2025</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewReport(4)">👁️</button>
                                <button class="btn-icon btn-action" title="Take Action" onclick="takeAction(4)">⚡</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#R005</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar blue">AM</div>
                                <div class="user-info">
                                    <div class="user-name">Ahmad Mahmud</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar green">TW</div>
                                <div class="user-info">
                                    <div class="user-name">Tan Wei Ming</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge type-harassment">Harassment</span></td>
                        <td><span class="priority-badge priority-high">High</span></td>
                        <td class="subject-cell">Inappropriate messages during transaction</td>
                        <td>Oct 2, 2025</td>
                        <td><span class="status-badge status-investigating">Investigating</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewReport(5)">👁️</button>
                                <button class="btn-icon btn-action" title="Take Action" onclick="takeAction(5)">⚡</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#R006</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar pink">SL</div>
                                <div class="user-info">
                                    <div class="user-name">Siti Lina</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar orange">RK</div>
                                <div class="user-info">
                                    <div class="user-name">Raj Kumar</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge type-damage">Item Damage</span></td>
                        <td><span class="priority-badge priority-low">Low</span></td>
                        <td class="subject-cell">Minor scratches on camera body</td>
                        <td>Oct 1, 2025</td>
                        <td><span class="status-badge status-resolved">Resolved</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewReport(6)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#R007</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar teal">LC</div>
                                <div class="user-info">
                                    <div class="user-name">Lee Chong</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar purple">NZ</div>
                                <div class="user-info">
                                    <div class="user-name">Nurul Zahra</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge type-late">Late Return</span></td>
                        <td><span class="priority-badge priority-low">Low</span></td>
                        <td class="subject-cell">Books returned 1 day late</td>
                        <td>Sep 30, 2025</td>
                        <td><span class="status-badge status-dismissed">Dismissed</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewReport(7)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#R008</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar indigo">KC</div>
                                <div class="user-info">
                                    <div class="user-name">Kevin Chen</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar red">FA</div>
                                <div class="user-info">
                                    <div class="user-name">Fatimah Ali</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="type-badge type-dispute">Dispute</span></td>
                        <td><span class="priority-badge priority-medium">Medium</span></td>
                        <td class="subject-cell">Refund dispute for cancelled booking</td>
                        <td>Sep 28, 2025</td>
                        <td><span class="status-badge status-pending">Pending</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewReport(8)">👁️</button>
                                <button class="btn-icon btn-action" title="Take Action" onclick="takeAction(8)">⚡</button>
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

        .stat-icon.red { background: #fee2e2; }
        .stat-icon.orange { background: #fed7aa; }
        .stat-icon.green { background: #d1fae5; }
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
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
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

        .user-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 13px;
        }

        /* Type Badge */
        .type-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .type-damage {
            background: #fee2e2;
            color: #991b1b;
        }

        .type-late {
            background: #fed7aa;
            color: #92400e;
        }

        .type-dispute {
            background: #fef3c7;
            color: #854d0e;
        }

        .type-fraud {
            background: #fecaca;
            color: #7f1d1d;
        }

        .type-harassment {
            background: #fce7f3;
            color: #831843;
        }

        /* Priority Badge */
        .priority-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .priority-high {
            background: #fee2e2;
            color: #991b1b;
        }

        .priority-medium {
            background: #fed7aa;
            color: #92400e;
        }

        .priority-low {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Subject Cell */
        .subject-cell {
            max-width: 250px;
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

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-investigating {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-resolved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-dismissed {
            background: #f3f4f6;
            color: #6b7280;
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

        .btn-action {
            background: #fef3c7;
        }

        .btn-action:hover {
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
    </style>

    <script>
        function exportReports() {
            alert('Exporting reports data...');
            console.log('Export reports functionality');
        }

        function filterPending() {
            const statusFilter = document.getElementById('statusFilter');
            statusFilter.value = 'pending';
            alert('Filtering pending reports...');
        }

        function viewReport(id) {
            alert('View report details for ID: R00' + id);
            console.log('Viewing report:', id);
        }

        function takeAction(id) {
            alert('Take action on report ID: R00' + id + '\n\nOptions:\n- Approve & Apply Penalty\n- Reject Report\n- Request More Info\n- Escalate to Senior Admin');
            console.log('Taking action on report:', id);
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