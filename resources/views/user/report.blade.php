@extends('layouts.app')

@section('title', 'Report Issue - GoRentUMS')

@php
    $hideSearch = true;
@endphp

@push('styles')
<style>
    .report-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .report-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .report-title {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .report-subtitle {
        font-size: 16px;
        color: #6b7280;
    }

    /* Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-label.required::after {
        content: ' *';
        color: #ef4444;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.3s;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #4461F2;
        box-shadow: 0 0 0 3px rgba(68, 97, 242, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 150px;
    }

    .form-hint {
        font-size: 12px;
        color: #6b7280;
        margin-top: 6px;
    }

    /* Upload Area */
    .upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        transition: all 0.3s;
        cursor: pointer;
        background: #f9fafb;
    }

    .upload-area:hover {
        border-color: #4461F2;
        background: #f5f7ff;
    }

    .upload-area.dragover {
        border-color: #4461F2;
        background: #eff6ff;
    }

    .upload-icon {
        font-size: 48px;
        color: #9ca3af;
        margin-bottom: 15px;
    }

    .upload-text {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 8px;
    }

    .upload-text strong {
        color: #4461F2;
        cursor: pointer;
    }

    .file-input {
        display: none;
    }

    /* Image Preview */
    .image-preview-container {
        display: none;
        margin-top: 15px;
        position: relative;
    }

    .image-preview {
        max-width: 100%;
        border-radius: 12px;
        max-height: 300px;
        object-fit: cover;
    }

    .remove-image {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        transition: all 0.2s;
    }

    .remove-image:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    /* Buttons */
    .button-group {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        justify-content: flex-end;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #4461F2;
        color: white;
    }

    .btn-primary:hover {
        background: #3651E2;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(68, 97, 242, 0.3);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #6b7280;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn:disabled:hover {
        transform: none;
        box-shadow: none;
    }

    /* Alert Messages */
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
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

    @media (max-width: 768px) {
        .report-container {
            padding: 20px 15px;
        }

        .form-card {
            padding: 25px 20px;
        }

        .button-group {
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .btn {
            width: 100%;
            max-width: 300px;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="report-container">
    <div class="report-header">
        <h1 class="report-title">REPORT YOUR ISSUE</h1>
        <p class="report-subtitle">Report to admin about your issues</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-times-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <form id="reportForm" action="{{ route('user.report.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-card">
            <div class="form-group">
                <label for="reportType" class="form-label required">Report Type</label>
                <select id="reportType" name="ReportType" class="form-select" required>
                    <option value="">Select report type</option>
                    <option value="item-damage">Item Damage</option>
                    <option value="late-return">Late Return</option>
                    <option value="dispute">Dispute</option>
                    <option value="fraud">Fraud</option>
                    <option value="harassment">Harassment</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="subject" class="form-label required">Subject</label>
                <input type="text" id="subject" name="Subject" class="form-input"
                       placeholder="Brief title of your issue" required maxlength="255" value="{{ old('Subject') }}">
            </div>

            <div class="form-group">
                <label for="reportedUser" class="form-label">Reported User (Optional)</label>
                <select id="reportedUser" name="ReportedUserID" class="form-select">
                    <option value="">Select a user if applicable</option>
                    @forelse($users ?? [] as $user)
                        <option value="{{ $user->UserID }}" {{ old('ReportedUserID') == $user->UserID ? 'selected' : '' }}>
                            {{ $user->UserName }} ({{ $user->Email }})
                        </option>
                    @empty
                        <option value="" disabled>No users to report (you haven't interacted with anyone yet)</option>
                    @endforelse
                </select>
                <p class="form-hint">Only users you've interacted with (as renter or lister) will appear here</p>
            </div>

            <div class="form-group">
                <label for="booking" class="form-label">Related Booking (Optional)</label>
                <select id="booking" name="BookingID" class="form-select" onchange="updateReportedUserFromBooking()">
                    <option value="">Select a booking if applicable</option>
                    @forelse($bookings ?? [] as $booking)
                        @php
                            $isRenter = $booking->UserID == auth()->id();
                            $otherParty = $isRenter
                                ? ($booking->item->user->UserName ?? 'Unknown')
                                : ($booking->user->UserName ?? 'Unknown');
                            $role = $isRenter ? 'You rented from' : 'Rented by';
                            $dates = $booking->StartDate->format('M d') . ' - ' . $booking->EndDate->format('M d, Y');
                        @endphp
                        <option value="{{ $booking->BookingID }}"
                                data-other-user="{{ $isRenter ? ($booking->item->UserID ?? '') : ($booking->UserID ?? '') }}"
                                {{ old('BookingID') == $booking->BookingID ? 'selected' : '' }}>
                            #{{ $booking->BookingID }} - {{ $booking->item->ItemName ?? 'N/A' }} ({{ $role }} {{ $otherParty }}, {{ $dates }})
                        </option>
                    @empty
                        <option value="" disabled>No bookings found</option>
                    @endforelse
                </select>
                <p class="form-hint">Select a related booking to auto-fill the reported user</p>
            </div>

            <div class="form-group">
                <label for="description" class="form-label required">Report Description</label>
                <textarea id="description" name="Description" class="form-textarea"
                          placeholder="Describe your report in details - condition, features, and etc."
                          required minlength="20" maxlength="2000">{{ old('Description') }}</textarea>
                <p class="form-hint">Minimum 20 characters, maximum 2000 characters</p>
            </div>

            <div class="form-group">
                <label class="form-label">Evidence Photo (Optional)</label>
                <div class="upload-area" id="uploadArea" onclick="document.getElementById('evidenceFile').click()">
                    <div class="upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <p class="upload-text">
                        <strong>Choose file</strong> or drag and drop here
                    </p>
                    <p class="form-hint">Please upload square image, size less than 10MB</p>
                </div>
                <input type="file" id="evidenceFile" name="EvidencePath" class="file-input"
                       accept="image/jpeg,image/png,image/jpg,image/gif" onchange="previewImage(event)">

                <div class="image-preview-container" id="imagePreviewContainer">
                    <img id="imagePreview" class="image-preview" src="" alt="Evidence preview">
                    <button type="button" class="remove-image" onclick="removeImage()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Report
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Auto-fill reported user when booking is selected
    function updateReportedUserFromBooking() {
        const bookingSelect = document.getElementById('booking');
        const reportedUserSelect = document.getElementById('reportedUser');

        if (bookingSelect.value) {
            const selectedOption = bookingSelect.options[bookingSelect.selectedIndex];
            const otherUserId = selectedOption.getAttribute('data-other-user');

            if (otherUserId) {
                for (let i = 0; i < reportedUserSelect.options.length; i++) {
                    if (reportedUserSelect.options[i].value == otherUserId) {
                        reportedUserSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        }
    }

    // Image preview
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 10485760) {
                alert('File size must be less than 10MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewContainer').style.display = 'block';
                document.getElementById('uploadArea').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('evidenceFile').value = '';
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.getElementById('uploadArea').style.display = 'block';
    }

    // Drag and drop
    const uploadArea = document.getElementById('uploadArea');

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');

        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            document.getElementById('evidenceFile').files = e.dataTransfer.files;
            previewImage({ target: { files: [file] } });
        }
    });
</script>
@endpush
