@extends('layouts.admin')

@section('main-content')
    <div class="header">
        <div class="header-content">
            <h1 class="header-title">Listings Management</h1>
            <p class="header-description">View and manage all property and item listings on the platform</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="exportListings()">
                📥 Export Data
            </button>
            <button class="btn btn-primary" onclick="addNewListing()">
                ➕ Add Listing
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">📦</div>
            <div class="stat-content">
                <div class="stat-value">248</div>
                <div class="stat-label">Total Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">✓</div>
            <div class="stat-content">
                <div class="stat-value">215</div>
                <div class="stat-label">Active Listings</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">⏳</div>
            <div class="stat-content">
                <div class="stat-value">18</div>
                <div class="stat-label">Pending Review</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">💰</div>
            <div class="stat-content">
                <div class="stat-value">RM 45,680</div>
                <div class="stat-label">Total Deposits</div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="table-controls">
        <div class="search-box">
            <span class="search-icon">🔍</span>
            <input type="text" placeholder="Search listings by name or owner..." class="search-input" id="searchInput">
        </div>
        <div class="filter-buttons">
            <select class="filter-select" id="categoryFilter">
                <option value="all">All Categories</option>
                <option value="electronics">Electronics</option>
                <option value="sports">Sports Equipment</option>
                <option value="books">Books & Stationery</option>
                <option value="furniture">Furniture</option>
            </select>
            <select class="filter-select" id="statusFilter">
                <option value="all">All Status</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="unavailable">Unavailable</option>
            </select>
            <select class="filter-select" id="sortFilter">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="price-high">Price: High to Low</option>
                <option value="price-low">Price: Low to High</option>
            </select>
        </div>
    </div>

    <!-- Listings Grid/Table -->
    <div class="listings-grid">
        <!-- Listing Card 1 -->
        <div class="listing-card">
            <div class="listing-image">
                <img src="https://via.placeholder.com/300x200/3b82f6/ffffff?text=Gaming+Laptop" alt="Gaming Laptop">
                <span class="listing-badge badge-active">Active</span>
            </div>
            <div class="listing-content">
                <div class="listing-category">💻 Electronics</div>
                <h3 class="listing-title">Gaming Laptop - ROG Strix</h3>
                <div class="listing-owner">
                    <div class="owner-avatar blue">AM</div>
                    <span>Ahmad Mahmud</span>
                </div>
                <div class="listing-details">
                    <div class="detail-item">
                        <span class="detail-label">Price/Day:</span>
                        <span class="detail-value">RM 45.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Deposit:</span>
                        <span class="detail-value">RM 500.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Bookings:</span>
                        <span class="detail-value">12</span>
                    </div>
                </div>
                <div class="listing-actions">
                    <button class="btn-action btn-view" onclick="viewListing(1)">👁️ View</button>
                    <button class="btn-action btn-edit" onclick="editListing(1)">✏️ Edit</button>
                    <button class="btn-action btn-more" onclick="showMoreActions(1)">⋮</button>
                </div>
            </div>
        </div>

        <!-- Listing Card 2 -->
        <div class="listing-card">
            <div class="listing-image">
                <img src="https://via.placeholder.com/300x200/10b981/ffffff?text=DSLR+Camera" alt="DSLR Camera">
                <span class="listing-badge badge-active">Active</span>
            </div>
            <div class="listing-content">
                <div class="listing-category">📷 Electronics</div>
                <h3 class="listing-title">Canon EOS 90D DSLR Camera</h3>
                <div class="listing-owner">
                    <div class="owner-avatar pink">SL</div>
                    <span>Siti Lina</span>
                </div>
                <div class="listing-details">
                    <div class="detail-item">
                        <span class="detail-label">Price/Day:</span>
                        <span class="detail-value">RM 80.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Deposit:</span>
                        <span class="detail-value">RM 1,200.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Bookings:</span>
                        <span class="detail-value">8</span>
                    </div>
                </div>
                <div class="listing-actions">
                    <button class="btn-action btn-view" onclick="viewListing(2)">👁️ View</button>
                    <button class="btn-action btn-edit" onclick="editListing(2)">✏️ Edit</button>
                    <button class="btn-action btn-more" onclick="showMoreActions(2)">⋮</button>
                </div>
            </div>
        </div>

        <!-- Listing Card 3 -->
        <div class="listing-card">
            <div class="listing-image">
                <img src="https://via.placeholder.com/300x200/f97316/ffffff?text=Mountain+Bike" alt="Mountain Bike">
                <span class="listing-badge badge-active">Active</span>
            </div>
            <div class="listing-content">
                <div class="listing-category">🚴 Sports Equipment</div>
                <h3 class="listing-title">Mountain Bike - Trek Marlin 7</h3>
                <div class="listing-owner">
                    <div class="owner-avatar green">TW</div>
                    <span>Tan Wei Ming</span>
                </div>
                <div class="listing-details">
                    <div class="detail-item">
                        <span class="detail-label">Price/Day:</span>
                        <span class="detail-value">RM 25.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Deposit:</span>
                        <span class="detail-value">RM 300.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Bookings:</span>
                        <span class="detail-value">15</span>
                    </div>
                </div>
                <div class="listing-actions">
                    <button class="btn-action btn-view" onclick="viewListing(3)">👁️ View</button>
                    <button class="btn-action btn-edit" onclick="editListing(3)">✏️ Edit</button>
                    <button class="btn-action btn-more" onclick="showMoreActions(3)">⋮</button>
                </div>
            </div>
        </div>

        <!-- Listing Card 4 -->
        <div class="listing-card">
            <div class="listing-image">
                <img src="https://via.placeholder.com/300x200/a855f7/ffffff?text=Study+Desk" alt="Study Desk">
                <span class="listing-badge badge-pending">Pending</span>
            </div>
            <div class="listing-content">
                <div class="listing-category">🪑 Furniture</div>
                <h3 class="listing-title">Study Desk with Storage</h3>
                <div class="listing-owner">
                    <div class="owner-avatar orange">RK</div>
                    <span>Raj Kumar</span>
                </div>
                <div class="listing-details">
                    <div class="detail-item">
                        <span class="detail-label">Price/Day:</span>
                        <span class="detail-value">RM 15.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Deposit:</span>
                        <span class="detail-value">RM 150.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Bookings:</span>
                        <span class="detail-value">0</span>
                    </div>
                </div>
                <div class="listing-actions">
                    <button class="btn-action btn-view" onclick="viewListing(4)">👁️ View</button>
                    <button class="btn-action btn-edit" onclick="editListing(4)">✏️ Edit</button>
                    <button class="btn-action btn-more" onclick="showMoreActions(4)">⋮</button>
                </div>
            </div>
        </div>

        <!-- Listing Card 5 -->
        <div class="listing-card">
            <div class="listing-image">
                <img src="https://via.placeholder.com/300x200/ec4899/ffffff?text=Textbooks" alt="Engineering Textbooks">
                <span class="listing-badge badge-active">Active</span>
            </div>
            <div class="listing-content">
                <div class="listing-category">📚 Books & Stationery</div>
                <h3 class="listing-title">Engineering Textbooks Set</h3>
                <div class="listing-owner">
                    <div class="owner-avatar purple">NZ</div>
                    <span>Nurul Zahra</span>
                </div>
                <div class="listing-details">
                    <div class="detail-item">
                        <span class="detail-label">Price/Day:</span>
                        <span class="detail-value">RM 10.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Deposit:</span>
                        <span class="detail-value">RM 100.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Bookings:</span>
                        <span class="detail-value">10</span>
                    </div>
                </div>
                <div class="listing-actions">
                    <button class="btn-action btn-view" onclick="viewListing(5)">👁️ View</button>
                    <button class="btn-action btn-edit" onclick="editListing(5)">✏️ Edit</button>
                    <button class="btn-action btn-more" onclick="showMoreActions(5)">⋮</button>
                </div>
            </div>
        </div>

        <!-- Listing Card 6 -->
        <div class="listing-card">
            <div class="listing-image">
                <img src="https://via.placeholder.com/300x200/14b8a6/ffffff?text=Projector" alt="Projector">
                <span class="listing-badge badge-active">Active</span>
            </div>
            <div class="listing-content">
                <div class="listing-category">🎥 Electronics</div>
                <h3 class="listing-title">Epson Portable Projector</h3>
                <div class="listing-owner">
                    <div class="owner-avatar teal">LC</div>
                    <span>Lee Chong</span>
                </div>
                <div class="listing-details">
                    <div class="detail-item">
                        <span class="detail-label">Price/Day:</span>
                        <span class="detail-value">RM 35.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Deposit:</span>
                        <span class="detail-value">RM 400.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Bookings:</span>
                        <span class="detail-value">9</span>
                    </div>
                </div>
                <div class="listing-actions">
                    <button class="btn-action btn-view" onclick="viewListing(6)">👁️ View</button>
                    <button class="btn-action btn-edit" onclick="editListing(6)">✏️ Edit</button>
                    <button class="btn-action btn-more" onclick="showMoreActions(6)">⋮</button>
                </div>
            </div>
        </div>

        <!-- Listing Card 7 -->
        <div class="listing-card">
            <div class="listing-image">
                <img src="https://via.placeholder.com/300x200/ef4444/ffffff?text=Tennis+Racket" alt="Tennis Racket">
                <span class="listing-badge badge-unavailable">Unavailable</span>
            </div>
            <div class="listing-content">
                <div class="listing-category">🎾 Sports Equipment</div>
                <h3 class="listing-title">Professional Tennis Racket</h3>
                <div class="listing-owner">
                    <div class="owner-avatar red">FA</div>
                    <span>Fatimah Ali</span>
                </div>
                <div class="listing-details">
                    <div class="detail-item">
                        <span class="detail-label">Price/Day:</span>
                        <span class="detail-value">RM 20.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Deposit:</span>
                        <span class="detail-value">RM 200.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Bookings:</span>
                        <span class="detail-value">4</span>
                    </div>
                </div>
                <div class="listing-actions">
                    <button class="btn-action btn-view" onclick="viewListing(7)">👁️ View</button>
                    <button class="btn-action btn-edit" onclick="editListing(7)">✏️ Edit</button>
                    <button class="btn-action btn-more" onclick="showMoreActions(7)">⋮</button>
                </div>
            </div>
        </div>

        <!-- Listing Card 8 -->
        <div class="listing-card">
            <div class="listing-image">
                <img src="https://via.placeholder.com/300x200/6366f1/ffffff?text=Drone" alt="Drone">
                <span class="listing-badge badge-active">Active</span>
            </div>
            <div class="listing-content">
                <div class="listing-category">🚁 Electronics</div>
                <h3 class="listing-title">DJI Mini 3 Pro Drone</h3>
                <div class="listing-owner">
                    <div class="owner-avatar indigo">KC</div>
                    <span>Kevin Chen</span>
                </div>
                <div class="listing-details">
                    <div class="detail-item">
                        <span class="detail-label">Price/Day:</span>
                        <span class="detail-value">RM 60.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Deposit:</span>
                        <span class="detail-value">RM 800.00</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Bookings:</span>
                        <span class="detail-value">14</span>
                    </div>
                </div>
                <div class="listing-actions">
                    <button class="btn-action btn-view" onclick="viewListing(8)">👁️ View</button>
                    <button class="btn-action btn-edit" onclick="editListing(8)">✏️ Edit</button>
                    <button class="btn-action btn-more" onclick="showMoreActions(8)">⋮</button>
                </div>
            </div>
        </div>
    </div>

    <style>
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
        }

        .stat-icon.blue { background: #dbeafe; }
        .stat-icon.green { background: #d1fae5; }
        .stat-icon.orange { background: #fed7aa; }
        .stat-icon.purple { background: #e9d5ff; }

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
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
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
            alert('Exporting listings data...');
            console.log('Export listings functionality');
        }

        function addNewListing() {
            alert('Add new listing form will open here');
            console.log('Add new listing functionality');
        }

        function viewListing(id) {
            alert('View listing details for ID: ' + id);
            console.log('Viewing listing:', id);
        }

        function editListing(id) {
            alert('Edit listing form for ID: ' + id);
            console.log('Editing listing:', id);
        }

        function showMoreActions(id) {
            alert('More actions for listing ID: ' + id + '\n- Feature Listing\n- Mark Unavailable\n- Delete Listing\n- View Reports');
            console.log('More actions for listing:', id);
        }

        // Search functionality
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.listing-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    </script>
@endsection