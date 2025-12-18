@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-with-menu">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="header-content">
                <h1 class="header-title">Deposits Overview</h1>
                <p class="header-description">Review and manage all deposit transactions from users</p>
            </div>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.deposits.export') }}" class="btn btn-secondary">
                <i class="fas fa-download"></i> <span class="btn-text">Export Data</span>
            </a>
            <button class="btn btn-primary" onclick="generateReport()">
                <i class="fas fa-chart-bar"></i> <span class="btn-text">Generate Report</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            ✗ {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">💰</div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($totalDeposits, 2) }}</div>
                <div class="stat-label">Total Deposits</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($refundedAmount, 2) }}</div>
                <div class="stat-label">Refunded</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">⏳</div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($heldAmount, 2) }}</div>
                <div class="stat-label">Held</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">⚠️</div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($forfeitedAmount, 2) }}</div>
                <div class="stat-label">Forfeited</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <form action="{{ route('admin.deposits') }}" method="GET" class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" 
                   name="search" 
                   placeholder="Search deposits by user or item..." 
                   class="search-input" 
                   value="{{ request('search') }}">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" name="status" onchange="this.form.submit()">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="held" {{ request('status') == 'held' ? 'selected' : '' }}>Held</option>
                <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                <option value="forfeited" {{ request('status') == 'forfeited' ? 'selected' : '' }}>Forfeited</option>
                <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial Refund</option>
            </select>
            <select class="filter-select" name="amount" onchange="this.form.submit()">
                <option value="all" {{ request('amount') == 'all' ? 'selected' : '' }}>All Amounts</option>
                <option value="0-500" {{ request('amount') == '0-500' ? 'selected' : '' }}>RM 0 - 500</option>
                <option value="500-1000" {{ request('amount') == '500-1000' ? 'selected' : '' }}>RM 500 - 1,000</option>
                <option value="1000+" {{ request('amount') == '1000+' ? 'selected' : '' }}>RM 1,000+</option>
            </select>
            <select class="filter-select" name="sort" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                <option value="amount-high" {{ request('sort') == 'amount-high' ? 'selected' : '' }}>Highest Amount</option>
                <option value="amount-low" {{ request('sort') == 'amount-low' ? 'selected' : '' }}>Lowest Amount</option>
            </select>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
    </form>

    <!-- Deposits Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Deposit Transactions</h3>
            <span class="table-count">Showing {{ $deposits->count() }} of {{ $deposits->total() }} deposits</span>
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
                    @forelse($deposits as $deposit)
                        <tr>
                            <td><span class="id-badge">#D{{ str_pad($deposit->DepositID, 3, '0', STR_PAD_LEFT) }}</span></td>
                            <td>
                                <div class="user-cell">
                                    @if($deposit->booking->user->ProfileImage)
                                        <img src="{{ asset('storage/' . $deposit->booking->user->ProfileImage) }}" 
                                             alt="{{ $deposit->booking->user->UserName }}" 
                                             class="user-avatar-img">
                                    @else
                                        <div class="user-avatar {{ ['blue', 'pink', 'green', 'orange', 'purple', 'teal', 'red', 'indigo'][$deposit->booking->user->UserID % 8] }}">
                                            {{ strtoupper(substr($deposit->booking->user->UserName, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="user-info">
                                        <div class="user-name">{{ $deposit->booking->user->UserName }}</div>
                                        <div class="user-email">{{ $deposit->booking->user->Email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="item-cell">
                                    <span class="item-name">{{ $deposit->booking->item->ItemName }}</span>
                                    <span class="item-owner">Owner: {{ $deposit->booking->item->user->UserName }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="amount-badge {{ $deposit->DepositAmount < 200 ? 'small' : ($deposit->DepositAmount < 600 ? 'medium' : 'large') }}">
                                    RM {{ number_format($deposit->DepositAmount, 2) }}
                                </span>
                            </td>
                            <td>
                                <div class="period-cell">
                                    <span class="period-dates">
                                        {{ $deposit->booking->StartDate->format('M d') }} - {{ $deposit->booking->EndDate->format('M d, Y') }}
                                    </span>
                                    <span class="period-duration">
                                        {{ $deposit->booking->StartDate->diffInDays($deposit->booking->EndDate) }} days
                                    </span>
                                </div>
                            </td>
                            <td>{{ $deposit->DateCollected->format('M d, Y') }}</td>
                            <td>
                                <span class="status-badge status-{{ $deposit->Status }}">
                                    {{ ucfirst($deposit->Status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn-action" onclick="viewDeposit({{ $deposit->DepositID }})">
                                    <i class="fas fa-ellipsis-v"></i> Actions
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 60px; color: #6b7280;">
                                <p style="font-size: 18px; font-weight: 600;">No deposits found</p>
                                <p style="margin-top: 10px;">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($deposits->hasPages())
        <div class="pagination-container">
            {{ $deposits->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Deposit Details Modal -->
    <div id="depositModal" class="modal">
        <div class="modal-overlay" onclick="closeDepositModal()"></div>
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Deposit Details</h2>
                <button class="modal-close" onclick="closeDepositModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="depositModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <style>
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin: 0 20px 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .pagination-container {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        .user-avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
            padding: 0 20px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-with-menu {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            flex: 1;
            min-width: 0;
        }

        .header-with-menu .mobile-menu-toggle {
            display: none;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #1f2937;
            font-size: 18px;
            transition: all 0.3s;
            flex-shrink: 0;
        }

        .header-with-menu .mobile-menu-toggle:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .header-content {
            flex: 1;
            min-width: 0;
        }

        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 8px 0;
            line-height: 1.2;
        }

        .header-description {
            font-size: 16px;
            color: #6b7280;
            margin: 0;
            line-height: 1.5;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
            align-items: flex-start;
            padding-top: 4px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 250px), 1fr));
            gap: 20px;
            margin-bottom: 32px;
            padding: 0 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            min-width: 0;
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
            flex-shrink: 0;
        }

        .stat-icon.blue { background: #dbeafe; }
        .stat-icon.green { background: #d1fae5; }
        .stat-icon.orange { background: #fed7aa; }
        .stat-icon.red { background: #fee2e2; }

        .stat-content {
            flex: 1;
            min-width: 0;
            overflow: hidden;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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

        /* Action Button */
        .btn-action {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-action:hover {
            background: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }

        .btn-action i {
            font-size: 14px;
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
            text-decoration: none;
            display: inline-block;
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
        }

        .modal-container {
            position: relative;
            background: white;
            border-radius: 16px;
            max-width: 700px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 28px;
            border-bottom: 2px solid #e5e7eb;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .modal-close {
            width: 36px;
            height: 36px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-size: 18px;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 28px;
            max-height: calc(90vh - 100px);
            overflow-y: auto;
        }

        .detail-section {
            margin-bottom: 28px;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .detail-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .detail-item.full-width {
            grid-column: 1 / -1;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .detail-value {
            font-size: 15px;
            font-weight: 600;
            color: #1f2937;
        }

        .detail-value.large {
            font-size: 24px;
            color: #3b82f6;
        }

        .detail-value.status {
            display: inline-block;
            width: fit-content;
        }

        .user-detail {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
        }

        .user-detail-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .user-detail-info {
            flex: 1;
        }

        .user-detail-name {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .user-detail-email {
            font-size: 13px;
            color: #6b7280;
        }

        .action-buttons-modal {
            display: flex;
            gap: 12px;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 2px solid #e5e7eb;
        }

        .btn-modal {
            flex: 1;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-modal-primary {
            background: #10b981;
            color: white;
        }

        .btn-modal-primary:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .btn-modal-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-modal-secondary:hover {
            background: #e5e7eb;
        }

        .btn-modal-primary:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }

        /* Responsive Breakpoints */
        @media (max-width: 968px) {
            .header {
                align-items: flex-start;
                gap: 16px;
            }

            .header-with-menu {
                order: -1;
                width: 100%;
                align-items: flex-start;
            }

            .header-with-menu .mobile-menu-toggle {
                display: flex;
            }

            .header-actions {
                width: auto;
                padding-top: 0;
            }

            .header-title {
                font-size: 28px;
            }

            .header-description {
                font-size: 15px;
            }

            .btn {
                padding: 10px 18px;
                font-size: 13px;
            }

            .stat-icon {
                width: 52px;
                height: 52px;
                font-size: 22px;
            }

            .stat-value {
                font-size: 26px;
            }

            .stat-label {
                font-size: 13px;
            }

            .btn-text {
                display: inline;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 16px;
                margin-bottom: 24px;
                gap: 12px;
                flex-direction: row;
                justify-content: space-between;
            }

            .header-with-menu {
                gap: 12px;
                flex: 1;
            }

            .header-actions {
                align-self: flex-start;
            }

            .header-title {
                font-size: 24px;
            }

            .header-description {
                font-size: 14px;
            }

            .btn {
                padding: 9px 16px;
                font-size: 13px;
            }

            .btn-text {
                display: none;
            }

            .stats-grid {
                padding: 0 16px;
                gap: 16px;
                grid-template-columns: repeat(auto-fit, minmax(min(100%, 200px), 1fr));
            }

            .stat-card {
                padding: 16px;
                gap: 14px;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                font-size: 20px;
            }

            .stat-value {
                font-size: 24px;
            }

            .stat-label {
                font-size: 12px;
            }

            .table-controls {
                flex-direction: column;
                align-items: stretch;
                padding: 0 16px;
                gap: 16px;
            }

            .search-box {
                max-width: 100%;
                min-width: 100%;
            }

            .filter-buttons {
                width: 100%;
                flex-direction: column;
            }

            .filter-select, .filter-buttons .btn {
                width: 100%;
            }

            .table-card {
                margin: 0 16px;
            }

            .table-header {
                padding: 20px;
            }

            .table-title {
                font-size: 18px;
            }

            .data-table th,
            .data-table td {
                padding: 14px 16px;
            }

            .alert {
                margin: 0 16px 16px;
                padding: 14px 16px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 0 12px;
                margin-bottom: 20px;
                gap: 10px;
            }

            .header-with-menu {
                gap: 8px;
            }

            .header-with-menu .mobile-menu-toggle {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .header-actions {
                padding-top: 2px;
            }

            .header-title {
                font-size: 20px;
                margin: 0 0 4px 0;
            }

            .header-description {
                font-size: 12px;
            }

            .btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            .btn i {
                font-size: 13px;
            }

            .stats-grid {
                padding: 0 12px;
                gap: 12px;
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: 14px;
                gap: 12px;
            }

            .stat-icon {
                width: 44px;
                height: 44px;
                font-size: 18px;
            }

            .stat-value {
                font-size: 22px;
            }

            .stat-label {
                font-size: 11px;
            }

            .table-controls {
                padding: 0 12px;
                gap: 12px;
            }

            .search-box {
                min-width: 100%;
            }

            .search-input {
                padding: 10px 38px 10px 38px;
                font-size: 13px;
            }

            .filter-select {
                padding: 10px 14px;
                font-size: 13px;
            }

            .table-card {
                margin: 0 12px;
            }

            .table-header {
                padding: 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .table-title {
                font-size: 16px;
            }

            .table-count {
                font-size: 13px;
            }

            .data-table th,
            .data-table td {
                padding: 12px 14px;
                font-size: 12px;
            }

            .data-table th {
                font-size: 11px;
            }

            .btn-icon {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }

            .alert {
                margin: 0 12px 12px;
                padding: 12px 14px;
                font-size: 12px;
            }

            .pagination-container {
                padding: 24px 12px;
            }
        }

        @media (max-width: 360px) {
            .header {
                padding: 0 10px;
                margin-bottom: 16px;
                gap: 8px;
            }

            .header-with-menu {
                gap: 6px;
            }

            .header-with-menu .mobile-menu-toggle {
                width: 36px;
                height: 36px;
                font-size: 15px;
            }

            .header-title {
                font-size: 18px;
            }

            .header-description {
                font-size: 11px;
            }

            .btn {
                padding: 7px 10px;
                font-size: 11px;
            }

            .btn i {
                font-size: 12px;
            }

            .stats-grid {
                padding: 0 10px;
                gap: 10px;
            }

            .stat-card {
                padding: 12px;
                gap: 10px;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
                border-radius: 10px;
            }

            .stat-value {
                font-size: 20px;
            }

            .stat-label {
                font-size: 10px;
            }

            .table-controls {
                padding: 0 10px;
                gap: 10px;
            }

            .search-input {
                padding: 9px 36px 9px 36px;
                font-size: 12px;
            }

            .filter-select {
                padding: 9px 12px;
                font-size: 12px;
            }

            .table-card {
                margin: 0 10px;
                border-radius: 10px;
            }

            .table-header {
                padding: 14px;
            }

            .table-title {
                font-size: 15px;
            }

            .table-count {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 10px 12px;
                font-size: 11px;
            }

            .data-table th {
                font-size: 10px;
            }

            .btn-icon {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }

            .alert {
                margin: 0 10px 10px;
                padding: 10px 12px;
                font-size: 11px;
            }

            .pagination-container {
                padding: 20px 10px;
            }
        }
    </style>

    <script>
        function viewDeposit(id) {
            fetch(`/admin/deposits/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const deposit = data.deposit;
                        const modalContent = document.getElementById('depositModalContent');

                        // Build modal content
                        let content = `
                            <!-- Transaction Information -->
                            <div class="detail-section">
                                <div class="detail-section-title">Transaction Information</div>
                                <div class="detail-grid">
                                    <div class="detail-item">
                                        <div class="detail-label">Transaction ID</div>
                                        <div class="detail-value">#D${id.toString().padStart(3, '0')}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Status</div>
                                        <div class="detail-value status">
                                            <span class="status-badge status-${deposit.status.toLowerCase()}">${deposit.status}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Deposit Amount</div>
                                        <div class="detail-value large">RM ${deposit.amount}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Date Collected</div>
                                        <div class="detail-value">${deposit.date_collected}</div>
                                    </div>
                                    ${deposit.refund_date !== 'N/A' ? `
                                        <div class="detail-item">
                                            <div class="detail-label">Refund Date</div>
                                            <div class="detail-value">${deposit.refund_date}</div>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>

                            <!-- User Information -->
                            <div class="detail-section">
                                <div class="detail-section-title">Renter Information</div>
                                <div class="user-detail">
                                    ${deposit.user.avatar ?
                                        `<img src="${deposit.user.avatar}" alt="${deposit.user.name}" class="user-detail-avatar">` :
                                        `<div class="user-avatar blue" style="width: 48px; height: 48px; font-size: 18px;">${deposit.user.name.substring(0, 2).toUpperCase()}</div>`
                                    }
                                    <div class="user-detail-info">
                                        <div class="user-detail-name">${deposit.user.name}</div>
                                        <div class="user-detail-email">${deposit.user.email}</div>
                                    </div>
                                </div>

                                <!-- Bank Account Details -->
                                <div class="detail-grid" style="margin-top: 16px;">
                                    <div class="detail-item">
                                        <div class="detail-label"><i class="fas fa-university"></i> Bank Name</div>
                                        <div class="detail-value">${deposit.user.bank_name}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label"><i class="fas fa-credit-card"></i> Account Number</div>
                                        <div class="detail-value" style="font-family: monospace; color: #3b82f6;">${deposit.user.bank_account_number}</div>
                                    </div>
                                    <div class="detail-item full-width">
                                        <div class="detail-label"><i class="fas fa-user"></i> Account Holder Name</div>
                                        <div class="detail-value">${deposit.user.bank_account_holder}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Item Information -->
                            <div class="detail-section">
                                <div class="detail-section-title">Item Information</div>
                                <div class="detail-grid">
                                    <div class="detail-item full-width">
                                        <div class="detail-label">Item Name</div>
                                        <div class="detail-value">${deposit.item.name}</div>
                                    </div>
                                    <div class="detail-item full-width">
                                        <div class="detail-label">Item Owner</div>
                                        <div class="detail-value">${deposit.item.owner}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Information -->
                            <div class="detail-section">
                                <div class="detail-section-title">Booking Information</div>
                                <div class="detail-grid">
                                    <div class="detail-item">
                                        <div class="detail-label">Start Date</div>
                                        <div class="detail-value">${deposit.booking.start_date}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">End Date</div>
                                        <div class="detail-value">${deposit.booking.end_date}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Duration</div>
                                        <div class="detail-value">${deposit.booking.duration}</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-label">Booking Status</div>
                                        <div class="detail-value">${deposit.booking.status || 'N/A'}</div>
                                    </div>
                                </div>
                            </div>

                            ${deposit.notes && deposit.notes !== 'N/A' ? `
                                <div class="detail-section">
                                    <div class="detail-section-title">Additional Notes</div>
                                    <div class="detail-value">${deposit.notes}</div>
                                </div>
                            ` : ''}

                            ${deposit.refund_queue ? `
                                <div class="detail-section">
                                    <div class="detail-section-title">Refund Queue Status</div>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <div class="detail-label">Refund Status</div>
                                            <div class="detail-value status">
                                                <span class="status-badge status-${deposit.refund_queue.status.toLowerCase()}">${deposit.refund_queue.status}</span>
                                            </div>
                                        </div>
                                        ${deposit.refund_queue.reference ? `
                                            <div class="detail-item">
                                                <div class="detail-label">Reference Number</div>
                                                <div class="detail-value">${deposit.refund_queue.reference}</div>
                                            </div>
                                        ` : ''}
                                        ${deposit.refund_queue.processed_at ? `
                                            <div class="detail-item">
                                                <div class="detail-label">Processed At</div>
                                                <div class="detail-value">${deposit.refund_queue.processed_at}</div>
                                            </div>
                                        ` : ''}
                                    </div>
                                    <div style="margin-top: 16px; padding: 12px; background: #dbeafe; border-left: 4px solid #3b82f6; border-radius: 8px;">
                                        <a href="/admin/refund-queue" style="color: #1e40af; font-weight: 600; text-decoration: none;">
                                            <i class="fas fa-external-link-alt"></i> View in Refund Queue
                                        </a>
                                    </div>
                                </div>
                            ` : ''}

                            <!-- Action Buttons -->
                            <div class="action-buttons-modal">
                                ${deposit.status.toLowerCase() === 'held' ? `
                                    <button class="btn-modal btn-modal-primary" onclick="processRefund(${id})">
                                        <i class="fas fa-check-circle"></i> Process Refund
                                    </button>
                                ` : ''}
                                <button class="btn-modal btn-modal-secondary" onclick="closeDepositModal()">
                                    <i class="fas fa-times"></i> Close
                                </button>
                            </div>
                        `;

                        modalContent.innerHTML = content;
                        document.getElementById('depositModal').classList.add('show');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load deposit details');
                });
        }

        function closeDepositModal() {
            document.getElementById('depositModal').classList.remove('show');
        }

        function processRefund(depositId) {
            if (confirm('Are you sure you want to process this refund?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/deposits/${depositId}/refund`;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;

                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDepositModal();
            }
        });

        function generateReport() {
            fetch('/admin/deposits-report')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const report = data.report;
                        alert(`Financial Report - Deposits
                        
Total Deposits: RM ${parseFloat(report.total_deposits).toFixed(2)}
Refunded: RM ${parseFloat(report.refunded).toFixed(2)}
Held: RM ${parseFloat(report.held).toFixed(2)}
Forfeited: RM ${parseFloat(report.forfeited).toFixed(2)}
Partial Refunds: RM ${parseFloat(report.partial).toFixed(2)}

Total Transactions: ${report.total_transactions}

Monthly Breakdown:
${report.monthly_breakdown.map(m => `${m.month_name}: RM ${parseFloat(m.total).toFixed(2)} (${m.count} transactions)`).join('\n')}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to generate report');
                });
        }
    </script>
@endsection