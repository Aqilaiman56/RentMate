@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Deposits Overview</h1>
            <p class="header-description">Review and manage all deposit transactions from users</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.deposits.export') }}" class="btn btn-secondary">
                📥 Export Data
            </a>
            <button class="btn btn-primary" onclick="generateReport()">
                📊 Generate Report
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
                                <div class="action-buttons">
                                    <button class="btn-icon btn-view" 
                                            title="View Details" 
                                            onclick="viewDeposit({{ $deposit->DepositID }})">
                                        👁️
                                    </button>
                                    @if($deposit->Status === 'held')
                                        <form action="{{ route('admin.deposits.refund', $deposit->DepositID) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Process refund for RM {{ number_format($deposit->DepositAmount, 2) }}?')">
                                            @csrf
                                            <button type="submit" class="btn-icon btn-refund" title="Process Refund">
                                                💰
                                            </button>
                                        </form>
                                    @endif
                                </div>
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
    </style>

    <script>
        function viewDeposit(id) {
            fetch(`/admin/deposits/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const deposit = data.deposit;
                        alert(`Deposit Details #D${id.toString().padStart(3, '0')}
                        
User: ${deposit.user.name} (${deposit.user.email})
Item: ${deposit.item.name}
Owner: ${deposit.item.owner}

Amount: RM ${deposit.amount}
Status: ${deposit.status}
Date Collected: ${deposit.date_collected}
${deposit.refund_date !== 'N/A' ? 'Refund Date: ' + deposit.refund_date : ''}

Booking Period: ${deposit.booking.start_date} to ${deposit.booking.end_date}
Duration: ${deposit.booking.duration}

Notes: ${deposit.notes}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load deposit details');
                });
        }

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