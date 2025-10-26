@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Listings Management</h1>
            <p class="header-description">View and manage all property and item listings on the platform</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportListings()">
                <i class="fas fa-download"></i> Export Data
            </button>
        </div>
    </div>



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
                        <a href="{{ route('item.details', $item->ItemID) }}" class="btn-action btn-view" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <button class="btn-action btn-more" onclick="showMoreActions({{ $item->ItemID }}, '{{ $item->ItemName }}')">
                            <i class="fas fa-ellipsis-v"></i>
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
            margin-bottom: 32px;
            padding: 0 20px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-content {
            flex: 1;
        }

        .header-title {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 8px 0;
        }

        .header-description {
            font-size: 16px;
            color: #6b7280;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
            padding: 0 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
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
            color: white;
        }

        .stat-icon.blue { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
        .stat-icon.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .stat-icon.orange { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }

        .stat-content {
            flex: 1;
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
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
            padding: 0 20px;
        }

        .listing-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
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
        }

        .listing-category {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .listing-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 12px 0;
            line-height: 1.4;
        }

        .listing-owner {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
            font-size: 14px;
            color: #6b7280;
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

        .btn-more {
            background: #f3f4f6;
            color: #374151;
            flex: 0 0 auto;
            width: 40px;
        }

        .btn-more:hover {
            background: #e5e7eb;
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

        /* Responsive */
        @media (max-width: 768px) {
            .listings-grid {
                grid-template-columns: 1fr;
            }
        }
        

    </style>

    <script>
        function exportListings() {
            window.location.href = '{{ route('admin.listings.export') }}';
        }

        function showMoreActions(id, name) {
            if (confirm(`Select action for "${name}":\n\nClick OK to delete listing\nClick Cancel to go back`)) {
                if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
                    // Create and submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/listings/${id}`;

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
            }
        }
    </script>
@endsection