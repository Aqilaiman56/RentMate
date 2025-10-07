<section class="profile-form-section">
    <form method="post" action="{{ route('password.update') }}" class="profile-form">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password" class="form-label">Current Password</label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="form-input" 
                autocomplete="current-password"
            />
            @error('current_password', 'updatePassword')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password" class="form-label">New Password</label>
            <input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="form-input" 
                autocomplete="new-password"
            />
            @error('password', 'updatePassword')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation" class="form-label">Confirm New Password</label>
            <input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="form-input" 
                autocomplete="new-password"
            />
            @error('password_confirmation', 'updatePassword')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Password</button>
            
            @if (session('status') === 'password-updated')
                <span class="success-message" id="passwordSuccess">
                    Password updated successfully!
                </span>
            @endif
        </div>
    </form>
</section>