@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <h1 class="header-title">Profile Settings</h1>
        <div class="header-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-section-card">
            <div class="card-header">
                <h2 class="card-title">Profile Information</h2>
                <p class="card-description">Update your account's profile information and email address.</p>
            </div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="profile-section-card">
            <div class="card-header">
                <h2 class="card-title">Update Password</h2>
                <p class="card-description">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="profile-section-card">
            <div class="card-header">
                <h2 class="card-title">Delete Account</h2>
                <p class="card-description">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

    <style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .profile-section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .card-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 8px 0;
    }

    .card-description {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }

    .card-body {
        padding: 24px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding: 0 20px;
    }

    .header-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    /* Form Styling */
    .profile-form-section {
        width: 100%;
    }

    .profile-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
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

    .form-error {
        font-size: 13px;
        color: #dc2626;
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 8px;
    }

    /* Buttons */
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
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
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .btn-danger {
        background: #dc2626;
        color: white;
    }

    .btn-danger:hover {
        background: #b91c1c;
    }

    /* Success Message */
    .success-message {
        font-size: 14px;
        color: #059669;
        font-weight: 500;
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Verification Notice */
    .verification-notice {
        margin-top: 12px;
        padding: 12px;
        background: #fef3c7;
        border: 1px solid #fbbf24;
        border-radius: 8px;
    }

    .verification-text {
        font-size: 13px;
        color: #92400e;
        margin: 0;
    }

    .verification-link {
        color: #3b82f6;
        text-decoration: underline;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 13px;
        padding: 0;
    }

    .verification-link:hover {
        color: #2563eb;
    }

    .verification-success {
        font-size: 13px;
        color: #059669;
        margin-top: 8px;
        font-weight: 500;
    }

    /* Danger Zone */
    .danger-zone {
        padding: 20px;
        background: #fef2f2;
        border: 2px solid #fecaca;
        border-radius: 8px;
    }

    .danger-zone-content {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .danger-zone-text {
        font-size: 14px;
        color: #991b1b;
        margin: 0;
        line-height: 1.6;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
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
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(2px);
    }

    .modal-content {
        position: relative;
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        animation: modalSlideIn 0.3s ease-out;
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
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 28px;
        color: #6b7280;
        cursor: pointer;
        line-height: 1;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .modal-close:hover {
        background: #f3f4f6;
        color: #374151;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-text {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.6;
        margin: 0 0 20px 0;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
    }
    </style>

    <script>
    // Auto-hide success messages
    document.addEventListener('DOMContentLoaded', function() {
        const successMessages = document.querySelectorAll('.success-message');
        successMessages.forEach(msg => {
            setTimeout(() => {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 300);
            }, 3000);
        });
    });

    // Delete Modal Functions
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
    </script>
@endsection