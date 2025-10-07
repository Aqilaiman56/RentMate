@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Tax Management</h1>
            <p class="header-description">View and manage tax-related information and transaction records</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportTaxes()">
                📥 Export Tax Report
            </button>
            <button class="btn btn-primary" onclick="generateTaxSummary()">
                📊 Tax Summary
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">💰</div>
            <div class="stat-content">
                <div class="stat-value">RM 12,450.00</div>
                <div class="stat-label">Total Tax Collected</div>
                <div class="stat-subtitle">All time</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">📅</div>
            <div class="stat-content">
                <div class="stat-value">RM 3,280.00</div>
                <div class="stat-label">This Month</div>
                <div class="stat-subtitle">October 2025</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">📊</div>
            <div class="stat-content">
                <div class="stat-value">207</div>
                <div class="stat-label">Tax Transactions</div>
                <div class="stat-subtitle">Total count</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">%</div>
            <div class="stat-content">
                <div class="stat-value">6%</div>
                <div class="stat-label">Tax Rate</div>
                <div class="stat-subtitle">Standard rate</div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h3 class="chart-title">Monthly Tax Collection</h3>
            <div class="chart-legend">
                <span class="legend-item"><span class="legend-dot blue"></span> Tax Amount</span>
            </div>
        </div>
        <div class="chart-container">
            <div class="chart-bars">
                <div class="chart-bar-wrapper">
                    <div class="chart-bar" style="height: 45%;">
                        <span class="bar-label">RM 1,890</span>
                    </div>
                    <span class="bar-month">Jul</span>
                </div>
                <div class="chart-bar-wrapper">
                    <div class="chart-bar" style="height: 60%;">
                        <span class="bar-label">RM 2,520</span>
                    </div>
                    <span class="bar-month">Aug</span>
                </div>
                <div class="chart-bar-wrapper">
                    <div class="chart-bar" style="height: 75%;">
                        <span class="bar-label">RM 3,150</span>
                    </div>
                    <span class="bar-month">Sep</span>
                </div>
                <div class="chart-bar-wrapper">
                    <div class="chart-bar active" style="height: 78%;">
                        <span class="bar-label">RM 3,280</span>
                    </div>
                    <span class="bar-month">Oct</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Search tax records..." class="search-input" id="searchInput">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" id="monthFilter">
                <option value="all">All Months</option>
                <option value="oct-2025">October 2025</option>
                <option value="sep-2025">September 2025</option>
                <option value="aug-2025">August 2025</option>
                <option value="jul-2025">July 2025</option>
            </select>
            <select class="filter-select" id="sortFilter">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="amount-high">Highest Amount</option>
                <option value="amount-low">Lowest Amount</option>
            </select>
        </div>
    </div>

    <!-- Taxes Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Tax Transaction Records</h3>
            <span class="table-count">Showing 10 transactions</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tax ID</th>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Item</th>
                        <th>Rental Amount</th>
                        <th>Tax (6%)</th>
                        <th>Total Charged</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="id-badge">#T001</span></td>
                        <td><span class="booking-badge">#B245</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar blue">AM</div>
                                <div class="user-name">Ahmad Mahmud</div>
                            </div>
                        </td>
                        <td class="item-cell">Gaming Laptop - ROG Strix</td>
                        <td><span class="amount">RM 315.00</span></td>
                        <td><span class="tax-amount">RM 18.90</span></td>
                        <td><span class="total-amount">RM 333.90</span></td>
                        <td>Oct 7, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(1)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(1)">🧾</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#T002</span></td>
                        <td><span class="booking-badge">#B244</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar pink">SL</div>
                                <div class="user-name">Siti Lina</div>
                            </div>
                        </td>
                        <td class="item-cell">Canon EOS 90D DSLR Camera</td>
                        <td><span class="amount">RM 560.00</span></td>
                        <td><span class="tax-amount">RM 33.60</span></td>
                        <td><span class="total-amount">RM 593.60</span></td>
                        <td>Oct 6, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(2)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(2)">🧾</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#T003</span></td>
                        <td><span class="booking-badge">#B243</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar green">TW</div>
                                <div class="user-name">Tan Wei Ming</div>
                            </div>
                        </td>
                        <td class="item-cell">Mountain Bike - Trek Marlin</td>
                        <td><span class="amount">RM 200.00</span></td>
                        <td><span class="tax-amount">RM 12.00</span></td>
                        <td><span class="total-amount">RM 212.00</span></td>
                        <td>Oct 5, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(3)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(3)">🧾</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#T004</span></td>
                        <td><span class="booking-badge">#B242</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar orange">RK</div>
                                <div class="user-name">Raj Kumar</div>
                            </div>
                        </td>
                        <td class="item-cell">DJI Mini 3 Pro Drone</td>
                        <td><span class="amount">RM 240.00</span></td>
                        <td><span class="tax-amount">RM 14.40</span></td>
                        <td><span class="total-amount">RM 254.40</span></td>
                        <td>Oct 5, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(4)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(4)">🧾</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#T005</span></td>
                        <td><span class="booking-badge">#B241</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar purple">NZ</div>
                                <div class="user-name">Nurul Zahra</div>
                            </div>
                        </td>
                        <td class="item-cell">Engineering Textbooks Set</td>
                        <td><span class="amount">RM 80.00</span></td>
                        <td><span class="tax-amount">RM 4.80</span></td>
                        <td><span class="total-amount">RM 84.80</span></td>
                        <td>Oct 4, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(5)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(5)">🧾</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#T006</span></td>
                        <td><span class="booking-badge">#B240</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar teal">LC</div>
                                <div class="user-name">Lee Chong</div>
                            </div>
                        </td>
                        <td class="item-cell">Epson Portable Projector</td>
                        <td><span class="amount">RM 140.00</span></td>
                        <td><span class="tax-amount">RM 8.40</span></td>
                        <td><span class="total-amount">RM 148.40</span></td>
                        <td>Oct 3, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(6)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(6)">🧾</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#T007</span></td>
                        <td><span class="booking-badge">#B239</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar red">FA</div>
                                <div class="user-name">Fatimah Ali</div>
                            </div>
                        </td>
                        <td class="item-cell">Professional Tennis Racket</td>
                        <td><span class="amount">RM 120.00</span></td>
                        <td><span class="tax-amount">RM 7.20</span></td>
                        <td><span class="total-amount">RM 127.20</span></td>
                        <td>Oct 2, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(7)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(7)">🧾</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#T008</span></td>
                        <td><span class="booking-badge">#B238</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar indigo">KC</div>
                                <div class="user-name">Kevin Chen</div>
                            </div>
                        </td>
                        <td class="item-cell">Study Desk with Storage</td>
                        <td><span class="amount">RM 105.00</span></td>
                        <td><span class="tax-amount">RM 6.30</span></td>
                        <td><span class="total-amount">RM 111.30</span></td>
                        <td>Oct 1, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(8)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(8)">🧾</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#T009</span></td>
                        <td><span class="booking-badge">#B237</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar blue">AM</div>
                                <div class="user-name">Ahmad Mahmud</div>
                            </div>
                        </td>
                        <td class="item-cell">Camping Tent - 4 Person</td>
                        <td><span class="amount">RM 125.00</span></td>
                        <td><span class="tax-amount">RM 7.50</span></td>
                        <td><span class="total-amount">RM 132.50</span></td>
                        <td>Sep 30, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(9)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(9)">🧾</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="id-badge">#T010</span></td>
                        <td><span class="booking-badge">#B236</span></td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar pink">SL</div>
                                <div class="user-name">Siti Lina</div>
                            </div>
                        </td>
                        <td class="item-cell">Portable Speaker - JBL</td>
                        <td><span class="amount">RM 90.00</span></td>
                        <td><span class="tax-amount">RM 5.40</span></td>
                        <td><span class="total-amount">RM 95.40</span></td>
                        <td>Sep 28, 2025</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-icon btn-view" title="View Details" onclick="viewTax(10)">👁️</button>
                                <button class="btn-icon btn-receipt" title="Generate Receipt" onclick="generateReceipt(10)">🧾</button>
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
            margin-bottom: 2px;
        }

        .stat-subtitle {
            font-size: 12px;
            color: #9ca3af;
        }

        /* Chart Card */
        .chart-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin: 0 20px 32px 20px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .chart-legend {
            display: flex;
            gap: 16px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #6b7280;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .legend-dot.blue {
            background: #3b82f6;
        }

        .chart-container {
            height: 200px;
        }

        .chart-bars {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            height: 100%;
            gap: 20px;
        }

        .chart-bar-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            justify-content: flex-end;
        }

        .chart-bar {
            width: 100%;
            max-width: 80px;
            background: #dbeafe;
            border-radius: 8px 8px 0 0;
            position: relative;
            transition: all 0.3s;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 8px;
        }

        .chart-bar:hover {
            background: #bfdbfe;
        }

        .chart-bar.active {
            background: #3b82f6;
        }

        .bar-label {
            font-size: 11px;
            font-weight: 600;
            color: #1f2937;
        }

        .chart-bar.active .bar-label {
            color: white;
        }

        .bar-month {
            margin-top: 8px;
            font-size: 13px;
            font-weight: 600;
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

        .booking-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #dbeafe;
            border-radius: 6px;
            font-weight: 600;
            font-size: 13px;
            color: #1e40af;
        }

        .user-cell {
            display: flex;
            align-items: center;
            gap: 10px;
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

        .user-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 13px;
        }

        .item-cell {
            max-width: 200px;
            color: #374151;
        }

        .amount {
            color: #6b7280;
            font-weight: 500;
        }

        .tax-amount {
            color: #3b82f6;
            font-weight: 700;
        }

        .total-amount {
            color: #065f46;
            font-weight: 700;
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

        .btn-receipt {
            background: #d1fae5;
        }

        .btn-receipt:hover {
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
        function exportTaxes() {
            alert('Exporting tax report...\n\nReport will include:\n- All tax transactions\n- Monthly breakdown\n- Total collections\n- Tax rate information');
            console.log('Export taxes functionality');
        }

        function generateTaxSummary() {
            alert('Generating tax summary...\n\nSummary includes:\n- Total tax collected: RM 12,450.00\n- This month: RM 3,280.00\n- Tax rate: 6%\n- Total transactions: 207\n- Average per transaction: RM 60.14');
            console.log('Generate tax summary functionality');
        }

        function viewTax(id) {
            alert('View tax transaction details for ID: T00' + id + '\n\nDetails:\n- Transaction breakdown\n- Booking information\n- Tax calculation\n- Payment details');
            console.log('Viewing tax:', id);
        }

        function generateReceipt(id) {
            alert('Generating receipt for tax ID: T00' + id + '\n\nReceipt will include:\n- Transaction details\n- Tax breakdown (6%)\n- Total amount charged\n- Date and time\n- User information');
            console.log('Generating receipt for tax:', id);
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