@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-with-menu">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title">Refund Queue</h1>
        </div>
        @include('admin.partials.header-actions', ['notificationCount' => $notificationCount ?? 0])
    </div>

    <p class="page-description">Process pending deposit refunds to users' bank accounts</p>

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
            <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending Refunds</div>
                <div class="stat-amount">RM {{ number_format($stats['pending_amount'], 2) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-spinner"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['processing'] }}</div>
                <div class="stat-label">Processing</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completed</div>
                <div class="stat-amount">RM {{ number_format($stats['total_refunded'], 2) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['failed'] }}</div>
                <div class="stat-label">Failed</div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form action="{{ route('admin.refund-queue') }}" method="GET" class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text"
                   name="search"
                   placeholder="Search by user or reference..."
                   class="search-input"
                   value="{{ request('search') }}">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" name="status" onchange="this.form.submit()">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
    </form>

    <!-- Refund Queue Table -->
    <div class="refund-cards-container">
        <div class="table-header">
            <h3 class="table-title">Refund Requests</h3>
            <span class="table-count">Showing {{ $refunds->count() }} of {{ $refunds->total() }} refunds</span>
        </div>

        @forelse($refunds as $refund)
            <div class="refund-card" id="refund-{{ $refund->RefundQueueID }}">
                <!-- Card Header - User Info (Always Visible) -->
                <div class="refund-card-header" onclick="toggleRefund({{ $refund->RefundQueueID }})">
                    <div class="refund-user-section">
                        @if($refund->user->ProfileImage)
                            <img src="{{ asset('storage/' . $refund->user->ProfileImage) }}"
                                 alt="{{ $refund->user->UserName }}"
                                 class="refund-avatar-img">
                        @else
                            <div class="refund-avatar blue">
                                {{ strtoupper(substr($refund->user->UserName, 0, 2)) }}
                            </div>
                        @endif
                        <div class="refund-user-info">
                            <div class="refund-user-name">{{ $refund->user->UserName }}</div>
                            <div class="refund-user-email">{{ $refund->user->Email }}</div>
                        </div>
                    </div>
                    <div class="refund-header-right">
                        <span class="status-badge status-{{ strtolower($refund->Status) }}">
                            {{ ucfirst($refund->Status) }}
                        </span>
                        <i class="fas fa-chevron-down refund-toggle-icon"></i>
                    </div>
                </div>

                <!-- Card Body - Details (Expandable) -->
                <div class="refund-card-body">
                    <div class="refund-details-grid">
                        <div class="refund-detail-item">
                            <span class="refund-detail-label">Ref ID</span>
                            <span class="refund-detail-value">
                                <span class="id-badge">#RQ{{ str_pad($refund->RefundQueueID, 4, '0', STR_PAD_LEFT) }}</span>
                            </span>
                        </div>

                        <div class="refund-detail-item">
                            <span class="refund-detail-label">Bank Details</span>
                            <span class="refund-detail-value">
                                <div class="bank-info-compact">
                                    <div>{{ $refund->BankName }}</div>
                                    <div class="bank-account-compact">{{ $refund->BankAccountNumber }}</div>
                                    <div class="bank-holder-compact">{{ $refund->BankAccountHolderName }}</div>
                                </div>
                            </span>
                        </div>

                        <div class="refund-detail-item">
                            <span class="refund-detail-label">Amount</span>
                            <span class="refund-detail-value">
                                <span class="amount-badge large">RM {{ number_format($refund->RefundAmount, 2) }}</span>
                            </span>
                        </div>

                        <div class="refund-detail-item">
                            <span class="refund-detail-label">Item</span>
                            <span class="refund-detail-value">{{ $refund->deposit->booking->item->ItemName ?? 'N/A' }}</span>
                        </div>

                        <div class="refund-detail-item">
                            <span class="refund-detail-label">Created At</span>
                            <span class="refund-detail-value">{{ $refund->created_at->format('M d, Y - h:i A') }}</span>
                        </div>

                        <div class="refund-detail-item">
                            <span class="refund-detail-label">Status</span>
                            <span class="refund-detail-value">
                                <span class="status-badge status-{{ strtolower($refund->Status) }}">
                                    {{ ucfirst($refund->Status) }}
                                </span>
                            </span>
                        </div>

                        @if($refund->RefundReference)
                        <div class="refund-detail-item" style="grid-column: 1 / -1;">
                            <span class="refund-detail-label">Refund Reference</span>
                            <span class="refund-detail-value">
                                <strong style="color: #059669;">{{ $refund->RefundReference }}</strong>
                                @if($refund->AutoGenerated)
                                    <span class="badge" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px; margin-left: 8px;">
                                        <i class="fas fa-robot"></i> Auto-Generated
                                    </span>
                                @endif
                                @if($refund->RefundMethod === 'manual')
                                    <span class="badge" style="background: #6b7280; color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px; margin-left: 8px;">
                                        <i class="fas fa-hand-paper"></i> Manual
                                    </span>
                                @endif
                            </span>
                        </div>
                        @endif

                        @if($refund->ProcessedAt)
                        <div class="refund-detail-item">
                            <span class="refund-detail-label">Processed At</span>
                            <span class="refund-detail-value">{{ $refund->ProcessedAt->format('M d, Y - h:i A') }}</span>
                        </div>
                        @endif

                        @if($refund->processor)
                        <div class="refund-detail-item">
                            <span class="refund-detail-label">Processed By</span>
                            <span class="refund-detail-value">{{ $refund->processor->UserName }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="refund-card-actions">
                        <button class="btn-action btn-action-full" onclick="showRefundActions({{ $refund->RefundQueueID }}, '{{ $refund->Status }}')">
                            <i class="fas fa-cog"></i> Actions
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px;"></i>
                <p style="font-size: 18px; font-weight: 600; color: #374151; margin-bottom: 8px;">No refund requests found</p>
                <p style="color: #6b7280;">Try adjusting your filters</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($refunds->hasPages())
        <div class="pagination-container">
            {{ $refunds->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Complete Refund Modal -->
    <div id="completeModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Complete Refund</h2>
                <span class="close" onclick="closeModal('completeModal')">&times;</span>
            </div>
            <form id="completeForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Bank Transfer Reference Number *</label>
                        <input type="text" name="refund_reference" class="form-input" required placeholder="e.g., TRF2023101234567">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Proof of Transfer (Optional)</label>
                        <input type="file" name="proof_of_transfer" class="form-input" accept="image/*,.pdf">
                        <small class="form-hint">Upload bank transfer receipt (JPG, PNG, PDF - Max 5MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('completeModal')">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Complete Refund
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mark Failed Modal -->
    <div id="failedModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h2 style="color: white; margin: 0;">Mark as Failed</h2>
                <span class="close" onclick="closeModal('failedModal')">&times;</span>
            </div>
            <form id="failedForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Reason for Failure *</label>
                        <textarea name="reason" class="form-input" rows="4" required placeholder="e.g., Invalid bank account number"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('failedModal')">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Mark as Failed
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Action Selection Modal -->
    <div id="actionModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h2>Refund Actions</h2>
                <span class="close" onclick="closeModal('actionModal')">&times;</span>
            </div>
            <div class="modal-body">
                <p class="modal-description">
                    Choose an action for Refund <strong id="actionRefundId"></strong>
                </p>
                <div class="action-buttons-grid">
                    <button id="btnAutoComplete" class="action-option btn-auto-complete" onclick="autoCompleteRefund()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                        <i class="fas fa-magic"></i>
                        <span class="action-title">Auto Process Refund</span>
                        <span class="action-desc">Automatic reference generation</span>
                    </button>

                    <button id="btnProcessing" class="action-option btn-processing" onclick="markAsProcessing()">
                        <i class="fas fa-spinner"></i>
                        <span class="action-title">Mark as Processing</span>
                        <span class="action-desc">Start processing this refund</span>
                    </button>

                    <button id="btnComplete" class="action-option btn-complete" onclick="showCompleteModal()">
                        <i class="fas fa-hand-paper"></i>
                        <span class="action-title">Manual Complete</span>
                        <span class="action-desc">Enter reference manually</span>
                    </button>

                    <button id="btnFailed" class="action-option btn-failed" onclick="showFailedModal()">
                        <i class="fas fa-times-circle"></i>
                        <span class="action-title">Mark as Failed</span>
                        <span class="action-desc">Refund could not be completed</span>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('actionModal')">Cancel</button>
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

        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.green { background: #d1fae5; color: #059669; }
        .stat-icon.orange { background: #fed7aa; color: #ea580c; }
        .stat-icon.red { background: #fee2e2; color: #dc2626; }

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

        .stat-amount {
            font-size: 12px;
            color: #3b82f6;
            font-weight: 600;
            margin-top: 4px;
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

        /* Table Card */
        /* Refund Cards Container */
        .refund-cards-container {
            margin: 0 20px;
        }

        .refund-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 16px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .refund-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .refund-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            cursor: pointer;
            background: white;
            transition: background 0.2s;
        }

        .refund-card-header:hover {
            background: #f9fafb;
        }

        .refund-user-section {
            display: flex;
            align-items: center;
            gap: 16px;
            flex: 1;
        }

        .refund-avatar-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .refund-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            color: white;
            flex-shrink: 0;
            background: #3b82f6;
        }

        .refund-user-info {
            flex: 1;
            min-width: 0;
        }

        .refund-user-name {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .refund-user-email {
            font-size: 13px;
            color: #6b7280;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .refund-header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .refund-toggle-icon {
            font-size: 18px;
            color: #9ca3af;
            transition: transform 0.3s;
        }

        .refund-card.expanded .refund-toggle-icon {
            transform: rotate(180deg);
        }

        .refund-card-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background: #f9fafb;
        }

        .refund-card.expanded .refund-card-body {
            max-height: 800px;
        }

        .refund-details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 20px;
        }

        .refund-detail-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .refund-detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .refund-detail-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }

        .bank-info-compact {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .bank-account-compact {
            font-size: 13px;
            color: #6b7280;
            font-family: monospace;
        }

        .bank-holder-compact {
            font-size: 13px;
            color: #9ca3af;
        }

        .refund-card-actions {
            padding: 0 20px 20px 20px;
            display: flex;
            justify-content: center;
        }

        .btn-action-full {
            width: auto;
            justify-content: center;
            padding: 8px 16px;
            font-size: 13px;
        }

        .empty-state {
            background: white;
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
            margin: 20px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            margin-bottom: 16px;
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
            font-weight: 500;
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

        .user-avatar-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
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
            background: #3b82f6;
        }

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

        .bank-cell {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .bank-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 13px;
        }

        .bank-account {
            font-size: 12px;
            color: #3b82f6;
            font-family: monospace;
        }

        .bank-holder {
            font-size: 11px;
            color: #6b7280;
        }

        .item-cell {
            max-width: 200px;
        }

        .item-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 13px;
        }

        .amount-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            background: #d1fae5;
            color: #065f46;
        }

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
            background: #fed7aa;
            color: #92400e;
        }

        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
        }

        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }

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

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
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
            display: none;
            position: center;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            z-index: var(--z-modal-backdrop);
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
            z-index: var(--z-modal);
            margin: auto;
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
        }

        .modal-body {
            padding: 28px;
            max-height: calc(90vh - 200px);
            overflow-y: auto;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px 28px;
            border-top: 2px solid #e5e7eb;
            background: #f9fafb;
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
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-hint {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }

        .pagination-container {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

        /* Responsive Breakpoints */
        @media (max-width: 968px) {
            .header { align-items: flex-start; gap: 16px; }
            .header-with-menu { order: -1; width: 100%; align-items: center; }
            .header-with-menu .mobile-menu-toggle { display: flex; }
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

            /* Refund Cards */
            .refund-cards-container {
                margin: 0 15px;
            }

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                padding: 16px;
            }

            .refund-card-header {
                padding: 16px;
            }

            .refund-avatar-img,
            .refund-avatar {
                width: 44px;
                height: 44px;
                font-size: 16px;
            }

            .refund-user-name {
                font-size: 15px;
            }

            .refund-user-email {
                font-size: 12px;
            }

            .refund-details-grid {
                grid-template-columns: 1fr;
                gap: 16px;
                padding: 16px;
            }

            .refund-card-actions {
                padding: 0 16px 16px 16px;
                display: flex;
                justify-content: center;
            }

            .empty-state {
                margin: 0 15px;
                padding: 40px 20px;
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

            /* Table controls */
            .table-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: 100%;
            }

            /* Refund Cards - Extra Small */
            .refund-card-header {
                padding: 12px;
            }

            .refund-user-section {
                gap: 12px;
            }

            .refund-avatar-img,
            .refund-avatar {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }

            .refund-user-name {
                font-size: 14px;
            }

            .refund-user-email {
                font-size: 11px;
            }

            .refund-header-right {
                gap: 10px;
            }

            .status-badge {
                font-size: 11px;
                padding: 4px 10px;
            }

            .refund-toggle-icon {
                font-size: 16px;
            }

            .refund-details-grid {
                padding: 12px;
                gap: 12px;
            }

            .refund-detail-label {
                font-size: 11px;
            }

            .refund-detail-value {
                font-size: 13px;
            }

            .refund-card-actions {
                padding: 0 12px 12px 12px;
                display: flex;
                justify-content: center;
            }

            .btn-action-full {
                padding: 8px 14px;
                font-size: 12px;
                width: auto;
            }

            .table-header {
                padding: 12px;
            }

            .table-title {
                font-size: 16px;
            }

            .table-count {
                font-size: 12px;
            }

            .empty-state {
                padding: 30px 15px;
            }

            .empty-state i {
                font-size: 36px !important;
            }

            .empty-state p {
                font-size: 14px !important;
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

        /* Modal Styles */
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
                transform: translate(-50%, -50%) translateY(20px);
                opacity: 0;
            }
            to {
                transform: translate(-50%, -50%) translateY(0);
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

        /* Action Modal Styles */
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

        .btn-processing {
            border-color: #3b82f6;
        }

        .btn-processing:hover {
            border-color: #2563eb;
            background: #dbeafe;
        }

        .btn-processing i {
            color: #3b82f6;
        }

        .btn-processing .action-title {
            color: #1e40af;
        }

        .btn-complete {
            border-color: #10b981;
        }

        .btn-complete:hover {
            border-color: #059669;
            background: #d1fae5;
        }

        .btn-complete i {
            color: #10b981;
        }

        .btn-complete .action-title {
            color: #065f46;
        }

        .btn-failed {
            border-color: #ef4444;
        }

        .btn-failed:hover {
            border-color: #dc2626;
            background: #fee2e2;
        }

        .btn-failed i {
            color: #ef4444;
        }

        .btn-failed .action-title {
            color: #991b1b;
        }

        .action-option:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .action-option:disabled:hover {
            border-color: #e5e7eb;
            background: white;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                padding: 0 15px;
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

            /* Refund table */
            .refund-table {
                overflow-x: auto;
                margin: 0 15px;
            }

            .refund-table table {
                min-width: 800px;
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
                padding: 15px;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .stat-value {
                font-size: 22px;
            }

            .stat-label {
                font-size: 12px;
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
        let currentRefundId = null;
        let currentRefundStatus = null;

        function toggleRefund(refundId) {
            const card = document.getElementById('refund-' + refundId);
            card.classList.toggle('expanded');
        }

        function showRefundActions(refundId, status) {
            currentRefundId = refundId;
            currentRefundStatus = status;

            // Update modal title with refund ID
            document.getElementById('actionRefundId').textContent = '#RQ' + refundId.toString().padStart(4, '0');

            // Show/hide action buttons based on status
            const btnProcessing = document.getElementById('btnProcessing');
            const btnComplete = document.getElementById('btnComplete');
            const btnFailed = document.getElementById('btnFailed');

            // Enable/disable buttons based on status
            if (status === 'pending') {
                // Pending: can do all actions
                btnProcessing.disabled = false;
                btnComplete.disabled = false;
                btnFailed.disabled = false;
            } else if (status === 'processing') {
                // Processing: can only complete or mark as failed
                btnProcessing.disabled = true;
                btnComplete.disabled = false;
                btnFailed.disabled = false;
            } else {
                // Completed or Failed: disable all
                btnProcessing.disabled = true;
                btnComplete.disabled = true;
                btnFailed.disabled = true;
            }

            // Show the action modal
            document.getElementById('actionModal').style.display = 'block';
        }

        function autoCompleteRefund() {
            if (currentRefundId && (currentRefundStatus === 'pending' || currentRefundStatus === 'processing')) {
                if (confirm('Process refund automatically? A unique reference will be auto-generated.')) {
                    // Close action modal
                    closeModal('actionModal');

                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/refund-queue/${currentRefundId}/auto-complete`;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }

        function markAsProcessing() {
            if (currentRefundId && currentRefundStatus === 'pending') {
                // Close action modal
                closeModal('actionModal');

                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/refund-queue/${currentRefundId}/processing`;
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showCompleteModal() {
            if (currentRefundId && (currentRefundStatus === 'pending' || currentRefundStatus === 'processing')) {
                // Close action modal
                closeModal('actionModal');

                // Set form action and show complete modal
                document.getElementById('completeForm').action = `/admin/refund-queue/${currentRefundId}/complete`;
                document.getElementById('completeModal').style.display = 'block';
            }
        }

        function showFailedModal() {
            if (currentRefundId && (currentRefundStatus === 'pending' || currentRefundStatus === 'processing')) {
                // Close action modal
                closeModal('actionModal');

                // Set form action and show failed modal
                document.getElementById('failedForm').action = `/admin/refund-queue/${currentRefundId}/failed`;
                document.getElementById('failedModal').style.display = 'block';
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('actionModal');
                closeModal('completeModal');
                closeModal('failedModal');
            }
        });
    </script>
@endsection
