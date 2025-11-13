{{-- resources/views/user/listings.blade.php --}}
@extends('layouts.app')

@section('title', 'My Listings - RentMate')

@php
    $hideSearch = true;
@endphp

@push('styles')
<style>
    .listings-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .page-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 20px;
    }

    .add-listing-btn {
        background: #4461F2;
        color: white;
        padding: 12px 30px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .add-listing-btn:hover {
        background: #3651E2;
        transform: translateY(-2px);
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
        gap: 20px;
    }

    .listing-card {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 20px;
        transition: transform 0.2s;
    }

    .listing-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .listing-image {
        width: 150px;
        height: 150px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .listing-content {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .listing-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 10px;
    }

    .listing-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 5px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-available {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-unavailable {
        background: #fee2e2;
        color: #991b1b;
    }

    .listing-meta {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: #6b7280;
    }

    .listing-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .listing-price {
        font-size: 20px;
        font-weight: 700;
        color: #4461F2;
    }

    .listing-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view {
        background: #4461F2;
        color: white;
    }

    .btn-view:hover {
        background: #3651E2;
    }

    .btn-edit {
        background: #fbbf24;
        color: white;
    }

    .btn-edit:hover {
        background: #f59e0b;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .btn-bookings {
        background: #10b981;
        color: white;
    }

    .btn-bookings:hover {
        background: #059669;
    }

    .empty-state {
        background: white;
        border-radius: 15px;
        padding: 60px 40px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 12px;
    }

    .empty-text {
        font-size: 16px;
        color: #6b7280;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .listings-container {
            padding: 20px 15px;
        }

        .listing-card {
            flex-direction: column;
        }

        .listing-image {
            width: 100%;
            height: 200px;
        }

        .listing-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .listing-actions {
            width: 100%;
        }

        .action-btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="listings-container">
    <div class="page-header">
        <h1 class="page-title"><i class="fas fa-box"></i> My Listings</h1>
        <p class="page-subtitle">{{ $items->total() }} listing(s) found</p>
        <a href="{{ route('user.add-listing') }}" class="add-listing-btn">
            <i class="fas fa-plus"></i> Add New Listing
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-times"></i> {{ session('error') }}
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
        </div>

        <div class="listings-grid">
            @foreach($items as $item)
                <div class="listing-card">
                    @php
                        $firstImage = $item->images->first();
                    @endphp
                    @if($firstImage)
                        <img src="{{ asset('storage/' . $firstImage->ImagePath) }}"
                             alt="{{ $item->ItemName }}"
                             class="listing-image"
                             onerror="this.src='https://via.placeholder.com/150'">
                    @else
                        <img src="https://via.placeholder.com/150"
                             alt="{{ $item->ItemName }}"
                             class="listing-image">
                    @endif

                    <div class="listing-content">
                        <div class="listing-header">
                            <div>
                                <h3 class="listing-title">{{ $item->ItemName }}</h3>
                            </div>
                            <span class="status-badge {{ $item->Availability ? 'badge-available' : 'badge-unavailable' }}">
                                {{ $item->Availability ? 'Available' : 'Unavailable' }}
                            </span>
                        </div>

                        <div class="listing-meta">
                            <div class="meta-item">
                                <span><i class="fas fa-map-marker-alt"></i></span>
                                <span>{{ $item->location->LocationName ?? 'N/A' }}</span>
                            </div>
                            <div class="meta-item">
                                <span><i class="fas fa-tag"></i></span>
                                <span>{{ $item->category->CategoryName ?? 'N/A' }}</span>
                            </div>
                            <div class="meta-item">
                                <span><i class="fas fa-calendar"></i></span>
                                <span>Listed {{ $item->DateAdded->diffForHumans() }}</span>
                            </div>
                            <div class="meta-item">
                                <span><i class="fas fa-calendar-check"></i></span>
                                <span>{{ $item->bookings->count() }} bookings</span>
                            </div>
                            <div class="meta-item">
                                <span><i class="fas fa-star"></i></span>
                                <span>{{ number_format($item->reviews->avg('Rating') ?? 0, 1) }} ({{ $item->reviews->count() }})</span>
                            </div>
                        </div>

                        <div class="listing-footer">
                            <div class="listing-price">
                                RM {{ number_format($item->PricePerDay, 2) }} / day
                            </div>
                            <div class="listing-actions">
                                <a href="{{ route('user.listings.bookings', $item->ItemID) }}" class="action-btn btn-bookings">
                                    <i class="fas fa-calendar-check"></i> Bookings
                                </a>
                                <a href="{{ route('item.details', $item->ItemID) }}" class="action-btn btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('items.edit', $item->ItemID) }}" class="action-btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('items.destroy', $item->ItemID) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete(event, '{{ $item->ItemName }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($items->hasPages())
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $items->links() }}
            </div>
        @endif
    @else
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-box"></i></div>
            <h2 class="empty-title">No Listings Yet</h2>
            <p class="empty-text">You haven't created any listings yet. Start earning by listing your first item!</p>
            <a href="{{ route('user.add-listing') }}" class="add-listing-btn">
                <i class="fas fa-plus"></i> Add Your First Listing
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