@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-with-menu">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title">Service Fee Management</h1>
        </div>
        <div class="header-actions" style="gap: 12px;">
            <a href="{{ route('admin.service-fees.export', ['year' => $year]) }}" class="btn btn-secondary">
                <i class="fas fa-download"></i> Export {{ $year }} Data
            </a>
            @include('admin.partials.header-actions', ['notificationCount' => $notificationCount ?? 0])
        </div>
    </div>

    <p class="page-description">View service fee collection summary and monthly breakdown</p>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Year Filter -->
    <div style="padding: 0 20px 20px;">
        <form action="{{ route('admin.service-fees') }}" method="GET" style="display: flex; gap: 12px; align-items: center;">
            <label style="font-weight: 600; color: #1f2937;">View Year:</label>
            <select name="year" class="filter-select" onchange="this.form.submit()">
                @forelse($availableYears as $availableYear)
                    <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                        {{ $availableYear }}
                    </option>
                @empty
                    <option value="{{ date('Y') }}">{{ date('Y') }}</option>
                @endforelse
            </select>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($totalServiceFees, 2) }}</div>
                <div class="stat-label">Total Service Fee Collected ({{ $year }})</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalTransactions }}</div>
                <div class="stat-label">Total Transactions</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($averagePerMonth, 2) }}</div>
                <div class="stat-label">Average Per Month</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-tag"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">RM 1.00</div>
                <div class="stat-label">Service Fee Per Booking</div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Monthly Service Fee Breakdown - {{ $year }}</h3>
            <span class="table-count">{{ $monthlyServiceFees->count() }} months with data</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Month</th>
                        <th>Number of Bookings</th>
                        <th>Service Fee Amount</th>
                        <th>Average Daily Collection</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyServiceFees as $monthData)
                        <tr>
                            <td>
                                <span style="font-weight: 600;">{{ $monthData->month_name }} {{ $monthData->year }}</span>
                            </td>
                            <td>
                                <span class="count-badge">{{ $monthData->count }} bookings</span>
                            </td>
                            <td>
                                <span class="amount-badge">RM {{ number_format($monthData->total, 2) }}</span>
                            </td>
                            <td>
                                <span style="color: #6b7280;">
                                    RM {{ number_format($monthData->total / Carbon\Carbon::create($monthData->year, $monthData->month)->daysInMonth, 2) }}/day
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 60px; color: #6b7280;">
                                <p style="font-size: 18px; font-weight: 600;">No service fee data for {{ $year }}</p>
                                <p style="margin-top: 10px;">Select a different year or create bookings to see service fee data</p>
                            </td>
                        </tr>
                    @endforelse

                    @if($monthlyServiceFees->count() > 0)
                        <tr style="background: #f9fafb; font-weight: 700; border-top: 2px solid #d1d5db;">
                            <td style="color: #1f2937; font-size: 15px;">YEAR TOTAL</td>
                            <td>
                                <span class="count-badge" style="background: #dbeafe; color: #1e40af;">
                                    {{ $totalTransactions }} bookings
                                </span>
                            </td>
                            <td>
                                <span class="amount-badge large">RM {{ number_format($totalServiceFees, 2) }}</span>
                            </td>
                            <td>
                                <span style="color: #374151; font-weight: 600;">
                                    RM {{ number_format($totalServiceFees / 365, 2) }}/day
                                </span>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="table-card" style="margin-top: 30px;">
        <div class="table-header">
            <h3 class="table-title">Recent Service Fee Transactions</h3>
            <span class="table-count">Last 20 transactions in {{ $year }}</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Service Fee ID</th>
                        <th>Date</th>
                        <th>User</th>
                        <th>Booking</th>
                        <th>Item</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentServiceFees as $serviceFee)
                        <tr>
                            <td><span class="id-badge">#SF{{ str_pad($serviceFee->ServiceFeeID, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td>{{ $serviceFee->DateCollected->format('M d, Y') }}</td>
                            <td>
                                <div class="user-cell">
                                    @if($serviceFee->user->ProfileImage)
                                        <img src="{{ asset('storage/' . $serviceFee->user->ProfileImage) }}"
                                             alt="{{ $serviceFee->user->UserName }}"
                                             class="user-avatar-img">
                                    @else
                                        <div class="user-avatar {{ ['blue', 'pink', 'green', 'orange', 'purple', 'teal'][$serviceFee->user->UserID % 6] }}">
                                            {{ strtoupper(substr($serviceFee->user->UserName, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="user-name">{{ $serviceFee->user->UserName }}</span>
                                </div>
                            </td>
                            <td><span class="id-badge">#B{{ $serviceFee->BookingID }}</span></td>
                            <td>
                                <span style="font-weight: 500; color: #374151;">
                                    {{ Str::limit($serviceFee->booking->item->ItemName ?? 'N/A', 30) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #6b7280;">
                                <p style="font-size: 16px; font-weight: 600;">No transactions in {{ $year }}</p>
                                <p style="margin-top: 8px; font-size: 14px;">Service fee records will appear here when bookings are created</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin: 0 20px 20px;
            font-size: 14px;
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 0 20px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-with-menu {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
            min-width: 0;
        }

        .header-with-menu .mobile-menu-toggle {
            display: none;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border: none;
            border-radius: 10px;
            width: 44px;
            height: 44px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            font-size: 18px;
            transition: all 0.3s;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
        }

        .header-with-menu .mobile-menu-toggle:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            line-height: 1.2;
            flex: 1;
            min-width: 0;
        }

        .page-description {
            font-size: 16px;
            color: #6b7280;
            margin: 0 0 32px 0;
            padding: 0 20px;
            line-height: 1.5;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

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
            align-items: flex-start;
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
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #dbeafe;
            color: #1e40af;
        }
        .stat-icon.green {
            background: #d1fae5;
            color: #065f46;
        }
        .stat-icon.purple {
            background: #e9d5ff;
            color: #7e22ce;
        }
        .stat-icon.orange {
            background: #fed7aa;
            color: #c2410c;
        }

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

        .amount-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            background: #dbeafe;
            color: #1e40af;
        }

        .amount-badge.small {
            font-size: 13px;
            padding: 4px 12px;
        }

        .amount-badge.large {
            font-size: 16px;
            padding: 8px 16px;
            background: #d1fae5;
            color: #065f46;
        }

        .count-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #f3f4f6;
            border-radius: 6px;
            font-weight: 500;
            font-size: 13px;
            color: #374151;
        }

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

        .user-avatar-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: white;
        }

        .user-avatar.blue { background: #3b82f6; }
        .user-avatar.pink { background: #ec4899; }
        .user-avatar.green { background: #10b981; }
        .user-avatar.orange { background: #f97316; }
        .user-avatar.purple { background: #a855f7; }
        .user-avatar.teal { background: #14b8a6; }

        .user-name {
            font-weight: 500;
            color: #1f2937;
            font-size: 14px;
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

        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        /* Responsive Breakpoints */
        @media (max-width: 968px) {
            .header { align-items: flex-start; gap: 16px; }
            .header-with-menu { order: -1; width: 100%; align-items: center; }
            .header-with-menu .mobile-menu-toggle { display: flex; }
            .header-actions { width: auto; padding-top: 0; }
            .header-title { font-size: 28px; }
            .page-description { font-size: 15px; }
        }

        @media (max-width: 768px) {
            .header { flex-direction: column; align-items: stretch; gap: 12px; }
            .header-actions { align-self: flex-start; }
            .header-title { font-size: 24px; }
            .page-description { font-size: 14px; }
            .stats-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .header-title { font-size: 20px; }
            .page-description { font-size: 12px; }
        }
    </style>
@endsection
