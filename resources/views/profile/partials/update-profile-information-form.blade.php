<section class="profile-form-section">
    <form method="post" action="{{ route('profile.update') }}" class="profile-form">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">Name</label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="form-input" 
                value="{{ old('name', $user->UserName ?? $user->name) }}" 
                required 
                autofocus
            />
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="form-input" 
                value="{{ old('email', $user->Email ?? $user->email) }}" 
                required
            />
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="verification-notice">
                    <p class="verification-text">
                        Your email address is unverified.
                        <button form="send-verification" class="verification-link">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="verification-success">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            
            @if (session('status') === 'profile-updated')
                <span class="success-message" id="profileSuccess">
                    Saved successfully!
                </span>
            @endif
        </div>
    </form>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
        @csrf
    </form>
</section>