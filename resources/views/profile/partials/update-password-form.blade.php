<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="adm-form-group">
        <label class="adm-label" for="current_password">Current Password</label>
        <input id="current_password" name="current_password" type="password" class="adm-input" autocomplete="current-password">
        @error('current_password', 'updatePassword') <p class="adm-error">{{ $message }}</p> @enderror
    </div>

    <div class="adm-form-group">
        <label class="adm-label" for="password">New Password</label>
        <input id="password" name="password" type="password" class="adm-input" autocomplete="new-password">
        @error('password', 'updatePassword') <p class="adm-error">{{ $message }}</p> @enderror
    </div>

    <div class="adm-form-group">
        <label class="adm-label" for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="adm-input" autocomplete="new-password">
        @error('password_confirmation', 'updatePassword') <p class="adm-error">{{ $message }}</p> @enderror
    </div>

    <div style="display: flex; align-items: center; gap: 12px; margin-top: 20px;">
        <button type="submit" class="adm-btn adm-btn-lime">Update Password</button>

        @if (session('status') === 'password-updated')
            <span style="font-size: 13px; color: #065F46; font-weight: 600;">Saved.</span>
        @endif
    </div>
</form>