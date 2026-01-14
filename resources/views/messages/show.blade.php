@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->UserName . ' - GoRentUMS')

@php
    $hideSearch = true;
    $hideFooter = true;
    $hideHeader = true;
@endphp

@push('styles')
<style>
    body {
        overflow: hidden;
    }

    .chat-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        height: 100vh;
        max-width: 100%;
        width: 100%;
    }

    .chat-header {
        background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
        border-radius: 0;
        padding: 16px 20px;
        box-shadow: 0 4px 20px rgba(68, 97, 242, 0.2);
        display: flex;
        align-items: center;
        gap: 16px;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
        flex-wrap: wrap;
        min-height: auto;
    }

    .chat-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
        pointer-events: none;
    }

    .chat-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 1;
    }

    .avatar-placeholder {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 1;
    }

    .chat-user-info {
        position: relative;
        z-index: 1;
    }

    .chat-user-info h2 {
        font-size: 20px;
        font-weight: 700;
        color: white;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .chat-user-info p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.85);
        margin: 4px 0 0 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .chat-user-info p i {
        font-size: 12px;
    }

    .back-btn {
        margin-left: auto;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px 16px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        z-index: 1;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .item-reference {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
        border-radius: 0;
        padding: 20px 30px;
        margin: 0;
        display: flex;
        gap: 20px;
        align-items: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-bottom: 1px solid rgba(68, 97, 242, 0.1);
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .item-reference::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, #4461F2 0%, #3651E2 100%);
    }

    .item-reference-image {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .item-reference-info {
        flex: 1;
    }

    .item-reference-label {
        font-size: 12px;
        color: #6b7280;
        background: rgba(68, 97, 242, 0.1);
        padding: 6px 12px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .item-reference-label i {
        color: #4461F2;
    }

    .item-reference-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .item-reference-price {
        font-size: 16px;
        color: #4461F2;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .item-reference-link {
        background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        white-space: nowrap;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .item-reference-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(68, 97, 242, 0.4);
    }

    .chat-messages {
        flex: 1;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        padding: 30px 25px 20px 25px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
        min-height: 0;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 120px;
    }

    .message {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        max-width: 65%;
        animation: messageSlide 0.3s ease-out;
    }

    @keyframes messageSlide {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.sent {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .message.received {
        align-self: flex-start;
    }

    .message-bubble {
        padding: 14px 18px;
        border-radius: 20px;
        word-wrap: break-word;
        line-height: 1.5;
        font-size: 14px;
    }

    .message.sent .message-bubble {
        background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
        color: white;
        border-bottom-right-radius: 6px;
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
    }

    .message.received .message-bubble {
        background: white;
        color: #1f2937;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .message-time {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 6px;
        font-weight: 500;
    }

    .message.sent .message-time {
        text-align: right;
        color: #d1d5db;
    }

    .message-item-ref {
        background: rgba(255, 255, 255, 0.15);
        border-left: 3px solid rgba(255, 255, 255, 0.5);
        padding: 12px;
        border-radius: 10px;
        margin-top: 10px;
        font-size: 13px;
        backdrop-filter: blur(10px);
    }

    .message.sent .message-item-ref {
        background: rgba(255, 255, 255, 0.2);
        border-left-color: rgba(255, 255, 255, 0.6);
    }

    .message.received .message-item-ref {
        background: rgba(68, 97, 242, 0.08);
        border-left-color: #4461F2;
    }

    .message-item-name {
        font-weight: 700;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .message-item-price {
        opacity: 0.9;
        font-weight: 600;
    }

    .chat-input-container {
        background: white;
        border-radius: 0;
        padding: 12px 16px;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.08);
        flex-shrink: 0;
        border-top: 1px solid #e5e7eb;
        width: 100%;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 100;
    }

    .chat-input-form {
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .chat-input {
        flex: 1;
        padding: 10px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 24px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s;
        background: #f9fafb;
        max-height: 80px;
        resize: none;
        min-height: 44px;
    }

    .chat-input:focus {
        border-color: #4461F2;
        background: white;
        box-shadow: 0 0 0 4px rgba(68, 97, 242, 0.1);
    }

    .send-btn {
        background: linear-gradient(135deg, #4461F2 0%, #3651E2 100%);
        color: white;
        padding: 10px 24px;
        border-radius: 24px;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        flex-shrink: 0;
        min-height: 44px;
    }

    .send-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(68, 97, 242, 0.4);
    }

    .send-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .date-divider {
        text-align: center;
        margin: 24px 0;
        position: relative;
    }

    .date-divider::before,
    .date-divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 40%;
        height: 1px;
        background: linear-gradient(to right, transparent, #d1d5db, transparent);
    }

    .date-divider::before {
        left: 0;
    }

    .date-divider::after {
        right: 0;
    }

    .date-divider span {
        background: white;
        color: #6b7280;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        position: relative;
        z-index: 1;
    }

    /* Custom Scrollbar */
    .chat-messages::-webkit-scrollbar {
        width: 8px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 10px;
    }

    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    @media (max-width: 968px) {
        .chat-header {
            padding: 14px 16px;
            gap: 12px;
        }

        .back-btn {
            padding: 8px 14px;
            font-size: 13px;
        }
    }

    @media (max-width: 768px) {
        .chat-header {
            padding: 12px 14px;
            gap: 10px;
        }

        .chat-avatar,
        .avatar-placeholder {
            width: 44px;
            height: 44px;
            font-size: 18px;
        }

        .chat-user-info h2 {
            font-size: 16px;
        }

        .chat-user-info p {
            font-size: 12px;
        }

        .back-btn {
            padding: 6px 12px;
            font-size: 12px;
            gap: 6px;
        }

        .back-btn i {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .chat-header {
            padding: 10px 12px;
            gap: 8px;
        }

        .chat-avatar,
        .avatar-placeholder {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .chat-user-info h2 {
            font-size: 14px;
            line-height: 1.2;
        }

        .chat-user-info p {
            font-size: 11px;
            display: none;
        }

        .back-btn {
            padding: 6px 10px;
            font-size: 11px;
            gap: 4px;
            min-width: auto;
        }

        .back-btn i {
            font-size: 11px;
        }

        .back-btn span {
            display: none;
        }

        .chat-input-container {
            padding: 10px 10px;
            bottom: 0;
        }

        .chat-input-form {
            gap: 8px;
        }

        .chat-input {
            padding: 10px 14px;
            font-size: 13px;
            min-height: 40px;
        }

        .send-btn {
            padding: 10px 16px;
            font-size: 12px;
            min-height: 40px;
        }

        .chat-messages {
            padding-bottom: 130px;
        }
    }
</style>
@endpush

@section('content')
<div class="chat-container">
    <div class="chat-header">
        @if($otherUser->ProfileImage)
            <img src="{{ asset('storage/' . $otherUser->ProfileImage) }}"
                 alt="{{ $otherUser->UserName }}"
                 class="chat-avatar">
        @else
            <div class="avatar-placeholder">
                {{ strtoupper(substr($otherUser->UserName, 0, 1)) }}
            </div>
        @endif

        <div class="chat-user-info">
            <h2>{{ $otherUser->UserName }}</h2>
            <p><i class="fas fa-envelope"></i> {{ $otherUser->Email }}</p>
        </div>

        <a href="{{ route('messages.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    @if($item)
        <div class="item-reference">
            @php
                $firstImage = $item->images ? $item->images->first() : null;
            @endphp
            @if($firstImage)
                <img src="{{ asset('storage/' . $firstImage->ImagePath) }}"
                     alt="{{ $item->ItemName }}"
                     class="item-reference-image"
                     onerror="this.src='https://via.placeholder.com/100'">
            @else
                <img src="https://via.placeholder.com/100"
                     alt="{{ $item->ItemName }}"
                     class="item-reference-image">
            @endif

            <div class="item-reference-info">
                <div class="item-reference-label">
                    <i class="fas fa-comments"></i> Discussing about:
                </div>
                <div class="item-reference-title">{{ $item->ItemName }}</div>
                <div class="item-reference-price">
                    <i class="fas fa-tag"></i> RM {{ number_format($item->PricePerDay, 2) }} / day
                </div>
            </div>

            <a href="{{ route('item.details', $item->ItemID) }}" class="item-reference-link" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Item
            </a>
        </div>
    @endif

    <div class="chat-messages" id="chatMessages">
        @php
            $lastDate = null;
        @endphp

        @foreach($messages as $message)
            @php
                $messageDate = $message->SentAt->format('Y-m-d');
                $showDate = $lastDate !== $messageDate;
                $lastDate = $messageDate;
            @endphp

            @if($showDate)
                <div class="date-divider">
                    <span>{{ $message->SentAt->format('M d, Y') }}</span>
                </div>
            @endif

            <div class="message {{ $message->SenderID == auth()->id() ? 'sent' : 'received' }}">
                <div>
                    <div class="message-bubble">
                        {{ $message->MessageContent }}

                        @if($message->item)
                            <div class="message-item-ref">
                                <div class="message-item-name"><i class="fas fa-box"></i> {{ $message->item->ItemName }}</div>
                                <div class="message-item-price">RM {{ number_format($message->item->PricePerDay, 2) }}/day</div>
                            </div>
                        @endif
                    </div>
                    <div class="message-time">
                        {{ $message->SentAt->format('g:i A') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="chat-input-container">
        <form action="{{ route('messages.send') }}" method="POST" class="chat-input-form" id="messageForm">
            @csrf
            <input type="hidden" name="receiver_id" value="{{ $otherUser->UserID }}">
            @if($item)
                <input type="hidden" name="item_id" value="{{ $item->ItemID }}">
            @endif
            <input type="text"
                   name="message"
                   class="chat-input"
                   placeholder="Type a message..."
                   required
                   autocomplete="off"
                   id="messageInput">
            <button type="submit" class="send-btn" id="sendBtn">
                <i class="fas fa-paper-plane"></i> Send
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to bottom on load
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Handle form submission with AJAX
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');

    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

        fetch('{{ route("messages.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Add message to chat
                const message = data.message;
                let itemRefHTML = '';

                if (message.item) {
                    itemRefHTML = `
                        <div class="message-item-ref">
                            <div class="message-item-name"><i class="fas fa-box"></i> ${message.item.ItemName}</div>
                            <div class="message-item-price">RM ${parseFloat(message.item.PricePerDay).toFixed(2)}/day</div>
                        </div>
                    `;
                }

                const messageHTML = `
                    <div class="message sent">
                        <div>
                            <div class="message-bubble">
                                ${message.MessageContent}
                                ${itemRefHTML}
                            </div>
                            <div class="message-time">
                                Just now
                            </div>
                        </div>
                    </div>
                `;
                chatMessages.insertAdjacentHTML('beforeend', messageHTML);
                chatMessages.scrollTop = chatMessages.scrollHeight;

                // Clear input
                messageInput.value = '';
            } else {
                alert(data.message || 'Failed to send message');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message: ' + error.message);
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send';
        });
    });

    // Poll for new messages every 3 seconds
    setInterval(function() {
        fetch('{{ route("messages.new", $otherUser->UserID) }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        let itemRefHTML = '';

                        if (message.item) {
                            itemRefHTML = `
                                <div class="message-item-ref">
                                    <div class="message-item-name"><i class="fas fa-box"></i> ${message.item.ItemName}</div>
                                    <div class="message-item-price">RM ${parseFloat(message.item.PricePerDay).toFixed(2)}/day</div>
                                </div>
                            `;
                        }

                        const messageHTML = `
                            <div class="message received">
                                <div>
                                    <div class="message-bubble">
                                        ${message.MessageContent}
                                        ${itemRefHTML}
                                    </div>
                                    <div class="message-time">
                                        Just now
                                    </div>
                                </div>
                            </div>
                        `;
                        chatMessages.insertAdjacentHTML('beforeend', messageHTML);
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            });
    }, 3000);
</script>
@endpush
