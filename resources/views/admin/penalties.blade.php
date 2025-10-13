@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Penalty Management</h1>
            <p class="header-description">Manage penalties and disciplinary actions for users</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.penalties.export') }}" class="btn btn-secondary">
                📥 Export Data
            </a>
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
            <div class="stat-icon red">⚠️</div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalPenalties }}</div>
                <div class="stat-label">Total Penalties</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">⏳</div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingPenalties }}</div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-content">
                <div class="stat-value">{{ $resolvedPenalties }}</div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">💰</div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($totalAmount, 2) }}</div>
                <div class="stat-label">Total Amount</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <form action="{{ route('admin.penalties') }}" method="GET" class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" name="search" placeholder="Search penalties..." class="search-input" value="{{ request('search') }}">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" name="status" onchange="this.form.submit()">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
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

    <!-- Penalties Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Penalty Records</h3>
            <span class="table-count">Showing {{ $penalties->count() }} of {{ $penalties->total() }} penalties</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Penalty ID</th>
                        <th>User</th>
                        <th>Report/Issue</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penalties as $penalty)
                        <tr>
                            <td><span class="id-badge">#P{{ str_pad($penalty->PenaltyID, 3, '0', STR_PAD_LEFT) }}</span></td>
                            <td>
                                <div class="user-cell">
                                    @if($penalty->reportedUser->ProfileImage)
                                        <img src="{{ asset('storage/' . $penalty->reportedUser->ProfileImage) }}" 
                                             alt="{{ $penalty->reportedUser->UserName }}" 
                                             class="user-avatar-img">
                                    @else
                                        <div class="user-avatar {{ ['blue', 'pink', 'green', 'orange', 'purple', 'teal'][$penalty->reportedUser->UserID % 6] }}">
                                            {{ strtoupper(substr($penalty->reportedUser->UserName, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="user-info">
                                        <div class="user-name">{{ $penalty->reportedUser->UserName }}</div>
                                        <div class="user-email">{{ $penalty->reportedUser->Email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($penalty->report)
                                    <a href="{{ route('admin.reports') }}?search={{ $penalty->report->ReportID }}" class="report-link">
                                        #R{{ str_pad($penalty->report->ReportID, 3, '0', STR_PAD_LEFT) }} - {{ ucwords(str_replace('-', ' ', $penalty->report->ReportType)) }}
                                    </a>
                                @else
                                    <span class="text-muted">No linked report</span>
                                @endif
                            </td>
                            <td class="description-cell">{{ Str::limit($penalty->Description, 50) }}</td>
                            <td><span class="amount-badge">RM {{ number_format($penalty->PenaltyAmount, 2) }}</span></td>
                            <td>{{ $penalty->DateReported->format('M d, Y') }}</td>
                            <td>
                                <span class="status-badge {{ $penalty->ResolvedStatus ? 'status-resolved' : 'status-pending' }}">
                                    {{ $penalty->ResolvedStatus ? 'Resolved' : 'Pending' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon btn-view" title="View Details" onclick="viewPenalty({{ $penalty->PenaltyID }})">👁️</button>
                                    @if(!$penalty->ResolvedStatus)
                                        <form action="{{ route('admin.penalties.resolve', $penalty->PenaltyID) }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Mark this penalty as resolved?')">
                                            @csrf
                                            <button type="submit" class="btn-icon btn-check" title="Mark as Resolved">✓</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 60px; color: #6b7280;">
                                <p style="font-size: 18px; font-weight: 600;">No penalties found</p>
                                <p style="margin-top: 10px;">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($penalties->hasPages())
        <div class="pagination-container">
            {{ $penalties->appends(request()->query())->links() }}
        </div>
    @endif

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
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
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
        }

        .report-link {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .report-link:hover {
            text-decoration: underline;
        }

        .text-muted {
            color: #9ca3af;
            font-style: italic;
        }

        .btn-check {
            background: #d1fae5;
            color: #065f46;
        }

        .btn-check:hover {
            background: #a7f3d0;
        }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; padding: 0 20px; }
        .header-content { flex: 1; }
        .header-title { font-size: 32px; font-weight: 700; color: #1f2937; margin: 0 0 8px 0; }
        .header-description { font-size: 16px; color: #6b7280; margin: 0; }
        .header-actions { display: flex; gap: 12px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px; padding: 0 20px; }
        .stat-card { background: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
        .stat-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .stat-icon.red { background: #fee2e2; }
        .stat-icon.orange { background: #fed7aa; }
        .stat-icon.green { background: #d1fae5; }
        .stat-icon.blue { background: #dbeafe; }
        .stat-content { flex: 1; }
        .stat-value { font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 4px; }
        .stat-label { font-size: 14px; color: #6b7280; }
        .table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding: 0 20px; gap: 20px; flex-wrap: wrap; }
        .search-box { position: relative; flex: 1; min-width: 250px; max-width: 400px; }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 16px; }
        .search-input { width: 100%; padding: 10px 14px 10px 40px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
        .search-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .filter-buttons { display: flex; gap: 12px; }
        .filter-select { padding: 10px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.2s; }
        .filter-select:focus { outline: none; border-color: #3b82f6; }
        .table-card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); margin: 0 20px; overflow: hidden; }
        .table-header { display: flex; justify-content: space-between; align-items: center; padding: 24px; border-bottom: 1px solid #e5e7eb; }
        .table-title { font-size: 20px; font-weight: 600; color: #1f2937; margin: 0; }
        .table-count { font-size: 14px; color: #6b7280; }
        .table-container { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead { background: #f9fafb; }
        .data-table th { padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; }
        .data-table td { padding: 16px 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #374151; }
        .data-table tbody tr { transition: background-color 0.2s; }
        .data-table tbody tr:hover { background: #f9fafb; }
        .id-badge { display: inline-block; padding: 4px 12px; background: #f3f4f6; border-radius: 6px; font-weight: 600; font-size: 13px; color: #6b7280; }
        .user-cell { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: #3b82f6; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 16px; }
        .user-avatar.blue { background: #3b82f6; }
        .user-avatar.pink { background: #ec4899; }
        .user-avatar.green { background: #10b981; }
        .user-avatar.orange { background: #f97316; }
        .user-avatar.purple { background: #a855f7; }
        .user-avatar.teal { background: #14b8a6; }
        .user-info { display: flex; flex-direction: column; }
        .user-name { font-weight: 600; color: #1f2937; }
        .user-email { font-size: 12px; color: #6b7280; }
        .amount-badge { display: inline-block; padding: 4px 12px; background: #dbeafe; border-radius: 6px; font-weight: 600; font-size: 13px; color: #1e40af; }
        .description-cell { max-width: 250px; }
        .status-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-resolved { background: #d1fae5; color: #065f46; }
        .action-buttons { display: flex; gap: 8px; }
        .btn-icon { width: 36px; height: 36px; border: none; border-radius: 8px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .btn-view { background: #dbeafe; }
        .btn-view:hover { background: #bfdbfe; }
        .btn { padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; display: inline-block; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background: #e5e7eb; }
    </style>

    <script>
        function viewPenalty(id) {
            fetch(`/admin/penalties/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const penalty = data.penalty;
                        alert(`Penalty Details #P${id.toString().padStart(3, '0')}

User: ${penalty.user.name} (${penalty.user.email})
${penalty.report ? 'Related Report: #R' + penalty.report.id + ' - ' + penalty.report.type : 'No linked report'}

Description: ${penalty.description}
Amount: RM ${penalty.amount}
Date Issued: ${penalty.date_issued}
Status: ${penalty.status}

${penalty.booking ? 'Booking: ' + penalty.booking.item + ' (' + penalty.booking.dates + ')' : ''}
${penalty.item ? 'Item: ' + penalty.item.name : ''}

Approved By: ${penalty.approved_by ? penalty.approved_by : 'N/A'}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load penalty details');
                });
        }
    </script>
@endsection