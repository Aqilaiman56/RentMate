{{-- resources/views/user/listings.blade.php --}}
@extends('layouts.app')

@section('title', 'My Listings - RentMate')

@php($hideSearch = true)

@push('styles')
<style>
    .listings-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
    }

    .add-listing-btn {
        background: #4461F2;
        color: white;
        padding: 12px 24px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .add-listing-btn:hover {
        background: #3651E2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
    }

    .alert {
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-info {
        background: #e8eeff;
        color: #1e3a8a;
        border: 1px solid #4461F2;
        text-align: center;
        padding: 40px;
    }

    .alert-info p {
        margin-bottom: 15px;
        font-size: 16px;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .stat-label {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }

    .listings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 25px;
    }

    .listing-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s;
        position: relative;
    }

    .listing-card:hover {
        transform: translateY(-5px);
    }

    .listing-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f3f4f6;
    }

    .listing-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-available {
        background: #10b981;
        color: white;
    }

    .badge-unavailable {
        background: #ef4444;
        color: white;
    }

    .listing-content {
        padding: 20px;
    }

    .listing-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .listing-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 15px;
    }

    .listing-detail {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6b7280;
    }

    .listing-price {
        font-size: 20px;
        font-weight: 700;
        color: #4461F2;
        margin-bottom: 15px;
    }

    .listing-stats {
        display: flex;
        gap: 15px;
        padding: 12px 0;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 15px;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #6b7280;
    }

    .listing-actions {
        display: flex;
        gap: 10px;
    }

    .action-btn {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.3s;
    }

    .btn-view {
        background: #e8eeff;
        color: #4461F2;
    }

    .btn-view:hover {
        background: #d0ddff;
    }

    .btn-edit {
        background: #fef3c7;
        color: #d97706;
    }

    .btn-edit:hover {
        background: #fde68a;
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-delete:hover {
        background: #fecaca;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .empty-text {
        color: #6b7280;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .add-listing-btn {
            justify-content: center;
        }

        .listings-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="listings-container">
    <div class="page-header">
        <h1 class="page-title">My Listings</h1>
        <a href="{{ route('user.add-listing') }}" class="add-listing-btn">
            <span>➕</span>
            Add New Listing
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($items->count() > 0)
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-label">Total Listings</div>
                <div class="stat-value">{{ $items->total() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Available</div>
                <div class="stat-value">{{ $items->where('Availability', 1)->count() }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Bookings</div>
                <div class="stat-value">{{ $items->sum(fn($item) => $item->bookings->count()) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">RM {{ number_format($items->sum('PricePerDay') * 30, 2) }}</div>
            </div>
        </div>

        <div class="listings-grid">
            @foreach($items as $item)
                <div class="listing-card">
                    @if($item->ImagePath)
                        <img src="{{ asset('storage/' . $item->ImagePath) }}" alt="{{ $item->ItemName }}" class="listing-image" onerror="this.src='https://via.placeholder.com/300x200/4461F2/fff?text={{ urlencode($item->ItemName) }}'">
                    @else
                        <img src="https://via.placeholder.com/300x200/4461F2/fff?text={{ urlencode($item->ItemName) }}" alt="{{ $item->ItemName }}" class="listing-image">
                    @endif
                    
                    <span class="listing-badge {{ $item->Availability ? 'badge-available' : 'badge-unavailable' }}">
                        {{ $item->Availability ? 'Available' : 'Unavailable' }}
                    </span>

                    <div class="listing-content">
                        <h3 class="listing-title">{{ $item->ItemName }}</h3>
                        
                        <div class="listing-details">
                            <div class="listing-detail">
                                <span>📍</span>
                                <span>{{ $item->location->LocationName ?? 'N/A' }}</span>
                            </div>
                            <div class="listing-detail">
                                <span>🏷️</span>
                                <span>{{ $item->category->CategoryName ?? 'N/A' }}</span>
                            </div>
                            <div class="listing-detail">
                                <span>📅</span>
                                <span>Listed {{ $item->DateAdded->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="listing-price">
                            RM {{ number_format($item->PricePerDay, 2) }} / day
                        </div>

                        <div class="listing-stats">
                            <div class="stat-item">
                                <span>📅</span>
                                <span>{{ $item->bookings->count() }} bookings</span>
                            </div>
                            <div class="stat-item">
                                <span>⭐</span>
                                <span>{{ number_format($item->reviews->avg('Rating') ?? 0, 1) }} ({{ $item->reviews->count() }})</span>
                            </div>
                        </div>

                        <div class="listing-actions">
                            <a href="{{ route('item.details', $item->ItemID) }}" class="action-btn btn-view">
                                👁️ View
                            </a>
                            <a href="{{ route('items.edit', $item->ItemID) }}" class="action-btn btn-edit">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('items.destroy', $item->ItemID) }}" method="POST" style="flex: 1;" onsubmit="return confirmDelete(event, '{{ $item->ItemName }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete" style="width: 100%;">
                                    🗑️ Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($items->hasPages())
            <div style="margin-top: 40px; display: flex; justify-content: center;">
                {{ $items->links() }}
            </div>
        @endif
    @else
        <div class="alert alert-info">
            <div class="empty-icon">📦</div>
            <div class="empty-title">No Listings Yet</div>
            <p class="empty-text">You haven't created any listings yet. Start earning by listing your first item!</p>
            <a href="{{ route('user.add-listing') }}" class="add-listing-btn">
                <span>➕</span>
                Add Your First Listing
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(event, itemName) {
        event.preventDefault();
        
        if (confirm(`Are you sure you want to delete "${itemName}"?\n\nThis action cannot be undone.`)) {
            event.target.submit();
        }
        
        return false;
    }
</script>
@endpush