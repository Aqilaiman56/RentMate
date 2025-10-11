@extends('layouts.app')

@section('title', 'Chat with ' . $otherUser->UserName . ' - RentMate')

@php($hideSearch = true)

@push('styles')
<style>
    .chat-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 120px);
    }

    .chat-header {
        background: white;
        border-radius: 15px 15px 0 0;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .chat-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #4461F2;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 600;
    }

    .chat-user-info h2 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .chat-user-info p {
        font-size: 13px;
        color: #6b7280;
        margin: 2px 0 0 0;
    }

    .back-btn {
        margin-left: auto;
        background: #f3f4f6;
        color: #374151;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: background-color 0.2s;
    }

    .back-btn:hover {
        background: #e5e7eb;
    }

    .chat-messages {
        flex: 1;
        background: #f9fafb;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .message {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        max-width: 70%;
    }

    .message.sent {
        align-self: flex-end;
        flex-direction: row-reverse;
    }

    .message.received {
        align-self: flex-start;
    }

    .message-bubble {
        padding: 12px 16px;
        border-radius: 18px;
        word-wrap: break-word;
    }

    .message.sent .message-bubble {
        background: #4461F2;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .message.received .message-bubble {
        background: white;
        color: #1f2937;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .message-time {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 4px;
    }

    .message.sent .message-time {
        text-align: right;
    }

    .chat-input-container {
        background: white;
        border-radius: 0 0 15px 15px;
        padding: 20px;
        box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.08);
    }

    .chat-input-form {
        display: flex;
        gap: 12px;
    }

    .chat-input {
        flex: 1;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 25px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
    }

    .chat-input:focus {
        border-color: #4461F2;
    }

    .send-btn {
        background: #4461F2;
        color: white;
        padding: 12px 24px;
        border-radius: 25px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .send-btn:hover {
        background: #3651E2;
    }

    .send-btn:disabled {
        background: #9ca3af;
        cursor: not-allowed;
    }

    .date-divider {
        text-align: center;
        margin: 20px 0;
    }

    .date-divider span {
        background: #e5e7eb;
        color: #6b7280;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .chat-container {
            padding: 0;
            height: calc(100vh - 80px);
        }

        .chat-header {
            border-radius: 0;
        }

        .chat-input-container {
            border-radius: 0;
        }

        .message {
            max-width: 85%;
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
            <p>{{ $otherUser->Email }}</p>
        </div>

        <a href="{{ route('messages.index') }}" class="back-btn">← Back</a>
    </div>

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
            <input type="text" 
                   name="message" 
                   class="chat-input" 
                   placeholder="Type a message..." 
                   required
                   autocomplete="off"
                   id="messageInput">
            <button type="submit" class="send-btn" id="sendBtn">Send</button>
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
        sendBtn.textContent = 'Sending...';

        fetch('{{ route("messages.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add message to chat
                const message = data.message;
                const messageHTML = `
                    <div class="message sent">
                        <div>
                            <div class="message-bubble">
                                ${message.MessageContent}
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
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message. Please try again.');
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.textContent = 'Send';
        });
    });

    // Poll for new messages every 3 seconds
    setInterval(function() {
        fetch('{{ route("messages.new", $otherUser->UserID) }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        const messageHTML = `
                            <div class="message received">
                                <div>
                                    <div class="message-bubble">
                                        ${message.MessageContent}
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