@extends('layouts.admin')

@section('title', 'User Profile - Admin')

@section('content')
<div class="user-profile-container">
    <!-- Back Button -->
    <div class="back-nav">
        <a href="{{ route('admin.users') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <!-- User Profile Header -->
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar-section">
                @if($user->ProfileImage)
                    <img src="{{ Storage::url($user->ProfileImage) }}" alt="{{ $user->UserName }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">
                        {{ strtoupper(substr($user->UserName, 0, 2)) }}
                    </div>
                @endif
            </div>

            <div class="profile-info">
                <h1 class="profile-name">{{ $user->UserName }}</h1>

                <div class="profile-badges">
                    @if($user->IsAdmin)
                        <span class="badge badge-admin">
                            <i class="fas fa-user-shield"></i> Admin
                        </span>
                    @else
                        <span class="badge badge-user">
                            <i class="fas fa-user"></i> User
                        </span>
                    @endif

                    @if($user->IsSuspended)
                        <span class="badge badge-suspended">
                            <i class="fas fa-ban"></i> Suspended
                        </span>
                    @endif
                </div>

                <div class="profile-details-grid">
                    <div class="detail-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $user->Email }}</span>
                    </div>
                    @if($user->PhoneNumber)
                        <div class="detail-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $user->PhoneNumber }}</span>
                        </div>
                    @endif
                    @if($user->Location)
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $user->Location }}</span>
                        </div>
                    @endif
                    @if($user->UserType)
                        <div class="detail-item">
                            <i class="fas fa-user-tag"></i>
                            <span>{{ $user->UserType }}</span>
                        </div>
                    @endif
                    <div class="detail-item">
                        <i class="fas fa-calendar"></i>
                        <span>Joined {{ $user->CreatedAt ? \Carbon\Carbon::parse($user->CreatedAt)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="profile-actions">
                @if($user->IsSuspended)
                    <button class="btn btn-success" onclick="unsuspendUser({{ $user->UserID }}, '{{ $user->UserName }}')">
                        <i class="fas fa-check-circle"></i> Unsuspend User
                    </button>
                @else
                    <button class="btn btn-warning" onclick="suspendUser({{ $user->UserID }}, '{{ $user->UserName }}')">
                        <i class="fas fa-ban"></i> Suspend User
                    </button>
                @endif
                <button class="btn btn-primary" onclick="resetPassword({{ $user->UserID }}, '{{ $user->UserName }}')">
                    <i class="fas fa-key"></i> Reset Password
                </button>
                <a href="{{ route('user.public.profile', $user->UserID) }}" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-eye"></i> View Public Profile
                </a>
            </div>
        </div>
    </div>

    <!-- Suspension Info (if suspended) -->
    @if($user->IsSuspended)
        <div class="alert alert-warning">
            <div class="alert-header">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>User is Currently Suspended</strong>
            </div>
            <div class="alert-body">
                @if($user->SuspensionReason)
                    <p><strong>Reason:</strong> {{ $user->SuspensionReason }}</p>
                @endif
                @if($user->SuspendedUntil)
                    <p><strong>Suspended Until:</strong> {{ \Carbon\Carbon::parse($user->SuspendedUntil)->format('M d, Y h:i A') }}</p>
                @else
                    <p><strong>Duration:</strong> Permanent suspension</p>
                @endif
                @if($user->suspendedBy)
                    <p><strong>Suspended By:</strong> {{ $user->suspendedBy->UserName }}</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Statistics -->
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-icon blue">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $itemsCount }}</div>
                <div class="stat-label">Total Listings</div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon green">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $bookingsCount }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
        </div>

        <div class="stat-box">
            <div class="stat-icon orange">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $reviewsCount }}</div>
                <div class="stat-label">Total Reviews</div>
            </div>
        </div>
    </div>

    <!-- Recent Items -->
    <div class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-box"></i> Recent Listings</h2>
        </div>

        @if($user->items->count() > 0)
            <div class="items-table">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Listed Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->items->take(10) as $item)
                            <tr>
                                <td>
                                    <div class="item-info">
                                        @if($item->ImagePath)
                                            <img src="{{ Storage::url($item->ImagePath) }}" alt="{{ $item->ItemName }}" class="item-thumb">
                                        @else
                                            <div class="item-thumb-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                        <span>{{ $item->ItemName }}</span>
                                    </div>
                                </td>
                                <td>{{ $item->Category }}</td>
                                <td>RM {{ number_format($item->RentalPrice, 2) }}/day</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($item->AvailabilityStatus) }}">
                                        {{ $item->AvailabilityStatus }}
                                    </span>
                                </td>
                                <td>{{ $item->CreatedAt ? \Carbon\Carbon::parse($item->CreatedAt)->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('item.details', $item->ItemID) }}" class="btn-sm btn-view" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>No listings yet</p>
            </div>
        @endif
    </div>

    <!-- Recent Bookings -->
    <div class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-calendar-check"></i> Recent Bookings</h2>
        </div>

        @if($user->bookings->count() > 0)
            <div class="items-table">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Booked Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->bookings->take(10) as $booking)
                            <tr>
                                <td>{{ $booking->item ? $booking->item->ItemName : 'N/A' }}</td>
                                <td>{{ $booking->StartDate ? \Carbon\Carbon::parse($booking->StartDate)->format('M d, Y') : 'N/A' }}</td>
                                <td>{{ $booking->EndDate ? \Carbon\Carbon::parse($booking->EndDate)->format('M d, Y') : 'N/A' }}</td>
                                <td>RM {{ number_format($booking->TotalAmount, 2) }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($booking->Status) }}">
                                        {{ $booking->Status }}
                                    </span>
                                </td>
                                <td>{{ $booking->CreatedAt ? \Carbon\Carbon::parse($booking->CreatedAt)->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>No bookings yet</p>
            </div>
        @endif
    </div>
</div>

<style>
    .user-profile-container {
        padding: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .back-nav {
        margin-bottom: 20px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        color: #374151;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .profile-header {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 24px;
        align-items: start;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #f3f4f6;
    }

    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        font-weight: 700;
        border: 4px solid #f3f4f6;
    }

    .profile-name {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
    }

    .profile-badges {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .badge-admin {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .badge-user {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .badge-suspended {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .profile-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 12px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #6b7280;
        font-size: 14px;
    }

    .detail-item i {
        color: #3b82f6;
        width: 20px;
    }

    .profile-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #f9fafb;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .alert {
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 24px;
    }

    .alert-warning {
        background: #fef3c7;
        border-left: 4px solid #f59e0b;
    }

    .alert-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        color: #92400e;
        font-size: 16px;
    }

    .alert-body {
        color: #78350f;
        font-size: 14px;
    }

    .alert-body p {
        margin: 8px 0;
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-box {
        background: white;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-icon.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
    .stat-icon.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .stat-icon.orange { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .stat-label {
        font-size: 14px;
        color: #6b7280;
    }

    .content-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
    }

    .section-header {
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .section-header h2 {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-header h2 i {
        color: #3b82f6;
    }

    .items-table {
        overflow-x: auto;
    }

    .items-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .items-table th {
        text-align: left;
        padding: 12px;
        background: #f9fafb;
        color: #6b7280;
        font-weight: 600;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .items-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        font-size: 14px;
    }

    .item-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .item-thumb {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
    }

    .item-thumb-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d1d5db;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    .status-available {
        background: #d1fae5;
        color: #065f46;
    }

    .status-rented {
        background: #fef3c7;
        color: #92400e;
    }

    .status-pending {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-approved, .status-completed {
        background: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 6px;
        text-decoration: none;
    }

    .btn-view {
        background: #3b82f6;
        color: white;
    }

    .btn-view:hover {
        background: #2563eb;
    }

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

    @media (max-width: 968px) {
        .profile-header {
            grid-template-columns: 1fr;
        }

        .profile-actions {
            flex-direction: row;
            flex-wrap: wrap;
        }
    }
</style>

<script>
    function suspendUser(id, name) {
        window.location.href = '{{ route("admin.users") }}';
    }

    function unsuspendUser(id, name) {
        if (confirm(`Unsuspend user "${name}"?\n\nThis will restore their access to the system.`)) {
            fetch(`/admin/users/${id}/unsuspend`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to unsuspend user'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while unsuspending the user');
            });
        }
    }

    function resetPassword(id, name) {
        if (confirm(`Reset password for user "${name}"?\n\nA new password will be generated and displayed.`)) {
            fetch(`/admin/users/${id}/reset-password`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`Password reset successfully for "${name}"\n\nNew Password: ${data.new_password}\n\nPlease save this password and send it to the user.`);
                } else {
                    alert('Error: ' + (data.message || 'Failed to reset password'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while resetting the password');
            });
        }
    }
</script>
@endsection
