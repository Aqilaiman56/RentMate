@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-with-menu">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title">Reports & Complaints</h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.reports.export') }}" class="btn btn-secondary">
                Export Reports
            </a>
        </div>
    </div>

    <p class="page-description">View and manage user reports, disputes, and complaints</p>

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
            <div class="stat-icon red"><i class="fas fa-clipboard-list"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalReports }}</div>
                <div class="stat-label">Total Reports</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingReports }}</div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $resolvedReports }}</div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-times-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $dismissedReports }}</div>
                <div class="stat-label">Dismissed</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <form action="{{ route('admin.reports') }}" method="GET" class="table-controls">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" name="search" placeholder="Search reports..." class="search-input" value="{{ request('search') }}">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" name="type" onchange="this.form.submit()">
                <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                <option value="item-damage" {{ request('type') == 'item-damage' ? 'selected' : '' }}>Item Damage</option>
                <option value="late-return" {{ request('type') == 'late-return' ? 'selected' : '' }}>Late Return</option>
                <option value="dispute" {{ request('type') == 'dispute' ? 'selected' : '' }}>Dispute</option>
                <option value="fraud" {{ request('type') == 'fraud' ? 'selected' : '' }}>Fraud</option>
                <option value="harassment" {{ request('type') == 'harassment' ? 'selected' : '' }}>Harassment</option>
                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            <select class="filter-select" name="status" onchange="this.form.submit()">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="investigating" {{ request('status') == 'investigating' ? 'selected' : '' }}>Investigating</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
            </select>
            <select class="filter-select" name="priority" onchange="this.form.submit()">
                <option value="all" {{ request('priority') == 'all' ? 'selected' : '' }}>All Priority</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High Priority</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low Priority</option>
            </select>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
    </form>

    <!-- Reports Table -->
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Report Records</h3>
            <span class="table-count">Showing {{ $reports->count() }} of {{ $reports->total() }} reports</span>
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
                    @forelse($reports as $report)
                        <tr>
                            <td><span class="id-badge">#R{{ str_pad($report->ReportID, 3, '0', STR_PAD_LEFT) }}</span></td>
                            <td>
                                <div class="user-cell">
                                    @if($report->reporter->ProfileImage)
                                        <img src="{{ asset('storage/' . $report->reporter->ProfileImage) }}" 
                                             alt="{{ $report->reporter->UserName }}" 
                                             class="user-avatar-img">
                                    @else
                                        <div class="user-avatar {{ ['blue', 'pink', 'green', 'orange', 'purple', 'teal', 'red', 'indigo'][$report->reporter->UserID % 8] }}">
                                            {{ strtoupper(substr($report->reporter->UserName, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="user-info">
                                        <div class="user-name">{{ $report->reporter->UserName }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="user-cell">
                                    @if($report->reportedUser->ProfileImage)
                                        <img src="{{ asset('storage/' . $report->reportedUser->ProfileImage) }}" 
                                             alt="{{ $report->reportedUser->UserName }}" 
                                             class="user-avatar-img">
                                    @else
                                        <div class="user-avatar {{ ['blue', 'pink', 'green', 'orange', 'purple', 'teal', 'red', 'indigo'][$report->reportedUser->UserID % 8] }}">
                                            {{ strtoupper(substr($report->reportedUser->UserName, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="user-info">
                                        <div class="user-name">{{ $report->reportedUser->UserName }}</div>
                                    </div>
                                </div>
                            </td>
                            <td><span class="type-badge type-{{ $report->ReportType }}">{{ ucwords(str_replace('-', ' ', $report->ReportType)) }}</span></td>
                            <td><span class="priority-badge priority-{{ $report->Priority }}">{{ ucfirst($report->Priority) }}</span></td>
                            <td class="subject-cell">{{ $report->Subject }}</td>
                            <td>{{ $report->DateReported->format('M d, Y') }}</td>
                            <td><span class="status-badge status-{{ $report->Status }}">{{ ucfirst($report->Status) }}</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-icon btn-view" title="View Details" onclick="viewReport({{ $report->ReportID }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($report->Status == 'pending' || $report->Status == 'investigating')
                                        <button class="btn-icon btn-action" title="Take Action" onclick="showActionModal({{ $report->ReportID }}, '{{ $report->Subject }}')">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                    @if($report->hasPenalty())
                                        <button class="btn-icon btn-penalty" title="View Penalty Details" onclick="viewPenalty({{ $report->penalty->PenaltyID }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @else
                                        <button class="btn-icon btn-penalty" title="Issue Penalty" onclick="showPenaltyModal({{ $report->ReportID }})">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </button>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 60px; color: #6b7280;">
                                <p style="font-size: 18px; font-weight: 600;">No reports found</p>
                                <p style="margin-top: 10px;">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($reports->hasPages())
        <div class="pagination-container">
            {{ $reports->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- View Report Details Modal -->
    <div id="viewModal" class="modal" style="display: none;">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2 id="viewModalTitle">Report Details</h2>
                <span class="close" onclick="closeViewModal()">&times;</span>
            </div>
            <div class="modal-body" id="reportDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- View Penalty Details Modal -->
    <div id="penaltyModal" class="modal" style="display: none;">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2 id="penaltyModalTitle">Penalty Details</h2>
                <span class="close" onclick="closePenaltyModal()">&times;</span>
            </div>
            <div class="modal-body" id="penaltyDetailsContent">
                <!-- Content will be loaded dynamically -->
            </div>
        </div>
    </div>

    <!-- Action Modal -->
    <div id="actionModal" class="modal" style="display: none;">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h2 id="modalTitle">Take Action on Report</h2>
                <span class="close" onclick="closeActionModal()">&times;</span>
            </div>
            <form id="actionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">
                            Action Type
                        </label>
                        <select name="action_type" id="actionType" class="form-control" onchange="toggleActionFields()" required>
                            <option value="">Select Action</option>
                            <optgroup label="User Actions">
                                <option value="suspend">Suspend User</option>
                                <option value="warning">Issue Warning</option>
                                <option value="hold_deposit">Hold Deposit</option>
                            </optgroup>
                            <optgroup label="Report Actions">
                                <option value="resolve">Resolve Report</option>
                                <option value="dismiss">Dismiss Report</option>
                            </optgroup>
                        </select>
                    </div>

                    <!-- Suspend User Fields -->
                    <div id="suspendFields" class="action-fields" style="display: none;">
                        <div class="alert-info">
                            <div>
                                <strong>Suspend User Account</strong>
                                <p>The reported user will be unable to access their account during suspension period.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Suspension Duration
                            </label>
                            <select name="suspension_duration" id="suspensionDuration" class="form-control" onchange="toggleCustomDate()">
                                <option value="">Select Duration</option>
                                <option value="3">3 Days</option>
                                <option value="7">7 Days (1 Week)</option>
                                <option value="14">14 Days (2 Weeks)</option>
                                <option value="30">30 Days (1 Month)</option>
                                <option value="90">90 Days (3 Months)</option>
                                <option value="custom">Custom Date</option>
                                <option value="permanent">Permanent</option>
                            </select>
                        </div>

                        <div id="customDateField" class="form-group" style="display: none;">
                            <label class="form-label">Custom Suspension End Date</label>
                            <input type="date" name="suspension_end_date" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Suspension Reason
                            </label>
                            <textarea name="suspension_reason" class="form-control" rows="3" placeholder="Explain why this user is being suspended..."></textarea>
                        </div>
                    </div>

                    <!-- Warning Fields -->
                    <div id="warningFields" class="action-fields" style="display: none;">
                        <div class="alert-warning">
                            <div>
                                <strong>Issue Official Warning</strong>
                                <p>A formal warning will be recorded and sent to the user via email.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Warning Level
                            </label>
                            <select name="warning_level" class="form-control">
                                <option value="minor">Minor Warning</option>
                                <option value="moderate" selected>Moderate Warning</option>
                                <option value="severe">Severe Warning</option>
                                <option value="final">Final Warning</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Warning Message
                            </label>
                            <textarea name="warning_message" class="form-control" rows="4" placeholder="Enter the warning message to be sent to the user..."></textarea>
                        </div>
                    </div>

                    <!-- Hold Deposit Fields -->
                    <div id="holdDepositFields" class="action-fields" style="display: none;">
                        <div class="alert-danger">
                            <div>
                                <strong>Hold Security Deposit</strong>
                                <p>Temporarily hold an amount from the user's deposits for damages or violations.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Hold Amount (RM)
                            </label>
                            <input type="number" name="hold_amount" class="form-control" step="0.01" min="0" placeholder="0.00">
                            <small class="form-hint">Amount to be held from user's deposits</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Reason for Hold
                            </label>
                            <textarea name="hold_reason" class="form-control" rows="3" placeholder="Explain why this amount is being held..."></textarea>
                        </div>
                    </div>

                    <!-- Resolve/Dismiss Fields -->
                    <div id="resolveFields" class="action-fields" style="display: none;">
                        <div class="alert-success">
                            <div>
                                <strong>Resolve Report</strong>
                                <p>Mark this report as resolved and optionally apply a penalty.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="apply_penalty" id="applyPenalty" onchange="togglePenaltyAmount()">
                                <span>Apply Penalty to Reported User</span>
                            </label>
                        </div>

                        <div id="penaltyAmountFields" style="display: none;">
                            <div class="form-group">
                                <label class="form-label">
                                    Penalty Amount (RM)
                                </label>
                                <input type="number" name="penalty_amount" class="form-control" step="0.01" min="0" placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label class="form-label">
                                    Penalty Reason
                                </label>
                                <textarea name="penalty_description" class="form-control" rows="3" placeholder="Explain the reason for this penalty..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div id="dismissFields" class="action-fields" style="display: none;">
                        <div class="alert-secondary">
                            <div>
                                <strong>Dismiss Report</strong>
                                <p>Mark this report as dismissed without taking action.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes (Common for all) -->
                    <div class="form-group">
                        <label class="form-label">
                            Admin Notes
                        </label>
                        <textarea name="admin_notes" class="form-control" rows="4" placeholder="Add any internal notes about this action..." required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeActionModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        Submit Action
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Issue Penalty Modal -->
    <div id="penaltyModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Issue Penalty</h2>
                <span class="close" onclick="closePenaltyModal()">&times;</span>
            </div>
            <form id="penaltyForm" onsubmit="submitPenalty(event)">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="penalty_report_id" name="ReportID">

                    <div class="form-group">
                        <label class="form-label">Penalty Amount (RM)</label>
                        <input type="number" name="PenaltyAmount" class="form-control" step="0.01" min="0" max="999999.99" placeholder="0.00" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Penalty Description</label>
                        <textarea name="Description" class="form-control" rows="4" placeholder="Describe the reason for this penalty..." required></textarea>
                    </div>

                    <div class="alert-warning">
                        <div>
                            <strong>Note:</strong>
                            <p>This penalty will be recorded in the user's account and they will be notified. The report will be automatically marked as resolved.</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closePenaltyModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">Issue Penalty</button>
                </div>
            </form>
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
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

      
       

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        .modal-content.modal-lg {
            max-width: 800px;
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
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
        }

        .modal-body {
            padding: 30px;
            max-height: calc(90vh - 160px);
            overflow-y: auto;
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
            color: #6b7280;
            line-height: 1;
        }

        .close:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #1f2937;
            font-size: 14px;
        }

        .label-icon {
            font-size: 16px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-hint {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: #6b7280;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .checkbox-label:hover {
            background: #f3f4f6;
        }

        .checkbox-label input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .checkbox-label span {
            font-weight: 500;
            color: #374151;
        }

        .action-fields {
            padding: 20px;
            background: #f9fafb;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .alert-info, .alert-warning, .alert-danger, .alert-success, .alert-secondary {
            display: flex;
            gap: 12px;
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #dbeafe;
            border-left: 4px solid #3b82f6;
        }

        .alert-warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
        }

        .alert-danger {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
        }

        .alert-success {
            background: #d1fae5;
            border-left: 4px solid #10b981;
        }

        .alert-secondary {
            background: #f3f4f6;
            border-left: 4px solid #6b7280;
        }

        .info-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .alert-info strong, .alert-warning strong, .alert-danger strong, .alert-success strong, .alert-secondary strong {
            display: block;
            font-weight: 700;
            margin-bottom: 4px;
            color: #1f2937;
        }

        .alert-info p, .alert-warning p, .alert-danger p, .alert-success p, .alert-secondary p {
            margin: 0;
            font-size: 13px;
            color: #374151;
        }

        .btn-icon {
            margin-right: 6px;
        }

        /* Header */
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
        .stat-icon.purple { background: #e9d5ff; color: #9333ea; }
        .stat-content { flex: 1; }
        .stat-value { font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 4px; }
        .stat-label { font-size: 14px; color: #6b7280; }
        .table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding: 0 20px; gap: 20px; flex-wrap: wrap; }
        .search-box { position: relative; flex: 1; min-width: 250px; max-width: 400px; }
        .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); font-size: 14px; color: #6b7280; }
        .search-input { width: 100%; padding: 10px 14px 10px 45px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
        .search-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
        .filter-buttons { display: flex; gap: 12px; flex-wrap: wrap; }
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
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; color: white; }
        .user-avatar.blue { background: #3b82f6; }
        .user-avatar.pink { background: #ec4899; }
        .user-avatar.green { background: #10b981; }
        .user-avatar.orange { background: #f97316; }
        .user-avatar.purple { background: #a855f7; }
        .user-avatar.teal { background: #14b8a6; }
        .user-avatar.red { background: #ef4444; }
        .user-avatar.indigo { background: #6366f1; }
        .user-name { font-weight: 600; color: #1f2937; font-size: 13px; }
        .type-badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .type-item-damage { background: #fee2e2; color: #991b1b; }
        .type-late-return { background: #fed7aa; color: #92400e; }
        .type-dispute { background: #fef3c7; color: #854d0e; }
        .type-fraud { background: #fecaca; color: #7f1d1d; }
        .type-harassment { background: #fce7f3; color: #831843; }
        .type-other { background: #f3f4f6; color: #6b7280; }
        .priority-badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .priority-high { background: #fee2e2; color: #991b1b; }
        .priority-medium { background: #fed7aa; color: #92400e; }
        .priority-low { background: #dbeafe; color: #1e40af; }
        .subject-cell { max-width: 250px; }
        .status-badge { display: inline-block; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-investigating { background: #dbeafe; color: #1e40af; }
        .status-resolved { background: #d1fae5; color: #065f46; }
        .status-dismissed { background: #f3f4f6; color: #6b7280; }
        .action-buttons { display: flex; gap: 8px; }
        .btn-icon { width: 36px; height: 36px; border: none; border-radius: 8px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; font-size: 16px; }
        .btn-view { background: #dbeafe; }
        .btn-view:hover { background: #bfdbfe; }
        .btn-action { background: #fef3c7; }
        .btn-action:hover { background: #fde68a; }
        .btn-penalty { background: #fee2e2; color: #dc2626; }
        .btn-penalty:hover { background: #fecaca; }
        .btn { padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; display: inline-block; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background: #e5e7eb; }

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
            .table-controls { flex-direction: column; align-items: stretch; }
            .search-box { max-width: 100%; }
        }
    </style>

    <script>
        let currentReportId = null;

        function viewReport(id) {
            fetch(`/admin/reports/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const report = data.report;
                        const content = `
                            <div style="display: grid; gap: 24px;">
                                <div>
                                    <h3 style="margin: 0 0 16px 0; font-size: 16px; color: #6b7280;">Report Information</h3>
                                    <div style="display: grid; gap: 12px;">
                                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #374151;">Report ID:</span>
                                            <span>#R${id.toString().padStart(3, '0')}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #374151;">Type:</span>
                                            <span>${report.type}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #374151;">Priority:</span>
                                            <span>${report.priority}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #374151;">Status:</span>
                                            <span>${report.status}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 style="margin: 0 0 16px 0; font-size: 16px; color: #6b7280;">Parties Involved</h3>
                                    <div style="display: grid; gap: 12px;">
                                        <div style="padding: 12px; background: #f9fafb; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #374151; display: block; margin-bottom: 4px;">Reporter:</span>
                                            <span>${report.reporter.name}</span><br>
                                            <span style="color: #6b7280; font-size: 13px;">${report.reporter.email}</span>
                                        </div>
                                        <div style="padding: 12px; background: #fee2e2; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #991b1b; display: block; margin-bottom: 4px;">Reported User:</span>
                                            <span>${report.reported_user.name}</span><br>
                                            <span style="color: #7f1d1d; font-size: 13px;">${report.reported_user.email}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #6b7280;">Subject</h3>
                                    <p style="margin: 0; padding: 12px; background: #f9fafb; border-radius: 8px;">${report.subject}</p>
                                </div>

                                <div>
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #6b7280;">Description</h3>
                                    <p style="margin: 0; padding: 12px; background: #f9fafb; border-radius: 8px; white-space: pre-wrap;">${report.description}</p>
                                </div>

                                ${report.booking ? `
                                <div>
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #6b7280;">Related Booking</h3>
                                    <div style="padding: 12px; background: #dbeafe; border-radius: 8px;">
                                        <span style="font-weight: 600;">Booking #${report.booking.id}</span><br>
                                        <span>Item: ${report.booking.item}</span><br>
                                        <span style="color: #1e40af; font-size: 13px;">${report.booking.dates}</span>
                                    </div>
                                </div>
                                ` : ''}

                                ${report.penalty ? `
                                <div>
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #6b7280;">Penalty Applied</h3>
                                    <div style="padding: 12px; background: #fee2e2; border-radius: 8px;">
                                        <span style="font-weight: 600; color: #991b1b;">Amount: ${report.penalty.amount}</span><br>
                                        <span style="font-size: 13px;">Resolved: ${report.penalty.resolved}</span>
                                    </div>
                                </div>
                                ` : ''}

                                <div>
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #6b7280;">Timeline</h3>
                                    <div style="display: grid; gap: 8px;">
                                        <div style="padding: 10px; background: #f9fafb; border-radius: 8px; font-size: 13px;">
                                            <span style="font-weight: 600;">Reported:</span> ${report.date_reported}
                                        </div>
                                        <div style="padding: 10px; background: #f9fafb; border-radius: 8px; font-size: 13px;">
                                            <span style="font-weight: 600;">Resolved:</span> ${report.date_resolved}
                                        </div>
                                    </div>
                                </div>

                                ${report.admin_notes !== 'No notes' ? `
                                <div>
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #6b7280;">Admin Notes</h3>
                                    <p style="margin: 0; padding: 12px; background: #fef3c7; border-radius: 8px; white-space: pre-wrap;">${report.admin_notes}</p>
                                </div>
                                ` : ''}
                            </div>
                        `;
                        document.getElementById('reportDetailsContent').innerHTML = content;
                        document.getElementById('viewModal').style.display = 'flex';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load report details');
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').style.display = 'none';
        }

        function showActionModal(reportId, subject) {
            currentReportId = reportId;
            document.getElementById('actionModal').style.display = 'flex';
            document.getElementById('modalTitle').textContent = `Take Action: ${subject}`;
            document.getElementById('actionForm').reset();
            hideAllActionFields();
        }

        function closeActionModal() {
            document.getElementById('actionModal').style.display = 'none';
            currentReportId = null;
        }

        function hideAllActionFields() {
            document.querySelectorAll('.action-fields').forEach(field => {
                field.style.display = 'none';
            });
        }

        function toggleActionFields() {
            const actionType = document.getElementById('actionType').value;
            hideAllActionFields();

            switch(actionType) {
                case 'suspend':
                    document.getElementById('suspendFields').style.display = 'block';
                    break;
                case 'warning':
                    document.getElementById('warningFields').style.display = 'block';
                    break;
                case 'hold_deposit':
                    document.getElementById('holdDepositFields').style.display = 'block';
                    break;
                case 'resolve':
                    document.getElementById('resolveFields').style.display = 'block';
                    break;
                case 'dismiss':
                    document.getElementById('dismissFields').style.display = 'block';
                    break;
            }
        }

        function toggleCustomDate() {
            const duration = document.getElementById('suspensionDuration').value;
            document.getElementById('customDateField').style.display = duration === 'custom' ? 'block' : 'none';
        }

        function togglePenaltyAmount() {
            const applyPenalty = document.getElementById('applyPenalty').checked;
            document.getElementById('penaltyAmountFields').style.display = applyPenalty ? 'block' : 'none';
        }

        document.getElementById('actionForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const actionType = formData.get('action_type');

            let url;
            switch(actionType) {
                case 'suspend':
                    url = `/admin/reports/${currentReportId}/suspend-user`;
                    break;
                case 'warning':
                    url = `/admin/reports/${currentReportId}/issue-warning`;
                    break;
                case 'hold_deposit':
                    url = `/admin/reports/${currentReportId}/hold-deposit`;
                    break;
                case 'resolve':
                    url = `/admin/reports/${currentReportId}/resolve`;
                    break;
                case 'dismiss':
                    url = `/admin/reports/${currentReportId}/dismiss`;
                    break;
                default:
                    alert('Please select an action type');
                    return;
            }

            this.action = url;
            this.submit();
        });

        // Penalty Modal Functions
        function showPenaltyModal(reportId) {
            document.getElementById('penalty_report_id').value = reportId;
            document.getElementById('penaltyModal').style.display = 'flex';
        }

        function closePenaltyModal() {
            document.getElementById('penaltyModal').style.display = 'none';
            document.getElementById('penaltyForm').reset();
        }

        function viewPenalty(penaltyId) {
            fetch(`/admin/penalties/${penaltyId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const penalty = data.penalty;
                        const content = `
                            <div style="display: grid; gap: 24px;">
                                <div>
                                    <h3 style="margin: 0 0 16px 0; font-size: 16px; color: #6b7280;">Penalty Information</h3>
                                    <div style="display: grid; gap: 12px;">
                                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #374151;">Penalty ID:</span>
                                            <span>#P${penalty.id.toString().padStart(3, '0')}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #374151;">Amount:</span>
                                            <span style="font-weight: 700; color: #dc2626;">RM ${penalty.amount}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #374151;">Status:</span>
                                            <span class="${penalty.resolved ? 'text-green-600' : 'text-orange-600'}">${penalty.resolved ? 'Resolved' : 'Pending'}</span>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; padding: 12px; background: #f9fafb; border-radius: 8px;">
                                            <span style="font-weight: 600; color: #374151;">Date Issued:</span>
                                            <span>${penalty.date_issued}</span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h3 style="margin: 0 0 16px 0; font-size: 16px; color: #6b7280;">User Information</h3>
                                    <div style="padding: 12px; background: #f9fafb; border-radius: 8px;">
                                        <span style="font-weight: 600; color: #374151; display: block; margin-bottom: 4px;">${penalty.user.name}</span>
                                        <span style="color: #6b7280; font-size: 13px;">${penalty.user.email}</span>
                                    </div>
                                </div>

                                <div>
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #6b7280;">Description</h3>
                                    <p style="margin: 0; padding: 12px; background: #f9fafb; border-radius: 8px; white-space: pre-wrap;">${penalty.description}</p>
                                </div>

                                ${penalty.report ? `
                                <div>
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #6b7280;">Related Report</h3>
                                    <div style="padding: 12px; background: #dbeafe; border-radius: 8px;">
                                        <span style="font-weight: 600;">Report #R${penalty.report.id.toString().padStart(3, '0')}</span><br>
                                        <span>${penalty.report.subject}</span>
                                    </div>
                                </div>
                                ` : ''}

                                ${penalty.evidence ? `
                                <div>
                                    <h3 style="margin: 0 0 12px 0; font-size: 16px; color: #6b7280;">Evidence</h3>
                                    <div style="padding: 12px; background: #f9fafb; border-radius: 8px;">
                                        <a href="${penalty.evidence}" target="_blank" style="color: #3b82f6; text-decoration: none;">
                                            <i class="fas fa-image"></i> View Evidence
                                        </a>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                        `;
                        document.getElementById('penaltyDetailsContent').innerHTML = content;
                        document.getElementById('penaltyModal').style.display = 'flex';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load penalty details');
                });
        }

        function submitPenalty(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const data = {
                ReportID: formData.get('ReportID'),
                PenaltyAmount: formData.get('PenaltyAmount'),
                Description: formData.get('Description')
            };

            fetch('/admin/penalties/create-from-report', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Penalty issued successfully!');
                    closePenaltyModal();
                    location.reload();
                } else {
                    alert(data.message || 'Failed to issue penalty');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while issuing the penalty');
            });
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
@endsection