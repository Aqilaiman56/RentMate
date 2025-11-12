@extends('layouts.app')

@section('title', 'My Penalties - GoRentUMS')

@php
    $hideSearch = true;
@endphp

@push('styles')
<style>
    .penalties-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .page-header {
        margin-bottom: 40px;
    }

    .page-title {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .page-subtitle {
        font-size: 16px;
        color: #6b7280;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 16px;
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

    .stat-icon.red {
        background: #fee2e2;
        color: #dc2626;
    }

    .stat-icon.orange {
        background: #fed7aa;
        color: #ea580c;
    }

    .stat-icon.purple {
        background: #e9d5ff;
        color: #9333ea;
    }

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

    /* Penalties List */
    .penalties-list {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .list-header {
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .list-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .penalty-item {
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s;
    }

    .penalty-item:last-child {
        border-bottom: none;
    }

    .penalty-item:hover {
        background: #f9fafb;
    }

    .penalty-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 16px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .penalty-id {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }

    .penalty-status {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-resolved {
        background: #d1fae5;
        color: #065f46;
    }

    .penalty-details {
        margin-bottom: 16px;
    }

    .penalty-description {
        font-size: 14px;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .penalty-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        font-size: 14px;
        color: #6b7280;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .meta-item i {
        font-size: 14px;
    }

    .penalty-amount {
        font-size: 24px;
        font-weight: 700;
        color: #dc2626;
    }

    .penalty-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
        flex-wrap: wrap;
        gap: 12px;
    }

    .evidence-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #3b82f6;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .evidence-link:hover {
        text-decoration: underline;
    }

    .no-penalties {
        text-align: center;
        padding: 60px 20px;
        color: #6b7280;
    }

    .no-penalties i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .no-penalties h3 {
        font-size: 20px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
    }

    .no-penalties p {
        font-size: 14px;
        color: #6b7280;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border-left: 4px solid #f59e0b;
    }

    @media (max-width: 768px) {
        .penalties-container {
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .penalty-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .penalty-meta {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="penalties-container">
    <div class="page-header">
        <h1 class="page-title">My Penalties</h1>
        <p class="page-subtitle">View and manage your penalty history</p>
    </div>

    @if($pendingPenalties > 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <span>You have {{ $pendingPenalties }} pending {{ Str::plural('penalty', $pendingPenalties) }} that require payment.</span>
        </div>
    @endif

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $totalPenalties }}</div>
                <div class="stat-label">Total Penalties</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $pendingPenalties }}</div>
                <div class="stat-label">Pending Payment</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon purple">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value">RM {{ number_format($totalAmount, 2) }}</div>
                <div class="stat-label">Outstanding Amount</div>
            </div>
        </div>
    </div>

    <!-- Penalties List -->
    <div class="penalties-list">
        <div class="list-header">
            <h2 class="list-title">Penalty History</h2>
        </div>

        @forelse($penalties as $penalty)
            <div class="penalty-item">
                <div class="penalty-header">
                    <div class="penalty-id">
                        Penalty #P{{ str_pad($penalty->PenaltyID, 3, '0', STR_PAD_LEFT) }}
                    </div>
                    <span class="penalty-status {{ $penalty->ResolvedStatus ? 'status-resolved' : 'status-pending' }}">
                        {{ $penalty->ResolvedStatus ? 'Resolved' : 'Pending Payment' }}
                    </span>
                </div>

                <div class="penalty-details">
                    <p class="penalty-description">{{ $penalty->Description }}</p>

                    <div class="penalty-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>Issued: {{ $penalty->DateReported->format('M d, Y') }}</span>
                        </div>
                        @if($penalty->reportedBy)
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span>Reported by: {{ $penalty->reportedBy->UserName }}</span>
                            </div>
                        @endif
                        @if($penalty->booking)
                            <div class="meta-item">
                                <i class="fas fa-calendar-check"></i>
                                <span>Booking: #B{{ str_pad($penalty->booking->BookingID, 3, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        @endif
                        @if($penalty->item)
                            <div class="meta-item">
                                <i class="fas fa-box"></i>
                                <span>Item: {{ $penalty->item->ItemName }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="penalty-footer">
                    <div class="penalty-amount">RM {{ number_format($penalty->PenaltyAmount, 2) }}</div>
                    @if($penalty->EvidencePath)
                        <a href="{{ asset('storage/' . $penalty->EvidencePath) }}" target="_blank" class="evidence-link">
                            <i class="fas fa-image"></i>
                            View Evidence
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="no-penalties">
                <i class="fas fa-check-circle"></i>
                <h3>No Penalties</h3>
                <p>You have no penalty records. Keep up the good work!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
