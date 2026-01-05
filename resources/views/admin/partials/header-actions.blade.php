<div class="header-actions">
    <a href="{{ route('admin.notifications.index') }}" class="notification-btn" id="notificationBtn">
        <i class="fas fa-bell"></i>
        @if(($notificationCount ?? 0) > 0)
            <span class="notification-badge">{{ $notificationCount }}</span>
        @endif
    </a>

    <div class="profile-section" id="profileSection">
        <span class="profile-name">{{ auth()->user()->UserName ?? 'Admin' }}</span>
        <i class="fas fa-chevron-down" style="font-size: 12px; color: #666;"></i>

        <!-- Dropdown Menu -->
        <div class="profile-dropdown" id="profileDropdown">
            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                <span class="dropdown-icon"><i class="fas fa-user"></i></span>
                <span>Profile Settings</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="dropdown-item logout" onclick="return confirmLogout(event)">
                    <span class="dropdown-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    /* Notification Button */
    .notification-btn {
        position: relative;
        background: white;
        border: 2px solid #E5E7EB;
        font-size: 16px;
        cursor: pointer;
        padding: 11px 14px;
        border-radius: 10px;
        transition: all 0.3s;
        color: #374151;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 44px;
        min-width: 44px;
        transform: translateY(-4px);
    }

    .notification-btn:hover {
        background-color: #F5F7FF;
        border-color: #4461F2;
        color: #4461F2;
    }

    .notification-btn:active {
        transform: scale(0.95);
    }

    .notification-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        min-width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        padding: 0 4px;
    }

    /* Profile Section */
    .profile-section {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 14px;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.2s;
        background: white;
        border: 2px solid #E5E7EB;
        height: 44px;
        white-space: nowrap;
        transform: translateY(-4px);
    }

    .profile-section:hover {
        background-color: #F5F7FF;
        border-color: #4461F2;
    }

    .profile-name {
        font-weight: 500;
        font-size: 14px;
        color: #374151;
        line-height: 1;
    }

    /* Dropdown Menu */
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
        color: #374151;
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
        background-color: #F9FAFB;
    }

    .dropdown-item.logout:hover {
        background-color: #FEE2E2;
        color: #DC2626;
    }

    .dropdown-icon {
        width: 20px;
        text-align: center;
    }

    .logout-form {
        margin: 0;
        padding: 0;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Ensure all buttons in header-actions have consistent height */
    .header-actions .btn,
    .header-actions .btn-secondary,
    .header-actions .btn-primary {
        height: 44px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 20px !important;
        gap: 8px;
        box-sizing: border-box;
        line-height: 1;
    }

    .header-actions a.btn,
    .header-actions a.btn-secondary,
    .header-actions button.btn,
    .header-actions button.btn-secondary {
        height: 44px !important;
        padding: 0 20px !important;
    }

    @media (max-width: 968px) {
        .header-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .header-actions .btn,
        .header-actions .btn-secondary,
        .header-actions .btn-primary,
        .header-actions a.btn,
        .header-actions a.btn-secondary,
        .header-actions button.btn,
        .header-actions button.btn-secondary {
            height: 40px !important;
            padding: 0 16px !important;
            font-size: 13px !important;
        }

        .notification-btn {
            padding: 10px 12px !important;
            font-size: 16px !important;
            height: 40px !important;
            min-width: 40px !important;
        }

        .profile-section {
            padding: 10px 12px !important;
            height: 40px !important;
        }

        .profile-name {
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .header-actions {
            gap: 0.5rem;
        }

        .header-actions .btn,
        .header-actions .btn-secondary,
        .header-actions .btn-primary,
        .header-actions a.btn,
        .header-actions a.btn-secondary,
        .header-actions button.btn,
        .header-actions button.btn-secondary {
            height: 36px !important;
            padding: 0 12px !important;
            font-size: 12px !important;
        }

        .notification-btn {
            padding: 8px 10px !important;
            font-size: 16px !important;
            height: 36px !important;
            min-width: 36px !important;
        }

        .profile-section {
            padding: 8px 10px !important;
            height: 36px !important;
        }

        .profile-name {
            display: none;
        }

        .profile-section .fa-chevron-down {
            display: none !important;
        }

        .profile-section::before {
            content: '\f007';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 16px;
        }
    }
</style>

<script>
    // Profile Dropdown Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const profileSection = document.getElementById('profileSection');
        const profileDropdown = document.getElementById('profileDropdown');

        if (profileSection && profileDropdown) {
            // Toggle dropdown on click
            profileSection.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!profileSection.contains(e.target)) {
                    profileDropdown.classList.remove('show');
                }
            });

            // Prevent dropdown from closing when clicking inside it
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
