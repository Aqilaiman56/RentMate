@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Deposits Overview</h1>
            <p class="header-description">Review and manage all deposit transactions from users</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportDeposits()">
                📥 Export Data
            </button>
            <button class="btn btn-primary" onclick="generateReport()">
                📊 Generate Report
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">💰</div>
            <div class="stat-content">
                <div class="stat-value">RM 45,680.00</div>
                <div class="stat-label">Total Deposits</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-content">
                <div class="stat-value">RM 38,450.00</div>
                <div class="stat-label">Refunded</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">⏳</div>
            <div class="stat-content">
                <div class="stat-value">RM 5,230.00</div>
                <div class="stat-label">Held</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">⚠️</div>
            <div class="stat-content">
                <div class="stat-value">RM 2,000.00</div>
                <div class="stat-label">Forfeited</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Search deposits by user or item..." class="search-input" id="searchInput">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" id="statusFilter">
                <option value="all">All Status</option>
                <option value="held">Held</option>
                <option value="refunded">Refunded</option>
                <option value="forfeited">Forfeited</option>
                <option value="partial">Partial Refund</option>
            </select>
            <select class="filter-select" id="amountFilter">
                <option value="all">All Amounts</option>
                <option value="0-500">RM 0 - 500</option>
                <option value="500-1000">RM 500 - 1,000</option>
                <option value="1000+">RM 1,000+</option>
            </select>
            <select class="filter-select" id="sortFilter">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="amount-high">Highest Amount</option>
                <option value="amount-low">Lowest Amount</option>
            </select>
        </div>
    </div>

    <!-- Deposits Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Deposit Transactions</h3>
            <span class="table-count">Showing 10 deposits</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>User</th>
                        <th>Item</th>
                        <th>Deposit Amount</th>
                        <th>Booking Period</th>
                        <th>Date Collected</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="id-badge">#D001</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar blue">AM</div>
                                <div class="user-info">
                                    <div class="user-name">Ahmad Mahmud</div>
                                    <div class="user-email">ahmad.m@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">Gaming Laptop - ROG Strix</span>
                                <span class="item-owner">Owner: Siti Lina</span>
                            </div>
                        </td>
                        <td><span class="amount-badge large">RM 500.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Oct 1 - Oct 7, 2025</span>
                                <span class="period-duration">7 days</span>
                            </div>
                        </td>
                        <td>Oct 1, 2025</td>
                        <td><span class="status-badge status-held">Held</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(1)">👁️</button>
                                <button class="btn-icon btn-refund" title="Process Refund" onclick="processRefund(1)">💰</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#D002</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar pink">SL</div>
                                <div class="user-info">
                                    <div class="user-name">Siti Lina</div>
                                    <div class="user-email">siti.lina@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">Mountain Bike - Trek Marlin</span>
                                <span class="item-owner">Owner: Tan Wei Ming</span>
                            </div>
                        </td>
                        <td><span class="amount-badge medium">RM 300.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Sep 25 - Oct 2, 2025</span>
                                <span class="period-duration">8 days</span>
                            </div>
                        </td>
                        <td>Sep 25, 2025</td>
                        <td><span class="status-badge status-refunded">Refunded</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(2)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#D003</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar green">TW</div>
                                <div class="user-info">
                                    <div class="user-name">Tan Wei Ming</div>
                                    <div class="user-email">tan.wei@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">Canon EOS 90D DSLR Camera</span>
                                <span class="item-owner">Owner: Lee Chong</span>
                            </div>
                        </td>
                        <td><span class="amount-badge large">RM 1,200.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Oct 3 - Oct 6, 2025</span>
                                <span class="period-duration">4 days</span>
                            </div>
                        </td>
                        <td>Oct 3, 2025</td>
                        <td><span class="status-badge status-held">Held</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(3)">👁️</button>
                                <button class="btn-icon btn-refund" title="Process Refund" onclick="processRefund(3)">💰</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#D004</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar orange">RK</div>
                                <div class="user-info">
                                    <div class="user-name">Raj Kumar</div>
                                    <div class="user-email">raj.k@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">Epson Portable Projector</span>
                                <span class="item-owner">Owner: Kevin Chen</span>
                            </div>
                        </td>
                        <td><span class="amount-badge medium">RM 400.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Sep 20 - Sep 25, 2025</span>
                                <span class="period-duration">6 days</span>
                            </div>
                        </td>
                        <td>Sep 20, 2025</td>
                        <td><span class="status-badge status-forfeited">Forfeited</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(4)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#D005</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar purple">NZ</div>
                                <div class="user-info">
                                    <div class="user-name">Nurul Zahra</div>
                                    <div class="user-email">nurul.z@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">Engineering Textbooks Set</span>
                                <span class="item-owner">Owner: Fatimah Ali</span>
                            </div>
                        </td>
                        <td><span class="amount-badge small">RM 100.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Sep 28 - Oct 5, 2025</span>
                                <span class="period-duration">8 days</span>
                            </div>
                        </td>
                        <td>Sep 28, 2025</td>
                        <td><span class="status-badge status-refunded">Refunded</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(5)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#D006</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar teal">LC</div>
                                <div class="user-info">
                                    <div class="user-name">Lee Chong</div>
                                    <div class="user-email">lee.chong@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">DJI Mini 3 Pro Drone</span>
                                <span class="item-owner">Owner: Ahmad Mahmud</span>
                            </div>
                        </td>
                        <td><span class="amount-badge large">RM 800.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Oct 2 - Oct 5, 2025</span>
                                <span class="period-duration">4 days</span>
                            </div>
                        </td>
                        <td>Oct 2, 2025</td>
                        <td><span class="status-badge status-held">Held</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(6)">👁️</button>
                                <button class="btn-icon btn-refund" title="Process Refund" onclick="processRefund(6)">💰</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#D007</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar red">FA</div>
                                <div class="user-info">
                                    <div class="user-name">Fatimah Ali</div>
                                    <div class="user-email">fatimah.a@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">Study Desk with Storage</span>
                                <span class="item-owner">Owner: Nurul Zahra</span>
                            </div>
                        </td>
                        <td><span class="amount-badge small">RM 150.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Sep 15 - Sep 22, 2025</span>
                                <span class="period-duration">8 days</span>
                            </div>
                        </td>
                        <td>Sep 15, 2025</td>
                        <td><span class="status-badge status-partial">Partial Refund</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(7)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#D008</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar indigo">KC</div>
                                <div class="user-info">
                                    <div class="user-name">Kevin Chen</div>
                                    <div class="user-email">kevin.chen@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">Professional Tennis Racket</span>
                                <span class="item-owner">Owner: Siti Lina</span>
                            </div>
                        </td>
                        <td><span class="amount-badge small">RM 200.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Sep 18 - Sep 23, 2025</span>
                                <span class="period-duration">6 days</span>
                            </div>
                        </td>
                        <td>Sep 18, 2025</td>
                        <td><span class="status-badge status-refunded">Refunded</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(8)">👁️</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#D009</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar blue">AM</div>
                                <div class="user-info">
                                    <div class="user-name">Ahmad Mahmud</div>
                                    <div class="user-email">ahmad.m@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">Camping Tent - 4 Person</span>
                                <span class="item-owner">Owner: Raj Kumar</span>
                            </div>
                        </td>
                        <td><span class="amount-badge medium">RM 250.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Oct 4 - Oct 8, 2025</span>
                                <span class="period-duration">5 days</span>
                            </div>
                        </td>
                        <td>Oct 4, 2025</td>
                        <td><span class="status-badge status-held">Held</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(9)">👁️</button>
                                <button class="btn-icon btn-refund" title="Process Refund" onclick="processRefund(9)">💰</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#D010</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar pink">SL</div>
                                <div class="user-info">
                                    <div class="user-name">Siti Lina</div>
                                    <div class="user-email">siti.lina@university.edu.my</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="item-cell">
                                <span class="item-name">Portable Speaker - JBL</span>
                                <span class="item-owner">Owner: Lee Chong</span>
                            </div>
                        </td>
                        <td><span class="amount-badge small">RM 180.00</span></td>
                        <td>
                            <div class="period-cell">
                                <span class="period-dates">Sep 12 - Sep 19, 2025</span>
                                <span class="period-duration">8 days</span>
                            </div>
                        </td>
                        <td>Sep 12, 2025</td>
                        <td><span class="status-badge status-refunded">Refunded</span></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewDeposit(10)">👁️</button>
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
        .stat-icon.red { background: #fee2e2; }

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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            color: white;
            flex-shrink: 0;
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
            min-width: 0;
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

        /* Item Cell */
        .item-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
            max-width: 200px;
        }

        .item-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 13px;
        }

        .item-owner {
            font-size: 12px;
            color: #6b7280;
        }

        /* Period Cell */
        .period-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .period-dates {
            font-size: 13px;
            color: #1f2937;
        }

        .period-duration {
            font-size: 11px;
            color: #6b7280;
            font-weight: 600;
        }

        /* Amount Badge */
        .amount-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
        }

        .amount-badge.small {
            background: #dbeafe;
            color: #1e40af;
        }

        .amount-badge.medium {
            background: #fef3c7;
            color: #92400e;
        }

        .amount-badge.large {
            background: #d1fae5;
            color: #065f46;
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

        .status-held {
            background: #fed7aa;
            color: #92400e;
        }

        .status-refunded {
            background: #d1fae5;
            color: #065f46;
        }

        .status-forfeited {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-partial {
            background: #fef3c7;
            color: #854d0e;
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

        .btn-refund {
            background: #d1fae5;
        }

        .btn-refund:hover {
            background: #a7f3d0;
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
        function exportDeposits() {
            alert('Exporting deposits data...');
            console.log('Export deposits functionality');
        }

        function generateReport() {
            alert('Generating financial report...');
            console.log('Generate report functionality');
        }

        function viewDeposit(id) {
            alert('View deposit details for ID: D00' + id + '\n\nDetails:\n- Transaction breakdown\n- Booking information\n- User contact details\n- Item condition report');
            console.log('Viewing deposit:', id);
        }

        function processRefund(id) {
            const confirmation = confirm('Process refund for deposit D00' + id + '?\n\nThis will:\n- Release the deposit to the user\n- Update transaction status\n- Send confirmation email');
            if (confirmation) {
                alert('Refund processed successfully for D00' + id);
                console.log('Processing refund for deposit:', id);
            }
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