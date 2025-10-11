@extends('layouts.app')

@section('title', 'Notifications - RentMate')

@php($hideSearch = true)

@push('styles')
<style>
    .notifications-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .header-left h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 5px 0;
    }

    .header-left p {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }

    .mark-all-btn {
        background: #4461F2;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .mark-all-btn:hover {
        background: #3651E2;
    }

    .notifications-list {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .notification-item {
        display: flex;
        align-items: start;
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s;
        position: relative;
    }

    .notification-item:hover {
        background-color: #f9fafb;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item.unread {
        background-color: #eff6ff;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .notification-icon.message {
        background: #dbeafe;
    }

    .notification-icon.booking {
        background: #d1fae5;
    }

    .notification-icon.review {
        background: #fef3c7;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-title {
        font
    font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .notification-text {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .notification-time {
        font-size: 12px;
        color: #9ca3af;
    }

    .notification-actions {
        display: flex;
        gap: 10px;
        margin-left: 15px;
        flex-shrink: 0;
    }

    .notification-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-view {
        background: #e8eeff;
        color: #4461F2;
    }

    .btn-view:hover {
        background: #d0ddff;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        background: #3b82f6;
        border-radius: 50%;
        position: absolute;
        top: 25px;
        left: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
    }

    .empty-text {
        font-size: 16px;
        color: #6b7280;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .pagination-wrapper {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .notifications-container {
            padding: 20px 15px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .notification-item {
            flex-direction: column;
        }

        .notification-actions {
            margin-left: 0;
            margin-top: 10px;
            width: 100%;
        }

        .notification-btn {
            flex: 1;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="notifications-container">
    <div class="page-header">
        <div class="header-left">
            <h1>🔔 Notifications</h1>
            <p>
                @if($unreadCount > 0)
                    {{ $unreadCount }} unread notification(s)
                @else
                    All caught up!
                @endif
            </p>
        </div>

        @if($unreadCount > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf
                <button type="submit" class="mark-all-btn">Mark All as Read</button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if($notifications->count() > 0)
        <div class="notifications-list">
            @foreach($notifications as $notification)
                <div class="notification-item {{ !$notification->IsRead ? 'unread' : '' }}">
                    @if(!$notification->IsRead)
                        <span class="unread-dot"></span>
                    @endif

                    <div class="notification-icon {{ $notification->Type }}">
                        @switch($notification->Type)
                            @case('message')
                                💬
                                @break
                            @case('booking')
                                📅
                                @break
                            @case('review')
                                ⭐
                                @break
                            @default
                                🔔
                        @endswitch
                    </div>

                    <div class="notification-content">
                        <div class="notification-title">{{ $notification->Title }}</div>
                        <div class="notification-text">{{ $notification->Content }}</div>
                        <div class="notification-time">{{ $notification->CreatedAt->diffForHumans() }}</div>
                    </div>

                    <div class="notification-actions">
                        <form action="{{ route('notifications.read', $notification->NotificationID) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="notification-btn btn-view">View</button>
                        </form>

                        <form action="{{ route('notifications.destroy', $notification->NotificationID) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this notification?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="notification-btn btn-delete">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($notifications->hasPages())
            <div class="pagination-wrapper">
                {{ $notifications->links() }}
            </div>
        @endif
    @else
        <div class="notifications-list">
            <div class="empty-state">
                <div class="empty-icon">🔔</div>
                <h2 class="empty-title">No Notifications</h2>
                <p class="empty-text">You're all caught up! You'll see notifications here when something happens.</p>
            </div>
        </div>
    @endif
</div>
@endsection