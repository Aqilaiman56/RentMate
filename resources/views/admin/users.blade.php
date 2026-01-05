@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-with-menu">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title">Users Management</h1>
        </div>
        @include('admin.partials.header-actions', ['notificationCount' => $notificationCount ?? 0])
    </div>

    <p class="page-description">Manage all registered users, account actions, view profiles</p>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div class="stat-content">
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-content">
            <div class="stat-value">{{ $activeUsers }}</div>
            <div class="stat-label">Active This Month</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-user"></i></div>
        <div class="stat-content">
            <div class="stat-value">{{ $regularUserCount }}</div>
            <div class="stat-label">Regular Users</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-user-shield"></i></div>
        <div class="stat-content">
            <div class="stat-value">{{ $adminCount }}</div>
            <div class="stat-label">Administrators</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-ban"></i></div>
        <div class="stat-content">
            <div class="stat-value">{{ $suspendedCount }}</div>
            <div class="stat-label">Suspended</div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<form action="{{ route('admin.users') }}" method="GET" class="table-controls" id="filterForm">
    <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input type="text"
               name="search"
               id="searchInput"
               placeholder="Search users by name or email..."
               class="search-input"
               value="{{ request('search') }}">
        @if(request('search'))
            <button type="button" class="clear-search-btn" onclick="clearSearch()" title="Clear search">
                <i class="fas fa-times"></i>
            </button>
        @endif
    </div>
    <div class="filter-buttons">
        <select class="filter-select" name="filter" onchange="this.form.submit()">
            <option value="all" {{ request('filter') == 'all' || !request('filter') ? 'selected' : '' }}>All Users</option>
            <option value="admin" {{ request('filter') == 'admin' ? 'selected' : '' }}>Admins Only</option>
            <option value="regular" {{ request('filter') == 'regular' ? 'selected' : '' }}>Regular Users</option>
            <option value="suspended" {{ request('filter') == 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
        <select class="filter-select" name="sort" onchange="this.form.submit()">
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
            <option value="name-az" {{ request('sort') == 'name-az' ? 'selected' : '' }}>Name (A-Z)</option>
            <option value="name-za" {{ request('sort') == 'name-za' ? 'selected' : '' }}>Name (Z-A)</option>
        </select>
        <button type="submit" class="btn btn-primary btn-filter">
            <i class="fas fa-filter"></i> <span class="btn-text">Apply Filters</span>
        </button>
        <button type="button" class="btn btn-secondary btn-action-small" onclick="exportUsers()">
            <i class="fas fa-download"></i> <span class="btn-text">Export</span>
        </button>
    </div>
</form>

<!-- Users Cards -->
<div class="table-card">
    <div class="table-header">
        <h3 class="table-title">User Accounts</h3>
        <span class="table-count">Showing {{ $users->count() }} of {{ $users->total() }} users</span>
    </div>
    <div class="users-container">
        @forelse($users as $user)
            <div class="user-card" id="user-{{ $user->UserID }}">
                <div class="user-card-header" onclick="toggleUser({{ $user->UserID }})">
                    <div class="user-section">
                        @if($user->ProfileImage)
                            <img src="{{ asset('storage/' . $user->ProfileImage) }}"
                                 alt="{{ $user->UserName }}"
                                 class="user-avatar-img">
                        @else
                            <div class="user-avatar {{ ['blue', 'pink', 'green', 'orange', 'purple', 'teal'][($user->UserID) % 6] }}">
                                {{ strtoupper(substr($user->UserName, 0, 2)) }}
                            </div>
                        @endif
                        <div class="user-info-header">
                            <div class="user-name">{{ $user->UserName }}</div>
                            <div class="user-email">{{ $user->Email }}</div>
                        </div>
                    </div>
                    <div class="user-header-right">
                        <div class="status-badges">
                            <span class="status-badge {{ $user->IsAdmin ? 'admin-badge' : 'user-badge' }}">
                                @if($user->IsAdmin)
                                    <i class="fas fa-user-shield"></i> Admin
                                @else
                                    <i class="fas fa-user"></i> User
                                @endif
                            </span>
                            @if($user->IsSuspended)
                                <span class="status-badge suspended-badge">
                                    <i class="fas fa-ban"></i> Suspended
                                </span>
                            @endif
                        </div>
                        <i class="fas fa-chevron-down user-toggle-icon"></i>
                    </div>
                </div>
                <div class="user-card-body">
                    <div class="user-details-grid">
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-hashtag"></i> User ID</div>
                            <div class="detail-value">{{ $user->UserID }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-phone"></i> Phone</div>
                            <div class="detail-value">{{ $user->PhoneNumber ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-map-marker-alt"></i> Location</div>
                            <div class="detail-value">{{ $user->Location ?? 'N/A' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-calendar"></i> Joined Date</div>
                            <div class="detail-value">{{ $user->CreatedAt ? \Carbon\Carbon::parse($user->CreatedAt)->format('M d, Y') : 'N/A' }}</div>
                        </div>
                        @if($user->IsSuspended && $user->SuspendedUntil)
                        <div class="detail-item">
                            <div class="detail-label"><i class="fas fa-clock"></i> Suspended Until</div>
                            <div class="detail-value">{{ \Carbon\Carbon::parse($user->SuspendedUntil)->format('M d, Y') }}</div>
                        </div>
                        @endif
                        @if($user->IsSuspended && $user->SuspensionReason)
                        <div class="detail-item full-width">
                            <div class="detail-label"><i class="fas fa-info-circle"></i> Suspension Reason</div>
                            <div class="detail-value">{{ $user->SuspensionReason }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('admin.users.show', $user->UserID) }}" class="action-btn btn-view-action">
                            <i class="fas fa-eye"></i> View Profile
                        </a>

                        @if($user->IsAdmin)
                            @if($user->UserID !== auth()->id())
                                <button onclick="demoteUser({{ $user->UserID }}, '{{ $user->UserName }}'); event.stopPropagation();" class="action-btn btn-warning">
                                    <i class="fas fa-user-minus"></i> Demote from Admin
                                </button>
                            @else
                                <button disabled class="action-btn btn-disabled">
                                    <i class="fas fa-user-shield"></i> You (Admin)
                                </button>
                            @endif
                        @else
                            <button onclick="promoteUser({{ $user->UserID }}, '{{ $user->UserName }}'); event.stopPropagation();" class="action-btn btn-success">
                                <i class="fas fa-user-plus"></i> Promote to Admin
                            </button>
                        @endif

                        @if($user->IsSuspended)
                            <button onclick="unsuspendUser({{ $user->UserID }}, '{{ $user->UserName }}'); event.stopPropagation();" class="action-btn btn-success">
                                <i class="fas fa-check-circle"></i> Unsuspend
                            </button>
                        @else
                            @if($user->UserID !== auth()->id())
                                <button onclick="suspendUser({{ $user->UserID }}, '{{ $user->UserName }}'); event.stopPropagation();" class="action-btn btn-warning">
                                    <i class="fas fa-ban"></i> Suspend
                                </button>
                            @endif
                        @endif

                        @if($user->UserID !== auth()->id() && !$user->IsAdmin)
                            <button onclick="deleteUser({{ $user->UserID }}, '{{ $user->UserName }}'); event.stopPropagation();" class="action-btn btn-danger">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>No users found</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="pagination-container">
            {{ $users->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Modals -->
<!-- Suspend User Modal -->
<div id="suspendModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-ban"></i> Suspend User</h3>
            <button class="modal-close" onclick="closeModal('suspendModal')">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-description">Suspending <strong id="suspendUserName"></strong> will prevent them from accessing the system.</p>

            <div class="form-group">
                <label for="suspensionDuration">Suspension Duration</label>
                <select id="suspensionDuration" class="form-control">
                    <option value="7">7 Days</option>
                    <option value="30" selected>30 Days</option>
                    <option value="90">90 Days</option>
                    <option value="permanent">Permanent</option>
                </select>
            </div>

            <div class="form-group">
                <label for="suspensionReason">Reason for Suspension *</label>
                <textarea id="suspensionReason" class="form-control" rows="4" placeholder="Enter the reason for suspending this user..." required></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('suspendModal')">Cancel</button>
            <button class="btn btn-danger" onclick="confirmSuspend()"><i class="fas fa-ban"></i> Suspend User</button>
        </div>
    </div>
</div>

<!-- Activity Log Modal -->
<div id="activityModal" class="custom-modal">
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3><i class="fas fa-history"></i> Activity Log - <span id="activityUserName"></span></h3>
            <button class="modal-close" onclick="closeModal('activityModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div id="activityContent" class="activity-list">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i> Loading activity log...
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('activityModal')">Close</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header modal-danger">
            <h3><i class="fas fa-trash-alt"></i> Delete User</h3>
            <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Warning:</strong> This action cannot be undone!
            </div>

            <p class="modal-description">You are about to delete <strong id="deleteUserName"></strong></p>

            <p>All user data will be permanently deleted, including:</p>
            <ul class="deletion-list">
                <li><i class="fas fa-user"></i> Profile information</li>
                <li><i class="fas fa-box"></i> Listings</li>
                <li><i class="fas fa-calendar"></i> Bookings</li>
                <li><i class="fas fa-comment"></i> Messages</li>
                <li><i class="fas fa-star"></i> Reviews</li>
            </ul>

            <div class="form-group">
                <label for="deleteConfirmation">Type <strong>DELETE</strong> to confirm:</label>
                <input type="text" id="deleteConfirmation" class="form-control" placeholder="Type DELETE">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
            <button class="btn btn-danger" onclick="confirmDelete()"><i class="fas fa-trash-alt"></i> Delete User</button>
        </div>
    </div>
</div>

    <style>
        /* Header */
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
            align-items: flex-start;
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

        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.green { background: #d1fae5; color: #059669; }
        .stat-icon.orange { background: #fed7aa; color: #ea580c; }
        .stat-icon.purple { background: #e9d5ff; color: #9333ea; }
        .stat-icon.red { background: #fee2e2; color: #dc2626; }

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
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #9ca3af;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 12px 42px 12px 42px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .clear-search-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: #f3f4f6;
            border: none;
            border-radius: 6px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: #6b7280;
        }

        .clear-search-btn:hover {
            background: #e5e7eb;
            color: #374151;
        }

        .filter-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }

        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .filter-select:hover {
            border-color: #d1d5db;
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
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
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

        /* Users Container */
        .users-container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* User Card */
        .user-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .user-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .user-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            cursor: pointer;
            background: #ffffff;
            transition: background 0.2s;
        }

        .user-card-header:hover {
            background: #f9fafb;
        }

        .user-section {
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 1;
            min-width: 0;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            color: white;
            flex-shrink: 0;
        }

        .user-avatar.blue { background: #3b82f6; }
        .user-avatar.pink { background: #ec4899; }
        .user-avatar.green { background: #10b981; }
        .user-avatar.orange { background: #f97316; }
        .user-avatar.purple { background: #a855f7; }
        .user-avatar.teal { background: #14b8a6; }

        .user-avatar-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .user-info-header {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 15px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-email {
            font-size: 13px;
            color: #6b7280;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .status-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .user-badge {
            background: #dbeafe;
            color: #1e40af;
        }

        .admin-badge {
            background: #fce7f3;
            color: #9f1239;
        }

        .suspended-badge {
            background: #fee2e2;
            color: #dc2626;
        }

        .user-toggle-icon {
            color: #9ca3af;
            transition: transform 0.3s;
            font-size: 14px;
        }

        .user-card.expanded .user-toggle-icon {
            transform: rotate(180deg);
        }

        /* User Card Body */
        .user-card-body {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .user-card.expanded .user-card-body {
            max-height: 1000px;
        }

        .user-details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            padding: 20px;
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
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-value {
            font-size: 14px;
            color: #1f2937;
            font-weight: 500;
        }

        /* Card Actions */
        .card-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 20px;
            padding-top: 0;
        }

        .action-btn {
            padding: 10px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-view-action {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-view-action:hover {
            background: #bfdbfe;
            transform: translateY(-1px);
        }

        .btn-success {
            background: #d1fae5;
            color: #065f46;
        }

        .btn-success:hover {
            background: #a7f3d0;
            transform: translateY(-1px);
        }

        .btn-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-warning:hover {
            background: #fde68a;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-danger:hover {
            background: #fecaca;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-primary:hover {
            background: #bfdbfe;
            transform: translateY(-1px);
        }

        .btn-disabled {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 64px;
            opacity: 0.3;
            margin-bottom: 16px;
        }

        .empty-state p {
            font-size: 16px;
            margin: 0;
        }

        .pagination-container {
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
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
            font-size: 14px;
            text-decoration: none;
        }

        .btn-view {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-view:hover {
            background: #bfdbfe;
            transform: translateY(-2px);
        }

        .btn-more {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-more:hover {
            background: #e5e7eb;
        }

        /* Dropdown */
        .dropdown-wrapper {
            position: relative;
        }

        .action-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 8px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1000;
            overflow: hidden;
        }

        .action-dropdown.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #374151;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 14px;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
            font-size: 14px;
        }

        .dropdown-item.danger {
            color: #dc2626;
        }

        .dropdown-item.danger:hover {
            background: #fee2e2;
        }

        .dropdown-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 4px 0;
        }

        /* Buttons */
        .btn {
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action-small {
            padding: 10px 16px;
            font-size: 13px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #374151;
            border: 2px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        /* Responsive Breakpoints */
        @media (max-width: 968px) {
            .header-with-menu .mobile-menu-toggle {
                display: flex;
            }

            .header-title {
                font-size: 28px;
            }

            .page-description {
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
                flex-direction: column;
                align-items: flex-start;
                padding: 0 16px;
                margin-bottom: 24px;
            }

            .header-with-menu {
                padding: 0 16px;
                margin-bottom: 20px;
            }

            .header-title {
                font-size: 24px;
            }

            .page-description {
                font-size: 14px;
            }

            .header-actions {
                width: 100%;
                justify-content: stretch;
                padding-top: 0;
            }

            .header-actions .btn {
                flex: 1;
                padding: 9px 16px;
                font-size: 13px;
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
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            .filter-select {
                grid-column: span 2;
            }

            .btn-filter {
                grid-column: span 2;
            }

            .btn-action-small {
                padding: 9px 14px;
                font-size: 12px;
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
        }

        @media (max-width: 480px) {
            .header-with-menu {
                padding: 0 12px;
                gap: 12px;
            }

            .header-with-menu .mobile-menu-toggle {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .header {
                padding: 0 12px;
                margin-bottom: 20px;
                gap: 16px;
            }

            .header-title {
                font-size: 22px;
            }

            .page-description {
                font-size: 13px;
                padding: 0 12px;
            }

            .header-actions {
                gap: 10px;
            }

            .header-actions .btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            .btn-text {
                display: none;
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

            .btn-action-small {
                padding: 8px 12px;
                font-size: 11px;
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

            .users-container {
                padding: 12px;
                gap: 12px;
            }

            .user-card {
                border-radius: 10px;
            }

            .user-card-header {
                padding: 12px 14px;
            }

            .user-section {
                gap: 10px;
            }

            .user-avatar,
            .user-avatar-img {
                width: 40px;
                height: 40px;
                font-size: 14px;
            }

            .user-name {
                font-size: 14px;
            }

            .user-email {
                font-size: 11px;
            }

            .status-badges {
                gap: 6px;
            }

            .status-badge {
                padding: 4px 8px;
                font-size: 10px;
                gap: 4px;
            }

            .user-toggle-icon {
                font-size: 12px;
            }

            .user-details-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 12px 14px;
            }

            .detail-label {
                font-size: 10px;
                gap: 4px;
            }

            .detail-value {
                font-size: 12px;
            }

            .card-actions {
                padding: 12px 14px;
                padding-top: 0;
                gap: 8px;
            }

            .action-btn {
                padding: 8px 12px;
                font-size: 11px;
                gap: 6px;
                width: 100%;
                justify-content: center;
            }

            .action-btn i {
                font-size: 11px;
            }

            .btn-icon {
                width: 32px;
                height: 32px;
                font-size: 13px;
            }

            .modal-content {
                width: 95%;
                max-width: 95%;
                border-radius: 12px;
            }

            .modal-header {
                padding: 16px 18px;
            }

            .modal-header h3 {
                font-size: 16px;
                gap: 8px;
            }

            .modal-close {
                width: 28px;
                height: 28px;
                font-size: 24px;
            }

            .modal-body {
                padding: 18px;
            }

            .modal-footer {
                padding: 16px 18px;
                flex-direction: column;
            }

            .modal-footer .btn {
                width: 100%;
                padding: 10px 16px;
            }

            .form-control {
                padding: 10px 14px;
                font-size: 13px;
            }

            .pagination-container {
                padding: 16px 12px;
            }
        }

        @media (max-width: 360px) {
            .header-with-menu {
                padding: 0 10px;
                gap: 10px;
            }

            .header-with-menu .mobile-menu-toggle {
                width: 36px;
                height: 36px;
                font-size: 15px;
            }

            .header {
                padding: 0 10px;
                margin-bottom: 16px;
                gap: 12px;
            }

            .header-title {
                font-size: 20px;
            }

            .page-description {
                font-size: 12px;
                padding: 0 10px;
            }

            .header-actions .btn {
                padding: 7px 10px;
                font-size: 11px;
                gap: 6px;
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

            .btn-action-small {
                padding: 7px 10px;
                font-size: 10px;
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

            .users-container {
                padding: 10px;
                gap: 10px;
            }

            .user-card {
                border-radius: 8px;
            }

            .user-card-header {
                padding: 10px 12px;
            }

            .user-section {
                gap: 8px;
            }

            .user-avatar,
            .user-avatar-img {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }

            .user-name {
                font-size: 13px;
            }

            .user-email {
                font-size: 10px;
            }

            .user-header-right {
                gap: 8px;
            }

            .status-badges {
                gap: 4px;
                flex-direction: column;
                align-items: flex-end;
            }

            .status-badge {
                padding: 3px 7px;
                font-size: 9px;
                gap: 3px;
            }

            .user-toggle-icon {
                font-size: 11px;
            }

            .user-details-grid {
                grid-template-columns: 1fr;
                gap: 10px;
                padding: 10px 12px;
            }

            .detail-label {
                font-size: 9px;
                gap: 3px;
            }

            .detail-label i {
                font-size: 10px;
            }

            .detail-value {
                font-size: 11px;
            }

            .card-actions {
                padding: 10px 12px;
                padding-top: 0;
                gap: 6px;
            }

            .action-btn {
                padding: 7px 10px;
                font-size: 10px;
                gap: 5px;
                width: 100%;
                justify-content: center;
            }

            .action-btn i {
                font-size: 10px;
            }

            .btn-icon {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }

            .action-buttons {
                gap: 6px;
            }

            .modal-content {
                width: 100%;
                max-width: 100%;
                border-radius: 0;
                max-height: 100vh;
            }

            .modal-header {
                padding: 14px 16px;
            }

            .modal-header h3 {
                font-size: 15px;
                gap: 6px;
            }

            .modal-close {
                width: 26px;
                height: 26px;
                font-size: 20px;
            }

            .modal-body {
                padding: 16px;
                max-height: calc(100vh - 140px);
            }

            .modal-footer {
                padding: 14px 16px;
            }

            .modal-footer .btn {
                padding: 9px 14px;
                font-size: 12px;
            }

            .form-control {
                padding: 9px 12px;
                font-size: 12px;
            }

            .form-group label {
                font-size: 13px;
            }

            .modal-description {
                font-size: 13px;
            }

            .password-box {
                padding: 12px;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .password-box code {
                font-size: 16px;
                text-align: center;
            }

            .copy-btn {
                width: 100%;
                justify-content: center;
            }

            .deletion-list li {
                padding: 8px 10px;
                font-size: 12px;
            }

            .pagination-container {
                padding: 14px 10px;
            }
        }

        /* Modal Styles */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease;
        }

        .custom-modal.active {
            display: flex;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            animation: slideUp 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .modal-content.modal-large {
            max-width: 700px;
        }

        .modal-header {
            padding: 24px 28px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .modal-header.modal-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .modal-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 32px;
            color: white;
            cursor: pointer;
            line-height: 1;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            padding: 28px;
            overflow-y: auto;
            flex: 1;
        }

        .modal-description {
            margin-bottom: 20px;
            color: #4b5563;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .modal-footer {
            padding: 20px 28px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: #f9fafb;
        }

        .alert-warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 14px 16px;
            border-radius: 8px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #92400e;
        }

        .alert-danger {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #991b1b;
        }

        .password-display {
            margin: 20px 0;
        }

        .password-display label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }

        .password-box {
            display: flex;
            gap: 12px;
            align-items: center;
            background: #f3f4f6;
            padding: 16px;
            border-radius: 8px;
            border: 2px solid #d1d5db;
        }

        .password-box code {
            flex: 1;
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            letter-spacing: 1px;
        }

        .copy-btn {
            padding: 8px 16px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .copy-btn:hover {
            background: #2563eb;
        }

        .deletion-list {
            list-style: none;
            padding: 0;
            margin: 16px 0;
        }

        .deletion-list li {
            padding: 10px 12px;
            background: #f9fafb;
            border-radius: 6px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6b7280;
        }

        .deletion-list li i {
            color: #ef4444;
        }

        .activity-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .activity-item {
            padding: 16px;
            border-left: 3px solid #e5e7eb;
            margin-bottom: 12px;
            background: #f9fafb;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .activity-item:hover {
            background: #f3f4f6;
            border-left-color: #3b82f6;
        }

        .activity-item .activity-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            margin-right: 12px;
        }

        .activity-item .activity-description {
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }

        .activity-item .activity-date {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .loading-spinner {
            text-align: center;
            padding: 40px;
            color: #9ca3af;
            font-size: 16px;
        }

        .loading-spinner i {
            font-size: 32px;
            margin-bottom: 12px;
            display: block;
        }

        .empty-activity {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .empty-activity i {
            font-size: 48px;
            opacity: 0.3;
            display: block;
            margin-bottom: 16px;
        }
    </style>

    <script>
        // Toggle user card expansion
        function toggleUser(userId) {
            const card = document.getElementById('user-' + userId);
            card.classList.toggle('expanded');
        }

        // Promote user to admin
        function promoteUser(id, name) {
            if (!confirm(`Promote "${name}" to admin?\n\nThis will grant them full administrative access.`)) {
                return;
            }

            fetch(`/admin/users/${id}/promote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast(data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert('Error: ' + (data.message || 'Failed to promote user'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while promoting the user');
            });
        }

        // Demote admin to regular user
        function demoteUser(id, name) {
            if (!confirm(`Demote "${name}" from admin?\n\nThis will remove their administrative access.`)) {
                return;
            }

            fetch(`/admin/users/${id}/demote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast(data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert('Error: ' + (data.message || 'Failed to demote user'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while demoting the user');
            });
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterForm').submit();
        }

        function exportUsers() {
            window.location.href = '{{ route('admin.users.export') }}';
        }

        function addNewUser() {
            alert('Add new user form will open here');
            console.log('Add new user functionality');
            // TODO: Implement add user modal or redirect to add user page
        }

        // Modal management
        let currentUserId = null;
        let currentUserName = null;

        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            // Reset forms
            if (modalId === 'suspendModal') {
                document.getElementById('suspensionReason').value = '';
                document.getElementById('suspensionDuration').value = '30';
            }
            if (modalId === 'deleteModal') {
                document.getElementById('deleteConfirmation').value = '';
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('custom-modal')) {
                e.target.classList.remove('active');
            }
        });

        function suspendUser(id, name) {
            currentUserId = id;
            currentUserName = name;
            document.getElementById('suspendUserName').textContent = name;
            openModal('suspendModal');
        }

        function confirmSuspend() {
            const duration = document.getElementById('suspensionDuration').value;
            const reason = document.getElementById('suspensionReason').value.trim();

            if (!reason) {
                alert('Please enter a reason for suspension');
                return;
            }

            // Send AJAX request
            fetch(`/admin/users/${currentUserId}/suspend`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ duration, reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal('suspendModal');
                    showSuccessToast(`User "${currentUserName}" has been suspended successfully`);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert('Error: ' + (data.message || 'Failed to suspend user'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while suspending the user');
            });
        }

        function unsuspendUser(id, name) {
            if (!confirm(`Unsuspend user "${name}"?\n\nThis will restore their access to the system.`)) {
                return;
            }

            // Send AJAX request
            fetch(`/admin/users/${id}/unsuspend`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessToast(`User "${name}" has been unsuspended successfully`);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert('Error: ' + (data.message || 'Failed to unsuspend user'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while unsuspending the user');
            });
        }

        function viewActivityLog(id, name) {
            currentUserId = id;
            currentUserName = name;
            document.getElementById('activityUserName').textContent = name;
            document.getElementById('activityContent').innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading activity log...</div>';
            openModal('activityModal');

            // Fetch activity log via AJAX
            fetch(`/admin/users/${id}/activity-log`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const activities = data.activities;

                    if (activities.length === 0) {
                        document.getElementById('activityContent').innerHTML = `
                            <div class="empty-activity">
                                <i class="fas fa-history"></i>
                                No activities recorded yet.
                            </div>
                        `;
                    } else {
                        let html = '<div class="activity-list">';
                        activities.forEach(activity => {
                            const date = new Date(activity.date).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'short',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            html += `
                                <div class="activity-item">
                                    <div class="activity-icon"><i class="fas ${activity.icon}"></i></div>
                                    <div>
                                        <div class="activity-description">${activity.description}</div>
                                        <div class="activity-date">${date}</div>
                                    </div>
                                </div>
                            `;
                        });
                        html += '</div>';
                        document.getElementById('activityContent').innerHTML = html;
                    }
                } else {
                    document.getElementById('activityContent').innerHTML = '<div class="empty-activity"><i class="fas fa-exclamation-triangle"></i>Error loading activity log</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('activityContent').innerHTML = '<div class="empty-activity"><i class="fas fa-exclamation-triangle"></i>An error occurred while loading the activity log</div>';
            });
        }

        function deleteUser(id, name) {
            currentUserId = id;
            currentUserName = name;
            document.getElementById('deleteUserName').textContent = name;
            document.getElementById('deleteConfirmation').value = '';
            openModal('deleteModal');
        }

        function confirmDelete() {
            const confirmation = document.getElementById('deleteConfirmation').value;

            if (confirmation !== 'DELETE') {
                alert('Please type DELETE to confirm deletion');
                return;
            }

            // Send AJAX request
            fetch(`/admin/users/${currentUserId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message) {
                    closeModal('deleteModal');
                    showSuccessToast(`User "${currentUserName}" has been deleted successfully`);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete user'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                closeModal('deleteModal');
                showSuccessToast('User has been deleted');
                setTimeout(() => location.reload(), 1500);
            });
        }

        function showSuccessToast(message) {
            // Create toast element
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                color: white;
                padding: 16px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                z-index: 10001;
                animation: slideIn 0.3s ease;
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 14px;
                font-weight: 500;
            `;
            toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            document.body.appendChild(toast);

            // Remove after 3 seconds
            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Real-time search
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        document.getElementById('filterForm').submit();
                    }
                }, 500);
            });
        }
    </script>
@endsection
