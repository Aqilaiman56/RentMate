@extends('layouts.app')

@section('title', 'GoRentUMS - My Profile')

@php
    $hideSearch = true;
@endphp

@push('styles')
<style>
    .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .profile-header {
            background: white;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #4461F2;
            flex-shrink: 0;
        }

        .profile-info h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 6px;
        }

        .profile-info p {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 3px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        /* Clickable stat cards */
        .stat-card-link {
            cursor: pointer;
        }

        .stat-card-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(68, 97, 242, 0.08) 0%, rgba(68, 97, 242, 0.03) 100%);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 0;
        }

        .stat-card-link:hover::before {
            opacity: 1;
        }

        .stat-card-link:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(68, 97, 242, 0.2);
        }

        .stat-card-link:hover .stat-number {
            color: #3651E2;
        }

        .stat-card-link::after {
            content: '→';
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 16px;
            color: #4461F2;
            opacity: 0;
            transition: opacity 0.3s, transform 0.3s;
        }

        .stat-card-link:hover::after {
            opacity: 1;
            transform: translateX(3px);
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #4461F2;
            margin-bottom: 5px;
            position: relative;
            z-index: 1;
            transition: color 0.3s;
        }

        .stat-label {
            font-size: 13px;
            color: #6B7280;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .profile-form {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 25px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        .form-input {
            padding: 10px 14px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: #4461F2;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            padding-top: 10px;
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
        }

        .btn-primary {
            background: #4461F2;
            color: white;
        }

        .btn-primary:hover {
            background: #3651E2;
        }

        .btn-secondary {
            background: #F3F4F6;
            color: #374151;
            border: 1px solid #D1D5DB;
        }

        .btn-secondary:hover {
            background: #E5E7EB;
        }

        .success-message {
            background: #D1FAE5;
            color: #065F46;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 14px;
        }

        .error-message {
            background: #FEE2E2;
            color: #991B1B;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 14px;
        }

        .error-message ul {
            margin-left: 20px;
        }

        .error-message li {
            margin-bottom: 4px;
        }

        .file-input-help {
            color: #6B7280;
            font-size: 12px;
            margin-top: 4px;
        }

        /* Password Input Wrapper */
        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-input-wrapper .form-input {
            padding-right: 45px;
            flex: 1;
        }

        .password-toggle-btn {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
            color: #6B7280;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle-btn:hover {
            color: #4461F2;
        }

        .password-toggle-btn i {
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-name {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 20px 15px;
            }
        }
</style>
@endpush

