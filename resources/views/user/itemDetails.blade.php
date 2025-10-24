<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $item->ItemName }} - RentMate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F8F9FA;
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

        .search-bar {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .search-input {
            padding: 12px 20px;
            border: 2px solid #E5E7EB;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            min-width: 150px;
        }

        .search-btn {
            background: #4461F2;
            color: white;
            padding: 12px 32px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
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
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
        }

        .breadcrumb {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 40px;
            display: flex;
            gap: 10px;
            font-size: 14px;
            color: #6B7280;
        }

        .breadcrumb a {
            color: #6B7280;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #4461F2;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px 40px;
        }

        .item-title {
            text-align: center;
            margin-bottom: 10px;
        }

        .item-title h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1E3A5F;
            margin-bottom: 5px;
        }

        .item-location {
            text-align: center;
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
        }

        .left-column {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .image-gallery {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .main-image {
            width: 100%;
            height: 400px;
            object-fit: contain;
            border-radius: 10px;
            background: #F9FAFB;
            margin-bottom: 20px;
        }

        .thumbnail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .thumbnail {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .thumbnail:hover {
            border-color: #4461F2;
        }

        .about-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .about-section h2 {
            font-size: 20px;
            font-weight: 700;
            color: #1E3A5F;
            margin-bottom: 15px;
        }

        .about-section p {
            color: #4B5563;
            line-height: 1.6;
            font-size: 14px;
        }

        .owner-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .owner-pic {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        .owner-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1E3A5F;
            margin-bottom: 3px;
        }

        .owner-info p {
            font-size: 13px;
            color: #6B7280;
        }

        .message-icon {
            margin-left: auto;
            background: #F3F4F6;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 20px;
        }

        .reviews-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .rating-summary {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #E5E7EB;
        }

        .rating-number {
            font-size: 48px;
            font-weight: 700;
            color: #1E3A5F;
        }

        .rating-bars {
            flex: 1;
        }

        .rating-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .bar {
            flex: 1;
            height: 8px;
            background: #E5E7EB;
            border-radius: 4px;
            overflow: hidden;
        }

        .bar-fill {
            height: 100%;
            background: #FFC107;
            border-radius: 4px;
        }

        .reviews-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .reviews-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #1E3A5F;
        }

        .add-review-link {
            color: #4461F2;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
        }

        .review-item {
            border-bottom: 1px solid #E5E7EB;
            padding: 20px 0;
        }

        .review-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .reviewer-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #E5E7EB;
        }

        .reviewer-name {
            font-weight: 600;
            color: #1E3A5F;
            font-size: 14px;
        }

        .review-date {
            color: #9CA3AF;
            font-size: 12px;
        }

        .review-rating {
            color: #FFC107;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .review-text {
            color: #4B5563;
            font-size: 14px;
            line-height: 1.5;
        }

        .right-column {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .calendar-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-title {
            font-size: 16px;
            font-weight: 600;
            color: #1E3A5F;
        }

        .calendar-nav {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #6B7280;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-bottom: 15px;
        }

        .calendar-day-header {
            text-align: center;
            font-size: 12px;
            color: #6B7280;
            font-weight: 500;
            padding: 8px 0;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .calendar-day.available {
            background: #D1FAE5;
            color: #065F46;
        }

        .calendar-day.booked {
            background: #FEE2E2;
            color: #991B1B;
        }

        .calendar-day.selected {
            background: #4461F2;
            color: white;
        }

        .calendar-day:hover:not(.booked) {
            transform: scale(1.1);
        }

        .calendar-legend {
            display: flex;
            gap: 15px;
            font-size: 12px;
            margin-top: 15px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .wishlist-btn {
            background: #4461F2;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.3s;
        }

        .wishlist-btn:hover {
            background: #3651E2;
        }

        .message-btn {
            background: #4461F2;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .message-btn:hover {
            background: #3651E2;
        }

        .booking-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .booking-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1E3A5F;
            margin-bottom: 15px;
        }

        .price-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .price-label {
            color: #6B7280;
            font-size: 14px;
        }

        .price-value {
            font-weight: 600;
            color: #10B981;
            font-size: 18px;
        }

        .deposit-value {
            color: #10B981;
            font-size: 16px;
        }

        .book-now-btn {
            width: 100%;
            background: #4461F2;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .book-now-btn:hover {
            background: #3651E2;
        }

        @media (max-width: 1024px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('home') }}" class="logo">RentMate</a>
        
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Availability">
            <input type="text" class="search-input" placeholder="Item name">
            <input type="text" class="search-input" placeholder="Select Location">
            <button class="search-btn">Search</button>
        </div>

        <div class="header-icons">
            <button class="icon-btn">🔔</button>
            <button class="icon-btn">✉️</button>
            <img src="https://via.placeholder.com/40" alt="Profile" class="profile-pic">
        </div>
    </header>

    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <span>Item Details</span>
    </div>

    <div class="container">
        <div class="item-title">
            <h1>{{ strtoupper($item->ItemName) }}</h1>
        </div>
        <div class="item-location">{{ $item->location->LocationName ?? 'Location' }}</div>

        <div class="content-wrapper">
            <div class="left-column">
                <!-- Image Gallery -->
                <div class="image-gallery">
                    @if($item->images->count() > 0)
                        <img src="{{ asset('storage/' . $item->images->first()->ImagePath) }}" alt="{{ $item->ItemName }}" class="main-image" id="mainImage">
                        <div class="thumbnail-grid">
                            @foreach($item->images as $image)
                                <img src="{{ asset('storage/' . $image->ImagePath) }}"
                                     alt="View {{ $loop->iteration }}"
                                     class="thumbnail"
                                     onclick="changeImage(this.src)">
                            @endforeach
                        </div>
                    @else
                        <img src="https://via.placeholder.com/600x400/4461F2/fff?text={{ urlencode($item->ItemName) }}"
                             alt="{{ $item->ItemName }}"
                             class="main-image"
                             id="mainImage">
                    @endif
                </div>

                <!-- About Section -->
                <div class="about-section">
                    <h2>About the Item</h2>
                    <p>{{ $item->Description }}</p>
                </div>

                <!-- Owner Section -->
                <div class="owner-section">
                    <img src="https://via.placeholder.com/60" alt="{{ $item->user->name ?? 'Owner' }}" class="owner-pic">
                    <div class="owner-info">
                        <h3>{{ $item->user->name ?? 'Owner Name' }}</h3>
                        <p>{{ $item->user->email ?? 'owner@email.com' }}</p>
                    </div>
                    <div class="message-icon">✉️</div>
                </div>

                <!-- Reviews Section -->
                <div class="reviews-section">
                    <div class="rating-summary">
                        <div class="rating-number">4.5 ⭐</div>
                        <div class="rating-bars">
                            <div class="rating-bar">
                                <span>5</span>
                                <div class="bar"><div class="bar-fill" style="width: 80%"></div></div>
                                <span>200</span>
                            </div>
                            <div class="rating-bar">
                                <span>4</span>
                                <div class="bar"><div class="bar-fill" style="width: 60%"></div></div>
                                <span>150</span>
                            </div>
                            <div class="rating-bar">
                                <span>3</span>
                                <div class="bar"><div class="bar-fill" style="width: 30%"></div></div>
                                <span>75</span>
                            </div>
                            <div class="rating-bar">
                                <span>2</span>
                                <div class="bar"><div class="bar-fill" style="width: 15%"></div></div>
                                <span>38</span>
                            </div>
                            <div class="rating-bar">
                                <span>1</span>
                                <div class="bar"><div class="bar-fill" style="width: 5%"></div></div>
                                <span>13</span>
                            </div>
                        </div>
                    </div>

                    <div class="reviews-header">
                        <h3>All Reviews (2)</h3>
                        <a href="#" class="add-review-link">+ Add Review</a>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-pic"></div>
                            <div>
                                <div class="reviewer-name">Anonymous User</div>
                                <div class="review-date">Posted on 15 Jun 2025</div>
                            </div>
                        </div>
                        <div class="review-rating">⭐⭐⭐⭐⭐ 5</div>
                        <div class="review-text">Kualiti okay, Barang masikg great, reject high sense camera nie</div>
                    </div>

                    <div class="review-item">
                        <div class="review-header">
                            <div class="reviewer-pic"></div>
                            <div>
                                <div class="reviewer-name">Rojii</div>
                                <div class="review-date">Posted on 20 May 2025</div>
                            </div>
                        </div>
                        <div class="review-rating">⭐⭐⭐⭐⭐ 5</div>
                        <div class="review-text">Barang baik harga berpatutan</div>
                    </div>
                </div>
            </div>

            <div class="right-column">
                <!-- Calendar Section -->
                <div class="calendar-section">
                    <div class="calendar-header">
                        <div class="calendar-title">Availability</div>
                        <div class="calendar-nav">
                            <button class="nav-btn" onclick="changeMonth(-1)">‹</button>
                            <span id="currentMonth">June 2025</span>
                            <button class="nav-btn" onclick="changeMonth(1)">›</button>
                        </div>
                    </div>

                    <div class="calendar-grid">
                        <div class="calendar-day-header">S</div>
                        <div class="calendar-day-header">M</div>
                        <div class="calendar-day-header">T</div>
                        <div class="calendar-day-header">W</div>
                        <div class="calendar-day-header">T</div>
                        <div class="calendar-day-header">F</div>
                        <div class="calendar-day-header">S</div>

                        <div class="calendar-day available">1</div>
                        <div class="calendar-day available">2</div>
                        <div class="calendar-day available">3</div>
                        <div class="calendar-day available">4</div>
                        <div class="calendar-day available">5</div>
                        <div class="calendar-day available">6</div>
                        <div class="calendar-day available">7</div>
                        <div class="calendar-day available">8</div>
                        <div class="calendar-day available">9</div>
                        <div class="calendar-day available">10</div>
                        <div class="calendar-day available">11</div>
                        <div class="calendar-day available">12</div>
                        <div class="calendar-day available">13</div>
                        <div class="calendar-day available">14</div>
                        <div class="calendar-day available">15</div>
                        <div class="calendar-day available">16</div>
                        <div class="calendar-day available">17</div>
                        <div class="calendar-day available">18</div>
                        <div class="calendar-day available">19</div>
                        <div class="calendar-day available">20</div>
                        <div class="calendar-day available">21</div>
                        <div class="calendar-day available">22</div>
                        <div class="calendar-day booked">23</div>
                        <div class="calendar-day booked">24</div>
                        <div class="calendar-day booked">25</div>
                        <div class="calendar-day booked">26</div>
                        <div class="calendar-day available">27</div>
                        <div class="calendar-day available">28</div>
                        <div class="calendar-day available">29</div>
                        <div class="calendar-day available">30</div>
                    </div>

                    <div class="calendar-legend">
                        <div class="legend-item">
                            <div class="legend-color" style="background: #D1FAE5;"></div>
                            <span>Available</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color" style="background: #FEE2E2;"></div>
                            <span>Booked</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="wishlist-btn" onclick="addToWishlist({{ $item->ItemID }})">
                        <span>Add to wishlist</span>
                        <span>💙</span>
                    </button>
                    <button class="message-btn" onclick="messageOwner({{ $item->UserID }})">Message Now !</button>
                </div>

                <!-- Booking Section -->
                <div class="booking-section">
                    <h3>Start Booking</h3>
                    <div class="price-item">
                        <span class="price-label">Price</span>
                        <span class="price-value">RM{{ $item->PricePerDay ?? '100' }} per Day</span>
                    </div>
                    <div class="price-item">
                        <span class="price-label">Deposit</span>
                        <span class="deposit-value">RM{{ $item->DepositAmount }}</span>
                    </div>
                    @php
                        $isOwner = auth()->id() == $item->UserID;
                    @endphp

                    @if($isOwner)
                        <div style="text-align: center; padding: 15px; background: #f3f4f6; border-radius: 10px;">
                            <p style="color: #6b7280; font-weight: 500; margin: 0;">This is your listing</p>
                            <p style="color: #9ca3af; font-size: 14px; margin: 5px 0 0 0;">You cannot book your own item</p>
                        </div>
                    @else
                        <form action="{{ route('booking.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="item_id" value="{{ $item->ItemID }}">
                            <button type="submit" class="book-now-btn">Book Now!</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }

        function changeMonth(direction) {
            // Implement month navigation logic
            console.log('Change month by:', direction);
        }

        function addToWishlist(itemId) {
            fetch(`/wishlist/add/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                alert('Added to wishlist!');
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function messageOwner(userId) {
            window.location.href = `/messages/${userId}`;
        }

        // Calendar day selection
        document.querySelectorAll('.calendar-day').forEach(day => {
            day.addEventListener('click', function() {
                if (!this.classList.contains('booked')) {
                    document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                    this.classList.add('selected');
                }
            });
        });
    </script>
</body>
</html>