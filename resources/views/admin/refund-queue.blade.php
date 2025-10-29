@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Refund Queue</h1>
            <p class="header-description">Process pending deposit refunds to users' bank accounts</p>
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
            <div class="stat-icon orange">⏳</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending Refunds</div>
                <div class="stat-amount">RM {{ number_format($stats['pending_amount'], 2) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon blue">⚙️</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['processing'] }}</div>
                <div class="stat-label">Processing</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-content">
                <div class="stat-value">{{ $stats['completed'] }}</div>
                <div class="stat-label">Completed</div>
                <div class="stat-amount">RM {{ number_format($stats['total_refunded'], 2) }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">✗</div>
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
    <div class="table-card">
        <div class="table-header">
            <h3 class="table-title">Refund Requests</h3>
            <span class="table-count">Showing {{ $refunds->count() }} of {{ $refunds->total() }} refunds</span>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Ref ID</th>
                        <th>User</th>
                        <th>Bank Details</th>
                        <th>Amount</th>
                        <th>Item</th>
                        <th>Created</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($refunds as $refund)
                        <tr>
                            <td><span class="id-badge">#RQ{{ str_pad($refund->RefundQueueID, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td>
                                <div class="user-cell">
                                    @if($refund->user->ProfileImage)
                                        <img src="{{ asset('storage/' . $refund->user->ProfileImage) }}"
                                             alt="{{ $refund->user->UserName }}"
                                             class="user-avatar-img">
                                    @else
                                        <div class="user-avatar blue">
                                            {{ strtoupper(substr($refund->user->UserName, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div class="user-info">
                                        <div class="user-name">{{ $refund->user->UserName }}</div>
                                        <div class="user-email">{{ $refund->user->Email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="bank-cell">
                                    <div class="bank-name">{{ $refund->BankName }}</div>
                                    <div class="bank-account">{{ $refund->BankAccountNumber }}</div>
                                    <div class="bank-holder">{{ $refund->BankAccountHolderName }}</div>
                                </div>
                            </td>
                            <td>
                                <span class="amount-badge large">
                                    RM {{ number_format($refund->RefundAmount, 2) }}
                                </span>
                            </td>
                            <td>
                                <div class="item-cell">
                                    <span class="item-name">{{ $refund->deposit->booking->item->ItemName ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td>{{ $refund->created_at->format('M d, Y') }}</td>
                            <td>
                                <span class="status-badge status-{{ strtolower($refund->Status) }}">
                                    {{ ucfirst($refund->Status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn-action" onclick="showRefundActions({{ $refund->RefundQueueID }}, '{{ $refund->Status }}')">
                                    <i class="fas fa-cog"></i> Actions
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 60px; color: #6b7280;">
                                <p style="font-size: 18px; font-weight: 600;">No refund requests found</p>
                                <p style="margin-top: 10px;">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($refunds->hasPages())
        <div class="pagination-container">
            {{ $refunds->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Complete Refund Modal -->
    <div id="completeModal" class="modal">
        <div class="modal-overlay" onclick="closeModal('completeModal')"></div>
        <div class="modal-container" style="max-width: 500px;">
            <div class="modal-header">
                <h2 class="modal-title">Complete Refund</h2>
                <button class="modal-close" onclick="closeModal('completeModal')">
                    <i class="fas fa-times"></i>
                </button>
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
    <div id="failedModal" class="modal">
        <div class="modal-overlay" onclick="closeModal('failedModal')"></div>
        <div class="modal-container" style="max-width: 500px;">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h2 class="modal-title">Mark as Failed</h2>
                <button class="modal-close" onclick="closeModal('failedModal')">
                    <i class="fas fa-times"></i>
                </button>
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
            margin-bottom: 32px;
            padding: 0 20px;
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
    </style>

    <script>
        function showRefundActions(refundId, status) {
            const actions = [
                { label: 'View Details', action: () => alert('Details for #RQ' + refundId.toString().padStart(4, '0')) },
            ];

            if (status === 'pending') {
                actions.push({
                    label: 'Mark as Processing',
                    action: () => {
                        if (confirm('Mark this refund as processing?')) {
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/refund-queue/${refundId}/processing`;
                            const csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = '{{ csrf_token() }}';
                            form.appendChild(csrf);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                });
            }

            if (status === 'pending' || status === 'processing') {
                actions.push({
                    label: 'Complete Refund',
                    action: () => {
                        document.getElementById('completeForm').action = `/admin/refund-queue/${refundId}/complete`;
                        document.getElementById('completeModal').classList.add('show');
                    }
                });
                actions.push({
                    label: 'Mark as Failed',
                    action: () => {
                        document.getElementById('failedForm').action = `/admin/refund-queue/${refundId}/failed`;
                        document.getElementById('failedModal').classList.add('show');
                    }
                });
            }

            // Create action menu
            const menu = actions.map(a => `<button onclick="(${a.action})()" class="action-menu-item">${a.label}</button>`).join('');

            // Show simple confirm for now
            const choice = prompt('Choose action:\n1. Mark as Processing\n2. Complete Refund\n3. Mark as Failed\n\nEnter number:');

            if (choice === '1' && status === 'pending') {
                if (confirm('Mark this refund as processing?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/refund-queue/${refundId}/processing`;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    form.submit();
                }
            } else if (choice === '2') {
                document.getElementById('completeForm').action = `/admin/refund-queue/${refundId}/complete`;
                document.getElementById('completeModal').classList.add('show');
            } else if (choice === '3') {
                document.getElementById('failedForm').action = `/admin/refund-queue/${refundId}/failed`;
                document.getElementById('failedModal').classList.add('show');
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('completeModal');
                closeModal('failedModal');
            }
        });
    </script>
@endsection
