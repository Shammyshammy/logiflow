<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="adm-form-group">
        <label class="adm-label" for="name">Name</label>
        <input id="name" name="name" type="text" class="adm-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name') <p class="adm-error">{{ $message }}</p> @enderror
    </div>

    <div class="adm-form-group">
        <label class="adm-label" for="email">Email</label>
        <input id="email" name="email" type="email" class="adm-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email') <p class="adm-error">{{ $message }}</p> @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <p style="font-size: 13px; color: #92400E; margin-top: 8px;">
                Your email address is unverified.
                <button form="send-verification" style="background: none; border: none; color: #073B3A; font-weight: 600; cursor: pointer; padding: 0; font-family: inherit; text-decoration: underline;">
                    Click here to re-send the verification email.
                </button>
            </p>

            @if (session('status') === 'verification-link-sent')
                <p style="font-size: 13px; color: #065F46; margin-top: 8px;">
                    A new verification link has been sent to your email.
                </p>
            @endif
        @endif
    </div>

    <div class="adm-form-group">
        <label class="adm-label" for="phone">Phone</label>
        <input id="phone" name="phone" type="text" class="adm-input" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
        @error('phone') <p class="adm-error">{{ $message }}</p> @enderror
    </div>

    <div style="display: flex; align-items: center; gap: 12px; margin-top: 20px;">
        <button type="submit" class="adm-btn adm-btn-lime">Save</button>

        @if (session('status') === 'profile-updated')
            <span style="font-size: 13px; color: #065F46; font-weight: 600;">Saved.</span>
        @endif
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display: none;">
    @csrf
</form>