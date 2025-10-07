<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentMate - My Profile</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #E8EEFF 0%, #F5F7FF 100%);
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #4461F2;
            text-decoration: none;
        }

        .header-icons {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            text-decoration: none;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .icon-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .profile-section {
            position: relative;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .profile-section:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-name {
            font-weight: 500;
            font-size: 14px;
            color: #1f2937;
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 200px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            overflow: hidden;
        }

        .profile-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.2s;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background-color: #f5f5f5;
        }

        .dropdown-item.logout {
            color: #dc3545;
            border-top: 1px solid #e0e0e0;
        }

        .dropdown-item.logout:hover {
            background-color: #fff5f5;
        }

        .dropdown-icon {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .logout-form {
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 40px;
        }

        .profile-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #4461F2;
        }

        .profile-info h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .profile-info p {
            font-size: 16px;
            color: #6B7280;
            margin-bottom: 4px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #4461F2;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: #6B7280;
            font-weight: 500;
        }

        .profile-form {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
            padding: 12px 16px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #4461F2;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
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
        }

        .error-message {
            background: #FEE2E2;
            color: #991B1B;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
            }

            .profile-name {
                display: none;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('user.HomePage') }}" class="logo">RentMate</a>

        <div class="header-icons">
            <a href="{{ route('notifications') }}" class="icon-btn">🔔</a>
            <a href="{{ route('messages.index') }}" class="icon-btn">✉️</a>

            <div class="profile-section" id="profileSection">
                @if(auth()->user()->ProfileImage)
                    <img src="{{ asset('storage/' . auth()->user()->ProfileImage) }}" alt="Profile" class="profile-pic">
                @else
                    <img src="https://via.placeholder.com/40" alt="Profile" class="profile-pic">
                @endif
                <span class="profile-name">{{ auth()->user()->UserName ?? 'User' }}</span>

                <div class="profile-dropdown" id="profileDropdown">
                    <a href="{{ route('user.profile') }}" class="dropdown-item">
                        <span class="dropdown-icon">👤</span>
                        <span>Profile Settings</span>
                    </a>
                    <a href="{{ route('user.listings') }}" class="dropdown-item">
                        <span class="dropdown-icon">📦</span>
                        <span>My Listings</span>
                    </a>
                    <a href="{{ route('user.add-listing') }}" class="dropdown-item">
                        <span class="dropdown-icon">➕</span>
                        <span>Add Listing</span>
                    </a>
                    <a href="{{ route('user.bookings') }}" class="dropdown-item">
                        <span class="dropdown-icon">📅</span>
                        <span>My Bookings</span>
                    </a>
                    <a href="{{ route('user.wishlist') }}" class="dropdown-item">
                        <span class="dropdown-icon">❤️</span>
                        <span>Wishlist</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="dropdown-item logout" onclick="confirmLogout(event)">
                            <span class="dropdown-icon">🚪</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="profile-header">
            @if($user->ProfileImage)
                <img src="{{ asset('storage/' . $user->ProfileImage) }}" alt="Profile Picture" class="profile-avatar">
            @else
                <img src="https://via.placeholder.com/120" alt="Profile Picture" class="profile-avatar">
            @endif

            <div class="profile-info">
                <h1>{{ $user->UserName }}</h1>
                <p>{{ $user->Email }}</p>
                @if($user->PhoneNumber)
                    <p>📞 {{ $user->PhoneNumber }}</p>
                @endif
                <p>Member since {{ $user->created_at->format('M Y') }}</p>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ $totalListings }}</div>
                <div class="stat-label">Total Listings</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalBookings }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
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
                    {{ session('success') }}
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
                    <label for="UserName" class="form-label">Full Name</label>
                    <input type="text" id="UserName" name="UserName" value="{{ old('UserName', $user->UserName) }}" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="Email" class="form-label">Email Address</label>
                    <input type="email" id="Email" name="Email" value="{{ old('Email', $user->Email) }}" class="form-input" required>
                </div>

                <div class="form-group">
                    <label for="PhoneNumber" class="form-label">Phone Number</label>
                    <input type="tel" id="PhoneNumber" name="PhoneNumber" value="{{ old('PhoneNumber', $user->PhoneNumber) }}" class="form-input">
                </div>

                <div class="form-group">
                    <label for="ProfileImage" class="form-label">Profile Picture</label>
                    <input type="file" id="ProfileImage" name="ProfileImage" class="form-input" accept="image/*">
                    <small style="color: #6B7280; font-size: 12px;">Leave empty to keep current image. Max 2MB, JPG/PNG/GIF only.</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <a href="{{ route('user.HomePage') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // Profile Dropdown Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const profileSection = document.getElementById('profileSection');
            const profileDropdown = document.getElementById('profileDropdown');

            if (profileSection && profileDropdown) {
                profileSection.addEventListener('click', function(e) {
                    e.stopPropagation();
                    profileDropdown.classList.toggle('show');
                });

                document.addEventListener('click', function(e) {
                    if (!profileSection.contains(e.target)) {
                        profileDropdown.classList.remove('show');
                    }
                });

                profileDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }
        });

        // Logout Confirmation
        function confirmLogout(event) {
            event.preventDefault();

            if (confirm('Are you sure you want to logout?')) {
                event.target.closest('form').submit();
            }

            return false;
        }
    </script>
</body>
</html>
