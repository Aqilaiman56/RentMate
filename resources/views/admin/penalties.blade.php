@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-with-menu">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title">Penalty Management</h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.penalties.export') }}" class="btn btn-secondary">
                Export Data
            </a>
        </div>
    </div>

    <p class="page-description">Manage penalties and disciplinary actions for users</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalPenalties }}</div>
                <div class="stat-label">Total Penalties</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingPenalties }}</div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $resolvedPenalties }}</div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-dollar-sign"></i></div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($totalAmount, 2) }}</div>
                <div class="stat-label">Total Amount</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <form action="{{ route('admin.penalties') }}" method="GET" class="table-controls">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
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
                                    <button class="btn-icon btn-view" title="View Details" onclick="viewPenalty({{ $penalty->PenaltyID }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(!$penalty->ResolvedStatus)
                                        <button class="btn-icon btn-action" title="Take Action" onclick="showPenaltyActionModal({{ $penalty->PenaltyID }}, '{{ addslashes($penalty->reportedUser->UserName) }}', {{ $penalty->PenaltyAmount }})">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                        <form action="{{ route('admin.penalties.resolve', $penalty->PenaltyID) }}"
                                              method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('Mark this penalty as resolved?')">
                                            @csrf
                                            <button type="submit" class="btn-icon btn-check" title="Mark as Resolved">
                                                <i class="fas fa-check"></i>
                                            </button>
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

    <!-- Penalty Action Modal -->
    <div id="penaltyActionModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="penaltyModalTitle">Penalty Actions</h2>
                <span class="close" onclick="closePenaltyActionModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p class="modal-description">Choose an action to take for this penalty:</p>
                <div class="action-buttons-grid">
                    <button class="action-option btn-suspend" onclick="confirmSuspend()">
                        <i class="fas fa-user-lock"></i>
                        <span class="action-title">Suspend User</span>
                        <span class="action-desc">Temporarily suspend user account</span>
                    </button>
                    <button class="action-option btn-forfeit" onclick="confirmForfeit()">
                        <i class="fas fa-hand-holding-usd"></i>
                        <span class="action-title">Forfeit Deposit</span>
                        <span class="action-desc">Transfer deposit to item owner</span>
                    </button>
                    <button class="action-option btn-warning" onclick="confirmWarning()">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="action-title">Issue Warning</span>
                        <span class="action-desc">Send warning notification to user</span>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closePenaltyActionModal()">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Suspend Confirmation Modal -->
    <div id="suspendModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <h2 style="color: white; margin: 0;">Suspend User</h2>
                <span class="close" onclick="closeSuspendModal()" style="color: white;">&times;</span>
            </div>
            <form id="suspendForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert-warning" style="margin-bottom: 20px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>Warning!</strong>
                            <p>The user will be unable to access their account during the suspension period.</p>
                        </div>
                    </div>
                    <p style="margin: 0 0 16px 0; color: #374151;">Suspend user: <strong id="suspendUserName"></strong></p>
                    <div class="form-group">
                        <label class="form-label">Suspension Duration *</label>
                        <select name="duration" class="form-input" required>
                            <option value="">Select duration</option>
                            <option value="7">7 Days</option>
                            <option value="14">14 Days</option>
                            <option value="30">30 Days</option>
                            <option value="permanent">Permanent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reason *</label>
                        <textarea name="reason" class="form-input" rows="3" required placeholder="Explain why the user is being suspended..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeSuspendModal()">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-user-lock"></i> Suspend User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Forfeit Deposit Modal -->
    <div id="forfeitModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h2 style="color: white; margin: 0;">Forfeit Deposit</h2>
                <span class="close" onclick="closeForfeitModal()" style="color: white;">&times;</span>
            </div>
            <form id="forfeitForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert-danger" style="margin-bottom: 20px;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <strong>Warning!</strong>
                            <p>The deposit will be transferred to the item owner and cannot be reversed.</p>
                        </div>
                    </div>
                    <p style="margin: 0 0 16px 0; color: #374151;">
                        Forfeit deposit of <strong id="forfeitAmount"></strong> from <strong id="forfeitUserName"></strong>
                    </p>
                    <div class="form-group">
                        <label class="form-label">Reason for Forfeiture *</label>
                        <textarea name="reason" class="form-input" rows="3" required placeholder="Explain why the deposit is being forfeited..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeForfeitModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-hand-holding-usd"></i> Forfeit Deposit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Warning Modal -->
    <div id="warningModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2 style="margin: 0;">Issue Warning</h2>
                <span class="close" onclick="closeWarningModal()">&times;</span>
            </div>
            <form id="warningForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="margin: 0 0 16px 0; color: #374151;">Issue warning to: <strong id="warningUserName"></strong></p>
                    <div class="form-group">
                        <label class="form-label">Warning Message *</label>
                        <textarea name="message" class="form-input" rows="4" required placeholder="Enter the warning message to send to the user..."></textarea>
                    </div>
                    <div class="alert-info" style="margin-top: 16px;">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Note:</strong>
                            <p>The user will receive a notification with this warning message.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeWarningModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Warning
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Penalty Details Modal -->
    <div id="penaltyDetailsModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2 style="margin: 0;">Penalty Details</h2>
                <span class="close" onclick="closePenaltyDetailsModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="penalty-detail-grid">
                    <div class="detail-row">
                        <span class="detail-label">PENALTY ID</span>
                        <span class="detail-value" id="detail-penalty-id">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">USER</span>
                        <span class="detail-value" id="detail-user">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">REPORT/ISSUE</span>
                        <span class="detail-value" id="detail-report">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">DESCRIPTION</span>
                        <span class="detail-value" id="detail-description" style="white-space: pre-wrap;">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">PENALTY AMOUNT</span>
                        <span class="detail-value highlight-amount" id="detail-amount">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">DATE ISSUED</span>
                        <span class="detail-value" id="detail-date">-</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">STATUS</span>
                        <span class="detail-value">
                            <span id="detail-status" class="status-badge">-</span>
                        </span>
                    </div>
                    <div class="detail-row" id="booking-row" style="display: none;">
                        <span class="detail-label">BOOKING DETAILS</span>
                        <span class="detail-value" id="detail-booking">-</span>
                    </div>
                    <div class="detail-row" id="approved-row" style="display: none;">
                        <span class="detail-label">APPROVED BY</span>
                        <span class="detail-value" id="detail-approved-by">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closePenaltyDetailsModal()">Close</button>
            </div>
        </div>
    </div>

    <style>
        .penalty-detail-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
            gap: 16px;
            align-items: flex-start;
        }

        .detail-label {
            font-size: 11px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            min-width: 140px;
            flex-shrink: 0;
        }

        .detail-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
            text-align: right;
            flex: 1;
            word-break: break-word;
        }

        .highlight-amount {
            font-size: 20px;
            font-weight: 700;
            color: #dc2626;
        }

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

        .btn-action {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-action:hover {
            background: #fde68a;
        }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding: 0 20px; flex-wrap: wrap; gap: 20px; }
        .header-with-menu { display: flex; align-items: center; gap: 16px; flex: 1; min-width: 0; }
        .header-with-menu .mobile-menu-toggle { display: none; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border: none; border-radius: 10px; width: 44px; height: 44px; align-items: center; justify-content: center; cursor: pointer; color: white; font-size: 18px; transition: all 0.3s; flex-shrink: 0; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3); }
        .header-with-menu .mobile-menu-toggle:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4); }
        .header-title { font-size: 32px; font-weight: 700; color: #1f2937; margin: 0; line-height: 1.2; flex: 1; min-width: 0; }
        .page-description { font-size: 16px; color: #6b7280; margin: 0 0 32px 0; padding: 0 20px; line-height: 1.5; }
        .header-actions { display: flex; gap: 12px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px; padding: 0 20px; }
        .stat-card { background: white; border-radius: 12px; padding: 20px; display: flex; align-items: flex-start; gap: 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); }
        .stat-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0; }
        .stat-icon.red { background: #fee2e2; color: #dc2626; }
        .stat-icon.orange { background: #fed7aa; color: #ea580c; }
        .stat-icon.green { background: #d1fae5; color: #059669; }
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-content { flex: 1; }
        .stat-value { font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 4px; }
        .stat-label { font-size: 14px; color: #6b7280; }
        .table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding: 0 20px; gap: 20px; flex-wrap: wrap; }
        .search-box { position: relative; flex: 1; min-width: 250px; max-width: 400px; }
        .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #6b7280; }
        .search-input { width: 100%; padding: 10px 14px 10px 45px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
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

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        /* Modal Styles */
        .modal {
            position: middle;
            z-index: var(--z-modal-backdrop);
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease-out;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: var(--z-modal);
            pointer-events: auto;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 24px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: white;
        }

        .modal-body {
            padding: 30px;
            max-height: calc(90vh - 160px);
            overflow-y: auto;
        }

        .modal-description {
            margin: 0 0 20px 0;
            font-size: 15px;
            color: #6b7280;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            background: #f9fafb;
        }

        .close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 24px;
            color: white;
            line-height: 1;
            background: rgba(255, 255, 255, 0.2);
        }

        .close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .action-buttons-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .action-option {
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 8px;
        }

        .action-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .action-option i {
            font-size: 32px;
            margin-bottom: 4px;
        }

        .action-title {
            font-size: 16px;
            font-weight: 700;
            display: block;
        }

        .action-desc {
            font-size: 13px;
            color: #6b7280;
            display: block;
        }

        .btn-suspend {
            border-color: #f59e0b;
        }

        .btn-suspend:hover {
            border-color: #d97706;
            background: #fef3c7;
        }

        .btn-suspend i {
            color: #f59e0b;
        }

        .btn-suspend .action-title {
            color: #92400e;
        }

        .btn-forfeit {
            border-color: #ef4444;
        }

        .btn-forfeit:hover {
            border-color: #dc2626;
            background: #fee2e2;
        }

        .btn-forfeit i {
            color: #ef4444;
        }

        .btn-forfeit .action-title {
            color: #991b1b;
        }

        .btn-warning {
            border-color: #f59e0b;
        }

        .btn-warning:hover {
            border-color: #d97706;
            background: #fef3c7;
        }

        .btn-warning i {
            color: #f59e0b;
        }

        .btn-warning .action-title {
            color: #92400e;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
            font-family: inherit;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .alert-warning {
            display: flex;
            gap: 12px;
            padding: 16px;
            border-radius: 10px;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }

        .alert-warning i {
            font-size: 20px;
            color: #f59e0b;
            flex-shrink: 0;
        }

        .alert-warning strong {
            display: block;
            font-weight: 700;
            margin-bottom: 4px;
            color: #92400e;
        }

        .alert-warning p {
            margin: 0;
            font-size: 13px;
            color: #78350f;
        }

        .alert-danger {
            display: flex;
            gap: 12px;
            padding: 16px;
            border-radius: 10px;
            background: #fee2e2;
            border-left: 4px solid #ef4444;
        }

        .alert-danger i {
            font-size: 20px;
            color: #ef4444;
            flex-shrink: 0;
        }

        .alert-danger strong {
            display: block;
            font-weight: 700;
            margin-bottom: 4px;
            color: #991b1b;
        }

        .alert-danger p {
            margin: 0;
            font-size: 13px;
            color: #7f1d1d;
        }

        .alert-info {
            display: flex;
            gap: 12px;
            padding: 16px;
            border-radius: 10px;
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
        }

        .alert-info i {
            font-size: 20px;
            color: #3b82f6;
            flex-shrink: 0;
        }

        .alert-info strong {
            display: block;
            font-weight: 700;
            margin-bottom: 4px;
            color: #1e40af;
        }

        .alert-info p {
            margin: 0;
            font-size: 13px;
            color: #1e3a8a;
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
            .header {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                padding: 0 15px;
            }
            .header-actions {
                align-self: flex-start;
                width: 100%;
            }
            .header-actions .btn {
                width: 100%;
                justify-content: center;
            }
            .header-title { font-size: 24px; }
            .page-description {
                font-size: 14px;
                padding: 0 15px;
            }
            .stats-grid {
                grid-template-columns: 1fr;
                padding: 0 15px;
                gap: 12px;
            }
            .stat-card {
                padding: 16px;
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
                font-size: 13px;
            }

            /* Table controls */
            .table-controls {
                flex-direction: column;
                padding: 0 15px;
                gap: 12px;
            }

            .search-box {
                width: 100%;
            }

            .filter-buttons {
                width: 100%;
                flex-direction: column;
                gap: 10px;
            }

            .filter-select {
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            /* Penalties table */
            .table-card {
                margin: 0 15px;
            }

            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .data-table {
                min-width: 1000px;
            }

            /* Table cells responsive */
            .user-cell {
                min-width: 200px;
            }

            .description-cell {
                min-width: 200px;
            }

            .action-buttons {
                min-width: 120px;
            }

            /* Modal adjustments */
            .modal-content {
                width: 95%;
                max-width: 95%;
                margin: 10px;
            }

            .modal-header {
                padding: 20px 15px;
            }

            .modal-body {
                padding: 20px 15px;
            }

            .modal-footer {
                padding: 15px;
                flex-direction: column;
            }

            .modal-footer .btn {
                width: 100%;
            }

            /* Form groups */
            .form-group {
                margin-bottom: 15px;
            }

            .form-label {
                font-size: 14px;
            }

            .form-input {
                font-size: 14px;
                padding: 10px;
            }

            /* Action options */
            .action-buttons-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .action-option {
                padding: 15px;
            }

            .action-option i {
                font-size: 28px;
            }

            .action-title {
                font-size: 15px;
            }

            .action-desc {
                font-size: 12px;
            }

            /* Pagination */
            .pagination-container {
                padding: 20px 15px;
            }

            /* Detail grid */
            .penalty-detail-grid {
                gap: 12px;
            }

            .detail-row {
                padding: 12px;
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .detail-label {
                min-width: auto;
                font-size: 12px;
            }

            .detail-value {
                font-size: 13px;
                text-align: left;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 20px;
                line-height: 1.3;
            }
            .page-description {
                font-size: 12px;
                line-height: 1.4;
            }

            /* Smaller stat cards */
            .stat-card {
                padding: 12px;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .stat-value {
                font-size: 20px;
            }

            .stat-label {
                font-size: 11px;
            }

            /* Modal further adjustments */
            .modal-content {
                width: 100%;
                max-width: 100%;
                margin: 0;
                border-radius: 0;
                max-height: 100vh;
            }

            .modal-header h2 {
                font-size: 18px;
            }

            .modal-body {
                padding: 15px;
                max-height: calc(100vh - 140px);
            }

            .modal-footer {
                padding: 12px 15px;
            }

            /* Close button */
            .close {
                width: 28px;
                height: 28px;
                font-size: 20px;
            }

            /* Smaller action cards */
            .action-option {
                padding: 12px;
            }

            .action-option i {
                font-size: 24px;
                margin-bottom: 2px;
            }

            .action-title {
                font-size: 14px;
            }

            .action-desc {
                font-size: 11px;
            }

            /* Button text adjustments */
            .btn {
                font-size: 13px;
                padding: 10px 16px;
            }

            .btn i {
                font-size: 12px;
            }

            /* Form inputs */
            .form-input {
                font-size: 13px;
                padding: 8px;
            }

            .form-label {
                font-size: 13px;
            }

            .form-hint {
                font-size: 11px;
            }

            /* Detail grid further adjustments */
            .detail-row {
                padding: 10px;
            }

            .detail-label {
                font-size: 11px;
            }

            .detail-value {
                font-size: 12px;
            }

            .highlight-amount {
                font-size: 16px;
            }
        }

        /* Landscape mobile view */
        @media (max-width: 768px) and (orientation: landscape) {
            .modal-content {
                max-height: 95vh;
            }

            .modal-body {
                max-height: calc(95vh - 140px);
            }
        }
    </style>

    <script>
        let currentPenaltyId = null;
        let currentUserName = '';
        let currentAmount = 0;

        function viewPenalty(id) {
            fetch(`/admin/penalties/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const penalty = data.penalty;

                        // Populate modal with penalty details
                        document.getElementById('detail-penalty-id').textContent = `#P${id.toString().padStart(3, '0')}`;
                        document.getElementById('detail-user').textContent = `${penalty.user.name} (${penalty.user.email})`;

                        // Report/Issue
                        if (penalty.report) {
                            document.getElementById('detail-report').innerHTML = `<a href="/admin/reports" style="color: #3b82f6; text-decoration: none;">#R${penalty.report.id} - ${penalty.report.type}</a>`;
                        } else {
                            document.getElementById('detail-report').innerHTML = '<span style="color: #9ca3af; font-style: italic;">No linked report</span>';
                        }

                        // Description
                        document.getElementById('detail-description').textContent = penalty.description;

                        // Amount
                        document.getElementById('detail-amount').textContent = `RM ${penalty.amount}`;

                        // Date
                        document.getElementById('detail-date').textContent = penalty.date_issued;

                        // Status with badge styling
                        const statusBadge = document.getElementById('detail-status');
                        statusBadge.textContent = penalty.status;
                        statusBadge.className = penalty.status === 'Resolved' ? 'status-badge status-resolved' : 'status-badge status-pending';

                        // Booking details (optional)
                        const bookingRow = document.getElementById('booking-row');
                        if (penalty.booking) {
                            bookingRow.style.display = 'flex';
                            document.getElementById('detail-booking').textContent = `${penalty.booking.item} (${penalty.booking.dates})`;
                        } else {
                            bookingRow.style.display = 'none';
                        }

                        // Approved by (optional)
                        const approvedRow = document.getElementById('approved-row');
                        if (penalty.approved_by) {
                            approvedRow.style.display = 'flex';
                            document.getElementById('detail-approved-by').textContent = penalty.approved_by;
                        } else {
                            approvedRow.style.display = 'none';
                        }

                        // Show the modal
                        document.getElementById('penaltyDetailsModal').style.display = 'flex';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load penalty details');
                });
        }

        function closePenaltyDetailsModal() {
            document.getElementById('penaltyDetailsModal').style.display = 'none';
        }

        function showPenaltyActionModal(penaltyId, userName, amount) {
            currentPenaltyId = penaltyId;
            currentUserName = userName;
            currentAmount = amount;
            document.getElementById('penaltyActionModal').style.display = 'flex';
            document.getElementById('penaltyModalTitle').textContent = `Penalty Actions: ${userName}`;
        }

        function closePenaltyActionModal() {
            document.getElementById('penaltyActionModal').style.display = 'none';
            currentPenaltyId = null;
            currentUserName = '';
            currentAmount = 0;
        }

        function confirmSuspend() {
            closePenaltyActionModal();
            document.getElementById('suspendUserName').textContent = currentUserName;
            document.getElementById('suspendForm').action = `/admin/penalties/${currentPenaltyId}/suspend`;
            document.getElementById('suspendModal').style.display = 'flex';
        }

        function closeSuspendModal() {
            document.getElementById('suspendModal').style.display = 'none';
        }

        function confirmForfeit() {
            closePenaltyActionModal();
            document.getElementById('forfeitUserName').textContent = currentUserName;
            document.getElementById('forfeitAmount').textContent = `RM ${currentAmount.toFixed(2)}`;
            document.getElementById('forfeitForm').action = `/admin/penalties/${currentPenaltyId}/forfeit`;
            document.getElementById('forfeitModal').style.display = 'flex';
        }

        function closeForfeitModal() {
            document.getElementById('forfeitModal').style.display = 'none';
        }

        function confirmWarning() {
            closePenaltyActionModal();
            document.getElementById('warningUserName').textContent = currentUserName;
            document.getElementById('warningForm').action = `/admin/penalties/${currentPenaltyId}/warning`;
            document.getElementById('warningModal').style.display = 'flex';
        }

        function closeWarningModal() {
            document.getElementById('warningModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePenaltyActionModal();
                closeSuspendModal();
                closeForfeitModal();
                closeWarningModal();
            }
        });
    </script>
@endsection