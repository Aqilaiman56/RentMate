@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Reports & Complaints</h1>
            <p class="header-description">View and manage user reports, disputes, and complaints</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.reports.export') }}" class="btn btn-secondary">
                📥 Export Reports
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
            <div class="stat-icon red">📋</div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalReports }}</div>
                <div class="stat-label">Total Reports</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">⏳</div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingReports }}</div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-content">
                <div class="stat-value">{{ $resolvedReports }}</div>
                <div class="stat-label">Resolved</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">❌</div>
            <div class="stat-content">
                <div class="stat-value">{{ $dismissedReports }}</div>
                <div class="stat-label">Dismissed</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <form action="{{ route('admin.reports') }}" method="GET" class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
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
                                    <button class="btn-icon btn-view" title="View Details" onclick="viewReport({{ $report->ReportID }})">👁️</button>
                                    @if($report->Status == 'pending' || $report->Status == 'investigating')
                                        <button class="btn-icon btn-action" title="Take Action" onclick="showActionModal({{ $report->ReportID }}, '{{ $report->Subject }}')">⚡</button>
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

    <!-- Action Modal -->
    <div id="actionModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeActionModal()">&times;</span>
            <h2 id="modalTitle">Take Action on Report</h2>
            <form id="actionForm" method="POST">
                @csrf
                <div class="form-group">
                    <label>Action Type:</label>
                    <select name="action_type" id="actionType" class="form-control" onchange="togglePenaltyFields()">
                        <option value="">Select Action</option>
                        <option value="resolve">Resolve Report</option>
                        <option value="dismiss">Dismiss Report</option>
                    </select>
                </div>

                <div id="penaltyFields" style="display: none;">
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="apply_penalty" id="applyPenalty" onchange="togglePenaltyAmount()">
                            Apply Penalty
                        </label>
                    </div>

                    <div id="penaltyAmountFields" style="display: none;">
                        <div class="form-group">
                            <label>Penalty Amount (RM):</label>
                            <input type="number" name="penalty_amount" class="form-control" step="0.01" min="0">
                        </div>
                        <div class="form-group">
                            <label>Penalty Reason:</label>
                            <textarea name="penalty_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Admin Notes:</label>
                    <textarea name="admin_notes" class="form-control" rows="4" required></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeActionModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Action</button>
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

        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: #ef4444;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1f2937;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        /* Keep all your existing styles from document #10 */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; padding: 0 20px; flex-wrap: wrap; gap: 20px; }
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
        .stat-icon.purple { background: #e9d5ff; }
        .stat-content { flex: 1; }
        .stat-value { font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 4px; }
        .stat-label { font-size: 14px; color: #6b7280; }
        .table-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; padding: 0 20px; gap: 20px; flex-wrap: wrap; }
        .search-box { position: relative; flex: 1; min-width: 250px; max-width: 400px; }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 16px; }
        .search-input { width: 100%; padding: 10px 14px 10px 40px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
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
        .btn-icon { width: 36px; height: 36px; border: none; border-radius: 8px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .btn-view { background: #dbeafe; }
        .btn-view:hover { background: #bfdbfe; }
        .btn-action { background: #fef3c7; }
        .btn-action:hover { background: #fde68a; }
        .btn { padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; display: inline-block; }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background: #e5e7eb; }
    </style>

    <script>
        function viewReport(id) {
            fetch(`/admin/reports/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const report = data.report;
                        alert(`Report Details #R${id.toString().padStart(3, '0')}

Type: ${report.type}
Priority: ${report.priority}
Subject: ${report.subject}
Status: ${report.status}

Reporter: ${report.reporter.name} (${report.reporter.email})
Reported User: ${report.reported_user.name} (${report.reported_user.email})

Description: ${report.description}

Date Reported: ${report.date_reported}
Date Resolved: ${report.date_resolved}

${report.penalty ? 'Penalty Applied: ' + report.penalty.amount + ' (Resolved: ' + report.penalty.resolved + ')' : 'No penalty applied'}

Admin Notes: ${report.admin_notes}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load report details');
                });
        }

        function showActionModal(reportId, subject) {
            document.getElementById('actionModal').style.display = 'flex';
            document.getElementById('modalTitle').textContent = `Take Action: ${subject}`;
            document.getElementById('actionForm').action = '';
        }

        function closeActionModal() {
            document.getElementById('actionModal').style.display = 'none';
        }

        function togglePenaltyFields() {
            const actionType = document.getElementById('actionType').value;
            document.getElementById('penaltyFields').style.display = actionType === 'resolve' ? 'block' : 'none';
        }

        function togglePenaltyAmount() {
            const applyPenalty = document.getElementById('applyPenalty').checked;
            document.getElementById('penaltyAmountFields').style.display = applyPenalty ? 'block' : 'none';
        }

        document.getElementById('actionForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const reportId = this.action.split('/').pop();
            const actionType = formData.get('action_type');
            
            const url = actionType === 'resolve' 
                ? `/admin/reports/${reportId}/resolve`
                : `/admin/reports/${reportId}/dismiss`;
            
            this.action = url;
            this.submit();
        });
    </script>
@endsection