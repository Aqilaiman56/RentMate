<section class="profile-form-section">
    <div class="danger-zone">
        <div class="danger-zone-content">
            <p class="danger-zone-text">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </p>
            <button 
                type="button" 
                class="btn btn-danger" 
                onclick="openDeleteModal()"
            >
                Delete Account
            </button>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-overlay" onclick="closeDeleteModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Delete Account</h3>
                <button type="button" class="modal-close" onclick="closeDeleteModal()">×</button>
            </div>
            
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-body">
                    <p class="modal-text">
                        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.
                    </p>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="form-input"
                            placeholder="Enter your password"
                        />
                        @error('password', 'userDeletion')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>