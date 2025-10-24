{{-- resources/views/user/add-listing.blade.php --}}
@extends('layouts.app')

@section('title', 'Add New Listing - RentMate')

@php($hideSearch = true)

@push('styles')
<style>
    .add-listing-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 10px;
    }

    .page-subtitle {
        color: #6b7280;
        margin-bottom: 30px;
    }

    .form-card {
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #4461F2;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .required {
        color: #dc3545;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.3s;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #4461F2;
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-help {
        font-size: 12px;
        color: #6b7280;
        margin-top: 5px;
    }

    .image-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #f9fafb;
    }

    .image-upload-area:hover {
        border-color: #4461F2;
        background: #f0f4ff;
    }

    .image-upload-area.dragover {
        border-color: #4461F2;
        background: #e8eeff;
    }

    .upload-icon {
        font-size: 48px;
        margin-bottom: 15px;
    }

    .upload-text {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .upload-hint {
        font-size: 12px;
        color: #9ca3af;
    }

    .images-preview-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 20px;
    }

    .images-preview-container.hidden {
        display: none;
    }

    .image-preview-item {
        position: relative;
        aspect-ratio: 4/3;
        border-radius: 10px;
        overflow: hidden;
    }

    .preview-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .remove-image {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #dc3545;
        color: white;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
        z-index: 10;
    }

    .remove-image:hover {
        background: #c82333;
    }

    .image-number {
        position: absolute;
        bottom: 8px;
        left: 8px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .price-input-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .price-prefix {
        font-size: 16px;
        font-weight: 600;
        color: #374151;
    }

    .price-suffix {
        font-size: 14px;
        color: #6b7280;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #4461F2;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 14px 32px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
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
        color: #374151;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 5px;
    }

    .alert {
        padding: 15px 20px;
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

    @media (max-width: 768px) {
        .form-card {
            padding: 25px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="add-listing-container">
    <h1 class="page-title">Add New Listing</h1>
    <p class="page-subtitle">Fill in the details below to list your item for rent</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Please fix the following errors:</strong>
            <ul style="margin-top: 10px; margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" id="addListingForm">
        @csrf
        
        <div class="form-card">
            <!-- Basic Information -->
            <div class="form-section">
                <h2 class="section-title">Basic Information</h2>
                
                <div class="form-group">
                    <label class="form-label" for="ItemName">
                        Item Name <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="ItemName" 
                        name="ItemName" 
                        class="form-input" 
                        placeholder="e.g., Canon EOS R5 Camera"
                        value="{{ old('ItemName') }}"
                        required
                    >
                    @error('ItemName')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="CategoryID">
                        Category <span class="required">*</span>
                    </label>
                    <select id="CategoryID" name="CategoryID" class="form-select" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->CategoryID }}" {{ old('CategoryID') == $category->CategoryID ? 'selected' : '' }}>
                                {{ $category->CategoryName }}
                            </option>
                        @endforeach
                    </select>
                    @error('CategoryID')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="Description">
                        Description <span class="required">*</span>
                    </label>
                    <textarea 
                        id="Description" 
                        name="Description" 
                        class="form-textarea" 
                        placeholder="Describe your item in detail... Include condition, features, and any important details."
                        required
                    >{{ old('Description') }}</textarea>
                    <div class="form-help">Provide as much detail as possible to attract renters</div>
                    @error('Description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Pricing & Location -->
            <div class="form-section">
                <h2 class="section-title">Pricing & Location</h2>
                
                <div class="form-group">
                    <label class="form-label" for="PricePerDay">
                        Price Per Day <span class="required">*</span>
                    </label>
                    <div class="price-input-group">
                        <span class="price-prefix">RM</span>
                        <input 
                            type="number" 
                            id="PricePerDay" 
                            name="PricePerDay" 
                            class="form-input" 
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            value="{{ old('PricePerDay') }}"
                            required
                            style="flex: 1;"
                        >
                        <span class="price-suffix">/ day</span>
                    </div>
                    @error('PricePerDay')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="DepositAmount">
                        Deposit Amount <span class="required">*</span>
                    </label>
                    <div class="price-input-group">
                        <span class="price-prefix">RM</span>
                        <input 
                            type="number" 
                            id="DepositAmount" 
                            name="DepositAmount" 
                            class="form-input" 
                            placeholder="0.00"
                            step="0.01"
                            min="0"
                            value="{{ old('DepositAmount') }}"
                            required
                            style="flex: 1;"
                        >
                    </div>
                    <div class="form-help">Refundable security deposit to protect your item</div>
                    @error('DepositAmount')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="LocationID">
                        Location <span class="required">*</span>
                    </label>
                    <select id="LocationID" name="LocationID" class="form-select" required>
                        <option value="">Select a location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->LocationID }}" {{ old('LocationID') == $location->LocationID ? 'selected' : '' }}>
                                {{ $location->LocationName }}
                            </option>
                        @endforeach
                    </select>
                    @error('LocationID')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Item Images -->
            <div class="form-section">
                <h2 class="section-title">Item Images</h2>

                <div class="form-group">
                    <label class="form-label">
                        Upload Images (1-4 images) <span class="required">*</span>
                    </label>
                    <div class="image-upload-area" id="imageUploadArea">
                        <div class="upload-icon">📸</div>
                        <div class="upload-text">Click to upload or drag and drop</div>
                        <div class="upload-hint">PNG, JPG or JPEG (MAX. 2MB each) - Maximum 4 images</div>
                        <input
                            type="file"
                            id="images"
                            name="images[]"
                            accept="image/png,image/jpeg,image/jpg,image/gif"
                            style="display: none;"
                            multiple
                            required
                        >
                    </div>
                    <div class="images-preview-container hidden" id="imagesPreview"></div>
                    @error('images')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Availability -->
             <div class="form-section">
                <h2 class="section-title">Availability</h2>
                
                <div class="form-group">
                    <label class="form-label" for="Quantity">
                        Quantity Available <span class="required">*</span>
                    </label>
                    <input 
                        type="number" 
                        id="Quantity" 
                        name="Quantity" 
                        class="form-input" 
                        placeholder="1"
                        min="1"
                        value="{{ old('Quantity', 1) }}"
                        required
                    >
                    <div class="form-help">How many units of this item do you have available for rent?</div>
                    @error('Quantity')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="Availability" 
                            name="Availability" 
                            class="form-checkbox"
                            value="1"
                            {{ old('Availability', true) ? 'checked' : '' }}
                        >
                        <label class="form-label" for="Availability" style="margin-bottom: 0;">
                            Make item immediately available for rent
                        </label>
                    </div>
                    <div class="form-help">Uncheck if you want to list the item but not make it available yet</div>
                </div>
            </div>
            

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    Publish Listing
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageUploadArea = document.getElementById('imageUploadArea');
        const imageInput = document.getElementById('images');
        const imagesPreview = document.getElementById('imagesPreview');
        const MAX_IMAGES = 4;
        const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB
        let selectedFiles = [];

        // Click to upload
        imageUploadArea.addEventListener('click', function() {
            imageInput.click();
        });

        // Handle file selection
        imageInput.addEventListener('change', function(e) {
            handleFiles(Array.from(e.target.files));
        });

        // Drag and drop
        imageUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            imageUploadArea.classList.add('dragover');
        });

        imageUploadArea.addEventListener('dragleave', function() {
            imageUploadArea.classList.remove('dragover');
        });

        imageUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            imageUploadArea.classList.remove('dragover');
            const files = Array.from(e.dataTransfer.files).filter(f => f.type.match('image.*'));
            handleFiles(files);
        });

        function handleFiles(files) {
            // Filter valid images
            const validFiles = files.filter(file => {
                if (!file.type.match('image/(png|jpeg|jpg|gif)')) {
                    return false;
                }
                if (file.size > MAX_FILE_SIZE) {
                    alert(`${file.name} is too large. Maximum size is 2MB.`);
                    return false;
                }
                return true;
            });

            // Check total count
            const totalFiles = selectedFiles.length + validFiles.length;
            if (totalFiles > MAX_IMAGES) {
                alert(`You can only upload a maximum of ${MAX_IMAGES} images. Currently you have ${selectedFiles.length} image(s).`);
                return;
            }

            // Add valid files
            validFiles.forEach(file => {
                selectedFiles.push(file);
            });

            updatePreview();
            updateFileInput();
        }

        function updatePreview() {
            imagesPreview.innerHTML = '';

            if (selectedFiles.length === 0) {
                imagesPreview.classList.add('hidden');
                return;
            }

            imagesPreview.classList.remove('hidden');

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="preview-image">
                        <button type="button" class="remove-image" data-index="${index}">×</button>
                        <div class="image-number">Image ${index + 1}</div>
                    `;
                    imagesPreview.appendChild(div);

                    // Add remove functionality
                    div.querySelector('.remove-image').addEventListener('click', function() {
                        removeImage(parseInt(this.getAttribute('data-index')));
                    });
                };
                reader.readAsDataURL(file);
            });
        }

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            updatePreview();
            updateFileInput();
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            imageInput.files = dataTransfer.files;
        }

        // Form validation
        const form = document.getElementById('addListingForm');
        form.addEventListener('submit', function(e) {
            const itemName = document.getElementById('ItemName').value.trim();
            const category = document.getElementById('CategoryID').value;
            const description = document.getElementById('Description').value.trim();
            const price = document.getElementById('PricePerDay').value;
            const deposit = document.getElementById('DepositAmount').value;
            const location = document.getElementById('LocationID').value;
            const images = selectedFiles.length;

            if (!itemName || !category || !description || !price || !deposit || !location || images === 0) {
                e.preventDefault();
                alert('Please fill in all required fields and upload at least 1 image');
                return false;
            }

            if (parseFloat(price) <= 0) {
                e.preventDefault();
                alert('Price must be greater than 0');
                return false;
            }

            if (parseFloat(deposit) < 0) {
                e.preventDefault();
                alert('Deposit amount cannot be negative');
                return false;
            }
        });
    });
</script>
@endpush