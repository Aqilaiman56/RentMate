@extends('layouts.app')

@section('title', 'Report Summary - GoRentUMS')

@php
    $hideSearch = true;
@endphp

@push('styles')
<style>
    .report-summary-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
        transition: color 0.2s;
    }

    .back-link:hover {
        color: #4461F2;
    }

    .report-header {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .report-title-section {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 20px;
    }

    .report-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 10px 0;
    }

    .report-id {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: #fed7aa;
        color: #92400e;
    }

    .status-under-review {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-resolved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-dismissed {
        background: #e5e7eb;
        color: #374151;
    }

    .report-meta {
        display: flex;
        gap: 30px;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .meta-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 15px;
        color: #1f2937;
        font-weight: 500;
    }

    .report-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e5e7eb;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-item.full-width {
        grid-column: 1 / -1;
    }

    .info-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 15px;
        color: #1f2937;
        font-weight: 500;
    }

    .user-info-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .user-avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #4461F2;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .user-details {
        flex: 1;
    }

    .user-name {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .user-email {
        font-size: 14px;
        color: #6b7280;
    }

    .description-text {
        font-size: 15px;
        line-height: 1.7;
        color: #374151;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    .evidence-image {
        max-width: 100%;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-top: 15px;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .alert-info {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #bfdbfe;
    }

    .alert-warning {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .priority-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .priority-high {
        background: #fee2e2;
        color: #991b1b;
    }

    .priority-medium {
        background: #fed7aa;
        color: #92400e;
    }

    .priority-low {
        background: #e0e7ff;
        color: #3730a3;
    }

    .admin-notes-box {
        background: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 20px;
        border-radius: 8px;
        margin-top: 15px;
    }

    .admin-notes-title {
        font-size: 14px;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .admin-notes-text {
        font-size: 14px;
        color: #1f2937;
        line-height: 1.6;
        white-space: pre-wrap;
    }

    @media (max-width: 768px) {
        .report-summary-container {
            padding: 20px 15px;
        }

        .report-header,
        .report-card {
            padding: 20px;
        }

        .report-title {
            font-size: 22px;
        }

        .report-title-section {
            flex-direction: column;
            gap: 15px;
        }

        .report-meta {
            flex-direction: column;
            gap: 15px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .user-info-card {
            flex-direction: column;
            text-align: center;
        }
    }
</style>
@endpush

@section('content')
<div class="report-summary-container">
    <a href="{{ route('notifications.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Notifications
    </a>

    @if($isReporter)
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            This is a summary of the report you submitted. The admin team is reviewing your case.
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            A report has been filed against you. Please review the details below.
        </div>
    @endif

    <!-- Report Header -->
    <div class="report-header">
        <div class="report-title-section">
            <div>
                <h1 class="report-title">{{ $report->Subject }}</h1>
                <p class="report-id">Report #{{ str_pad($report->ReportID, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $report->Status)) }}">
                    {{ ucfirst($report->Status) }}
                </span>
            </div>
        </div>

        <div class="report-meta">
            <div class="meta-item">
                <span class="meta-label">Report Type</span>
                <span class="meta-value">{{ ucwords(str_replace('-', ' ', $report->ReportType)) }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Priority</span>
                <span class="meta-value">
                    <span class="priority-badge priority-{{ strtolower($report->Priority) }}">
                        @if($report->Priority === 'high')
                            <i class="fas fa-exclamation-circle"></i>
                        @elseif($report->Priority === 'medium')
                            <i class="fas fa-exclamation-triangle"></i>
                        @else
                            <i class="fas fa-info-circle"></i>
                        @endif
                        {{ ucfirst($report->Priority) }}
                    </span>
                </span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Date Reported</span>
                <span class="meta-value">{{ $report->DateReported ? $report->DateReported->format('M d, Y') : 'N/A' }}</span>
            </div>
            @if($report->DateResolved)
                <div class="meta-item">
                    <span class="meta-label">Date Resolved</span>
                    <span class="meta-value">{{ $report->DateResolved->format('M d, Y') }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Parties Involved -->
    <div class="report-card">
        <h2 class="card-title"><i class="fas fa-users"></i> Parties Involved</h2>

        <div class="info-grid">
            <div class="info-item full-width">
                <span class="info-label">Reported By</span>
                <div class="user-info-card">
                    <div class="user-avatar-placeholder" style="background: #9ca3af;">
                        <i class="fas fa-user-secret"></i>
                    </div>
                    <div class="user-details">
                        <div class="user-name" style="color: #6b7280; font-style: italic;">Anonymous</div>
                        <div class="user-email" style="color: #9ca3af;">Identity protected for privacy</div>
                    </div>
                </div>
            </div>

            @if($report->reportedUser)
                <div class="info-item full-width">
                    <span class="info-label">Reported User</span>
                    <div class="user-info-card">
                        @if($report->reportedUser->ProfileImage)
                            <img src="{{ asset('storage/' . $report->reportedUser->ProfileImage) }}"
                                 alt="{{ $report->reportedUser->UserName }}"
                                 class="user-avatar">
                        @else
                            <div class="user-avatar-placeholder">
                                {{ strtoupper(substr($report->reportedUser->UserName, 0, 2)) }}
                            </div>
                        @endif
                        <div class="user-details">
                            <div class="user-name">{{ $report->reportedUser->UserName }}</div>
                            <div class="user-email">{{ $report->reportedUser->Email }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Report Details -->
    <div class="report-card">
        <h2 class="card-title"><i class="fas fa-file-alt"></i> Report Details</h2>

        <div class="info-grid">
            @if($report->booking)
                <div class="info-item full-width">
                    <span class="info-label">Related Booking</span>
                    <span class="info-value">
                        Booking #{{ $report->BookingID }}
                        @if($report->booking->item)
                            - {{ $report->booking->item->ItemName }}
                        @endif
                    </span>
                </div>
            @endif

            <div class="info-item full-width">
                <span class="info-label">Description</span>
                <p class="description-text">{{ $report->Description }}</p>
            </div>

            @if($report->EvidencePath)
                <div class="info-item full-width">
                    <span class="info-label">Evidence</span>
                    <img src="{{ asset('storage/' . $report->EvidencePath) }}"
                         alt="Evidence"
                         class="evidence-image">
                </div>
            @endif
        </div>
    </div>

    <!-- Admin Review -->
    @if($report->AdminNotes || $report->reviewer)
        <div class="report-card">
            <h2 class="card-title"><i class="fas fa-user-shield"></i> Admin Review</h2>

            @if($report->reviewer)
                <div class="info-item">
                    <span class="info-label">Reviewed By</span>
                    <span class="info-value">{{ $report->reviewer->UserName }}</span>
                </div>
            @endif

            @if($report->AdminNotes)
                <div class="admin-notes-box">
                    <div class="admin-notes-title">
                        <i class="fas fa-sticky-note"></i> Admin Notes
                    </div>
                    <div class="admin-notes-text">{{ $report->AdminNotes }}</div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