@section('content')
<div class="container">
        <div class="profile-header">
            @if($user->ProfileImage)
                <img src="{{ asset('storage/' . $user->ProfileImage) }}" alt="Profile Picture" class="profile-avatar">
            @else
                <img src="https://via.placeholder.com/80" alt="Profile Picture" class="profile-avatar">
            @endif

            <div class="profile-info">
                <h1>{{ $user->UserName }}</h1>
                <p><i class="fa-solid fa-envelope"></i> {{ $user->Email }}</p>
                @if($user->PhoneNumber)
                    <p><i class="fa-solid fa-mobile-screen-button"></i> {{ $user->PhoneNumber }}</p>
                @endif
                @if($user->Location)
                    <p><i class="fa-solid fa-location-dot"></i> {{ $user->Location }}</p>
                @endif
                <p><i class="fas fa-calendar"></i> Member since {{ $user->CreatedAt->format('M Y') }}</p>
            </div>
        </div>

        <div class="stats-grid">
            <a href="{{ route('user.listings') }}" class="stat-card stat-card-link">
                <div class="stat-number">{{ $totalListings }}</div>
                <div class="stat-label">Total Listings</div>
            </a>
            <a href="{{ route('user.bookings') }}" class="stat-card stat-card-link">
                <div class="stat-number">{{ $totalBookings }}</div>
                <div class="stat-label">Total Bookings</div>
            </a>
            <div class="stat-card">
                <div class="stat-number">{{ $totalReviews }}</div>
                <div class="stat-label">Total Reviews</div>
            </div>
        </div>

        <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data" class="profile-form">
            @csrf
            @method('PATCH')

            <h2 class="form-title">Edit Profile Information</h2>

            @if(session('success'))
                <div class="success-message">
                    <i class="fas fa-check"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-grid">
                <div class="form-group">
                    <label for="UserName" class="form-label">User Name*</label>
                    <input type="text" id="UserName" name="UserName" value="{{ old('UserName', $user->UserName) }}" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="Email" class="form-label">Email Address *</label>
                    <input type="email" id="Email" name="Email" value="{{ old('Email', $user->Email) }}" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="PhoneNumber" class="form-label">Phone Number</label>
                    <input type="tel" id="PhoneNumber" name="PhoneNumber" value="{{ old('PhoneNumber', $user->PhoneNumber) }}" class="form-input" placeholder="+60123456789">
                </div>

                <div class="form-group">
                    <label for="Location" class="form-label">Location</label>
                    <input type="text" id="Location" name="Location" value="{{ old('Location', $user->Location) }}" class="form-input" placeholder="e.g., Inside or outside UMS">
                </div>

                <div class="form-group full-width">
                    <label for="ProfileImage" class="form-label">Profile Picture</label>
                    <input type="file" id="ProfileImage" name="ProfileImage" class="form-input" accept="image/*">
                    <small class="file-input-help">Leave empty to keep current image. Max 2MB, JPG/PNG/GIF only.</small>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('user.HomePage') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>

        <!-- Bank Account Information Section -->
        <form method="POST" action="{{ route('user.profile.bank.update') }}" class="profile-form" style="margin-top: 20px;">
            @csrf
            @method('PATCH')

            <h2 class="form-title"><i class="fas fa-university"></i> Bank Account Information</h2>

            <div style="background: #EFF6FF; border-left: 4px solid #3B82F6; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p style="margin: 0; font-size: 13px; color: #1E40AF; line-height: 1.6;">
                    <i class="fas fa-info-circle"></i> <strong>Important:</strong> Your bank account details are required for receiving deposit refunds.
                    Please ensure the information is accurate. Your data is encrypted and secure.
                </p>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="BankName" class="form-label">
                        <i class="fas fa-building"></i> Bank Name *
                    </label>
                    <select id="BankName" name="BankName" class="form-input" required>
                        <option value="">-- Select Your Bank --</option>
                        <option value="Maybank" {{ old('BankName', $user->BankName) == 'Maybank' ? 'selected' : '' }}>Maybank</option>
                        <option value="CIMB Bank" {{ old('BankName', $user->BankName) == 'CIMB Bank' ? 'selected' : '' }}>CIMB Bank</option>
                        <option value="Public Bank" {{ old('BankName', $user->BankName) == 'Public Bank' ? 'selected' : '' }}>Public Bank</option>
                        <option value="RHB Bank" {{ old('BankName', $user->BankName) == 'RHB Bank' ? 'selected' : '' }}>RHB Bank</option>
                        <option value="Hong Leong Bank" {{ old('BankName', $user->BankName) == 'Hong Leong Bank' ? 'selected' : '' }}>Hong Leong Bank</option>
                        <option value="AmBank" {{ old('BankName', $user->BankName) == 'AmBank' ? 'selected' : '' }}>AmBank</option>
                        <option value="Bank Islam" {{ old('BankName', $user->BankName) == 'Bank Islam' ? 'selected' : '' }}>Bank Islam</option>
                        <option value="Bank Rakyat" {{ old('BankName', $user->BankName) == 'Bank Rakyat' ? 'selected' : '' }}>Bank Rakyat</option>
                        <option value="BSN" {{ old('BankName', $user->BankName) == 'BSN' ? 'selected' : '' }}>BSN (Bank Simpanan Nasional)</option>
                        <option value="Affin Bank" {{ old('BankName', $user->BankName) == 'Affin Bank' ? 'selected' : '' }}>Affin Bank</option>
                        <option value="Alliance Bank" {{ old('BankName', $user->BankName) == 'Alliance Bank' ? 'selected' : '' }}>Alliance Bank</option>
                        <option value="OCBC Bank" {{ old('BankName', $user->BankName) == 'OCBC Bank' ? 'selected' : '' }}>OCBC Bank</option>
                        <option value="Standard Chartered" {{ old('BankName', $user->BankName) == 'Standard Chartered' ? 'selected' : '' }}>Standard Chartered</option>
                        <option value="HSBC Bank" {{ old('BankName', $user->BankName) == 'HSBC Bank' ? 'selected' : '' }}>HSBC Bank</option>
                        <option value="UOB Bank" {{ old('BankName', $user->BankName) == 'UOB Bank' ? 'selected' : '' }}>UOB Bank</option>
                        <option value="Other" {{ old('BankName', $user->BankName) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="BankAccountNumber" class="form-label">
                        <i class="fas fa-credit-card"></i> Account Number *
                    </label>
                    <input type="text"
                           id="BankAccountNumber"
                           name="BankAccountNumber"
                           value="{{ old('BankAccountNumber', $user->BankAccountNumber) }}"
                           class="form-input"
                           placeholder="Enter your account number"
                           pattern="[0-9]{8,20}"
                           maxlength="20"
                           required>
                    <small class="file-input-help">Enter 8-20 digits only</small>
                </div>

                <div class="form-group">
                    <label for="BankAccountHolderName" class="form-label">
                        <i class="fas fa-user"></i> Account Holder Name *
                    </label>
                    <input type="text"
                           id="BankAccountHolderName"
                           name="BankAccountHolderName"
                           value="{{ old('BankAccountHolderName', $user->BankAccountHolderName) }}"
                           class="form-input"
                           placeholder="Enter name as per bank account"
                           required>
                    <small class="file-input-help">Must match your bank account exactly</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Bank Details
                </button>
            </div>
        </form>

        <!-- Change Password Section -->
        <form method="POST" action="{{ route('user.profile.password') }}" class="profile-form" style="margin-top: 20px;">
            @csrf

            <h2 class="form-title"><i class="fas fa-lock"></i> Change Password</h2>

            @if(session('password_success'))
                <div class="success-message">
                    <i class="fas fa-check"></i> {{ session('password_success') }}
                </div>
            @endif

            @if(session('password_error'))
                <div class="error-message">
                    {{ session('password_error') }}
                </div>
            @endif

            <div style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <p style="margin: 0; font-size: 13px; color: #92400E; line-height: 1.6;">
                    <i class="fas fa-shield-alt"></i> <strong>Security Tip:</strong> Use a strong password with at least 8 characters, including uppercase, lowercase, numbers, and special characters.
                </p>
            </div>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="current_password" class="form-label">
                        <i class="fas fa-key"></i> Current Password *
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" id="current_password" name="current_password" class="form-input" required placeholder="Enter your current password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('current_password')">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">
                        <i class="fas fa-lock"></i> New Password *
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" id="new_password" name="new_password" class="form-input" required placeholder="Enter new password (min 8 characters)">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('new_password')">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label">
                        <i class="fas fa-check-circle"></i> Confirm New Password *
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input" required placeholder="Re-enter new password">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('new_password_confirmation')">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Password
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const button = input.parentElement.querySelector('.password-toggle-btn');
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush