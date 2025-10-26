@extends('layouts.app')

@section('title', 'Messages - RentMate')

@php($hideSearch = true)

@push('styles')
<style>
    .messages-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #6b7280;
    }

    .conversations-list {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .conversation-item {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #e5e7eb;
        cursor: pointer;
        transition: background-color 0.2s;
        text-decoration: none;
        color: inherit;
    }

    .conversation-item:hover {
        background-color: #f9fafb;
    }

    .conversation-item:last-child {
        border-bottom: none;
    }

    .conversation-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #4461F2;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 600;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .conversation-content {
        flex: 1;
        min-width: 0;
    }

    .conversation-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .conversation-name {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .conversation-time {
        font-size: 12px;
        color: #9ca3af;
    }

    .conversation-message {
        font-size: 14px;
        color: #6b7280;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .conversation-message.unread {
        font-weight: 600;
        color: #1f2937;
    }

    .unread-badge {
        background: #ef4444;
        color: white;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 12px;
        margin-left: 10px;
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

    @media (max-width: 768px) {
        .messages-container {
            padding: 20px 15px;
        }

        .conversation-avatar,
        .avatar-placeholder {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="messages-container">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-comments"></i> Messages</h1>
        <p class="page-subtitle">{{ $conversations->count() }} conversation(s)</p>
    </div>

    @if($conversations->count() > 0)
        <div class="conversations-list">
            @foreach($conversations as $conversation)
                <a href="{{ route('messages.show', $conversation['user']->UserID) }}" class="conversation-item">
                    @if($conversation['user']->ProfileImage)
                        <img src="{{ asset('storage/' . $conversation['user']->ProfileImage) }}" 
                             alt="{{ $conversation['user']->UserName }}" 
                             class="conversation-avatar">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr($conversation['user']->UserName, 0, 1)) }}
                        </div>
                    @endif

                    <div class="conversation-content">
                        <div class="conversation-header">
                            <span class="conversation-name">
                                {{ $conversation['user']->UserName }}
                                @if($conversation['unreadCount'] > 0)
                                    <span class="unread-badge">{{ $conversation['unreadCount'] }}</span>
                                @endif
                            </span>
                            <span class="conversation-time">
                                {{ $conversation['lastMessage']->SentAt->diffForHumans() }}
                            </span>
                        </div>
                        <div class="conversation-message {{ $conversation['unreadCount'] > 0 ? 'unread' : '' }}">
                            @if($conversation['lastMessage']->SenderID == auth()->id())
                                You: 
                            @endif
                            {{ Str::limit($conversation['lastMessage']->MessageContent, 60) }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="conversations-list">
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-comments"></i></div>
                <h2 class="empty-title">No Messages Yet</h2>
                <p class="empty-text">Start a conversation by contacting a seller from their item listing!</p>
            </div>
        </div>
    @endif
</div>
@endsection