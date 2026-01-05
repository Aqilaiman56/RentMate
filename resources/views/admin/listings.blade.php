@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-with-menu">
            <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="header-title">Listings Management</h1>
        </div>
        <div class="header-actions" style="gap: 12px;">
            <button class="btn btn-secondary" onclick="exportListings()">
                <i class="fas fa-download"></i><span class="btn-text">  Export Data</span>
            </button>
            @include('admin.partials.header-actions', ['notificationCount' => $notificationCount ?? 0])
        </div>
    </div>

    <p class="page-description">View and manage all property and item listings</p>



    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-box"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalListings }}</div>
                <div class="stat-label">Total Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeListings }}</div>
                <div class="stat-label">Active Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-times-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $unavailableListings }}</div>
                <div class="stat-label">Unavailable</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <form action="{{ route('admin.listings') }}" method="GET" class="table-controls">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text"
                   name="search"
                   placeholder="Search listings by name or owner..."
                   class="search-input"
                   value="{{ request('search') }}">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" name="category" onchange="this.form.submit()">
                <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->CategoryID }}" {{ request('category') == $category->CategoryID ? 'selected' : '' }}>
                        {{ $category->CategoryName }}
                    </option>
                @endforeach
            </select>
            <select class="filter-select" name="status" onchange="this.form.submit()">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
            </select>
            <select class="filter-select" name="sort" onchange="this.form.submit()">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                <option value="price-high" {{ request('sort') == 'price-high' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="price-low" {{ request('sort') == 'price-low' ? 'selected' : '' }}>Price: Low to High</option>
            </select>
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </div>
    </form>

    <!-- Listings Grid -->
    <div class="listings-grid">
        @forelse($items as $item)
            <div class="listing-card">
                <div class="listing-image">
                    @php
                        $firstImage = $item->images->first();
                    @endphp
                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage->ImagePath) }}" alt="{{ $item->ItemName }}">
                    @else
                        <img src="https://via.placeholder.com/300x200/3b82f6/ffffff?text={{ urlencode($item->ItemName) }}" alt="{{ $item->ItemName }}">
                    @endif
                    <span class="listing-badge {{ $item->Availability ? 'badge-active' : 'badge-unavailable' }}">
                        {{ $item->Availability ? 'Active' : 'Unavailable' }}
                    </span>
                </div>
                <div class="listing-content">
                    <div class="listing-category">
                        {{ $item->category->CategoryName ?? 'Uncategorized' }}
                    </div>
                    <h3 class="listing-title">{{ $item->ItemName }}</h3>
                    <div class="listing-owner">
                        @if($item->user->ProfileImage)
                            <img src="{{ asset('storage/' . $item->user->ProfileImage) }}" 
                                 alt="{{ $item->user->UserName }}" 
                                 style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div class="owner-avatar {{ ['blue', 'pink', 'green', 'orange', 'purple', 'teal'][$item->UserID % 6] }}">
                                {{ strtoupper(substr($item->user->UserName, 0, 2)) }}
                            </div>
                        @endif
                        <span>{{ $item->user->UserName ?? 'Unknown' }}</span>
                    </div>
                    <div class="listing-details">
                        <div class="detail-item">
                            <span class="detail-label">Price/Day:</span>
                            <span class="detail-value">RM {{ number_format($item->PricePerDay, 2) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Deposit:</span>
                            <span class="detail-value">RM {{ number_format($item->DepositAmount, 2) }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Bookings:</span>
                            <span class="detail-value">{{ $item->bookings->count() }}</span>
                        </div>
                    </div>
                    <div class="listing-actions">
                        <a href="{{ route('admin.listings.show', $item->ItemID) }}" class="btn-action btn-view">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <button class="btn-action btn-delete-only" onclick="showDeleteModal({{ $item->ItemID }}, '{{ addslashes($item->ItemName) }}')">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 60px; color: #6b7280;">
                <p style="font-size: 18px; font-weight: 600;">No listings found</p>
                <p style="margin-top: 10px;">Try adjusting your filters</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="pagination-container">
            {{ $items->appends(request()->query())->links() }}
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <h2 style="color: white; margin: 0;">Confirm Delete</h2>
                <span class="close" onclick="closeDeleteModal()" style="color: white;">&times;</span>
            </div>
            <div class="modal-body">
                <div class="alert-danger" style="margin-bottom: 20px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Warning!</strong>
                        <p>This action cannot be undone. The listing will be permanently deleted.</p>
                    </div>
                </div>
                <p style="margin: 0; color: #374151;">Are you sure you want to delete "<strong id="deleteItemName"></strong>"?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteListing()">
                    <i class="fas fa-trash-alt"></i> Delete Listing
                </button>
            </div>
        </div>
    </div>

    <style>
        /* Add alert styles */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin: 0 20px 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .pagination-container {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
        }

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
            gap: 0.75rem;
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
            flex-shrink: 0;
            align-items: flex-start;
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

        .stat-content {
            flex: 1;
            min-width: 0;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: #6b7280;
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
            padding: 10px 14px 10px 40px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-buttons {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
        }

        /* Listings Grid */
        .listings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 320px), 1fr));
            gap: 24px;
            padding: 0 20px;
        }

        .listing-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .listing-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .listing-image {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .listing-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .listing-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .badge-active {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-unavailable {
            background: #fee2e2;
            color: #991b1b;
        }

        .listing-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .listing-category {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .listing-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 12px 0;
            line-height: 1.4;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .listing-owner {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            color: #6b7280;
            min-width: 0;
        }

        .listing-owner span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .owner-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            color: white;
        }

        .owner-avatar.blue { background: #3b82f6; }
        .owner-avatar.pink { background: #ec4899; }
        .owner-avatar.green { background: #10b981; }
        .owner-avatar.orange { background: #f97316; }
        .owner-avatar.purple { background: #a855f7; }
        .owner-avatar.teal { background: #14b8a6; }
        .owner-avatar.red { background: #ef4444; }
        .owner-avatar.indigo { background: #6366f1; }

        .listing-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }

        .detail-label {
            color: #6b7280;
        }

        .detail-value {
            font-weight: 600;
            color: #1f2937;
        }

        .listing-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .btn-action {
            flex: 1;
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
        }

        .btn-view {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-view:hover {
            background: #bfdbfe;
        }

        .btn-edit {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-edit:hover {
            background: #fde68a;
        }

        .btn-delete-only {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete-only:hover {
            background: #fecaca;
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
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

    

        .modal-content {
            background: white;
            border-radius: 16px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease-out;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            z-index: var(--z-modal);
            pointer-events: auto;
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 24px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: white;
        }

        .modal-body {
            padding: 30px;
            max-height: calc(90vh - 160px);
            overflow-y: auto;
        }

        .modal-description {
            margin: 0 0 20px 0;
            font-size: 15px;
            color: #6b7280;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            background: #f9fafb;
        }

        .close {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 24px;
            color: white;
            line-height: 1;
            background: rgba(255, 255, 255, 0.2);
        }

        .close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .alert-danger {
            display: flex;
            gap: 12px;
            padding: 16px;
            border-radius: 10px;
            background: #fee2e2;
            border-left: 4px solid #ef4444;
        }

        .alert-danger i {
            font-size: 20px;
            color: #ef4444;
            flex-shrink: 0;
        }

        .alert-danger strong {
            display: block;
            font-weight: 700;
            margin-bottom: 4px;
            color: #991b1b;
        }

        .alert-danger p {
            margin: 0;
            font-size: 13px;
            color: #7f1d1d;
        }

        /* Responsive Styles */
        @media (max-width: 968px) {
            .header {
                align-items: flex-start;
                gap: 16px;
            }

            .header-with-menu {
                order: -1;
                width: 100%;
                align-items: center;
            }

            .header-with-menu .mobile-menu-toggle {
                display: flex;
            }

            .header-actions {
                width: auto;
                padding-top: 0;
            }

            .header-title {
                font-size: 28px;
            }

            .page-description {
                font-size: 15px;
            }

            .btn {
                padding: 8px 16px;
                font-size: 13px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .stat-card {
                padding: 16px;
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
                font-size: 13px;
            }

            .table-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: 100%;
            }

            .filter-buttons {
                width: 100%;
            }

            .filter-select {
                flex: 1;
            }

            .btn {
                width: 100%;
            }

            .listings-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 16px;
                margin-bottom: 24px;
                gap: 12px;
                flex-direction: row;
                justify-content: space-between;
            }

            .header-with-menu {
                gap: 0.625rem;
                flex: 1;
            }

            .header-actions {
                align-self: flex-start;
            }

            .header-title {
                font-size: 24px;
            }

            .page-description {
                font-size: 14px;
            }

            .btn {
                padding: 7px 14px;
                font-size: 12px;
            }

            .btn-text {
                display: none;
            }

            .stats-grid {
                padding: 0 16px;
                gap: 12px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-icon {
                width: 44px;
                height: 44px;
                font-size: 18px;
            }

            .stat-value {
                font-size: 20px;
            }

            .stat-label {
                font-size: 12px;
            }

            .table-controls {
                padding: 0 16px;
                gap: 12px;
            }

            .filter-buttons {
                flex-direction: column;
            }

            .filter-select, .btn {
                width: 100%;
            }

            .listings-grid {
                padding: 0 16px;
                gap: 16px;
            }

            .listing-content {
                padding: 16px;
            }

            .listing-title {
                font-size: 16px;
            }

            .listing-details {
                padding: 10px;
            }

            .detail-item {
                font-size: 12px;
            }

            .pagination-container {
                padding: 32px 16px;
            }

            .alert {
                margin: 0 16px 16px;
                padding: 14px 16px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 0 12px;
                margin-bottom: 20px;
                gap: 10px;
            }

            .header-with-menu {
                gap: 0.5rem;
            }

            .header-actions {
                padding-top: 2px;
            }

            .header-title {
                font-size: 18px;
                margin: 0 0 4px 0;
            }

            .page-description {
                font-size: 12px;
            }

            .btn {
                padding: 6px 10px;
                font-size: 11px;
            }

            .btn i {
                font-size: 12px;
            }

            .stats-grid {
                padding: 0 12px;
                gap: 10px;
            }

            .stat-card {
                padding: 12px;
                gap: 12px;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .stat-value {
                font-size: 18px;
            }

            .stat-label {
                font-size: 11px;
            }

            .table-controls {
                padding: 0 12px;
                gap: 10px;
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                max-width: 100%;
            }

            .search-input {
                padding: 8px 12px 8px 36px;
                font-size: 13px;
            }

            .filter-select {
                padding: 8px 12px;
                font-size: 13px;
            }

            .listings-grid {
                padding: 0 12px;
                gap: 12px;
            }

            .listing-image {
                height: 180px;
            }

            .listing-badge {
                font-size: 10px;
                padding: 4px 10px;
            }

            .listing-content {
                padding: 14px;
            }

            .listing-category {
                font-size: 11px;
            }

            .listing-title {
                font-size: 15px;
            }

            .listing-owner {
                font-size: 13px;
            }

            .owner-avatar {
                width: 28px;
                height: 28px;
                font-size: 11px;
            }

            .listing-details {
                padding: 8px;
                gap: 6px;
            }

            .detail-item {
                font-size: 11px;
            }

            .btn-action {
                padding: 6px 10px;
                font-size: 12px;
                gap: 3px;
            }


            .pagination-container {
                padding: 24px 12px;
            }

            .alert {
                margin: 0 12px 12px;
                padding: 12px 14px;
                font-size: 12px;
            }
        }

        @media (max-width: 360px) {
            .header {
                gap: 8px;
            }

            .header-title {
                font-size: 16px;
            }

            .header-description {
                font-size: 10px;
            }

            .btn {
                padding: 5px 8px;
                font-size: 10px;
            }

            .btn i {
                font-size: 11px;
            }

            .stat-value {
                font-size: 16px;
            }

            .stat-label {
                font-size: 10px;
            }

            .listing-image {
                height: 160px;
            }

            .listing-title {
                font-size: 14px;
            }

            .detail-item {
                font-size: 10px;
            }
        }

    </style>

    <script>
        let currentItemId = null;
        let currentItemName = '';

        function exportListings() {
            window.location.href = '{{ route('admin.listings.export') }}';
        }

        function showDeleteModal(id, name) {
            currentItemId = id;
            currentItemName = name;
            document.getElementById('deleteItemName').textContent = currentItemName;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
            currentItemId = null;
            currentItemName = '';
        }

        function deleteListing() {
            if (!currentItemId) return;

            // Create and submit delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/listings/${currentItemId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
@endsection